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
                'address' => '9692 East 3rd Rd. Havertown, PA 19083.'
            ]),
            'product_id' => 1,
            'token' => bin2hex(openssl_random_pseudo_bytes(64)),
            'dispatcher_id' => 3,
            'delivery_driver_id' => 4,
        ];
    }

    /**
     * Indicate that the order should be under pending status.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending'
            ];
        });
    }

    /**
     * Indicate that the order should be under rejected status.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected'
            ];
        });
    }

    /**
     * Indicate that the order should be under dispatched status.
     * 
     * @param  int  $delivery_driver_id
     * @param  int  $dispatcher_id
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dispatched(int $delivery_driver_id = 4, int $dispatcher_id = 3)
    {
        return $this->state(function (array $attributes) use ($delivery_driver_id, $dispatcher_id) {
            return [
                'status' => 'dispatched',
                'delivery_driver_id' => $delivery_driver_id,
                'dispatcher_id' => $dispatcher_id
            ];
        });
    }

    /**
     * Indicate that the order should be under delivered status.
     * 
     * @param  int  $delivery_driver_id
     * @param  int  $dispatcher_id
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function delivered(int $delivery_driver_id = 4, int $dispatcher_id = 3)
    {
        return $this->state(function (array $attributes) use ($delivery_driver_id, $dispatcher_id) {
            return [
                'status' => 'delivered',
                'delivery_driver_id' => $delivery_driver_id,
                'dispatcher_id' => $dispatcher_id
            ];
        });
    }
}
