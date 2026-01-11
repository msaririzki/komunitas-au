<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Komunitas AU') }}</title>
        <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-white selection:bg-neon-purple selection:text-white bg-midnight-900 h-screen overflow-hidden">
        <!-- Persistent Mesh Gradient Background -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-neon-purple/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-neon-blue/20 rounded-full blur-[120px] animate-blob animation-delay-4000"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
        </div>

        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 relative z-10 p-4">
            <div class="mb-8">
                <a href="/" class="group flex flex-col items-center gap-4">
                     <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-neon-purple to-neon-pink flex items-center justify-center text-white font-black text-2xl tracking-tighter shadow-2xl shadow-neon-purple/30 group-hover:scale-110 transition-transform duration-500 hover:rotate-6">
                        AU
                    </div>
                    <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400 group-hover:from-neon-purple group-hover:to-neon-pink transition-all duration-300">
                        Komunitas AU
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 glass-card border border-white/10 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
                <!-- Card Glow Effect -->
                <div class="absolute -top-20 -right-20 w-40 h-40 bg-neon-blue/20 rounded-full blur-3xl opacity-50 group-hover:opacity-80 transition duration-1000"></div>
                <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-neon-purple/20 rounded-full blur-3xl opacity-50 group-hover:opacity-80 transition duration-1000"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-8 text-xs text-gray-500 font-medium">
                &copy; {{ date('Y') }} Komunitas AU. All rights reserved.
            </div>
        </div>
    </body>
</html>
