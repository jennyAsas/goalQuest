<x-layouts.app>

    <header class="top">
        <div>
            <p class="eyebrow">Quest Calendar</p>
            <h1>{{ $monthLabel }}</h1>
            <p class="quote-of-day">Each mark below is a day you showed up.</p>
        </div>
        <div style="display:flex;gap:10px;">
            <a class="btn btn-ghost btn-sm"
                href="{{ route('calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}">&larr;
                {{ $prevMonth->format('M') }}</a>
            <a class="btn btn-ghost btn-sm" href="{{ route('calendar') }}">Today</a>
            <a class="btn btn-ghost btn-sm"
                href="{{ route('calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}">{{ $nextMonth->format('M') }}
                &rarr;</a>
        </div>
    </header>

    @if ($goals->isEmpty())
        <div class="emptystate">
            <h3>No goals to track yet</h3>
            <p>Add a goal from the <a href="{{ route('dashboard') }}" style="color:var(--gold-soft)">dashboard</a>
                first, then its check-ins will show up here.</p>
        </div>
    @else
        <div class="chips" style="margin-bottom:20px;">
            @foreach ($goals as $goal)
                @php
                    $colors = [
                        'Health' => '#7c9a92',
                        'Career' => '#d4af37',
                        'Learning' => '#a67c52',
                        'Personal' => '#c97c5d',
                        'Finance' => '#e0c97a',
                    ];
                    $color = $colors[$goal->category] ?? '#aaa';
                @endphp
                <span class="chip"
                    style="border-color:{{ $color }};color:{{ $color }};cursor:default;">{{ $goal->title }}</span>
            @endforeach
        </div>

        <div class="calendar-card">
            <div class="calendar-weekdays">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d)
                    <div>{{ $d }}</div>
                @endforeach
            </div>

            @foreach ($weeks as $week)
                <div class="calendar-week">
                    @foreach ($week as $day)
                        <div
                            class="calendar-day {{ $day['inMonth'] ? '' : 'outmonth' }} {{ $day['isToday'] ? 'is-today' : '' }}">
                            <div class="daynum">{{ $day['date']->day }}</div>
                            <div class="daylabels">
                                @foreach ($goals as $goal)
                                    @php
                                        $color = $colors[$goal->category] ?? '#aaa';
                                        $checked = $day['checkedGoalIds']->contains($goal->id);
                                    @endphp

                                    {{-- Check-ins --}}
                                    @if ($checked)
                                        <label class="daychk checked" style="--c: {{ $color }}">
                                            <input type="checkbox" checked disabled>
                                            <span>{{ \Illuminate\Support\Str::limit($goal->title, 12) }}</span>
                                        </label>
                                    @elseif ($day['isToday'] && $goal->status === 'active')
                                        <form method="POST" action="{{ route('goals.checkin', $goal) }}">
                                            @csrf
                                            @if ($goal->isFinance())
                                                <input type="hidden" name="amount" value="25">
                                            @else
                                                <input type="hidden" name="progress_delta" value="10">
                                            @endif
                                            <button type="submit" class="daychk unchecked"
                                                style="--c: {{ $color }}">
                                                <span class="box"></span>
                                                <span>{{ \Illuminate\Support\Str::limit($goal->title, 12) }}</span>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Subtasks --}}
                                    <div class="day-subtasks">
                                        @foreach (($day['subtasks'] ?? collect())->where('goal_id', $goal->id) as $subtask)
                                            <form method="POST" action="{{ route('subtasks.toggle', $subtask) }}">
                                                @csrf
                                                <label class="daychk" style="--c: {{ $color }}">
                                                    <input type="checkbox" {{ $subtask->completed ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                    <span>{{ \Illuminate\Support\Str::limit($subtask->title, 12) }}</span>
                                                </label>
                                            </form>
                                        @endforeach

                                        @if ($day['isToday'] && $goal->status === 'active')
                                            <form method="POST" action="{{ route('subtasks.store') }}">
                                                @csrf
                                                <input type="hidden" name="goal_id" value="{{ $goal->id }}">
                                                <input type="hidden" name="date"
                                                    value="{{ $day['date']->toDateString() }}">
                                                <input type="text" name="title" placeholder="Add subtask..."
                                                    class="subtask-input">
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <p style="color:#aaa;font-size:12.5px;margin-top:16px;">
            Filled boxes are days you checked in. On today's date, tap an empty box to log a quick check-in
            (+10% progress, or +$25 for a Finance goal). You can also add subtasks directly inside each day cell —
            completing them will mark your progress in the quest log.
        </p>
    @endif

    {{-- Dark Classical Styling --}}
    <style>
        body {
            background: #1c1c1c;
            color: #e0d6b9;
            font-family: 'Garamond', 'Georgia', serif;
        }

        .calendar-card {
            background: #2a2a2a;
            border: 2px solid #6b5630;
            border-radius: 6px;
            padding: 12px;
        }

        .calendar-week {
            display: flex;
        }

        .calendar-day {
            flex: 1;
            border: 1px solid #6b5630;
            background: #1f1f1f;
            padding: 6px;
            min-height: 140px;
            position: relative;
        }

        .calendar-day.is-today {
            background: #3a2f1f;
            box-shadow: inset 0 0 6px #d4af37;
        }

        .daynum {
            font-weight: bold;
            color: #d4af37;
            margin-bottom: 4px;
        }

        .daychk {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            margin-top: 4px;
            color: #e0d6b9;
        }

        .daychk input[type="checkbox"] {
            accent-color: #d4af37;
            width: 14px;
            height: 14px;
        }

        .subtask-input {
            border: 1px solid #6b5630;
            background: #2a2a2a;
            color: #e0d6b9;
            font-family: 'Garamond', serif;
            font-size: 12px;
            padding: 2px 4px;
            width: 100%;
            margin-top: 4px;
        }

        .day-subtasks {
            margin-top: 6px;
        }

        .chip {
            background: #1f1f1f;
            border: 1px solid currentColor;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</x-layouts.app>
    