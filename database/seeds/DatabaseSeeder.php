<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call('UserTableSeeder');
        $this->command->info('Users table seeded!');

        $this->call('AcademicYearTableSeeder');
        $this->command->info('Academic_year table seeded!');

        $this->call('ProjectsTableSeeder');
        $this->command->info('Projects table seeded!');

        $this->call('ProjectParticipantsTableSeeder');
        $this->command->info('Projejrct_participants table seeded!');

        $this->call('ProjectStorageTableSeeder');
        $this->command->info('Project_storage table seeded!');

        $this->call('ProjectRestrictionsTableSeeder');
        $this->command->info('Project_restriction table seeded!');

        $this->call('UserRoleTableSeeder');
        $this->command->info('UserRole table seeded!');

        $this->call('EngagementTableSeeder');
        $this->command->info('Engagement table seeded!');

        $this->call('CategoriesTableSeeder');
        $this->command->info('Categories table seeded!');

    }

}
