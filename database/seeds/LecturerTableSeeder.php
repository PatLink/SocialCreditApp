<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class LecturerTableSeeder extends Seeder {

    public function run()
    {
        DB::table('project_storage')->delete();
    }

}