<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-xl font-bold text-white mb-2">Lupa Password? 🔒</h2>
        <p class="text-sm text-gray-400 leading-relaxed">
            {{ __('Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-300 ml-1" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" class="block w-full pl-10 bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-purple focus:ring-neon-purple shadow-inner px-4 py-3 sm:text-sm transition-all" 
                       type="email" 
                       name="email" 
                       :value="old('email')" 
                       required autofocus 
                       placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-neon-purple to-neon-pink text-white py-3 rounded-xl font-bold shadow-lg shadow-neon-purple/20 hover:shadow-neon-purple/40 transform hover:scale-[1.02] active:scale-95 transition-all duration-300 tracking-wide uppercase text-sm">
            {{ __('Kirim Link Reset') }}
        </button>

        <div class="text-center mt-6 pt-6 border-t border-white/5">
            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-white transition flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('Kembali ke Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
