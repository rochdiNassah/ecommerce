<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class InitSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'fullname' => 'Rochdi Nassah',
            'email' => 'rochdinassah.1998@gmail.com',
            'password' => Hash::make('1234'),
            'phone_number' => '+212620901143',
            'role' => 'admin',
            'status' => 'active',
            'is_super_admin' => true
        ]);
    }
}
