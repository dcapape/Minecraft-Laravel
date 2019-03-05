<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Pages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function($t)
		{
			$t->increments('id');
			$t->integer('pageId')->unsigned();
			$t->string('language', 2);
			$t->string('title', 255);
			$t->string('slug', 255);
			$t->text('content');
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
		Schema::dropIfExists("pages");
	}

}
