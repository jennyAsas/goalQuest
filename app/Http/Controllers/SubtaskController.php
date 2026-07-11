<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request)
    {
        Subtask::create($request->validate([
            'goal_id' => 'required|exists:goals,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
        ]));

        return back()->with('success', 'Subtask added!');
    }

    public function toggle(Subtask $subtask)
    {
        $subtask->update(['completed' => ! $subtask->completed]);
        return back();
    }
}
