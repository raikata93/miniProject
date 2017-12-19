<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(is_null(User::find(1))){
            User::create([
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@abv.bg',
                'password' => bcrypt('qwerty'),
                'api_token' => str_slug(str_random(60)),
            ]);
        }
    }
}
