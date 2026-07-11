<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    private array $generalQuotes = [
        ['t' => 'You have power over your mind, not outside events.', 'a' => 'Marcus Aurelius'],
        ['t' => 'We are what we repeatedly do; excellence is a habit.', 'a' => 'Aristotle'],
        ['t' => 'It does not matter how slowly you go, as long as you do not stop.', 'a' => 'Confucius'],
        ['t' => 'He who has a why to live can bear almost any how.', 'a' => 'Nietzsche'],
        ['t' => 'Luck is what happens when preparation meets opportunity.', 'a' => 'Seneca'],
        ['t' => 'Know thyself.', 'a' => 'Socrates'],
        ['t' => 'The unexamined life is not worth living.', 'a' => 'Socrates'],
        ['t' => 'First say to yourself what you would be, then do what you have to do.', 'a' => 'Epictetus'],
        ['t' => 'Well begun is half done.', 'a' => 'Aristotle'],
        ['t' => 'Patience is bitter, but its fruit is sweet.', 'a' => 'Aristotle'],
    ];

    public function index(Request $request)
    {
        $user = $request->user();
        $category = $request->query('category', 'All');

        // Filter goals by category
        $goalsQuery = $user->goals()->orderBy('target_date');
        $goals = $category === 'All'
            ? $goalsQuery->get()
            : $goalsQuery->where('category', $category)->get();

        // Active vs completed
        $activeGoals = $goals->where('status', 'active')->sortBy('target_date');
        $completedGoals = $goals->where('status', 'completed');

        $categories = ['All', 'Health', 'Career', 'Learning', 'Personal', 'Finance'];

        // Stats
        $stats = [
            'active' => $user->goals()->where('status', 'active')->count(),
            'completed' => $user->goals()->where('status', 'completed')->count(),
            'longestStreak' => (int) $user->goals()->max('streak'),
            'totalSaved' => (float) $user->goals()->where('category', 'Finance')->sum('amount_saved'),
        ];

        // Weekly check-ins
        $week = collect(range(6, 0))->map(function ($daysAgo) use ($user) {
            $date = Carbon::today()->subDays($daysAgo);
            $count = $user->goals()
                ->join('checkins', 'goals.id', '=', 'checkins.goal_id')
                ->whereDate('checkins.date', $date)
                ->count();

            return ['label' => $date->format('D'), 'count' => $count];
        });
        $max = max(1, $week->max('count'));
        $week = $week->map(fn($d) => $d + ['pct' => round(($d['count'] / $max) * 100)]);

        // Quote of the day
        $quote = $this->generalQuotes[now()->day % count($this->generalQuotes)];

        // Reflections
        $reflections = $user->goals()
            ->with('reflections')
            ->get()
            ->pluck('reflections')
            ->flatten()
            ->sortByDesc('date');

        return view('dashboard', [
            'activeGoals' => $activeGoals,
            'completedGoals' => $completedGoals,
            'categories' => $categories,
            'activeCategory' => $category,
            'stats' => $stats,
            'week' => $week,
            'quote' => $quote,
            'reflections' => $reflections,
            'loginStreak' => $request->session()->get('login_streak', 1),
        ]);
    }
}
