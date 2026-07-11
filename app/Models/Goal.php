<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'start_date',
        'target_date',
        'progress',
        'streak',
        'last_checkin',
        'status',
        'reward',
        'reward_unlocked',
        'amount_target',
        'amount_saved',
        'completed_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date',
        'last_checkin' => 'date',
        'completed_date' => 'date',
        'reward_unlocked' => 'boolean',
        'amount_target' => 'decimal:2',
        'amount_saved' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function moods(): HasMany
    {
        return $this->hasMany(Mood::class);
    }

    public function reflections(): HasMany
    {
        return $this->hasMany(Reflection::class);
    }

    public function isFinance(): bool
    {
        return $this->category === 'Finance';
    }

    /** Percentage complete, computed for finance goals from amounts. */
    public function displayProgress(): int
    {
        if ($this->isFinance() && $this->amount_target > 0) {
            return (int) min(100, round(($this->amount_saved / $this->amount_target) * 100));
        }

        return (int) $this->progress;
    }

    public function isOverdue(): bool
    {
        return $this->status === 'active' && Carbon::today()->gt($this->target_date);
    }

    public function isDueSoon(): bool
    {
        return $this->status === 'active' && ! $this->isOverdue()
            && Carbon::today()->diffInDays($this->target_date, false) <= 3;
    }

    /**
     * Apply a check-in: bump progress/amount, update the streak, and
     * mark the goal complete + unlock the reward if the target is reached.
     */
    public function applyCheckin(?int $progressDelta = null, ?float $amount = null): void
    {
        $today = Carbon::today();

        if ($this->isFinance() && $amount !== null) {
            $this->amount_saved = (float) $this->amount_saved + $amount;
            $this->progress = min(100, (int) round(($this->amount_saved / $this->amount_target) * 100));
        } elseif ($progressDelta !== null) {
            $this->progress = min(100, $this->progress + $progressDelta);
        }

        if (! $this->last_checkin) {
            $this->streak = 1;
        } elseif ($this->last_checkin->isSameDay($today)) {
            // already checked in today, streak unchanged
        } elseif ($this->last_checkin->diffInDays($today) === 1) {
            $this->streak += 1;
        } else {
            $this->streak = 1;
        }
        $this->last_checkin = $today;

        $reachedTarget = $this->isFinance()
            ? (float) $this->amount_saved >= (float) $this->amount_target
            : $this->progress >= 100;

        if ($reachedTarget && $this->status !== 'completed') {
            $this->status = 'completed';
            $this->completed_date = $today;
            if ($this->reward) {
                $this->reward_unlocked = true;
            }
        }

        $this->save();
    }

    public function reschedule(int $days = 7): void
    {
        $this->target_date = $this->target_date->copy()->addDays($days);
        $this->save();
    }
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
