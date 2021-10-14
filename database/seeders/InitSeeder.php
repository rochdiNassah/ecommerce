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
                'avatar_path' => asset('images/avatars/super-admin.jpg'),
                'is_super_admin' => true
            ],
            [ // Super Admin
                'fullname' => 'Admin',
                'email' => 'admin@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'admin',
                'status' => 'active',
                'avatar_path' => asset('images/avatars/admin.jpg'),
                'is_super_admin' => false
            ],
            [ // Dispatcher
                'fullname' => 'Samir',
                'email' => 'dispatcher@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'dispatcher',
                'status' => 'active',
                'avatar_path' => asset('images/avatars/dispatcher.jpg'),
                'is_super_admin' => false
            ],
            [ // Delivery driver
                'fullname' => 'Ali',
                'email' => 'delivery@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'delivery_driver',
                'status' => 'active',
                'avatar_path' => asset('images/avatars/delivery-driver.jpg'),
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
                'name' => 'Table',
                'price' => 30.00,
                'image_path' => asset('images/products/table.jpg')
            ],
            [
                'name' => 'Mac pro',
                'price' => 5000.00,
                'image_path' => asset('images/products/mac-pro.jpg')
            ]
        ];

        DB::table('users')->insert($users);
        DB::table('products')->insert($products);
    }
}
