<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('card_members', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('card_id');
            $table->integer('user_id');
            $table->softDeletes();
            $table->unique(array('card_id', 'user_id'));
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
        Schema::drop('card_members');
	}

}