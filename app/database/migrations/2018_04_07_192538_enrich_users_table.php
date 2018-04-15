<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnrichUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
				$table->string('city')->after('remember_token')->nullable();
				$table->string('country')->after('remember_token')->nullable();
				$table->string('postalcode')->after('remember_token')->nullable();
				$table->string('address')->after('remember_token')->nullable();
				$table->string('surname')->after('remember_token')->nullable();
				$table->string('realname')->after('remember_token')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table)
		{
		    $table->dropColumn('name');
				$table->dropColumn('surname');
				$table->dropColumn('address');
				$table->dropColumn('postalcode');
				$table->dropColumn('country');
				$table->dropColumn('city');
		});
	}

}
