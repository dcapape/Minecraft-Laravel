<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forumTopics', function($table)
		{
			$table->foreign('categoryId')
				->references('id')->on('forumCategories')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table->foreign('userId')
				->references('id')->on('users')
				->onDelete('restrict')
				->onUpdate('cascade');
		});

		Schema::table('forumPosts', function($table)
		{
			$table->foreign('topicId')
				->references('id')->on('forumTopics')
				->onDelete('cascade')
				->onUpdate('cascade');

			$table->foreign('userId')
				->references('id')->on('users')
				->onDelete('restrict')
				->onUpdate('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('forumTopics', function($table)
		{
			$table->dropForeign('forumTopics_categoryId_foreign');
			$table->dropForeign('forumTopics_userId_foreign');
		});

		Schema::table('forumPosts', function($table)
		{
			$table->dropForeign('forumPosts_topicId_foreign');
			$table->dropForeign('forumPosts_userId_foreign');
		});
	}

}
