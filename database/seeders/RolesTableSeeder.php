<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'admin',

            ],
            [
                'id' => 2,
                'name' => 'editor',

            ],
            [
                'id' => 3,
                'name' => 'reader',

            ],
        ];

        DB::table('roles')->insert($data);
    }
}
