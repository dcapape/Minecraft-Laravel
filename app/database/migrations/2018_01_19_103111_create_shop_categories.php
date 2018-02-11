<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopCategories', function($t)
		{
			$t->increments('id');
			$t->string('name', 255);
			$t->string('description', 255);
			$t->string('image', 255);
			$t->timestamps();
			$t->index('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("shopCategories");
	}

}
