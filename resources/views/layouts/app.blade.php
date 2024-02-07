<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>@yield('title', 'Time Tracking App')</title>
    <!-- Add your CSS styles or include a CSS framework here -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            display: inline;
        }
    </style>
    <!-- ... other meta tags ... -->
    @stack('styles')
</head>
<body>

    <header>
        <h1>@yield('header', 'Time Tracking App')</h1>
    </header>

    <nav>
        <!-- Add navigation links if needed -->
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Add footer content if needed -->
    </footer>
</body>
</html>
