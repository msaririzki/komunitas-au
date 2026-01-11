<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-bold text-white mb-2">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-sm text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-500/10 border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white px-6 py-3 rounded-xl font-bold transition-all duration-300 uppercase tracking-wider text-sm flex items-center gap-2 group"
    >
        <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-midnight-800 border border-white/10 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-red-500 focus:ring-red-500 shadow-inner px-4 py-3 sm:text-sm transition-all"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5 transition">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-bold shadow-lg shadow-red-500/20 transition transform active:scale-95">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
