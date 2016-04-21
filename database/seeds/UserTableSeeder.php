<?php

use Illuminate\Database\Seeder;
use SocialCreditPointsApp\Course;
use SocialCreditPointsApp\User;
use SocialCreditPointsApp\Students;
use SocialCreditPointsApp\Lecturer;
use SocialCreditPointsApp\Professor;

class UserTableSeeder extends Seeder {

    public function __construct(){
        $this->faker = Faker\Factory::create();
    }
    private function fakeUserData($year = false){
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        /* different email schemata for user differenttypes */
        if($year ){
            $email = strtolower($firstName).'.'.strtolower($lastName).".".$year."@dhbw-mosbach.de";

        }
        else{
            $email = strtolower($firstName).'.'.strtolower($lastName)."@dhbw-mosbach.de";
        }
        /* bake it */
        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make('weristdas'),
            'userrole' => '$role'
        ];
        return $data;
    }
    public function run() {


        // clear tables
        DB::table('users')->delete();
        DB::table('courses')->delete();
        DB::table('students')->delete();
        DB::table('lecturers')->delete();
        DB::table('professors')->delete();

        $this->createDummyUsers();
        $this->createDevUsers();
    }
    private function createDummyUsers(){
        $i = 0;
        $year = 12;
        while($year < 15){

            $course = Course::create([
                'name' 	=> 'Onlinemedien',
                'enrollment_year' 		=> '20'.$year,
                'abbreviation'       => 'ON'.$year,
                'campus' 		=> 'Mosbach',
            ]);

            while($i <= 24) {


                $studentId = DB::table('users')->insertGetId($this->fakeUserData($year));
                Students::create([
                    'user_id' => $studentId,
                    'matriculation_number' => (string) $this->faker->randomNumber($nbDigits = 7),
                    'course_id' => $course->id
                ]);

                $i++;
            }
            $year++;
            $i=0;
        }

        $course = DB::table('courses')->where('name', 'Onlinemedien')->first();
        # Erstelle  Dozenten
        $i = 0;
        while($i <= 20) {
            $lecturerId = DB::table('users')->insertGetId($this->fakeUserData());
            Lecturer::create( [
                'user_id' => $lecturerId,
                'course' => $course->name
            ]);
            $i++;
        }

        # Erstelle  professor
        $i = 0;
        while($i <= 3) {
            $professorId = DB::table('users')->insertGetId($this->fakeUserData());
            Professor::create( [
                'user_id' => $professorId,
                'course' => $course->name
            ]);
            $i++;
        }

    }
    private function createDevUsers(){
        $course = DB::table('courses')->where('name', 'Onlinemedien')->first();
        $studentData = [
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'email' => 'max.mustermann@dhbw.de',
            'password' => Hash::make('weristdas'),
            'userrole' => 1,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ];
        $studentId = DB::table('users')->insertGetId($studentData);
        Students::create([
            'user_id' => $studentId,
            'matriculation_number' => (string) $this->faker->randomNumber($nbDigits = 7),
            'course_id' => $course->id
        ]);

        $lecturerData = [
            'first_name' => 'Norman',
            'last_name' => 'Schemel',
            'email' => 'norman.schemel@dhbw.de',
            'password' => Hash::make('weristdas'),
            'userrole' => 2,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ];
        $lecturerId = DB::table('users')->insertGetId($lecturerData);
        Lecturer::create( [
            'user_id' => $lecturerId,
            'course' => $course->name
        ]);

        $professorData = [
            'first_name' => 'Arnulf',
            'last_name' => 'Mester',
            'email' => 'mester@dhbw.de',
            'password' => Hash::make('weristdas'),
            'userrole' => 3,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ];

        $professorId = DB::table('users')->insertGetId($professorData);
        Professor::create( [
            'user_id' => $professorId,
            'course' => $course->name
        ]);

        DB::table('users')->insert([

            'first_name' => 'Marcel',
            'last_name' => 'Engelmann',
            'email' => 'mar.engelmann.13@dhbw.de',
            'password' => Hash::make('weristdas'),
            'userrole' => 1,
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()

        ]);
    }
}
