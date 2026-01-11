<nav x-data="{ open: false }" class="fixed w-full z-[100] top-4 transition-all duration-300 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="glass-nav rounded-2xl px-6 py-3 flex justify-between items-center shadow-2xl shadow-neon-purple/5 ring-1 ring-white/10 relative">
            
            <!-- Logo -->
            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-neon-purple to-neon-pink flex items-center justify-center text-white font-black text-xs tracking-tighter shadow-lg shadow-neon-purple/20 group-hover:scale-110 transition-transform duration-300">
                        AU
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-neon-purple group-hover:to-neon-pink transition-all duration-300">
                        Komunitas AU
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden sm:flex items-center gap-8">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white transition relative group">
                    {{ __('Beranda') }}
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-neon-purple transition-all group-hover:w-full"></span>
                </x-nav-link>
            </div>

            <!-- Right Actions -->
            <div class="hidden sm:flex items-center gap-4">
                <!-- Search -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400 group-focus-within:text-neon-blue transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" placeholder="Cari..." class="bg-white/5 border border-white/10 rounded-full py-1.5 pl-10 pr-4 text-sm text-gray-200 focus:ring-2 focus:ring-neon-purple/50 focus:border-transparent focus:bg-white/10 w-48 transition-all placeholder-gray-500">
                </div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                    <div @click="open = ! open">
                        <button class="flex items-center gap-3 hover:bg-white/5 rounded-full p-1 pl-2 pr-4 transition border border-transparent hover:border-white/10">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ Auth::user()->profile_photo }}" class="h-8 w-8 rounded-full object-cover ring-2 ring-neon-purple/50">
                            @else
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-neon-blue to-neon-purple flex items-center justify-center text-white font-bold text-xs ring-2 ring-white/10">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-200 max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-48 rounded-xl shadow-2xl glass-card py-1 ring-1 ring-black ring-opacity-5 origin-top-right focus:outline-none"
                            style="display: none;">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-300 hover:bg-white/10 hover:text-white px-4 py-2 block text-sm transition font-medium">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-400 hover:bg-red-500/10 hover:text-red-300 px-4 py-2 block text-sm transition font-medium">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden mt-4 glass-card rounded-2xl overflow-hidden mx-auto max-w-5xl">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard') && !request()->has('mobile_view')">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') && !request()->has('mobile_view') ? 'text-neon-purple' : 'text-gray-500 group-hover:text-white' }} transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Beranda') }}
                </div>
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('dashboard', ['mobile_view' => 'create'])" :active="request('mobile_view') == 'create'">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request('mobile_view') == 'create' ? 'text-neon-pink' : 'text-gray-500 group-hover:text-white' }} transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('Buat Postingan') }}
                </div>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('dashboard', ['mobile_view' => 'activity'])" :active="request('mobile_view') == 'activity'">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request('mobile_view') == 'activity' ? 'text-neon-blue' : 'text-gray-500 group-hover:text-white' }} transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ __('Aktivitas') }}
                </div>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/5">
            <div class="px-4">
                <div class="font-medium text-base text-neon-purple">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300 hover:text-white hover:bg-white/5 block pl-3 pr-4 py-2 border-l-4 border-transparent hover:border-neon-blue text-base font-medium transition">
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-400 hover:text-red-300 hover:bg-red-500/10 block pl-3 pr-4 py-2 border-l-4 border-transparent hover:border-red-500 text-base font-medium transition">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
