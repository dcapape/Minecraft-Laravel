<?php

class shopItemsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => ['index', 'show']));
        $this->beforeFilter('admin', array('except' => ['index', 'show','postBuy', 'getSuccess']));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        Session::put('redirect', Request::url());
        return Redirect::route('shop.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
      $categories = shopCategory::orderBy('weight', 'ASC')->get();
      $catsarray = array();
      foreach ($categories as $category) {
        $catsarray[$category->id] = $category->name;
      }
      return View::make('public.pages.shop.items.edit', ['categories' => $catsarray]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {

      $validator = Validator::make(Input::all(), shopItem::$rules);
      if ($validator->passes()) {
          try {
              Input::file('image')->move(public_path('img'), Input::file('image')->getClientOriginalName());

              $post = new shopItem;
              $post->name = Input::get('name');
              $post->slug = Input::get('slug');
              $post->description = Input::get('description');
              $post->image = Input::file('image')->getClientOriginalName();
              $post->categoryId = Input::get('categoryId');
              $post->allopassId = (Input::get('allopassId') != "") ? Input::get('allopassId') : null;
              $post->command = Input::get('command');
              $post->weight = Input::get('weight');
              $post->sellable = Input::get('sellable');
              $post->save();

              return Redirect::route('shop.item.edit', $post->id);
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
      if (!shopItem::where('slug', $slug)->count())
        return App::abort(404);

      Session::put('redirect', Request::url());
      $userlocation = GeoIP::getLocation();

      // Item selected
      $item = shopItem::where('slug', $slug)->first();
      $item->costs = shopCost::where('itemId', $item->id)->get();
      $item->servers = shopCost::where('itemId', $item->id)->groupBy('serverId')->get();

      // Real Money Item?
      if ($item->allopassId != null){
        $allopass = new Allopass;
        $item->allopass = $allopass->getOnetimePrices($item->allopassId);
      }

      //TODO STRIPE https://medium.com/justlaravel/how-to-integrate-stripe-payment-gateway-in-laravel-94b145ce4ede

      return View::make('public.pages.shop.items.show',
                  ['item' => $item,
                  'userlocation' => $userlocation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

      $item = shopItem::where('id', $id)->first();
      $item->costs = shopCost::where('itemId', $item->id)->get();

      $categories = shopCategory::orderBy('weight', 'ASC')->get();
      $catsarray = array();
      foreach ($categories as $category) {
        $catsarray[$category->id] = $category->name;
      }
      return View::make('public.pages.shop.items.edit',
                  ['categories' => $catsarray,
                  'item' => $item]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {

      $validator = Validator::make(Input::all(), shopItem::$rulesUpdate);
      if ($validator->passes()) {
          try {
              $post = shopItem::find($id);
              $post->name = Input::get('name');
              $post->slug = Input::get('slug');
              $post->description = Input::get('description');
              $post->categoryId = Input::get('categoryId');
              $post->allopassId = (Input::get('allopassId') != "") ? Input::get('allopassId') : null;
              $post->command = Input::get('command');
              $post->weight = Input::get('weight');
              $post->sellable = Input::get('sellable');

              if (Input::file('image') != null){
                Input::file('image')->move(public_path('img'), Input::file('image')->getClientOriginalName());
                $post->image = Input::file('image')->getClientOriginalName();
              }

              $post->save();

              return Redirect::to('shop/'.shopCategory::find($post->categoryId)->slug);
          } catch (Exception $e) {
              return Redirect::back()->with('message', 'The following errors occurred: <br> ' . $e->getMessage())->withInput();
          }
      } else {
          return Redirect::back()->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
    }

    public function postBuy($item)
    {
      //dd(Input::all());
      $validator = Validator::make(Input::all(), shopItem::$rulesBuy);
      if ($validator->passes()) {
          try {
            try {
              // Define GET Inputs
              $coin = Input::get("coin");
              $price = Input::get("price");
              $itemId = Input::get("itemId");
              $serverId = Input::get("serverId");

              // Check if Server ID valid
              if (!Server::where('id', $serverId)->count() && $serverId <> 0)
                return App::abort(500, 'Unauthorized Server ID.');
              $server = Server::where('id', $serverId)->first();

              // Check if Item exists
              if (!shopItem::find($itemId)->count())
                return App::abort(500, 'Unauthorized Shop Item.');

              // Get Item object and related costs
              $item = shopItem::where('id', $itemId)->first();
              $item->costs = shopCost::where('itemId', $item->id)->get();
              //$item->servers = shopCost::where('itemId', $item->id)->groupBy('serverId')->get();

              // Check if User request is valid
              $valid = false;
              foreach ($item->costs as $cost) {
                if ($cost->itemId == $itemId && $cost->serverId == $serverId && $cost->coin == $coin && $cost->price == $price)
                  $valid = true;
              }

              if (!$valid)
                return App::abort(500, 'Unauthorized Transaction.');

              $user = User::find(Auth::user()->id);
              $uuid = new UuidGen;
              $transactionid = $uuid->generateUuid();

              $sell = new shopSell;
              $sell->uuid = $user->uuid;
              $sell->userId = $user->id;
              $sell->nick = $user->nick;
              $sell->ip = GeoIP::getLocation()["ip"];
              $sell->email = $user->email;
              $sell->premium = $user->premium;

              $sell->itemId = $itemId;
              $sell->itemName = $item->name;

              $sell->serverId = $serverId;
              $sell->serverName = $server->name;

              $sell->coin = $coin;
              $sell->cost = $price;

              $sell->transactionId = $transactionid;
              $sell->status = "PENGING";
              $sell->save();
            }catch (Exception $e) {
                return Redirect::back()->with('message', '-The following errors occurred: <br> ' . $e->getMessage());
            }

            try {
              $transaction = new shopTransaction();
              $transaction->uuid = $user->uuid;
              $transaction->serverId = $serverId;
              $transaction->soldId = $itemId;
              $transaction->commands = $item->command;
              $transaction->status = "pending";
              $transaction->transactionId = $transactionid;
              $transaction->log = "";
              $transaction->save();
            }catch (Exception $e) {
                return Redirect::back()->with('message', '*The following errors occurred: <br> ' . $e->getMessage());
            }

            if (Coin::where('uuid', $user->uuid)->where('coin', $coin)->count() > 0){
              $coin = Coin::where('uuid', $user->uuid)->where('coin', $coin)->first();
              $coin->balance = $coin->balance - $price;
              $coin->save();
            }

              return Redirect::to('shop/item/success/'.$transactionid);
          } catch (Exception $e) {
              return Redirect::back()->with('message', '.The following errors occurred: <br> ' . $e->getMessage())->withInput();
          }
      } else {
        //dd($validator);
          return Redirect::back()->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
    }

    public function getSuccess($transactionid)
    {
      return View::make('public.pages.shop.items.success',
                  ['transactionid' => $transactionid]);
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
      $post = shopItem::find($id);
      $post->delete();

      // redirect
      Session::flash('message', 'Successfully deleted the nerd!');
      return Redirect::to('shop');
    }

  }
