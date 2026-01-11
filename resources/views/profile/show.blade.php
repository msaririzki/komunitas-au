<x-app-layout>
    <div class="py-24">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Profile Header -->
            <div class="glass-card rounded-[2rem] overflow-hidden relative group">
                <!-- Dynamic Banner -->
                <div class="h-48 bg-gradient-to-r from-neon-purple via-midnight-700 to-neon-blue relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay"></div>
                    <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-neon-purple/30 rounded-full blur-3xl animate-blob"></div>
                </div>

                <div class="px-8 pb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-end -mt-16 mb-6 gap-4">
                        <div class="relative group-hover:scale-105 transition duration-500">
                            <div class="absolute -inset-1 bg-gradient-to-br from-neon-purple to-neon-blue rounded-full blur opacity-75"></div>
                            @if($user->profile_photo)
                                <img src="{{ $user->profile_photo }}" class="h-32 w-32 rounded-full object-cover border-4 border-midnight-900 relative z-10 shadow-2xl">
                            @else
                                <div class="h-32 w-32 rounded-full bg-midnight-800 flex items-center justify-center border-4 border-midnight-900 relative z-10 shadow-2xl">
                                    <span class="text-4xl font-black text-white bg-clip-text bg-gradient-to-br from-neon-purple to-neon-blue text-transparent">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex gap-3 relative z-10">
                            @if(Auth::id() === $user->id)
                                <a href="{{ route('profile.edit') }}" class="glass-nav px-6 py-2.5 rounded-full text-sm font-bold border border-white/10 hover:bg-white/10 hover:border-white/30 transition text-white">
                                    Edit Profil
                                </a>
                            @else
                                <button class="bg-white text-midnight-900 px-8 py-2.5 rounded-full font-bold shadow-lg hover:shadow-xl hover:scale-105 transition text-sm">
                                    Follow
                                </button>
                                <button class="p-2.5 rounded-full glass-nav hover:bg-white/10 transition text-white">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h1 class="text-3xl font-black text-white tracking-tight mb-1 flex items-center gap-2">
                            {{ $user->name }}
                        </h1>
                        <p class="text-neon-blue font-medium tracking-wide mb-4">{{ '@' . $user->username }}</p>
                        
                        @if($user->bio)
                            <p class="text-gray-300 leading-relaxed max-w-2xl font-light text-lg">{{ $user->bio }}</p>
                        @endif

                        <div class="flex items-center gap-8 mt-6 pt-6 border-t border-white/5">
                            <div class="flex flex-col">
                                <span class="font-black text-xl text-white">{{ $posts->count() }}</span>
                                <span class="text-xs text-gray-500 font-bold tracking-wider uppercase">Postingan</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-xl text-white">1.2k</span>
                                <span class="text-xs text-gray-500 font-bold tracking-wider uppercase">Pengikut</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-xl text-white">450</span>
                                <span class="text-xs text-gray-500 font-bold tracking-wider uppercase">Mengikuti</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Posts Header -->
            <div class="flex items-center gap-4 text-white pb-2 border-b border-white/10">
                <h2 class="text-xl font-bold tracking-tight">Timeline</h2>
                <div class="h-1 flex-1 bg-gradient-to-r from-neon-purple/50 to-transparent rounded-full"></div>
            </div>

            <!-- User Posts Feed -->
            <div>
                @forelse($posts as $post)
                     <x-post-card :post="$post" />
                @empty
                    <div class="text-center py-20 bg-white/5 rounded-3xl border border-white/5 border-dashed">
                        <p class="text-gray-500 text-lg">Hening... Belum ada cerita.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
