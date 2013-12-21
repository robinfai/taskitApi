<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoardMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('board_members', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('board_id');
            $table->integer('user_id');
            $table->boolean('is_admin')->default(0);
            $table->softDeletes();
            $table->unique(array('board_id', 'user_id'));
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
        Schema::drop('board_members');
	}

}
