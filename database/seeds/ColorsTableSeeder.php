<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsTableSeeder extends Seeder
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
                'name' => 'black'
            ],
            [
                'name' => 'green'
            ],
            [
                'name' => 'yellow'
            ],
            [
                'name' => 'red'
            ],
            [
                'name' => 'blue'
            ],
            [
                'name' => 'brown'
            ]
        ];

        DB::table('colors')->insert($data);
    }
}
