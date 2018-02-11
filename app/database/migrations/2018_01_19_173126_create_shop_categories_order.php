<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCategoriesOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shopCategories', function($table)
		{
				$table->string('slug')->after('name');
		    $table->integer('weight')->default('999')->after('image');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shopCategories', function($table)
		{
		    $table->dropColumn('slug');
				$table->dropColumn('weight');
		});
	}

}
