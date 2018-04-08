<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinsBalance extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	 public function up()
 	{
 		Schema::create('coinsBalance', function($t)
 		{
 			$t->increments('id');
			$t->string('uuid', 40);
 			$t->string('nick', 16);
 			$t->enum('coin', array('real', 'standard', 'premium'));
 			$t->decimal('balance', 15,2);
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
 		Schema::dropIfExists("coinsBalance");
 	}

}
