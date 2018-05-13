<?php

class forumCategoriesController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = forumCategory::orderBy('language', 'asc')->orderBy('weight', 'asc')->get();
        foreach ($categories as $category) {
    			$category->last = forumTopic::where('categoryId', $category->id)->orderBy('date', 'desc')->first();

          if ($category->last){
            $posts = forumPost::where('topicId', $category->last->id)->orderBy('id', 'DESC')->first();
            $category->last->length = forumPost::where('topicId', $category->last->id)->count();
            $category->last->lastPostUser = User::getNickById($posts->userId)->nick;
            $category->last->lastPostDate = $posts->date;
          }


    			$category->topics = forumTopic::where('categoryId', $category->id)->count();
    		}
        $languages = forumCategory::select('language')->groupBy('language')->get();
//echo "<pre>";
//dd($categories);
        return View::make('public.pages.forum.categories.index', ['categories' => $categories, 'languages' => $languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
      $category = forumCategory::where('id', $id)->first();
      $topics = forumTopic::leftJoin('forumPosts', function($join){
        $join->on('forumPosts.TopicId', "=" ,'forumTopics.id');
      })
      ->select(
        'forumTopics.id',
        DB::raw('COUNT(forumPosts.id) as length'),
        DB::raw('MAX(CAST(forumPosts.date AS CHAR)) as lastPostDate'),
        'forumTopics.subject',
        'forumTopics.date',
        'forumTopics.userId',
        'forumPosts.content')
      ->groupBy('forumTopics.id')
      ->orderBy('lastPostDate', 'DESC')
      ->where('categoryId', $id)->get();

      foreach ($topics as $topic) {
        //$topic->content = forumPost::where('topicId', $topic->id)->first()->content;
        $posts = forumPost::where('topicId', $topic->id)->orderBy('id', 'DESC')->first();
        //$topic->length = forumPost::where('topicId', $topic->id)->count();
        $topic->lastPostUser = User::getNickById($posts->userId)->nick;
        $topic->lastPostDate = $posts->date;
      }

      return View::make('public.pages.forum.topics.show', [
          'category' => $category,
          'topics' => $topics
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

  }
