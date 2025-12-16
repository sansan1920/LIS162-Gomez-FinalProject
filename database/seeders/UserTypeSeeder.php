<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_types')->insert([
            ['user_types_id' => 1, 'user_type' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['user_types_id' => 2, 'user_type' => 'Undergraduate', 'created_at' => now(), 'updated_at' => now()],
            ['user_types_id' => 3, 'user_type' => 'Graduate', 'created_at' => now(), 'updated_at' => now()],
            ['user_types_id' => 4, 'user_type' => 'Faculty', 'created_at' => now(), 'updated_at' => now()],
            ['user_types_id' => 5, 'user_type' => 'Guest', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
