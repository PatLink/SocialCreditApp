<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectParticipantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('project_participants', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('user_id');
            $table->integer('status');
            $table->integer('documentation_file_id');
            $table->text('comment');
            $table->timestamps();
        });

        # Status
        /*
        * User's can have an active (1) project or a closed project (2)
        */
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('project_participants');
    }

}
