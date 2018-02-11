<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('servers', function($t)
		{
			$t->increments('id');
			$t->string('name');
			$t->string('folderName');
			$t->string('dbName');
			$t->integer('rconPort');
			$t->boolean('enabled');
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
		Schema::dropIfExists("servers");
	}

}
