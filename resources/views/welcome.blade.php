<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Freelancer Timesheet</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="antialiased">
    <div class="container text-center">
        <h1>Welcome To Freelancer Timesheet</h1>
        <a href="{{ route('timeLogs.index') }}" class="btn btn-success">View Timelogs</a>
    </div>
</body>
</html>
