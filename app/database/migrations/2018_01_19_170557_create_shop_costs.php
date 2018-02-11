<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopCosts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopCosts', function($t)
		{
			$t->increments('id');
			$t->integer('itemId')->unsigned();
			$t->integer('serverId')->unsigned();
			$t->enum('coin', array('real', 'standard', 'premium'));
			$t->decimal('price', 5,2);
			$t->decimal('discount', 5,2);
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
		Schema::dropIfExists("shopCosts");
	}

}
