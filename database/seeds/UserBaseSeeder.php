<?php

use Illuminate\Database\Seeder;
use DB;
use SocialCreditPointsApp\Course;
use SocialCreditPointsApp\User;
use SocialCreditPointsApp\Students;
use SocialCreditPointsApp\Lecturer;
use SocialCreditPointsApp\Professor;

class UserBaseSeeder extends Seeder {

    public function run() {
        $faker = Faker\Factory::create();

        // clear tables
        DB::table('users')->delete();
        DB::table('student_class')->delete();
        DB::table('students')->delete();
        DB::table('dozent')->delete();
        DB::table('studiengangsleiter')->delete();
        # Erstelle Studenten
        $i = 0;
        $jahr = 12;
        while($jahr < 15){

            $class = Course::create([
                'studiengang' 	=> 'Onlinemedien',
                'jahrgang' 		=> '20'.$jahr,
                'kuerzel'       => 'ON'.$jahr,
                'campus' 		=> 'Mosbach',
            ]);

            while($i <= 24) {

                $firstName = $faker->firstName;
                $lastName = $faker->lastName;

                $student = User::create( [
                    'vorname' => $firstName,
                    'nachname' => $lastName,
                    'username' => strtolower($firstName).'.'.strtolower($lastName).".".$jahr,
                    'email' => strtolower($firstName).'.'.strtolower($lastName).".".$jahr."@dhbw-mosbach.de",
                    'userrole' => '1'
                ]);

                Students::create([
                    'userid' => $student->id,
                    'matrikelnummer' => (string) $faker->randomNumber($nbDigits = 7),
                    'kursid' => $class->id
                ]);

                $i++;
            }
            $jahr++;
            $i=0;
        }

        $kurs = DB::table('student_class')->where('studiengang', 'Onlinemedien')->first();
        # Erstelle  Dozenten
        $i = 0;
        while($i <= 20) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $dozent = User::create( [
                'vorname' => $firstName,
                'nachname' => $lastName,
                'username' => strtolower($firstName).'.'.strtolower($lastName),
                'email' => strtolower($firstName).'.'.strtolower($lastName)."@dhbw-mosbach.de",
                'userrole' => '2'
            ]);
            Lecturer::create( [
                'user_id' => $dozent->id,
                'course' => $kurs->studiengang
            ]);
            $i++;
        }

        # Erstelle  Studiengangsleiter
        $i = 0;
        while($i <= 3) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $studiengangsleiter = User::create( [
                'vorname' => $firstName,
                'nachname' => $lastName,
                'username' => strtolower($firstName).'.'.strtolower($lastName),
                'email' => strtolower($firstName).'.'.strtolower($lastName)."@dhbw-mosbach.de",
                'userrole' => '3'
            ]);

            Professor::create( [
                'user_id' => $studiengangsleiter->id,
                'course' => $kurs->studiengang
            ]);
            $i++;
        }

    }
}
