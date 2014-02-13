<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCardTableAddFieldCompletionTime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('cards', function(Blueprint $table)
        {
            $table->dateTime('completion_time');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        Schema::table('cards', function(Blueprint $table)
        {
            $table->dropColumn('completion_time');
        });
	}

}