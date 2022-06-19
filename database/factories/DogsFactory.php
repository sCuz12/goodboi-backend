<?php

namespace Database\Factories;

use App\Models\Dogs;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DogsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dogs::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(5),
            'title' => $this->faker->text(5),
            'description' => $this->faker->text,
            'dob'        => $this->faker->date(),
            'cover_image'     => 'default_cover.png',
            'size'            => "s",
            'user_id'   => '1',
            'shelter_id' => '1',
            'slug'      => 'test',
            'status_id' => rand(1, 5),
            'breed_id' => rand(0, 100),
            'city_id' => rand(1, 10),
        ];
    }
}
