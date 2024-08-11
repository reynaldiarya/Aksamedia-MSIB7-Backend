<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'username' =>  'admin',
            'phone' =>  '1111111111',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('pastibisa'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
