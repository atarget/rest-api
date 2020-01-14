<?php

use Illuminate\Database\Seeder;

class seed_users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        \App\User::query()->delete();
        for ($i = 0; $i < 100000; $i++) {
            $user = new \App\User();
            $user->name = Faker\Provider\ru_RU\Person::firstNameMale();
            $user->username = "user_" . $i;
            $user->email = "user_" . $i . "@gmail.com";
            $user->password = "password123123";
            \App\Api\Service\Service::user()->save($user);
        }
    }
}
