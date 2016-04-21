<?php

use Illuminate\Database\Seeder;
// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ProjectParticipantsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('project_participants')->delete();

        $projects = DB::table('projects')->get();
        $students = DB::table('students')->get();

        $devStudent = DB::table('users')->where('email','max.mustermann@dhbw.de')->first();
        $devStudentParticipationCount = 24;

        foreach($projects as $project){
            if($project->status !== 'draft'){

                $capacity = $project->participants_capacity;
                $participantCount = rand(1,$capacity);

                shuffle($students);
                $participants = array_slice($students, 0, $participantCount);
                foreach($participants as $participant){
                    DB::table('project_participants')->insert([
                        'project_id' => $project->id,
                        'user_id' => $participant->user_id
                    ]);
                }

                /* Entwickleraccount mit teilnahmen versehen. Vorsicht, es wird nicht sichergestellt, dass der devStudent tatsaechlich an 24 projekten teilnimmt */
                if ($devStudentParticipationCount !== 0 && $participantCount < $capacity){
                    /* DB::table('project_participants')->insert(array( */
                    /*     'projectid' => $project->id, */
                    /*     'userid' => $devStudent->id */
                    /* )); */
                    DB::table('project_participants')->insert([
                        ['project_id' => $project->id,
                        'user_id' => $devStudent->id]
                    ]);
                    $devStudentParticipationCount--;
                }
            }
        }
    }

}
