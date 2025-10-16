<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <div class="min-h-screen flex flex-col">
        <header class="bg-slate-800 shadow">
            <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-amber-300">Gösterge Paneli</a>
                    <a href="{{ route('learn') }}" class="hover:text-amber-300">Öğren</a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-amber-300">Çıkış Yap</button>
                        </form>
                    @endauth
                </nav>
            </div>
        </header>
        <main class="flex-1">
            @yield('content')
        </main>
        <footer class="bg-slate-800 text-center py-4 text-sm text-slate-400">
            &copy; {{ date('Y') }} DeutschLern
        </footer>
    </div>
    @livewireScripts
</body>
</html>
