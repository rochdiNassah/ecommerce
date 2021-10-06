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
        $users = [
            [ // Super Admin
                'fullname' => 'Super Admin',
                'email' => 'admin@ecommerce.com',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'admin',
                'status' => 'active',
                'is_super_admin' => true
            ],
            [ // Super Admin
                'fullname' => 'Admin',
                'email' => 'super.admin@ecommerce.com',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'admin',
                'status' => 'active',
                'is_super_admin' => false
            ],
            [ // Dispatcher
                'fullname' => 'Samir',
                'email' => 'samir@ecommerce.com',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'dispatcher',
                'status' => 'active',
                'is_super_admin' => false
            ],
            [ // Delivery driver
                'fullname' => 'Ali',
                'email' => 'ali@ecommerce.com',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'delivery_driver',
                'status' => 'active',
                'is_super_admin' => false
            ],
        ];

        $products = [
            [
                'name' => 'Mimilk',
                'description' => 'Milk for kids.',
                'price' => 2.45
            ],
            [
                'name' => 'Coffee',
                'description' => 'Just a black coffee.',
                'price' => 3.00
            ],
            [
                'name' => 'Palace',
                'description' => 'World\'s largest palace',
                'price' => 1000000000.01
            ]
        ];

        DB::table('users')->insert($users);
        DB::table('products')->insert($products);
    }
}
