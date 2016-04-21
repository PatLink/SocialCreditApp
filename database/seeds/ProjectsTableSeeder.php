<?php

use Illuminate\Database\Seeder;
use SocialCreditPointsApp\Project;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class ProjectsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('project_storage')->delete();
		DB::table('projects')->delete();
		$faker = Faker\Factory::create();
		// clear table
		DB::table('projects')->delete();


		$counter = 0;
    $betreuer = DB::table('users')->where('userrole', 3)->get();
		$stati = array('unbestätigt','abgelehnt','bestätigt','abgeschlossen','draft');
		$engagements = array('Studiengang','Hochschule', 'Extern');
		$creators = [];
		array_push($creators, DB::table('users')->where('email','max.mustermann@dhbw.de')->first());
		array_push($creators, DB::table('users')->where('email','mester@dhbw.de')->first());
		array_push($creators, DB::table('users')->where('email','norman.schemel@dhbw.de')->first());

		while($counter < 40){
			$creator_id = $creators[array_rand($creators)]->id;
			$betreuer_id = $betreuer[array_rand($betreuer)]->id;
			$status = $stati[array_rand($stati)];
			$engagement_art = $engagements[array_rand($engagements)];
			$seen = true;
			if($status =='abgelehnt'|'bestätigt'){
				$seen = false;
			}
			if($status == 'draft'){
				Project::create([
						'created_by' => $creator_id,
						'name' => $faker->bs,
						'seen' => true,
						'tutor' => $betreuer_id,
						'participants_capacity' => (string)rand(1,20),
						'scp_reward' => (string)rand(1,20),
						'engagement' => $engagement_art,
						'categories' => (string)rand(1,20),
						'description' => $faker->paragraph($nbSentences = 3),
						'status' => $status,
						'storage_id' => '0',
						]);
			}
			else{
				Project::create([

						'created_by' => $creator_id,
						'name' => $faker->bs,
						'tutor' => $betreuer_id,
						'seen' => $seen,
						'participants_capacity' => (string)rand(1,20),
						'scp_reward' => (string)rand(1,20),
						'engagement' => $engagement_art,
						'categories' => (string)rand(1,20),
						'description' => $faker->paragraph($nbSentences = 3),
						'status' => $status,
						'storage_id' => '0',
						'start_date' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now'),
						'end_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '2 years')
						]);
			}
			$counter++;
		}
	}

}

