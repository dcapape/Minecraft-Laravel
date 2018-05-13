<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForumCategoriesLanguages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('forumCategories', function($table)
		{
				$table->string('language', 2)->after('description')->nullable();
				$table->string('weight', 2)->after('description')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('forumCategories', function($table)
		{
		    $table->dropColumn('language');
				$table->dropColumn('weight');
		});
	}

}
