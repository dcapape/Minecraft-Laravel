<?php

class coinItemsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => ['index']));
        $this->beforeFilter('admin', array('except' => ['index', 'show', 'create']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
      $userlocation = GeoIP::getLocation();
      $items = coinsItem::orderBy('weight', 'ASC')->get();

      return View::make('public.pages.coins.index', ['items' => $items, 'userlocation' => $userlocation]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // TODO
          return "hola2";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
      $validator = Validator::make(Input::all(), coinsItem::$step1);
      if ($validator->passes()) {
        if (!coinsItem::where('id', Input::get('itemId'))->count())
          return App::abort(404);

        Session::put('redirect', Request::url());
        $userlocation = GeoIP::getLocation();
        $item = coinsItem::where('id', Input::get('itemId'))->first();

        // Real Money Item?
        if ($item->allopassId != null){
          $allopass = new Allopass;
          $item->allopass = $allopass->getOnetimePrices($item->allopassId);
        }

        return View::make('public.pages.coins.data', [
          'item' => $item,
          'userlocation' => $userlocation,
          'agreement' => Input::get('agreement'),
          'nonpremiumagreement' => Input::get('nonpremium-agreement')
          ]);
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
      if (!coinsItem::where('slug', $slug)->count())
        return App::abort(404);

      Session::put('redirect', Request::url());
      $userlocation = GeoIP::getLocation();
      $item = coinsItem::where('slug', $slug)->orderBy('weight', 'ASC')->first();

      // Real Money Item?
      if ($item->allopassId != null){
        $allopass = new Allopass;
        $item->allopass = $allopass->getOnetimePrices($item->allopassId);
      }

      return View::make('public.pages.coins.show', ['item' => $item, 'userlocation' => $userlocation]);

      //TODO STRIPE https://medium.com/justlaravel/how-to-integrate-stripe-payment-gateway-in-laravel-94b145ce4ede

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
      // TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // TODO
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
      $post = coinsItem::find($id);
      $post->delete();

      // redirect
      Session::flash('message', 'Successfully deleted the nerd!');
      return Redirect::to('coins');
    }

  }
