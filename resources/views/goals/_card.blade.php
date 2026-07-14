@php
    $colors = [
        'Health' => 'var(--sage)',
        'Career' => 'var(--gold)',
        'Learning' => '#B08968',
        'Personal' => '#C97C5D',
        'Finance' => 'var(--gold-soft)',
    ];
    $color = $colors[$goal->category] ?? 'var(--text-muted)';
    $pct = $goal->displayProgress();
    $r = 27;
    $circ = 2 * M_PI * $r;
    $offset = $circ - (min($pct, 100) / 100) * $circ;
@endphp

<div class="goalcard {{ $goal->status === 'completed' ? 'completed' : '' }}">
    <div class="catband" style="background:{{ $color }}"></div>
    <div class="cardtop">
        <div class="ring-wrap">
            <svg width="64" height="64" viewBox="0 0 64 64">
                <circle class="ringtrack" cx="32" cy="32" r="{{ $r }}"></circle>
                <circle class="ringval" cx="32" cy="32" r="{{ $r }}"
                    stroke="{{ $color }}" stroke-dasharray="{{ $circ }}"
                    stroke-dashoffset="{{ $offset }}"></circle>
            </svg>
            <div class="pct">{{ $pct }}%</div>
        </div>
        <div>
            <div class="goaltitle">{{ $goal->title }}</div>
            <span class="tag"
                style="background:rgba(237,224,200,.06);color:{{ $color }}">{{ $goal->category }}</span>
        </div>
    </div>

    <div class="datesline">
        <span>{{ $goal->start_date->format('M j') }} → {{ $goal->target_date->format('M j') }}</span>
        @if ($goal->status === 'completed')
            <span>Completed {{ $goal->completed_date->format('M j') }}</span>
        @elseif ($goal->isOverdue())
            <span class="overdue">Overdue by {{ round(now()->diffInDays($goal->target_date)) }}d</span>
        @elseif ($goal->isDueSoon())
            <span class="duesoon">Due in {{ round(now()->diffInDays($goal->target_date, false)) }}d</span>
        @else
            <span>Due in {{ round(now()->diffInDays($goal->target_date, false)) }}d</span>
        @endif
    </div>


    @if ($goal->isFinance())
        <div class="amountline">₱{{ number_format($goal->amount_saved) }} of
            ₱{{ number_format($goal->amount_target) }}</div>
    @endif

    <div class="streakline">{{ $goal->streak }}-day streak</div>

    @if ($goal->reward)
        <div class="rewardline {{ $goal->reward_unlocked ? 'unlocked' : '' }}">
            {{ $goal->reward_unlocked ? 'Unlocked: ' : 'Reward: ' }}{{ $goal->reward }}
        </div>
    @endif

    <div class="cardbtns">
        @if ($goal->status === 'active')
            <button class="btn btn-primary btn-sm"
                @click="logOpen = true; activeGoalId = {{ $goal->id }}; activeGoalIsFinance = {{ $goal->isFinance() ? 'true' : 'false' }}; mood = null; delta = 10; amount = ''">
                Log progress
            </button>
            <button class="btn btn-ghost btn-sm" @click="viewLogOpen = true; activeGoalId = {{ $goal->id }}">
                View progress log
            </button>
            @if ($goal->isOverdue())
                <form method="POST" action="{{ route('goals.reschedule', $goal) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-rose btn-sm">Push +7d</button>
                </form>
            @endif
        @endif
        <form method="POST" action="{{ route('goals.destroy', $goal) }}" style="display:inline">
            @csrf @method('DELETE')
            <button type="submit"
                class="btn btn-ghost btn-sm">{{ $goal->status === 'active' ? 'Delete' : 'Remove' }}</button>
        </form>
    </div>
</div>

{{-- Progress Log Modal --}}
<div class="modal-overlay" x-show="viewLogOpen && activeGoalId === {{ $goal->id }}" x-cloak style="display:none">
    <div class="modal" @click.outside="viewLogOpen = false">
        <h3>Progress Log for {{ $goal->title }}</h3>
        <div class="log-history">
            @foreach ($goal->checkins()->orderByDesc('created_at')->get() as $c)
                <div class="log-entry">
                    <span class="log-date">{{ $c->created_at->timezone('Asia/Manila')->format('M j, Y g:i A') }}</span>
                    <span class="log-progress">
                        @if ($c->progress_delta)
                            +{{ $c->progress_delta }}%
                        @endif
                        @if ($c->amount)
                            ₱{{ number_format($c->amount, 2) }}
                        @endif
                    </span>
                    <span class="log-mood">
                        @switch($c->mood)
                            @case('content')
                                🏰
                            @break

                            @case('driven')
                                ⚔️
                            @break

                            @case('calm')
                                🌙
                            @break

                            @case('weary')
                                🕯️
                            @break

                            @case('weighed')
                                ⚖️
                            @break
                        @endswitch
                    </span>
                    @if ($c->note)
                        <p class="log-note">{{ $c->note }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <div style="display:flex;gap:10px;margin-top:16px;justify-content:flex-end;">
            <button type="button" class="btn btn-ghost" @click="viewLogOpen = false">Close</button>
        </div>
    </div>
</div>

