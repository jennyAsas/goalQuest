<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoalQuest</title>
    @include('partials.theme-style')
    @include('partials.pwa-head')
    <style>
        .hero {
            max-width: 720px;
            margin: 80px auto 0;
            text-align: center;
            padding: 0 20px;
        }

        .hero .eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 12px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--gold-soft);
        }

        .hero h1 {
            font-size: clamp(36px, 6vw, 56px);
            margin: 14px 0 18px;
        }

        .hero p {
            color: var(--text-muted);
            font-size: 17px;
            line-height: 1.6;
            max-width: 520px;
            margin: 0 auto 32px;
        }

        .hero .ctas {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .features {
            max-width: 900px;
            margin: 80px auto 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 0 20px 60px;
        }

        .feature {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 24px;
        }

        .feature h3 {
            font-size: 19px;
            margin-bottom: 8px;
            color: var(--gold-soft);
        }

        .feature p {
            color: var(--text-muted);
            font-size: 14.5px;
            line-height: 1.55;
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="hero">
        <p class="eyebrow">Quest Log</p>
        <h1>GoalQuest</h1>
        <p>Track goals with dates, streaks, and dollar targets — logged on a calendar, celebrated with a reward, and
            remembered in a journal.</p>
        <div class="ctas">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-ghost">Register</a>
                @endif
            @endauth
        </div>
    </div>

    <div class="features">
        <div class="feature">
            <h3>Goals & streaks</h3>
            <p>Health, Career, Learning, Personal, or Finance goals with start/target dates and a running streak.</p>
        </div>
        <div class="feature">
            <h3>Quest Calendar</h3>
            <p>A month grid showing every check-in, with quick boxes to log today's progress at a glance.</p>
        </div>
        <div class="feature">
            <h3>Rewards & reflections</h3>
            <p>Set a reward for finishing, then write a short reflection — building a journal of what got you there.</p>
        </div>
    </div>

</body>

</html>
