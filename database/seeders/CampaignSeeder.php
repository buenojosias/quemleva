<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campaign::factory()
            ->count(5)
            ->has(Item::factory(rand(5, 10)))
            ->create();
    }
}
