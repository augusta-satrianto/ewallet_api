<?php

namespace Database\Seeders;

use App\Models\OperatorCard;
use Illuminate\Database\Seeder;

class OperatorCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operatorcards = [
            [
                'name' => 'Telkomsel',
                'thumbnail' => 'http://sipela.my.id/storage/operator/img_provider_telkomsel.png',
            ],
            [
                'name' => 'Indosat',
                'thumbnail' => 'http://sipela.my.id/storage/operator/img_provider_indosat.png',
            ],
            [
                'name' => 'Singtel',
                'thumbnail' => 'http://sipela.my.id/storage/operator/img_provider_singtel.png',
            ],
        ];

        foreach ($operatorcards as $operatorcard) {
            OperatorCard::create($operatorcard);
        }
    }
}
