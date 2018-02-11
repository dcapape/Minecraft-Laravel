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
        $categories = forumCategory::all();

        return View::make('public.pages.forum.categories.index', ['categories' => $categories]);
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
      $topics = forumTopic::where('categoryId', $id)->get();

      foreach ($topics as $topic) {
        //$topic->content = forumPost::where('topicId', $topic->id)->first()->content;
        $posts = forumPost::where('topicId', $topic->id)->orderBy('id', 'DESC')->first();
        $topic->length = forumPost::where('topicId', $topic->id)->count();
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
