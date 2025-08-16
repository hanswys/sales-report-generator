<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;

class SaleSeeder extends Seeder
{
public function run(): void
{
\App\Models\Sale::factory()->count(20)->create();
}
}
