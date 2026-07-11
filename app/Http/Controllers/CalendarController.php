<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        $firstOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $lastOfMonth = $firstOfMonth->copy()->endOfMonth();

        // Pad the grid out to full weeks (Sun–Sat) before/after the month.
        $gridStart = $firstOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $gridEnd = $lastOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $goals = $user->goals()->orderBy('title')->get();
        $goalIds = $goals->pluck('id');

        $checkins = Checkin::whereIn('goal_id', $goalIds)
            ->whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->get()
            ->groupBy(fn($c) => $c->date->toDateString())
            ->map(fn($dayCheckins) => $dayCheckins->pluck('goal_id'));

        $days = collect();
        $cursor = $gridStart->copy();
        while ($cursor->lte($gridEnd)) {
            $dateKey = $cursor->toDateString();
            $days->push([
                'date' => $cursor->copy(),
                'inMonth' => $cursor->month === $month,
                'isToday' => $cursor->isToday(),
                'checkedGoalIds' => $checkins->get($dateKey, collect()),
            ]);
            $cursor->addDay();
        }
        $subtasks = \App\Models\Subtask::whereIn('goal_id', $goalIds)
            ->whereBetween('date', [$gridStart, $gridEnd])
            ->get()
            ->groupBy(fn($s) => $s->date->toDateString());

        $days = collect();
        $cursor = $gridStart->copy();
        while ($cursor->lte($gridEnd)) {
            $dateKey = $cursor->toDateString();
            $days->push([
                'date' => $cursor->copy(),
                'inMonth' => $cursor->month === $month,
                'isToday' => $cursor->isToday(),
                'checkedGoalIds' => $checkins->get($dateKey, collect()),
                'subtasks' => $subtasks->get($dateKey, collect()), // ✅ always set
            ]);
            $cursor->addDay();
        }



        return view('calendar', [
            'weeks' => $days->chunk(7),
            'goals' => $goals,
            'month' => $month,
            'year' => $year,
            'monthLabel' => $firstOfMonth->format('F Y'),
            'prevMonth' => $firstOfMonth->copy()->subMonth(),
            'nextMonth' => $firstOfMonth->copy()->addMonth(),
        ]);
    }
}
