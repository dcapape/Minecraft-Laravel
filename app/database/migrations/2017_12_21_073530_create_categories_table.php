<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forumCategories', function($t)
		{
			$t->increments('id');
			$t->string('name', 255);
			$t->string('description', 255);
			$t->boolean('locked')->default(false);
			$t->timestamps();
			$t->index('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("forumCategories");
	}

}

/*Schema::create('forumPermissions', function($t)
{
	$t->increments('id');
	$t->enum('type', ['category','topic','post']); //cat crear categorias / topic crear hilos / post responder hilos
	$t->enum('permission', ['show','edit','create']);
	$t->integer('categoryId')->unsigned();
	$t->string('group');
	$t->boolean('allowed');
	$t->timestamps();
});*/
/*
Ver una categoria
Crear hilos en una categoria
Responder en un hilo

SHOW CATEGORY
EDIT CATEGORY
CREATE CATEGORY

SHOW TOPICS BY CATEGORYID
EDIT TOPICS BY CATEGORYID
CREATE TOPICS BY CATEGORYID

SHOW POSTS BY CATEGORYID
EDIT POSTS BY CATEGORYID
CREATE POSTS BY CATEGORYID


*/
