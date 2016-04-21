<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class UserRoleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('user_role')->delete();

        DB::table('user_role')->insert([
            ['id' => 1, 'name' => 'Student'],
            ['id' => 2, 'name' => 'Dozent'],
            ['id' => 3, 'name' => 'Professor']
        ]);
    }

}