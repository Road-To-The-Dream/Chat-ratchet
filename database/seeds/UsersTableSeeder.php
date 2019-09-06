<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Сергей',
                'email' => 'fhlbc2012@gmail.com',
                'token' => Str::random(16),
                'gravatar_img' => 'http://www.gravatar.com/avatar/' . md5('fhlbc2012@gmail.com') . '?d=robohash&s=50',
                'role' => 'admin',
                'color_id' => 1,
                'password' => bcrypt('fhlbc2012')
            ],
            [
                'name' => 'Дмитрий',
                'email' => 'dima@gmail.com',
                'token' => Str::random(16),
                'gravatar_img' => 'http://www.gravatar.com/avatar/' . md5('dima@gmail.com') . '?d=robohash&s=50',
                'role' => 'user',
                'color_id' => 2,
                'password' => bcrypt('fhlbc2012')
            ]
        ];

        DB::table('users')->insert($data);
    }
}
