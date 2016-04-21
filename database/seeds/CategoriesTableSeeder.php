<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CategoriesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('categories')->delete();

        DB::table('categories')->insert([
            ['name' => 'Webdesign'],
            ['name' => 'Aufbauarbeiten'],
            ['name' => 'Oeffentlichkeitsarbeit'],
            ['name' => 'Hilfe'],
            ['name' => 'Test']
        ]);
    }

}
