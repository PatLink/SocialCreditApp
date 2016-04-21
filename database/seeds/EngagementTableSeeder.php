<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class EngagementTableSeeder extends Seeder {

    public function run()
    {
        DB::table('engagement')->delete();

        DB::table('engagement')->insert([
            ['name' => 'Studiengang'],
            ['name' => 'Hochschule'],
            ['name' => 'Extern']
        ]);
    }

}