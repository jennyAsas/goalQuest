<?php

namespace App\Console\Commands;

use App\Models\Goal;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class SendGoalReminders extends Command
{
    protected $signature = 'goals:remind';

    protected $description = 'Email each user a reminder for goals due within 3 days or overdue.';

    public function handle(): int
    {
        $goals = Goal::with('user')
            ->where('status', 'active')
            ->get()
            ->filter(fn(Goal $g) => $g->isOverdue() || $g->isDueSoon());

        $byUser = $goals->groupBy('user_id');

        foreach ($byUser as $userId => $userGoals) {
            $user = $userGoals->first()->user;
            if (! $user || ! $user->email) {
                continue;
            }

            Mail::raw($this->buildBody($userGoals), function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('GoalQuest — upcoming deadlines');
            });

            $this->info("Reminder sent to {$user->email} for {$userGoals->count()} goal(s).");
        }

        return self::SUCCESS;
    }

    private function buildBody($goals): string
    {
        $lines = ["Here's where your goals stand today:", ''];

        foreach ($goals as $goal) {
            $status = $goal->isOverdue()
                ? 'overdue by ' . Carbon::today()->diffInDays($goal->target_date) . ' day(s)'
                : 'due in ' . Carbon::today()->diffInDays($goal->target_date, false) . ' day(s)';

            $lines[] = "- {$goal->title} ({$goal->category}): {$status}, {$goal->displayProgress()}% complete";
        }

        $lines[] = '';
        $lines[] = '"It does not matter how slowly you go, as long as you do not stop." — Confucius';

        return implode("\n", $lines);
    }
}
