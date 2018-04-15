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

	Route::get('/discord', function()
	{
	    return "<script>if(self != top) { top.location = 'https://discordapp.com/invite/qxW6U2f'; }</script>";
	});

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
  Route::resource('forum/topic', 'forumTopicsController');
	Route::resource('forum.topic', 'forumTopicsController'); //  /en/forum/2/topic/create
  Route::resource('forum', 'forumCategoriesController', array('except' => array('create', 'store', 'update', 'destroy')));

  Route::resource('shop/item.cost', 'shopCostsController');
  Route::resource('shop/item', 'shopItemsController', ['except' => ['store', 'update', 'destroy']]);
  Route::resource('shop', 'shopController', ['as' => 'shop']);

	//Route::resource('coins', 'coinItemsController', ['except' => ['store', 'update', 'destroy']]);
	Route::get('/coins', 							['uses' => 'coinItemsController@index', 'as' => 'coins.index']);
	Route::get('/coins/status', 			['uses' => 'coinItemsController@getStatus', 'as' => 'coins.status']);
  Route::get('/coins/{slug}', 			['uses' => 'coinItemsController@show', 'as' => 'coins.show']);
	Route::get('/coins/data/{slug}', 	['uses' => 'coinItemsController@getData']);
	Route::post('/coins/data/{slug}', ['uses' => 'coinItemsController@postData', 'as' => 'coins.data']);
  Route::post('/coins/buy/{slug}', 	['uses' => 'coinItemsController@postBuy', 'as' => 'coins.buy']);
});

Route::resource('forum/topic', 'forumTopicsController', ['only' => ['store','update', 'destroy']]);

Route::resource('shop/item.cost', 'shopCostsController', ['only' => ['update', 'destroy']]);
Route::resource('shop/item', 'shopItemsController', ['only' => ['store', 'update', 'destroy']]);
Route::resource('shop', 'shopController', ['as' => 'shop', 'only' => 'update']);

Route::get('payment', ['as' => 'payment', 'uses' => 'PayPalController@postPayment']);
Route::get('payment/status', ['as' => 'payment.status','uses' => 'PayPalController@getPaymentStatus']);
Route::get('hypay/store', ['as' => 'payment2.status','uses' => 'PayPalController@getPaymentStatus']);

//Route::resource('coins', 'coinItemsController', ['only' => ['store','update', 'destroy']]);
