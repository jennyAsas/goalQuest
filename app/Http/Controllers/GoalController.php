<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Goal;
use App\Models\Mood;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:Health,Career,Learning,Personal,Finance'],
            'start_date' => ['required', 'date'],
            'target_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reward' => ['nullable', 'string', 'max:255'],
            'amount_target' => ['nullable', 'numeric', 'min:1'],
            'amount_start' => ['nullable', 'numeric', 'min:0'],
        ]);

        $goal = new Goal([
            'title' => $data['title'],
            'category' => $data['category'],
            'start_date' => $data['start_date'],
            'target_date' => $data['target_date'],
            'reward' => $data['reward'] ?? null,
        ]);
        $goal->user_id = $request->user()->id;

        if ($data['category'] === 'Finance') {
            $goal->amount_target = $data['amount_target'] ?? 100;
            $goal->amount_saved = $data['amount_start'] ?? 0;
            $goal->progress = min(100, (int) round(($goal->amount_saved / $goal->amount_target) * 100));
        }

        $goal->save();

        return back()->with('status', 'New goal added to your quest log.');
    }

    public function checkin(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal, $request);

        $data = $request->validate([
            'progress_delta' => ['nullable', 'integer', 'min:1', 'max:100'],
            'amount' => ['nullable', 'numeric', 'min:0.01'],
            'mood' => ['nullable', 'string', 'in:content,driven,calm,weary,weighed'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        Checkin::create([
            'goal_id' => $goal->id,
            'user_id' => $request->user()->id,
            'date' => now()->toDateString(),
            'amount' => $data['amount'] ?? null,
            'progress_delta' => $data['progress_delta'] ?? null,
            'note' => $data['note'] ?? null,
        ]);

        if (! empty($data['mood'])) {
            Mood::create([
                'goal_id' => $goal->id,
                'user_id' => $request->user()->id,
                'mood' => $data['mood'],
                'note' => $data['note'] ?? null,
                'logged_at' => now()->toDateString(),
            ]);
        }

        $goal->applyCheckin($data['progress_delta'] ?? null, $data['amount'] ?? null);

        $justCompleted = $goal->status === 'completed' && $goal->completed_date->isToday();

        if ($justCompleted) {
            return redirect()->route('dashboard')->with('justCompletedGoal', $goal->id);
        }

        $quote = ! empty($data['mood']) ? collect(Mood::quotesFor($data['mood']))->random() : null;

        return back()->with('status', $quote ? "\"{$quote['t']}\" — {$quote['a']}" : 'Check-in saved. The streak endures.');
    }

    public function reschedule(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal, $request);
        $goal->reschedule(7);

        return back()->with('status', 'Deadline pushed by 7 days — a wise reset, not a failure.');
    }

    public function destroy(Request $request, Goal $goal)
    {
        $this->authorizeGoal($goal, $request);
        $goal->delete();

        return back()->with('status', 'Goal removed.');
    }

    private function authorizeGoal(Goal $goal, Request $request): void
    {
        abort_unless($goal->user_id === $request->user()->id, 403);
    }
}
