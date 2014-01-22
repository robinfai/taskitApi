<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cards', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title',32);
            $table->string('description',32);
            $table->integer('creator_id');
            $table->integer('card_list_id');
            $table->softDeletes();
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
        Schema::drop('cards');
	}

}