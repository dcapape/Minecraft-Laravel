<?php

class forumPostsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('auth', array('only' => 'store'));
        $this->beforeFilter('csrf', array('on' => 'post'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // N/A
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

      if (forumTopic::where('id', Input::get('topicId'))->first()->locked && !User::imAdmin())
        return Redirect::back()->with('message', 'This topic is Locked')->withInput();

      $validator = Validator::make(Input::all(), forumPost::$rules);
      if ($validator->passes()) {
          try {
              $post = new forumPost;
              $post->content = Input::get('content');
              $post->date = date("Y-m-d H:i:s");
              $post->topicId = Input::get('topicId');
              $post->userId  = Auth::user()->id;
              $post->save();

              return Redirect::back();
          } catch (Exception $e) {
              return Redirect::back()->with('message', 'The following errors occurred: <br> ' . $e->getMessage());
          }
      } else {
          return Redirect::back()->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
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
