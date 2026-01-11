<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white mb-2">Buat Akun Baru 🚀</h2>
        <p class="text-sm text-gray-400">Gabung komunitas dan mulai berkarya.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-300 ml-1" />
            <input id="name" class="block mt-1 w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-teal focus:ring-neon-teal shadow-inner px-4 py-3 sm:text-sm transition-all" 
                   type="text" 
                   name="name" 
                   :value="old('name')" 
                   required autofocus autocomplete="name"
                   placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-300 ml-1" />
            <input id="email" class="block mt-1 w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-teal focus:ring-neon-teal shadow-inner px-4 py-3 sm:text-sm transition-all" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   required autocomplete="username"
                   placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="text-gray-300 ml-1" />
            <div class="relative mt-1">
                <input id="password" class="block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-teal focus:ring-neon-teal shadow-inner px-4 py-3 pr-10 sm:text-sm transition-all"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required autocomplete="new-password"
                       placeholder="••••••••" />
                
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

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-300 ml-1" />
            <div class="relative mt-1">
                <input id="password_confirmation" class="block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:border-neon-teal focus:ring-neon-teal shadow-inner px-4 py-3 pr-10 sm:text-sm transition-all"
                       :type="show ? 'text' : 'password'"
                       name="password_confirmation" required autocomplete="new-password"
                       placeholder="••••••••" />
                
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
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-neon-teal to-neon-blue text-white py-3 rounded-xl font-bold shadow-lg shadow-neon-teal/20 hover:shadow-neon-teal/40 transform hover:scale-[1.02] active:scale-95 transition-all duration-300 tracking-wide uppercase text-sm">
            {{ __('Daftar Sekarang') }}
        </button>

        <div class="text-center mt-6 pt-6 border-t border-white/5">
            <p class="text-sm text-gray-500">
                {{ __('Sudah punya akun?') }}
                <a href="{{ route('login') }}" class="font-bold text-white hover:text-neon-teal transition underline decoration-neon-teal/30 decoration-2 underline-offset-4 hover:decoration-neon-teal">
                    {{ __('Masuk disini') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
