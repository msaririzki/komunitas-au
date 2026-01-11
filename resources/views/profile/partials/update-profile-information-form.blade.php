<section>
    <header>
        <h2 class="text-2xl font-bold text-white mb-2">
            {{ __('Profile Information') }}
        </h2>

        <p class="text-sm text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6" enctype="multipart/form-data" 
          x-data="{ submitting: false }" 
          @submit="submitting = true">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div x-data="{ photoName: null, photoPreview: null }">
            <label class="block text-sm font-medium text-gray-300 mb-2">Profile Photo</label>
            <div class="flex items-center gap-6">
                <!-- Current Profile Photo -->
                <div class="shrink-0">
                    <img x-show="!photoPreview" src="{{ $user->profile_photo ? $user->profile_photo : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                         alt="{{ $user->name }}" 
                         class="h-24 w-24 object-cover rounded-full ring-4 ring-white/10 shadow-xl">
                    <!-- New Photo Preview -->
                    <div x-show="photoPreview" style="display: none;">
                        <span class="block h-24 w-24 rounded-full ring-4 ring-neon-blue shadow-xl shadow-neon-blue/20 bg-cover bg-no-repeat bg-center"
                              :style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>
                </div>

                <!-- File Input Button -->
                <div class="relative">
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden"
                           x-ref="photo"
                           x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />
                    <button type="button" x-on:click.prevent="$refs.photo.click()" class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/10 active:bg-white/20 focus:outline-none focus:ring-2 focus:ring-neon-blue focus:ring-offset-2 focus:ring-offset-gray-800 transition ease-in-out duration-150 shadow-lg">
                        {{ __('Select New Photo') }}
                    </button>
                    <p class="mt-2 text-xs text-gray-500" x-text="photoName ?? 'No file selected'"></p>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-gray-300" />
            <input id="name" name="name" type="text" class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Nama Lengkap Anda" />
            <p class="text-xs text-gray-500 mt-1">Nama yang akan muncul di profil dan postingan (Boleh pakai spasi).</p>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" class="text-gray-300" />
            <div class="relative mt-1 rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="text-gray-400 sm:text-sm">@</span>
                </div>
                <input id="username" name="username" type="text" class="block w-full rounded-xl border border-white/10 bg-midnight-900/50 py-3 pl-8 pr-4 text-white placeholder-gray-500 focus:border-neon-blue focus:ring-neon-blue sm:text-sm shadow-inner transition-all" value="{{ old('username', $user->username) }}" required autocomplete="username" placeholder="username_unik" />
            </div>
             <p class="text-xs text-gray-500 mt-1">ID Unik untuk login dan link profil (Tanpa spasi, contoh: @jokowidodo).</p>
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio')" class="text-gray-300" />
            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all resize-none" placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
            <input id="email" name="email" type="email" class="mt-1 block w-full bg-midnight-900/50 border border-white/10 rounded-xl text-white focus:border-neon-blue focus:ring-neon-blue shadow-inner px-4 py-3 sm:text-sm transition-all" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-neon-blue hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" 
                    :disabled="submitting"
                    class="bg-gradient-to-r from-neon-blue to-neon-purple text-white px-8 py-3 rounded-full font-bold shadow-lg shadow-neon-blue/20 hover:shadow-neon-blue/40 transform hover:scale-105 active:scale-95 transition-all duration-300 tracking-wide uppercase text-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                
                <!-- Spinner -->
                <svg x-show="submitting" class="animate-spin -ml-1 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span x-text="submitting ? 'Menyimpan...' : '{{ __('Simpan Perubahan') }}'"></span>
            </button>

            @if (session('status') === 'profile-updated')
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        window.toast('Profil berhasil diperbarui! ✨');
                    })
                </script>
            @endif
        </div>
    </form>
</section>
