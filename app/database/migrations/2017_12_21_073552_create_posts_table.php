<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forumPosts', function($t)
		{
			$t->increments('id');
			$t->text('content');
			$t->dateTime('date');
			$t->integer('topicId')->unsigned();
			$t->integer('userId')->unsigned();
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
		Schema::dropIfExists("forumPosts");
	}

}
