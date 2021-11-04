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
        $members = [
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
                'fullname' => 'Corge',
                'email' => 'dispatcher@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'dispatcher',
                'status' => 'active',
                'is_super_admin' => false
            ],
            [ // Delivery driver
                'fullname' => 'Grault',
                'email' => 'delivery@foo.bar',
                'password' => Hash::make('1234'),
                'phone_number' => '1020304050',
                'role' => 'delivery_driver',
                'status' => 'active',
                'is_super_admin' => false
            ],
        ];
        $products = [
            [
                'name' => 'Table',
                'price' => 29.98,
                'image_path' => 'images/products/table.jpg'
            ],
            [
                'name' => 'Solid wooden bed',
                'price' => 950.00,
                'image_path' => 'images/products/solid-wooden-bed.jpg'
            ],
            [
                'name' => 'Sofa',
                'price' => 399.90,
                'image_path' => 'images/products/sofa.jpg'
            ],
            [
                'name' => 'Bookshelves',
                'price' => 59.90,
                'image_path' => 'images/products/bookshelves.jpg'
            ],
            [
                'name' => 'Wooden bookshelve',
                'price' => 79.90,
                'image_path' => 'images/products/wooden-bookshelve.jpg'
            ],
            [
                'name' => 'Chair',
                'price' => 45.00,
                'image_path' => 'images/products/chair.jpg'
            ],
            [
                'name' => 'Desk',
                'price' => 499.90,
                'image_path' => 'images/products/desk.jpg'
            ],
            [
                'name' => 'Small desk',
                'price' => 299.90,
                'image_path' => 'images/products/small-desk.jpg'
            ]
        ];

        DB::table('members')->insert($members);
        DB::table('products')->insert($products);
    }
}
