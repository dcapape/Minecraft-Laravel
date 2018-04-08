<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinsItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coinsItems', function($t)
 		{
			$t->increments('id');
			$t->string('name', 255);
			$t->string('slug', 255);
			$t->string('image', 255);
			$t->text('description');
			$t->integer('allopassId')->unsigned()->nullable();
			$t->enum('rewardCoin', array('standard', 'premium'));
			$t->decimal('rewardQty', 5,2);
			$t->decimal('price', 5,2);
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
		Schema::dropIfExists("coinsItems");
	}

}
