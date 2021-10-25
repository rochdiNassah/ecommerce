<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
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
        $order = [
            [    
                'customer' => json_encode([
                    'fullname' => Str::random(10),
                    'email' => Str::random(10).'@foo.bar',
                    'phone_number' => str_repeat('0', 10),
                    'address' => 'Corge, grault'
                ]),
                'product_id' => 1,
                'token' => bin2hex(openssl_random_pseudo_bytes(64))
            ]
        ];

        DB::table('users')->insert($users);
        DB::table('products')->insert($products);
        DB::table('orders')->insert($order);
    }
}
