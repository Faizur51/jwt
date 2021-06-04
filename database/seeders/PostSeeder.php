<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Models\Post;
class PostSeeder extends Seeder
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
            Post::create([
                'title'=>$faker->paragraph,
                'description'=>$faker->text,
            ]);
        }
    }
}
