<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopSells extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopSells', function($t)
		{
			$t->increments('id');
			$t->string('name', 255);
			$t->integer('itemId')->unsigned();
			$t->integer('guid')->unsigned();
			$t->integer('coinCost');
			$t->integer('premCost');
			$t->decimal('discount', 5,2);
			$t->text('log');
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
		Schema::dropIfExists("shopSells");
	}

}
