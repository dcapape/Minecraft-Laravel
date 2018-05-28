<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedefineShopSells extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists("shopSells");

		Schema::create('shopSells', function($t)
 		{
 			$t->increments('id');
 			$t->string('uuid', 40);
			$t->integer('userId')->unsigned();
			$t->string('nick', 16);
			$t->string('ip', 15);
			//Personal Data
			$t->string('email', 150);
			$t->boolean('premium');
			//Item Data
 			$t->integer('itemId')->unsigned();
			$t->string('itemName', 255);
			//Server Data
			$t->integer('serverId')->unsigned();
			$t->string('serverName', 255);

			//Payment Data
			$t->enum('coin', array('standard', 'premium'));
 			$t->decimal('cost', 5,2);

			$t->string('status', 20);

 			$t->text('log')->nullable();
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
