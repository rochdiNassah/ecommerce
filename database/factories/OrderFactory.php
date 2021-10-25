<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer' => json_encode([
                'fullname' => Str::random(10),
                'email' => Str::random(10).'@foo.bar',
                'phone_number' => str_repeat('0', 10),
                'address' => 'Corge, grault'
            ]),
            'product_id' => 1,
            'token' => bin2hex(openssl_random_pseudo_bytes(64))
        ];
    }
}
