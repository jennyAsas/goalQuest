<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GoalQuest</title>
@include('partials.theme-style')
@include('partials.pwa-head')
</head>
<body class="authbody">
    <a href="/" class="authlogo"><x-application-logo /></a>
    <div class="authcard">
        {{ $slot }}
    </div>
</body>
</html>