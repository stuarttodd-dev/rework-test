<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        $name = fake()->company().' Warehouse';
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
            'geo_location' => null,
            'address_1' => fake()->streetAddress(),
            'address_2' => fake()->optional()->secondaryAddress(),
            'town' => fake()->city(),
            'county' => fake()->optional()->state(),
            'postcode' => fake()->postcode(),
            'state_code' => fake()->optional()->stateAbbr(),
            'country_code' => fake()->countryCode(),
        ];
    }
}
