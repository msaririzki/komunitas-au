<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all users who have a NULL username
        $users = \App\Models\User::whereNull('username')->get();
        
        foreach ($users as $user) {
            $username = \Illuminate\Support\Str::slug($user->name);
            
            // Ensure uniqueness
            if (\App\Models\User::where('username', $username)->exists()) {
                $username = $username . '-' . $user->id;
            }
            
            $user->update(['username' => $username]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert this as username is a required field for application logic now.
    }
};
