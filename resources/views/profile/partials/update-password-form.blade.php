<section>
    <header>
        <h2 class="text-2xl font-bold text-white mb-2">
            {{ __('Update Password') }}
        </h2>

        <p class="text-sm text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-6"
          x-data="{ submitting: false }"
          @submit="submitting = true">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-gray-300" />
            <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-gray-300" />
            <input id="update_password_password" name="password" type="password" class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-gray-300" />
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" 
                    :disabled="submitting"
                    class="bg-gradient-to-r from-neon-purple to-neon-pink text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-neon-purple/20 hover:shadow-neon-purple/40 transform hover:scale-105 active:scale-95 transition-all duration-300 tracking-wide uppercase text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                
                <!-- Spinner -->
                <svg x-show="submitting" class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                
                <span x-text="submitting ? 'Menyimpan...' : '{{ __('Update Password') }}'"></span>
            </button>

            @if (session('status') === 'password-updated')
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        window.toast('Password berhasil diperbarui! 🔒');
                    })
                </script>
            @endif
        </div>
    </form>
</section>
