<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LimeSpace</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans bg-gray-50 text-black/50 dark:bg-gray-900 dark:text-white/50">
    <div class="relative min-h-screen flex flex-col items-center justify-between selection:text-white">
        <header class="flex py-4">
            @if (Route::has('login'))
            <livewire:welcome.navigation />
            @endif
        </header>

        <main class="mt-6 space-y-2">
            <div class="flex lg:justify-center lg:col-start-2">
                <img src="/logo.svg" alt="LimeSpace Logo" class="w-40 h-auto">
            </div>
            <div class="flex-col place-items-center max-w-md space-y-2">
                <h1 class="mb-6">Welcome to LimeSpace!</h1>
                <p>LimeSpace is an place for you to create playlists and show off your taste in music to others.</p>
                <p>Click register to create your account and get started showing off and discovering music!</p>
            </div>
        </main>

        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
            <p>is this a security vulnerability...</p>
        </footer>
    </div>
</body>

</html>