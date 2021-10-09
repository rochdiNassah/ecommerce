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
                'price' => 3.45,
                'image_path' => asset('images/products/coffee.jpg')
            ],
            [
                'name' => 'Camera',
                'price' => 600.00,
                'image_path' => asset('images/products/camera.jpg')
            ],
            [
                'name' => 'Dog',
                'price' => 90.00,
                'image_path' => asset('images/products/dog.jpg')
            ],
            [
                'name' => 'Cat',
                'price' => 69.95,
                'image_path' => asset('images/products/cat.jpg')
            ],
            [
                'name' => 'Chair',
                'price' => 60.00,
                'image_path' => asset('images/products/chair.jpg')
            ],
            [
                'name' => 'Table',
                'price' => 30.00,
                'image_path' => asset('images/products/table.jpg')
            ],
            [
                'name' => 'Mac pro',
                'price' => 5000.00,
                'image_path' => asset('images/products/mac-pro.jpg')
            ],
            [
                'name' => 'Oven',
                'price' => 270.00,
                'image_path' => asset('images/products/oven.jpg')
            ]
        ];

        DB::table('users')->insert($users);
        DB::table('products')->insert($products);
    }
}
