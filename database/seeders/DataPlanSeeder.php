<?php

namespace Database\Seeders;

use App\Models\DataPlan;
use Illuminate\Database\Seeder;

class DataPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataplans = [
            // 1
            [
                'name' => '10 GB',
                'price' => 100000,
                'operator_card_id' => 1
            ],
            [
                'name' => '25 GB',
                'price' => 200000,
                'operator_card_id' => 1
            ],
            [
                'name' => '40 GB',
                'price' => 300000,
                'operator_card_id' => 1
            ],
            [
                'name' => '60 GB',
                'price' => 400000,
                'operator_card_id' => 1
            ],
            // 2
            [
                'name' => '10 GB',
                'price' => 100000,
                'operator_card_id' => 2
            ],
            [
                'name' => '25 GB',
                'price' => 200000,
                'operator_card_id' => 2
            ],
            [
                'name' => '40 GB',
                'price' => 300000,
                'operator_card_id' => 2
            ],
            [
                'name' => '60 GB',
                'price' => 400000,
                'operator_card_id' => 2
            ],
            // 3
            [
                'name' => '10 GB',
                'price' => 100000,
                'operator_card_id' => 3
            ],
            [
                'name' => '25 GB',
                'price' => 200000,
                'operator_card_id' => 3
            ],
            [
                'name' => '40 GB',
                'price' => 300000,
                'operator_card_id' => 3
            ],
            [
                'name' => '60 GB',
                'price' => 400000,
                'operator_card_id' => 3
            ],
        ];

        foreach ($dataplans as $dataplan) {
            DataPlan::create($dataplan);
        }
    }
}
