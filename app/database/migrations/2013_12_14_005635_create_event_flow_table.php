<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventFlowTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('event_flows', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('board_id');
            $table->integer('user_id');
            $table->string('event',1023);
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('event_flows');
	}

}