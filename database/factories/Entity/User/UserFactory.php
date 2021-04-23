<?php

namespace Database\Factories\Entity\User;

use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $active = $this->faker->boolean;
        $phoneActive = $this->faker->boolean;

        return [
            'name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'phone_verified' => $phoneActive,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'verify_token' => $active ? null : Str::uuid(),
            'phone_verify_token' => $phoneActive ? null : Str::uuid(),
            'phone_verify_token_expire' => $phoneActive ? null : Carbon::now()->addSeconds(300),
            'role' => $active
                ? $this->faker->randomElement(array_keys(User::rolesList()))
                : User::ROLE_USER,
            'status' => $active ? User::STATUS_ACTIVE : User::STATUS_WAIT,
        ];
    }
}
