<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinSells extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	 public function up()
 	{
 		Schema::create('coinsSells', function($t)
 		{
 			$t->increments('id');
 			$t->string('uuid', 40);
			$t->integer('userId')->unsigned();
			$t->string('nick', 16);
			$t->string('ip', 15);
			//Personal Data
			$t->string('email', 150);
			$t->boolean('premium');
			$t->string('name');
			$t->string('surname');
			$t->string('address');
			$t->string('postalcode');
			$t->string('country');
			$t->string('city');
			//Item Data
 			$t->integer('itemId')->unsigned();
			$t->string('itemName', 255);

			//Payment Data
			$t->string('itemCurrency', 3);
 			$t->decimal('itemCost', 5,2);
			$t->decimal('countryVat', 5,2);

			$t->string('paymentId', 100)->nullable();
			$t->decimal('paymentSubtotal', 5,2);
			$t->decimal('paymentVat', 5,2);
			$t->string('paymentMethod', 50);
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
 		Schema::dropIfExists("coinsSells");
 	}

}
