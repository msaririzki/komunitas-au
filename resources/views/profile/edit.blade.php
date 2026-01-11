<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Profile Info & Bio -->
            <div class="p-8 glass-card border border-white/10 rounded-[2.5rem] relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-neon-blue/10 rounded-full blur-3xl group-hover:bg-neon-blue/20 transition duration-1000"></div>
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Update -->
            <div class="p-8 glass-card border border-white/10 rounded-[2.5rem] relative overflow-hidden group">
                <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-neon-purple/10 rounded-full blur-3xl group-hover:bg-neon-purple/20 transition duration-1000"></div>
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-8 glass-card border border-red-500/10 rounded-[2.5rem] relative overflow-hidden group">
                 <div class="absolute inset-0 bg-red-500/5 group-hover:bg-red-500/10 transition duration-700"></div>
                <div class="relative z-10 max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
