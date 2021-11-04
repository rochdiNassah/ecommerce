<?php declare(strict_types=1);

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fullname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'phone_number' => str_repeat('0', 10),
            'role' => 'delivery_driver',
            'status' => 'active'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the member should be under pending status.
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
     * Indicate that the member should be an admin.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin'
            ];
        });
    }

    /**
     * Indicate that the member should be a super admin.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function superAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin',
                'is_super_admin' => true
            ];
        });
    }

    /**
     * Indicate that the member should be a delivery driver.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function deliveryDriver()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'delivery_driver'
            ];
        });
    }

    /**
     * Indicate that the member should be a dispatcher.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function dispatcher()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'dispatcher'
            ];
        });
    }
}
