<?php

class shopCostsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => ['index', 'show']));
        $this->beforeFilter('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Redirect::to('shop.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($itemId)
    {

      $item = shopItem::where('id', $itemId)->first();
      $servers = Server::orderBy('id', 'DESC')->get();
      $serverssarray = array("global" => "Global");
      foreach ($servers as $server) {
        $serverssarray[$server->id] = $server->name;
      }
      return View::make('public.pages.shop.costs.edit',
                    ['servers' => $serverssarray,
                    'item' => $item]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($itemId)
    {

      $validator = Validator::make(Input::all(), shopCost::$rules);
      if ($validator->passes()) {
          try {

              $post = new shopCost;
              $post->itemId = $itemId;
              $post->serverId = Input::get('serverId');
              $post->coin = Input::get('coin');
              $post->price = Input::get('price');
              $post->discount = Input::get('discount');
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
    public function show($id)
    {
      return Redirect::to('shop.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($itemId, $id)
    {
      $item = shopItem::where('id', $itemId)->first();
      $servers = Server::orderBy('id', 'DESC')->get();
      $serverssarray = array("global" => "Global");
      foreach ($servers as $server) {
        $serverssarray[$server->id] = $server->name;
      }
      $cost = shopCost::find($id);
      return View::make('public.pages.shop.costs.edit',
                    ['servers' => $serverssarray,
                    'item' => $item,
                    'cost' => $cost]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($itemId, $id)
    {

      $validator = Validator::make(Input::all(), shopCost::$rules);
      if ($validator->passes()) {

          try {
              $post = shopCost::find($id);
              $post->itemId = $itemId;
              $post->serverId = Input::get('serverId');
              $post->coin = Input::get('coin');
              $post->price = Input::get('price');
              $post->discount = Input::get('discount');

              $post->save();

              return Redirect::to('shop/item/'.$itemId.'/edit');
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
    public function destroy($itemId, $id)
    {
      // delete
      $post = shopCost::find($id);
      $post->delete();

      // redirect
      Session::flash('message', 'Successfully deleted the nerd!');
      return Redirect::to('shop');
    }

  }
