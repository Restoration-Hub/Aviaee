<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missions</title>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<body>
    <div class="header">
        <div>AVIAEE</div>
    </div>

    <div class="missions-header-container">
        <h1 class="missions-header">Missions</h1>
    </div>

    <div class="missions-main">

        {{-- Livewire missions table (includes search input & status filter, and the Create button) --}}
        <livewire:missions-table />

    </div>

    @livewireScripts

</body>
</html>