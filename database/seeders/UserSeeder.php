<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Rafael',
                'email' => 'rafael@gmail.com',
                'password' => Hash::make('password'),
                'balance' => 0,
                'card_number' => "8596310974839384",
                'pin' => '123456',
                'profile_picture' => 'http://sipela.my.id/storage/profile/user_rafael.png'
            ],
            [
                'name' => 'Renata',
                'email' => 'renata@gmail.com',
                'password' => Hash::make('password'),
                'balance' => 0,
                'card_number' => "9837192837858619",
                'pin' => '123456',
                'profile_picture' => 'http://sipela.my.id/storage/profile/user_renata.png'
            ],
            [
                'name' => 'Kevin',
                'email' => 'kevin@gmail.com',
                'password' => Hash::make('password'),
                'balance' => 0,
                'card_number' => "9283759285730628",
                'pin' => '123456',
                'profile_picture' => 'http://sipela.my.id/storage/profile/user_kevin.png'
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
