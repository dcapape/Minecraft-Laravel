<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
          $table->increments('id');
					$table->string('uuid', 40);
          $table->string('nick', 16)->unique();
					$table->string('email', 150)->unique()->nullable();
					$table->string('password', 60)->nullable();
          $table->boolean('premium');
					$table->string('firstIP', 15);
					$table->string('lastIP', 15);
					$table->string('firstJoined', 13);
					$table->string('lastJoined', 13);
					$table->boolean('checked');
					$table->boolean('registeredByAdmin');
          $table->rememberToken();
          $table->timestamps();
      });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("users");
	}

}
