<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::group([
		'prefix' => LaravelLocalization::setLocale(),
		'before' => 'LaravelLocalizationRedirectFilter' // LaravelLocalization filter
	],function(){

  Route::get('/', 'HomeController@showWelcome');


  Route::controller('user', 'UsersController');
  Route::get('/login', ['as' => 'login', 'uses' => 'UsersController@getLogin']);
  Route::get('/logout', ['as' => 'logout', 'uses' => 'UsersController@getLogout']);
  Route::get('/register', ['as' => 'register', 'uses' => 'UsersController@getRegister']);

  Route::get('/maps/{id}', function($id) { return View::make('public.pages.map')->with('worldid', $id); });

  Route::get('/stats/{name}/{server}', ['uses' => 'StatsController@show']);
  Route::get('/stats/{server}', ['uses' => 'StatsController@index']);
  Route::get('/profile/{name}', ['uses' => 'StatsController@show']);
  Route::get('/profile', ['as' => 'profile', 'uses' => 'StatsController@showme']);
  Route::resource('stats', 'StatsController');

  Route::resource('forum/post', 'forumPostsController');
  Route::resource('forum.topic', 'forumTopicsController');
  Route::resource('forum', 'forumCategoriesController', array('except' => array('create', 'store', 'update', 'destroy')));

  Route::resource('shop/item.cost', 'shopCostsController');
  Route::resource('shop/item', 'shopItemsController');
  Route::resource('shop', 'shopController', ['as' => 'shop']);

});

Route::resource('forum.topic', 'forumTopicsController', ['only' => ['store','update', 'destroy']]);

Route::resource('shop/item.cost', 'shopCostsController', ['only' => ['update', 'destroy']]);
Route::resource('shop/item', 'shopItemsController', ['only' => ['update', 'destroy']]);
Route::resource('shop', 'shopController', ['as' => 'shop', 'only' => 'update']);

Route::get('payment', ['as' => 'payment', 'uses' => 'PayPalController@postPayment']);
Route::get('payment/status', ['as' => 'payment.status','uses' => 'PayPalController@getPaymentStatus']);
Route::get('hypay/store', ['as' => 'payment.status','uses' => 'PayPalController@getPaymentStatus']);
