<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forumTopics', function($t)
		{
			$t->increments('id');
			$t->string('subject', 255);
			$t->dateTime('date');
			$t->integer('categoryId')->unsigned();
			$t->integer('userId')->unsigned();
			$t->boolean('home');
			$t->boolean('locked');
			$t->timestamps();
		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("forumTopics");
	}

}
