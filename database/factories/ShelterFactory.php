<?php

namespace Database\Factories;

use App\Models\Shelter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ShelterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shelter::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' =>   $this->faker->numberBetween(0, 10),
            'shelter_name' => $this->faker->text,
            'slug'        =>  $this->faker->text,
            'address' => $this->faker->text,
            'phone'  =>  $this->faker->phoneNumber,
            'description' => $this->faker->text,
            'city_id'  => rand(1, 5),
            'is_profile_complete' => rand(0, 1)
        ];
    }
}
