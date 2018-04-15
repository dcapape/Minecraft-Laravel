<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEuVAT extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	 public function up()
 	{
 		Schema::create('euVAT', function($t)
  		{
 			$t->increments('id');
 			$t->string('code', 255);
			$t->string('name', 255);
			$t->decimal('vat', 5,2);
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
 		Schema::dropIfExists("euVAT");
 	}

}
