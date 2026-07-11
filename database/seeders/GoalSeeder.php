<?php

namespace Database\Seeders;

use App\Models\Checkin;
use App\Models\Goal;
use App\Models\Mood;
use App\Models\Reflection;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        // Reuses the first user if one exists (e.g. the account you registered
        // through Breeze), otherwise creates a demo account you can log in with.
        $user = User::first() ?? User::factory()->create([
            'name' => 'Demo Quester',
            'email' => 'demo@goalquest.test',
            'password' => bcrypt('password'),
        ]);

        $today = Carbon::today();

        // --- Goal 1: Health streak goal, in progress ---
        $healthGoal = Goal::create([
            'user_id' => $user->id,
            'title' => 'Morning Walk Streak',
            'category' => 'Health',
            'start_date' => $today->copy()->subDays(6),
            'target_date' => $today->copy()->addDays(20),
            'progress' => 40,
            'streak' => 3,
            'last_checkin' => $today->copy()->subDay(),
            'status' => 'active',
            'reward' => 'New walking shoes',
            'reward_unlocked' => false,
        ]);

        foreach ([6, 4, 3, 1] as $daysAgo) {
            Checkin::create([
                'goal_id' => $healthGoal->id,
                'user_id' => $user->id,
                'date' => $today->copy()->subDays($daysAgo),
                'progress_delta' => 10,
                'note' => null,
            ]);
        }
        Mood::create([
            'goal_id' => $healthGoal->id,
            'user_id' => $user->id,
            'mood' => 'driven',
            'note' => 'Felt strong on today\'s walk.',
            'logged_at' => $today->copy()->subDay(),
        ]);

        // --- Goal 2: Finance goal, partway to target ---
        $financeGoal = Goal::create([
            'user_id' => $user->id,
            'title' => 'Emergency Fund',
            'category' => 'Finance',
            'start_date' => $today->copy()->subDays(10),
            'target_date' => $today->copy()->addDays(60),
            'progress' => 35,
            'amount_target' => 2000,
            'amount_saved' => 700,
            'streak' => 2,
            'last_checkin' => $today,
            'status' => 'active',
            'reward' => 'Peace of mind',
            'reward_unlocked' => false,
        ]);

        foreach ([[10, 400], [3, 300]] as [$daysAgo, $amount]) {
            Checkin::create([
                'goal_id' => $financeGoal->id,
                'user_id' => $user->id,
                'date' => $today->copy()->subDays($daysAgo),
                'amount' => $amount,
                'note' => null,
            ]);
        }
        Mood::create([
            'goal_id' => $financeGoal->id,
            'user_id' => $user->id,
            'mood' => 'calm',
            'note' => 'Comfortable with this pace.',
            'logged_at' => $today,
        ]);

        // --- Goal 3: a completed goal, to show off the reflection journal ---
        $doneGoal = Goal::create([
            'user_id' => $user->id,
            'title' => 'Read "Meditations"',
            'category' => 'Learning',
            'start_date' => $today->copy()->subDays(30),
            'target_date' => $today->copy()->subDays(2),
            'progress' => 100,
            'streak' => 5,
            'last_checkin' => $today->copy()->subDays(2),
            'status' => 'completed',
            'completed_date' => $today->copy()->subDays(2),
            'reward' => 'Buy a new notebook',
            'reward_unlocked' => true,
        ]);

        Reflection::create([
            'goal_id' => $doneGoal->id,
            'user_id' => $user->id,
            'text' => 'Reading a chapter each night made the whole book feel effortless by the end. Consistency really did more than willpower.',
            'date' => $today->copy()->subDays(2),
        ]);
    }
}
