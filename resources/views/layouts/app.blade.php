<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-midnight-900 text-white selection:bg-neon-purple selection:text-white h-screen overflow-hidden">
        <!-- Persistent Mesh Gradient Background -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-neon-purple/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-neon-blue/20 rounded-full blur-[120px] animate-blob animation-delay-4000"></div>
            <div class="absolute top-[20%] right-[10%] w-[30%] h-[30%] bg-neon-pink/10 rounded-full blur-[100px] animate-blob animation-delay-2000"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
        </div>

        <div class="relative h-full flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="relative z-20 pt-24 pb-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                    {{ $header }}
                </header>
            @endisset

            <!-- Fixed Container for Dashboard (Columns Scroll) / Auto Scroll for Others -->
            <main class="absolute inset-x-0 bottom-0 top-[100px] {{ request()->routeIs('dashboard') ? 'overflow-hidden' : 'overflow-y-auto no-scrollbar' }}">
                <div class="h-full">
                    {{ $slot }}
                </div>
            </main>
        </div>
        
        <!-- Global Toast Notification -->
        <div id="toast-container" class="fixed bottom-6 right-6 z-[200] flex flex-col gap-2 pointer-events-none"></div>

        <script>
            window.toast = function(message, type = 'success') {
                const container = document.getElementById('toast-container');
                const id = 'toast-' + Date.now();
                
                const el = document.createElement('div');
                el.id = id;
                el.className = `transform transition-all duration-300 translate-y-8 opacity-0 glass-card px-6 py-4 rounded-xl shadow-2xl shadow-neon-blue/20 border border-white/10 flex items-center gap-3 pointer-events-auto min-w-[300px]`;
                
                el.innerHTML = `
                    <div class="shrink-0">
                        <svg class="h-6 w-6 text-neon-blue animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-white tracking-wide">${message}</p>
                `;

                container.appendChild(el);

                // Animate In
                requestAnimationFrame(() => {
                    el.classList.remove('translate-y-8', 'opacity-0');
                });

                // Remove after 3s
                setTimeout(() => {
                    el.classList.add('translate-y-8', 'opacity-0');
                    setTimeout(() => el.remove(), 300);
                }, 3000);
            }
        </script>
    </body>
</html>
