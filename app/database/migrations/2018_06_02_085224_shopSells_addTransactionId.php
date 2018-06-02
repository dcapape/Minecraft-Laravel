<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopSellsAddTransactionId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('shopSells', function($table)
		{
				$table->string('transactionId', 60)->after('cost')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('shopSells', function($table)
		{
		    $table->dropColumn('transactionId');
		});
	}

}
