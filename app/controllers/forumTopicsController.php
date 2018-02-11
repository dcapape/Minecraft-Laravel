<?php

class forumTopicsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('auth', array('only' => ['create', 'store']));
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($categoriId = null)
    {
      if ($categoriId === null)
        return Redirect::route('forum.index');

      $category = forumCategory::where('id', $categoriId)->first();

      if ($category->locked && !User::imAdmin())
        return Redirect::back()->with('message', 'This category is Locked')->withInput();

      return View::make('public.pages.forum.topics.edit', ['category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
      $validator = Validator::make(Input::all(), forumTopic::$rules);
      if ($validator->passes()) {
          try {

              if (forumCategory::where('id', Input::get('categoryId'))->first()->locked && !User::imAdmin())
                return Redirect::back()->with('message', 'The following errors occurred: <br> This forum is Locked')->withInput();


              $topic = new forumTopic;
              $topic->subject = Input::get('subject');
              $topic->date = date("Y-m-d H:i:s");
              $topic->categoryId = Input::get('categoryId');
              $topic->userId  = Auth::user()->id;
              if (User::imAdmin())
                $topic->home  = (Input::get('home') == true) ? true : false;

              $topic->save();



              $validator = Validator::make(Input::all(), forumPost::$rulesCreate);
              if ($validator->passes()) {
                  try {

                      $post = new forumPost;
                      $post->content = Input::get('content');
                      $post->date = date("Y-m-d H:i:s");
                      $post->topicId = $topic->id;
                      $post->userId  = Auth::user()->id;
                      $post->save();

                      return Redirect::to('/forum/topic/'.$topic->id);
                  } catch (Exception $e) {
                      return Redirect::back()->with('message', 'The following post errors occurred: <br> ' . $e->getMessage())->withInput();
                  }
              } else {
                  return Redirect::back()->with('message', 'The following post issues occurred: ')->withErrors($validator)->withInput();
              }

          } catch (Exception $e) {
              return Redirect::back()->with('message', 'The following errors occurred: <br> ' . $e->getMessage()->withInput());
          }
      } else {
          return Redirect::back()->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
      Session::put('redirect', Request::url());
      $topic = forumTopic::where('id', $id)->first();
      $posts = forumPost::where('topicId', $id)->get();
      $category = forumCategory::where('id', $topic->categoryId)->first();

      return View::make('public.pages.forum.posts.show', [
          'category' => $category,
          'topic' => $topic,
          'posts' => $posts
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
        //TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //TODO
    }

  }
