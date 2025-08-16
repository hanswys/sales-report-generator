<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
public function definition(): array
{
return [
'product' => $this->faker->word(),
'quantity' => $this->faker->numberBetween(1, 20),
'price' => $this->faker->randomFloat(2, 5, 500),
'sale_date' => $this->faker->dateTimeThisYear(),
];
}
}
