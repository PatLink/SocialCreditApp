<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class StudentsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('students')->delete();

        $marcel_user = DB::table('users')->where('email', 'engelmann.marcel@yahoo.de')->first();
        $max_musermann_user =DB::table('users')->where('email', 'engelmann.marcel@yahoo.de')->first();


        DB::table('students')->insert([
            [
                'user_id' => $marcel_user->id,
                'matriculation_number' => 245343,
                'course_id' => 1
            ],
            [
                'user_id' => $max_musermann_user->id,
                'matriculation_number' => 245343,
                'course_id' => 1
            ]
        ]);

    }

}