<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		//$topics = forumTopic::where('home', 1)->orderBy('date', 'desc')->take(5)->get();

		$topics = forumTopic::leftJoin('forumCategories', 'forumTopics.categoryId', '=', 'forumCategories.id')
		->select('forumTopics.*','forumCategories.name','forumCategories.language')->where('home', 1)->where('language', LaravelLocalization::getCurrentLocale())->orderBy('date', 'desc')->take(5)->get();

		foreach ($topics as $topic) {
			$topic->content = forumPost::where('topicId', $topic->id)->first()->content;
			$topic->length = forumPost::where('topicId', $topic->id)->count();
		}

		return View::make('public.pages.home', ['sidebar' => true, 'topics' => $topics]);
	}

}
