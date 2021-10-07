<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Radio',
            'normal_price' => 800.00,
            'promotional_price' => 712.99,
        ]);
        Product::create([
            'name' => 'Portable Audio',
            'normal_price' => 16.00,
            'promotional_price' => 15.00,
        ]);
        Product::create([
            'name' => 'Radio',
            'normal_price' => 9.99,
            'promotional_price' => 8.79,
        ]);
        Product::create([
            'name' => 'Scanner',
            'normal_price' => 124.00,
            'promotional_price' => 120.00,
        ]);
        Product::create([
            'name' => 'Camcorders',
            'normal_price' => 359.00,
            'promotional_price' => 303.00,
        ]);
        User::create([
            'name' => 'Customer 1',
            'email' => 'customer1@point.system',
            'email_verified_at' => now(),
            'is_customer' => true,
            'password' => Hash::make('Customer'),
        ]);
    }
}
