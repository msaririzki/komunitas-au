<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Selamat Datang! 👋</h2>
        <p class="text-sm text-gray-400">Masuk untuk mulai berbagi cerita seru.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                <input id="email" class="block w-full pl-10 bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all" 
                       type="email" 
                       name="email" 
                       :value="old('email')" 
                       required autofocus autocomplete="username"
                       placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="text-gray-300 ml-1" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input id="password" class="block w-full pl-10 pr-10 bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required autocomplete="current-password"
                       placeholder="••••••••" />
                
                <!-- Eye Toggle -->
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white transition focus:outline-none">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-600 bg-midnight-800 text-neon-blue shadow-sm focus:ring-neon-blue" name="remember">
                <span class="ms-2 text-sm text-gray-400 group-hover:text-white transition">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-neon-purple hover:text-white transition font-medium" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-neon-blue to-neon-purple text-white py-3 rounded-xl font-bold shadow-lg shadow-neon-blue/20 hover:shadow-neon-blue/40 transform hover:scale-[1.02] active:scale-95 transition-all duration-300 tracking-wide uppercase text-sm">
            {{ __('Masuk Sekarang') }}
        </button>

        <!-- Register Link -->
        <div class="text-center mt-6 pt-6 border-t border-white/5">
            <p class="text-sm text-gray-500">
                {{ __('Belum punya akun?') }}
                <a href="{{ route('register') }}" class="font-bold text-white hover:text-neon-blue transition underline decoration-neon-blue/30 decoration-2 underline-offset-4 hover:decoration-neon-blue">
                    {{ __('Daftar disini') }}
                </a>
                🚀
            </p>
        </div>
    </form>
</x-guest-layout>
