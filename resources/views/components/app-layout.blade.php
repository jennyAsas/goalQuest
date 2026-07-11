<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoalQuest</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js" defer></script>
    @include('partials.theme-style')
    @include('partials.pwa-head')
</head>

<body>
    <div class="wrap">
        @include('layouts.navigation')

        @if (isset($header))
            <header style="margin-bottom:24px;">{{ $header }}</header>
        @endif

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        <main>{{ $slot }}</main>
    </div>
</body>

</html>
