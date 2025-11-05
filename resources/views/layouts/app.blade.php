<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if (isset($title))
            {{ $title . ' - Cat Media' }}
        @else
            Cat Media
        @endif
    </title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Birthstone&family=Tangerine:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-purple">
    <div class="min-h-screen  flex">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="flex-1 lg:ml-64 px-8 pt-8 transition-all duration-300 relative z-10">
            {{ $slot }}
        </main>
    </div>
    @stack('scripts')
</body>

</html>
