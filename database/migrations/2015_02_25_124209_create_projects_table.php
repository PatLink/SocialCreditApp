<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
				{
				$table->increments('id');
				$table->integer('created_by');
				$table->string('slug');
				$table->boolean('seen');
				$table->string('name');
				$table->string('tutor');
				$table->integer('participants_capacity');
				$table->integer('scp_reward');
				$table->string('engagement');
				$table->string('categories');
				$table->text('description');
				$table->string('status');
				$table->integer('storage_id');
				$table->string('image');
				$table->date('start_date');
				$table->date('end_date');
				$table->timestamps();
				});
	}


# Status Information
	/*
	 * 1. draft
	 * 2. unconfirmed
	 * 3. confirmed
	 * 4. closed
	 * 5. rejected
	 */

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('projects');
	}

}
