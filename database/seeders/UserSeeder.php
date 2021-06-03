<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Factory::create();
        foreach (range(1,10) as $index){
           User::create([
               'name'=>$faker->name,
               'email'=>$faker->email,
               'password'=>bcrypt(123456),
           ]);
        }
    }
}
