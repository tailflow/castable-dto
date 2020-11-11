<?php

declare(strict_types=1);

namespace Tailflow\DataTransferObjects\Tests\Fixtures\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tailflow\DataTransferObjects\Tests\Fixtures\App\Models\User;

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
        return [
            'name' => $this->faker->name,
            'work_address' => [
                'country' => $this->faker->countryCode,
                'city' => $this->faker->city,
                'street' => $this->faker->streetAddress
            ]
        ];
    }
}