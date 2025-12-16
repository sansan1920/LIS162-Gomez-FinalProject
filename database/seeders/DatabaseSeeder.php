<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserTypeSeeder::class);
        $this->call(FacilityScheduleSeeder::class);
        
        User::factory()->create([
            'first_name' => 'Site',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'user_types_id' => 1,           
        ]);
    }
}
