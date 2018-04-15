<?php

class coinItemsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => ['index']));
        //$this->beforeFilter('admin', array('except' => ['index', 'show', 'create', 'edit', 'update']));
    }


    public function index()
    {
      $userlocation = GeoIP::getLocation();
      $items = coinsItem::orderBy('weight', 'ASC')->get();

      if(!is_null(Input::get('cost'))){
        if (Auth::guest()){
          Session::flash('cost', Lang::get('shop.notenough', ['cost' => (Input::get('cost')+0)]));
        }else{
          if ((Input::get('cost') - Coin::getBalance(null,"premium")) > 0)
            Session::flash('cost', Lang::get('shop.notenough', ['cost' => (Input::get('cost') - Coin::getBalance(null,"premium"))+0]));
        }
      }

      return View::make('public.pages.coins.index', [
        'items' => $items,
        'userlocation' => $userlocation
      ]);
    }


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


      return View::make('public.pages.coins.show', [
        'id' => 0,
        'item' => $item,
        'userlocation' => $userlocation,
        'slug' => $slug
      ]);

      //TODO STRIPE https://medium.com/justlaravel/how-to-integrate-stripe-payment-gateway-in-laravel-94b145ce4ede

    }

    public function postData($slug)
    {
      $validator = Validator::make(Input::all(), coinsItem::$step1);
      if ($validator->passes()) {
        return Redirect::route('coins.data', $slug)->withInput();
      }else{
        return Redirect::route('coins.show', $slug)->with('message', 'The following errors occurred: ')->withErrors($validator)->withInput();
      }
    }

    public function getData($slug){

      if (!coinsItem::where('id', Input::old('itemId', Input::get('itemId')))->count())
        return App::abort(404);

      $userlocation = GeoIP::getLocation();
      $item = coinsItem::where('id', Input::old('itemId', Input::get('itemId')))->first();

      // Real Money Item?
      if ($item->allopassId != null){
        $allopass = new Allopass;
        $item->allopass = $allopass->getOnetimePrices($item->allopassId);
      }

      return View::make('public.pages.coins.data', [
        'item' => $item,
        'userlocation' => GeoIP::getLocation(),
        'agreement' => Input::old('agreement', Input::get('agreement')),
        'nonpremiumagreement' =>  Input::old('nonpremiumagreement', Input::get('nonpremiumagreement')),
        'paymentmode' => Input::old('paymentmode', Input::get('paymentmode')),
        'slug' => $slug
      ]);
    }

    public function postBuy($slug)
    {
      $validator = Validator::make(Input::all(), coinsItem::$step2);
      if ($validator->passes()) {
        if (!coinsItem::where('id', Input::get('itemId'))->count())
          return App::abort(404);

        $item = coinsItem::where('id', Input::get('itemId'))->first();

        $user = User::find(Auth::user()->id);
        $user->realname = Input::get('realname');
        $user->surname = Input::get('surname');
        $user->address = Input::get('address');
        $user->postalcode = Input::get('postalcode');
        $user->country = GeoIP::getLocation()["country"];
        $user->city = Input::get('city');
        $user->save();

        $sell = new coinsSell;
        $sell->uuid = $user->uuid;
        $sell->userId = $user->id;
        $sell->nick = $user->nick;
        $sell->ip = GeoIP::getLocation()["ip"];
        $sell->email = Input::get('email');
        $sell->premium = $user->premium;
        $sell->name = Input::get('realname');
        $sell->surname = Input::get('surname');
        $sell->address = Input::get('address');
        $sell->postalcode = Input::get('postalcode');
        $sell->country = GeoIP::getLocation()["country"];
        $sell->city = Input::get('city');
        $sell->itemId = Input::get('itemId');
        $sell->itemName = $item->name;
        //$sell->save();

        if (Input::get('paymentmode') == "payment-paypal"){
          $userlocation = GeoIP::getLocation();
          $convert = explode(" ", Currency::fromCountryCode($userlocation['isoCode'], $item->price));
          $price = $convert[0];
          $currency = $convert[1];

          if(euVAT::where('code', $userlocation['isoCode'])->count() > 0){
            $vat = number_format(euVAT::calculate($userlocation['isoCode'], $price, false),2);
            $countryVat = number_format(euVAT::where('code', $userlocation['isoCode'])->first()->vat);
          }else{
            $vat = number_format(0,2);
            $countryVat = number_format(0,2);
          }

          $item = new PayPal\Api\Item;
          $item->setName($item->name)
              ->setCurrency($currency)
              ->setQuantity(1)
              ->setPrice(number_format($price - $vat,2)); //no taxes
          $subtotal = number_format($price - $vat,2) ; // total no taxes
//dd(floatval(number_format($countryVat,2)));


          $sell->itemCurrency = $currency;
          $sell->itemCost = floatval(number_format($price,2)); // total with taxes
          $sell->countryVat = floatval(number_format($countryVat,2));
          $sell->paymentSubtotal = floatval($subtotal);
          $sell->paymentVat = floatval(number_format($vat,2));
          $sell->paymentMethod = Input::get('paymentmode');
          $sell->status = "PENDING_PAYMENT";
          $sell->log = "";
          $sell->save();
          Session::put('transId', $sell->id);

          $paypal = new Paypal();
          return $paypal->postPayment('paypal', array($item), $currency, $vat, number_format($subtotal,2), 'Gold Royal Coins' );

        }


      } else {
          return Redirect::route('coins.data', $slug)
          ->with('message', 'The following errors occurred: ')
          ->withErrors($validator)
          ->withInput();
      }
    }

    public function getStatus(){

      return View::make('public.pages.coins.status');

    }

  }
