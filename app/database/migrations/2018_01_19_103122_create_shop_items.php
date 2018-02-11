<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopItems', function($t)
		{
			$t->increments('id');
			$t->string('name', 255);
			$t->string('slug', 255);
			$t->string('image', 255);
			$t->text('description');
			$t->integer('categoryId')->unsigned();
			$t->integer('allopassId', 8)->nullable();
			$t->text('command');
			$t->integer('sales');
			$t->integer('weight');
			$t->boolean('sellable');
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
		Schema::dropIfExists("shopItems");
	}

}
