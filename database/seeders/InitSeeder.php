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
                'email' => 'sadmin@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'admin',
                'status' => 'active',
                'is_super_admin' => true
            ],
            [ // Super Admin
                'fullname' => 'Admin',
                'email' => 'admin@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'admin',
                'status' => 'active',
                'is_super_admin' => false
            ],
            [ // Dispatcher
                'fullname' => 'Samir',
                'email' => 'samir@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'dispatcher',
                'status' => 'active',
                'is_super_admin' => false
            ],
            [ // Delivery driver
                'fullname' => 'Ali',
                'email' => 'ali@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'delivery_driver',
                'status' => 'active',
                'is_super_admin' => false
            ],
        ];

        $products = [
            [
                'name' => 'Coffee',
                'description' => 'Just a black coffee.',
                'price' => 3.45,
                'image_path' => asset('images/products/coffee.jpg')
            ],
            [
                'name' => 'Palace',
                'description' => 'The biggest palace in the world.',
                'price' => 1000000000.01,
                'image_path' => asset('images/products/palace.jpg')
            ]
        ];

        DB::table('users')->insert($users);
        DB::table('products')->insert($products);
    }
}
