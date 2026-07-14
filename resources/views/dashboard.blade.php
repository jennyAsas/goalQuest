<x-layouts.app>

    <div x-data="{
        logOpen: false,
        reflectOpen: false,
        activeGoalId: null,
        activeGoalIsFinance: false,
        formOpen: false,
        catFinance: false,
        mood: null,
        delta: 10,
        amount: ''
    }">

        <header class="top">
            <div>
                <p class="eyebrow">Quest Log · {{ now()->format('l, M j') }}</p>
                <h1>GoalQuest</h1>
                <p class="quote-of-day">"{{ $quote['t'] }}"<span class="attr">— {{ $quote['a'] }}</span></p>
            </div>
            <div class="streakbadge">{{ $loginStreak }}-day login streak</div>
        </header>

        <div class="statsrow">
            <div class="statcard">
                <div class="num">{{ $stats['active'] }}</div>
                <div class="lbl">Active goals</div>
            </div>
            <div class="statcard">
                <div class="num">{{ $stats['completed'] }}</div>
                <div class="lbl">Completed</div>
            </div>
            <div class="statcard">
                <div class="num">{{ $stats['longestStreak'] }}</div>
                <div class="lbl">Longest streak</div>
            </div>
            <div class="statcard">
                <div class="num">${{ number_format($stats['totalSaved']) }}</div>
                <div class="lbl">Total saved</div>
            </div>
        </div>

        <div class="chartcard">
            <h3>This week's check-ins</h3>
            <div class="bars">
                @foreach ($week as $day)
                    <div class="bar-col">
                        <div class="bar" style="height:{{ max($day['pct'], 4) }}%"></div>
                        <span>{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section-head">
            <h2>Your goals</h2>
            <button class="btn btn-primary" @click="formOpen = !formOpen"
                x-text="formOpen ? 'Close' : '+ New goal'"></button>
        </div>

        <div class="formcard" x-show="formOpen" x-cloak>
            <form method="POST" action="{{ route('goals.store') }}">
                @csrf
                <div class="formgrid">
                    <div><label>Title</label><input name="title" required></div>
                    <div>
                        <label>Category</label>
                        <select name="category" @change="catFinance = $event.target.value === 'Finance'">
                            <option>Health</option>
                            <option>Career</option>
                            <option>Learning</option>
                            <option>Personal</option>
                            <option>Finance</option>
                        </select>
                    </div>
                    <div><label>Start date</label><input type="date" name="start_date"
                            value="{{ now()->toDateString() }}" required></div>
                    <div><label>Target date</label><input type="date" name="target_date"
                            value="{{ now()->addDays(14)->toDateString() }}" required></div>
                    <div x-show="catFinance" x-cloak><label>Target amount ($)</label><input type="number"
                            step="0.01" name="amount_target" placeholder="2000"></div>
                    <div x-show="catFinance" x-cloak><label>Starting amount ($)</label><input type="number"
                            step="0.01" name="amount_start" placeholder="0"></div>
                    <div><label>Reward when complete (optional)</label><input name="reward"
                            placeholder="e.g. Buy ice cream"></div>
                </div>
                <button class="btn btn-primary" type="submit">Save goal</button>
            </form>
        </div>

        <div class="chips">
            @foreach ($categories as $cat)
                <a href="{{ route('dashboard', ['category' => $cat]) }}"
                    class="chip {{ $activeCategory === $cat ? 'active' : '' }}">{{ $cat }}</a>
            @endforeach
        </div>

        @if ($activeGoals->isEmpty() && $completedGoals->isEmpty())
            <div class="emptystate">
                <h3>No goals here yet</h3>
                <p>Add your first goal above — give it a category, a target date, and maybe a reward waiting at the
                    finish line.</p>
            </div>
        @else
            <div class="goalgrid">
                @foreach ($activeGoals->concat($completedGoals) as $goal)
                    @include('goals._card', ['goal' => $goal])
                @endforeach
            </div>
        @endif

        <div class="section-head">
            <h2>Reflection journal</h2>
        </div>
        @if ($reflections->isEmpty())
            <div class="emptystate">
                <h3>Your journal is empty</h3>
                <p>Finish a goal and write a reflection — it'll show up here.</p>
            </div>
        @else
            @foreach ($reflections as $r)
                <div class="journalentry">
                    <div class="jmeta">{{ $r->goal->title }} · {{ $r->date->format('M j') }}</div>
                    <p>{{ $r->text }}</p>
                </div>
            @endforeach
        @endif

        {{-- Log progress modal --}}
        <div class="modal-overlay" x-show="logOpen" x-cloak style="display:none">
            <div class="modal" @click.outside="logOpen = false">
                <h3>Log progress</h3>
                <form method="POST" :action="'/goals/' + activeGoalId + '/checkin'">
                    @csrf
                    <template x-if="!activeGoalIsFinance">
                        <div>
                            <label>How much progress?</label>
                            <div class="quickbtns">
                                <button type="button" class="btn btn-sm"
                                    :class="delta === 10 ? 'btn-primary' : 'btn-ghost'"
                                    @click="delta = 10">+10%</button>
                                <button type="button" class="btn btn-sm"
                                    :class="delta === 25 ? 'btn-primary' : 'btn-ghost'"
                                    @click="delta = 25">+25%</button>
                                <button type="button" class="btn btn-sm"
                                    :class="delta === 100 ? 'btn-primary' : 'btn-ghost'" @click="delta = 100">Complete
                                    it</button>
                            </div>
                            <input type="hidden" name="progress_delta" :value="delta">
                        </div>
                    </template>
                    <template x-if="activeGoalIsFinance">
                        <div>
                            <label>How much are you adding?</label>
                            <div class="quickbtns">
                                <button type="button" class="btn btn-sm"
                                    :class="amount == 25 ? 'btn-primary' : 'btn-ghost'"
                                    @click="amount = 25">+$25</button>
                                <button type="button" class="btn btn-sm"
                                    :class="amount == 100 ? 'btn-primary' : 'btn-ghost'"
                                    @click="amount = 100">+$100</button>
                                <button type="button" class="btn btn-sm"
                                    :class="amount == 500 ? 'btn-primary' : 'btn-ghost'"
                                    @click="amount = 500">+$500</button>
                            </div>
                            <input type="number" step="0.01" name="amount" x-model="amount"
                                placeholder="Custom amount">
                        </div>
                    </template>

                    <label>How are you feeling about it?</label>
                    <div class="moodrow">
                        @php
                            $moodEmoji = [
                                'content' => '🏰',
                                'driven' => '⚔️',
                                'calm' => '🌙',
                                'weary' => '🕯️',
                                'weighed' => '⚖️',
                            ];
                        @endphp
                        @foreach ($moodEmoji as $m => $emoji)
                            <button type="button" class="moodbtn"
                                :class="mood === '{{ $m }}' ? 'sel' : ''"
                                @click="mood = '{{ $m }}'">
                                <span style="font-size:16px;">{{ $emoji }}</span> {{ ucfirst($m) }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="mood" x-model="mood">

                    <label>Quick note (optional)</label>
                    <textarea name="note" placeholder="What happened today?"></textarea>

                    <div style="display:flex;gap:10px;margin-top:16px;justify-content:flex-end;">
                        <button type="button" class="btn btn-ghost" @click="logOpen = false">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save check-in</button>
                    </div>
                </form>
            </div>
        </div>

        @if (session('justCompletedGoal'))
            <div class="modal-overlay">
                <div class="modal">
                    <h3>A goal fulfilled</h3>
                    <p style="color:var(--text-muted);margin-bottom:12px;">Well done — take a moment to reflect on it.
                    </p>
                    <form method="POST" action="{{ route('reflections.store', session('justCompletedGoal')) }}">
                        @csrf
                        <label>Write a short reflection — what got you here?</label>
                        <textarea name="text" placeholder="I stuck with it because..." required></textarea>
                        <div style="display:flex;gap:10px;margin-top:16px;justify-content:flex-end;">
                            <a href="{{ route('dashboard') }}" class="btn btn-ghost">Skip</a>
                            <button type="submit" class="btn btn-primary">Save to journal</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</x-layouts.app>
