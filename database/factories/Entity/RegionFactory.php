<?php

namespace Database\Factories\Entity;

use App\Entity\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Region::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->city,
            'slug' => $this->faker->unique()->slug(2),
            'parent_id' => null,
        ];
    }
}
