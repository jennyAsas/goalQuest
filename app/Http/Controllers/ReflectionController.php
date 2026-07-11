<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Reflection;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function store(Request $request, Goal $goal)
    {
        abort_unless($goal->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'text' => ['required', 'string', 'max:2000'],
        ]);

        Reflection::create([
            'goal_id' => $goal->id,
            'user_id' => $request->user()->id,
            'text' => $data['text'],
            'date' => now()->toDateString(),
        ]);

        return back()->with('status', 'Reflection saved to your journal.');
    }
}
