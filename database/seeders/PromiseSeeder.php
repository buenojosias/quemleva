<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = Campaign::with('items')->limit(1)->get();

        foreach ($campaigns as $campaign) {
            // $promises = $campaign->promises()->createMany([
            //     [
            //         'user_id' => 1,
            //         'donor_name' => 'John Doe',
            //         'confirmed_at' => now(),
            //     ],
            //     [
            //         'donor_name' => 'Jane Smith',
            //         'donor_whatsapp' => '0987654321',
            //         'confirmation_code' => 'XYZ789',
            //         'confirmed_at' => now(),
            //     ],
            //     [
            //         'donor_name' => 'Lorem Ipsum',
            //         'donor_whatsapp' => '4136853359',
            //         'confirmation_code' => 'ABC123',
            //     ],
            // ]);

            $promises = \App\Models\Promise::all(); // apagar depois

            foreach ($promises as $promise) {
                $randomItems = $campaign->items->random(3);
                foreach ($randomItems as $item) {
                    $promise->items()->create([
                        'item_id' => $item->id,
                        'promised_quantity' => rand(1, $item->required_quantity),
                        'status' => $promise->confirmed_at ? 'promised' : 'pending',
                    ]);
                }
            }
        }
    }
}
