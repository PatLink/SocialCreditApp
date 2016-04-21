<?php

use Illuminate\Database\Seeder;
use SocialCreditPointsApp\Course;


// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class CourseTableSeeder extends Seeder {

    public function run()
    {
        DB::table('courses')->delete();

        for($i=0; $i < 16; $i++) {
            $jahr = $i;
            if($jahr < 10){
                $jahr = '0' . (string)$i;
            }

            DB::table('courses')->insert([
                'name' => 'Onlinemedien',
                'enrollment_year' => '20' . $jahr,
                'abbreviation' => 'ON' . $jahr,
                'campus' => 'Mosbach'
            ]);
        };
    }

}