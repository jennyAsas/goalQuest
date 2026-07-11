<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoalQuest</title>
    @include('partials.pwa-head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=EB+Garamond:wght@400;500;600&family=Cinzel:wght@600&display=swap');

        :root {
            --bg-deep: #241511;
            --bg-panel: #2E1B15;
            --bg-panel-2: #3A231B;
            --maroon-bright: #8C2A2A;
            --gold: #C9A227;
            --gold-soft: #E4C567;
            --parchment: #EDE0C8;
            --text-muted: #B8A48C;
            --sage: #7C8B5E;
            --border: rgba(228, 197, 103, 0.16);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background:
                radial-gradient(ellipse at top, rgba(140, 42, 42, .18), transparent 60%),
                radial-gradient(ellipse at bottom, rgba(124, 139, 94, .08), transparent 55%),
                var(--bg-deep);
            color: var(--parchment);
            font-family: 'EB Garamond', serif;
            font-size: 16px;
            min-height: 100vh;
            padding: 32px 20px 80px;
        }

        .wrap {
            max-width: 1080px;
            margin: 0 auto;
        }

        h1,
        h2,
        h3 {
            font-family: 'Cormorant Garamond', serif;
            margin: 0;
        }

        .eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--gold-soft);
            margin: 0 0 8px;
        }

        header.top {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin-bottom: 32px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 26px;
        }

        header.top h1 {
            font-size: clamp(32px, 4.5vw, 46px);
            font-weight: 700;
        }

        .quote-of-day {
            max-width: 440px;
            font-size: 15px;
            color: var(--text-muted);
            font-style: italic;
            line-height: 1.55;
        }

        .quote-of-day .attr {
            display: block;
            font-size: 12px;
            font-family: 'Cinzel', serif;
            letter-spacing: .06em;
            color: var(--gold);
            margin-top: 4px;
            font-style: normal;
        }

        .streakbadge {
            display: flex;
            align-items: center;
            gap: 9px;
            background: linear-gradient(135deg, rgba(201, 162, 39, .16), rgba(201, 162, 39, .04));
            border: 1px solid rgba(201, 162, 39, .4);
            padding: 10px 18px;
            border-radius: 6px;
            font-family: 'Cinzel', serif;
            font-size: 13px;
            color: var(--gold-soft);
            white-space: nowrap;
        }

        .statsrow {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px;
            margin-bottom: 28px;
        }

        .statcard {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px 18px;
        }

        .statcard .num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 30px;
            font-weight: 700;
        }

        .statcard .lbl {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
            font-family: 'Cinzel', serif;
        }

        .chartcard {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 18px 20px;
            margin-bottom: 28px;
        }

        .chartcard h3 {
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .1em;
            font-family: 'Cinzel', serif;
            margin-bottom: 14px;
        }

        .bars {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            height: 90px;
        }

        .bar-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            justify-content: flex-end;
            height: 100%;
        }

        .bar {
            width: 100%;
            max-width: 28px;
            background: linear-gradient(180deg, var(--gold), rgba(201, 162, 39, .2));
            border-radius: 3px 3px 1px 1px;
            min-height: 4px;
        }

        .bar-col span {
            font-size: 10px;
            color: var(--text-muted);
            font-family: 'Cinzel', serif;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .section-head h2 {
            font-size: 22px;
            font-weight: 600;
        }

        .chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .chip {
            padding: 6px 15px;
            border-radius: 4px;
            border: 1px solid var(--border);
            background: var(--bg-panel);
            color: var(--text-muted);
            font-size: 13px;
            cursor: pointer;
            font-family: 'Cinzel', serif;
            text-decoration: none;
            display: inline-block;
        }

        .chip:hover {
            border-color: var(--gold);
            color: var(--parchment);
        }

        .chip.active {
            background: var(--maroon-bright);
            color: var(--parchment);
            border-color: var(--gold);
            font-weight: 600;
        }

        .btn {
            font-family: 'EB Garamond', serif;
            font-weight: 600;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            padding: 9px 17px;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--gold);
            color: #2A1B04;
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--parchment);
        }

        .btn-rose {
            background: rgba(140, 42, 42, .25);
            color: #E8A5A5;
            border: 1px solid rgba(140, 42, 42, .5);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 4px;
        }

        .goalgrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .goalcard {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .goalcard.completed {
            border-color: rgba(201, 162, 39, .5);
        }

        .catband {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
        }

        .cardtop {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .ring-wrap {
            position: relative;
            width: 64px;
            height: 64px;
            flex-shrink: 0;
        }

        .ring-wrap svg {
            transform: rotate(-90deg);
        }

        .ring-wrap .ringtrack {
            fill: none;
            stroke: rgba(237, 224, 200, .08);
            stroke-width: 6;
        }

        .ring-wrap .ringval {
            fill: none;
            stroke-width: 6;
            stroke-linecap: round;
        }

        .ring-wrap .pct {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            font-size: 12px;
        }

        .goaltitle {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            font-weight: 600;
        }

        .tag {
            display: inline-block;
            font-size: 11px;
            padding: 2px 9px;
            border-radius: 4px;
            font-family: 'Cinzel', serif;
            margin-top: 4px;
        }

        .datesline {
            font-size: 12.5px;
            color: var(--text-muted);
            display: flex;
            justify-content: space-between;
        }

        .datesline .overdue {
            color: #E8A5A5;
            font-weight: 600;
        }

        .datesline .duesoon {
            color: var(--gold-soft);
            font-weight: 600;
        }

        .amountline {
            font-size: 13px;
            color: var(--gold-soft);
            font-family: 'Cinzel', serif;
        }

        .streakline {
            font-size: 12.5px;
            color: var(--text-muted);
        }

        .rewardline {
            font-size: 12.5px;
            padding: 7px 10px;
            border-radius: 5px;
            background: rgba(237, 224, 200, .03);
            border: 1px dashed var(--border);
        }

        .rewardline.unlocked {
            border-style: solid;
            border-color: var(--gold);
            color: var(--gold-soft);
            background: rgba(201, 162, 39, .09);
        }

        .cardbtns {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: auto;
        }

        .emptystate {
            border: 1px dashed var(--border);
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            color: var(--text-muted);
        }

        .journalentry {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 12px;
        }

        .journalentry .jmeta {
            font-size: 11px;
            color: var(--gold-soft);
            font-family: 'Cinzel', serif;
            margin-bottom: 6px;
        }

        .formcard {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .formgrid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        label {
            font-size: 12px;
            color: var(--text-muted);
            display: block;
            margin-bottom: 5px;
            font-family: 'Cinzel', serif;
        }

        input,
        select,
        textarea {
            width: 100%;
            background: var(--bg-panel-2);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 9px 10px;
            color: var(--parchment);
            font-family: 'EB Garamond', serif;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 70px;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(20, 10, 7, .75);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            padding: 20px;
        }

        .modal {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 26px;
            max-width: 420px;
            width: 100%;
        }

        .moodrow {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .moodbtn {
            background: var(--bg-panel-2);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 9px 12px;
            cursor: pointer;
        }

        .moodbtn.sel {
            border-color: var(--gold);
            background: rgba(201, 162, 39, .14);
        }

        .quickbtns {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .flash {
            background: var(--bg-panel-2);
            border: 1px solid var(--gold);
            padding: 12px 18px;
            border-radius: 6px;
            font-style: italic;
            margin-bottom: 20px;
        }

        .questnav {
            display: flex;
            gap: 6px;
            margin-bottom: 22px;
        }

        .questnav a {
            font-family: 'Cinzel', serif;
            font-size: 12.5px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            border: 1px solid transparent;
        }

        .questnav a:hover {
            color: var(--parchment);
        }

        .questnav a.active {
            color: var(--gold-soft);
            border-color: var(--border);
            background: var(--bg-panel);
        }

        .calendar-card {
            background: var(--bg-panel);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px;
            overflow-x: auto;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            margin-bottom: 8px;
            min-width: 700px;
        }

        .calendar-weekdays div {
            text-align: center;
            font-family: 'Cinzel', serif;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .calendar-week {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 6px;
            margin-bottom: 6px;
            min-width: 700px;
        }

        .calendar-day {
            background: var(--bg-panel-2);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px;
            min-height: 96px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .calendar-day.outmonth {
            opacity: .35;
        }

        .calendar-day.is-today {
            border-color: var(--gold);
            box-shadow: inset 0 0 0 1px var(--gold);
        }

        .daynum {
            font-family: 'Cormorant Garamond', serif;
            font-size: 15px;
            font-weight: 600;
            color: var(--parchment);
        }

        .daylabels {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .daychk {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 10.5px;
            font-family: 'Cinzel', serif;
            letter-spacing: .02em;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid var(--c);
            background: transparent;
            cursor: default;
            text-align: left;
        }

        .daychk.checked {
            background: color-mix(in srgb, var(--c) 18%, transparent);
            color: var(--parchment);
        }

        .daychk.checked input {
            accent-color: var(--c);
        }

        .daychk.unchecked {
            cursor: pointer;
            color: var(--text-muted);
        }

        .daychk.unchecked:hover {
            color: var(--parchment);
            background: color-mix(in srgb, var(--c) 12%, transparent);
        }

        .daychk .box {
            width: 10px;
            height: 10px;
            border: 1.4px solid var(--c);
            border-radius: 2px;
            flex-shrink: 0;
        }

        .daychk span:last-child {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <nav class="questnav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('calendar') }}" class="{{ request()->routeIs('calendar') ? 'active' : '' }}">Calendar</a>
        </nav>
        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif
        {{ $slot }}
    </div>
</body>

</html>
