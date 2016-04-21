<?php

use Illuminate\Database\Seeder;
use SocialCreditPointsApp\AcademicYear;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class AcademicYearTableSeeder extends Seeder {

    public function run()
    {
        DB::table('academic_years')->delete();

        $kursON12 = DB::table('courses')->where('name', 'Onlinemedien')->where('enrollment_year', '2012')->first();
        $kursON13 = DB::table('courses')->where('name', 'Onlinemedien')->where('enrollment_year', '2013')->first();
        $kursON14 = DB::table('courses')->where('name', 'Onlinemedien')->where('enrollment_year', '2014')->first();
        $kursON15 = DB::table('courses')->where('name', 'Onlinemedien')->where('enrollment_year', '2015')->first();

        AcademicYear::create([
            'course_id' => $kursON12->id,
            'start_date' => '2012-10-01 00:00:00',
            'end_date' => '2013-09-01 23:59:59',
            'name' => '1. Studienjahr',
            'workload' => '30'
        ]);

        AcademicYear::create([
            'course_id' => $kursON12->id,
            'start_date' => '2013-09-02 00:00:00',
            'end_date' => '2014-09-28 23:59:59',
            'name' => '2. Studienjahr',
            'workload' => '35'
        ]);

        AcademicYear::create([
            'course_id' => $kursON12->id,
            'start_date' => '2014-09-29 00:00:00',
            'end_date' => '2015-09-30 23:59:59',
            'name' => '3. Studienjahr',
            'workload' => '40'
        ]);

        AcademicYear::create([
            'course_id' => $kursON13->id,
            'start_date' => '2013-10-01 00:00:00',
            'end_date' => '2014-08-31 23:59:59',
            'name' => '1. Studienjahr',
            'workload' => '30'
        ]);

        AcademicYear::create([
            'course_id' => $kursON13->id,
            'start_date' => '2014-09-01 00:00:00',
            'end_date' => '2015-09-27 23:59:59',
            'name' => '2. Studienjahr',
            'workload' => '35'
        ]);

        AcademicYear::create([
            'course_id' => $kursON13->id,
            'start_date' => '2015-09-28 00:00:00',
            'end_date' => '2016-09-30 23:59:59',
            'name' => '3. Studienjahr',
            'workload' => '40'
        ]);

        AcademicYear::create([
            'course_id' => $kursON14->id,
            'start_date' => '2014-10-01 00:00:00',
            'end_date' => '2015-08-30 23:59:59',
            'name' => '1. Studienjahr',
            'workload' => '30'
        ]);

        AcademicYear::create([
            'course_id' => $kursON14->id,
            'start_date' => '2015-08-31 00:00:00',
            'end_date' => '2016-09-25 23:59:59',
            'name' => '2. Studienjahr',
            'workload' => '35'
        ]);

        AcademicYear::create([
            'course_id' => $kursON14->id,
            'start_date' => '2016-09-26 00:00:00',
            'end_date' => '2017-09-30 23:59:59',
            'name' => '3. Studienjahr',
            'workload' => '40'
        ]);
    }

}
