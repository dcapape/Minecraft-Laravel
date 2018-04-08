<?php

class shopController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => ['index', 'show']));
        $this->beforeFilter('admin', array('except' => ['index', 'show']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      //$location = GeoIP::getLocation();
      //dd($location);
        $categories = shopCategory::orderBy('weight', 'ASC')->get();

        return View::make('public.pages.shop.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

      return View::make('public.pages.shop.categories.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

      $validator = Validator::make(Input::all(), shopCategory::$rules);
      if ($validator->passes()) {
          try {
              Input::file('image')->move(public_path('img'), Input::file('image')->getClientOriginalName());

              $post = new shopCategory;
              $post->name = Input::get('name');
              $post->slug = Input::get('slug');
              $post->description = Input::get('description');
              $post->image = Input::file('image')->getClientOriginalName();
              $post->weight = Input::get('weight');
              $post->save();

              return Redirect::to('shop');
          } catch (Exception $e) {
              return Redirect::back()->with('message', 'The following errors occurred: <br> ' . $e->getMessage())->withInput();
          }
      } else {
          return Redirect::back()->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return Response
     */
    public function show($slug)
    {
      // If invalid category
      if (!shopCategory::where('slug', $slug)->count())
        return App::abort(404);

      // Categories list
      $categories = shopCategory::orderBy('weight', 'ASC')->get();

      // Category selected
      $category = shopCategory::where('slug', $slug)->first();
      if (User::imAdmin())
        $items = shopItem::where('categoryId', $category->id)->get();
      else
        $items = shopItem::where('categoryId', $category->id)->where('sellable', true)->get();
      foreach ($items as $id => $item) {
        // Get Costs from Item
        $items[$id]->costs = shopCost::where('itemId', $item->id)->get();
        // Get Servers available for this Item from Costs
        $servers = array();
        foreach ($items[$id]->costs as $cost) {
          if ($cost->serverId == 0)
            $servers[0] = "Global";
          else
            $servers[$cost->serverId] = Server::find($cost->serverId)->name;
        }
        $servers = array_unique($servers);
        //$servers = asort($servers);
        $items[$id]->servers = $servers;
      }

      return View::make('public.pages.shop.index',
                  ['categories' => $categories,
                  'category' => $category,
                  'items' => $items]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

      $category = shopCategory::where('id', $id)->first();
      return View::make('public.pages.shop.categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {

      $validator = Validator::make(Input::all(), shopCategory::$rulesUpdate);
      if ($validator->passes()) {
          try {
              $post = shopCategory::find($id);
              $post->name = Input::get('name');
              $post->slug = Input::get('slug');
              $post->description = Input::get('description');
              $post->weight = Input::get('weight');

              if (Input::file('image') != null){
                Input::file('image')->move(public_path('img'), Input::file('image')->getClientOriginalName());
                $post->image = Input::file('image')->getClientOriginalName();
              }

              $post->save();

              return Redirect::to('shop');
          } catch (Exception $e) {
              return Redirect::back()->with('message', 'The following errors occurred: <br> ' . $e->getMessage())->withInput();
          }
      } else {
          return Redirect::back()->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
      // delete
      $post = shopCategory::find($id);
      $post->delete();

      // redirect
      Session::flash('message', 'Successfully deleted the nerd!');
      return Redirect::to('shop');
    }

  }
