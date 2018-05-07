<?php

class coinItemsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
        $this->beforeFilter('auth', array('except' => ['index', 'getHipaySuccess']));
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

        }else{
          $allopassSelectedPaymentMethod = str_replace("payment-", "", Input::get('paymentmode'));

          $uuid = new UuidGen;
          $sell->paymentId = $uuid->generateUuid();
          $sell->paymentMethod = Input::get('paymentmode');
          $sell->status = "PENDING_PAYMENT";
          $sell->log = "";
          $sell->save();
          Session::put('transId', $sell->id);

          if ($item->allopassId != null){
            $allopass = new Allopass;
            $item->allopass = $allopass->getOnetimePrices($item->allopassId);
          }

          foreach ($item->allopass as $availablePaymentMethod) {
            if ($availablePaymentMethod["type"] == $allopassSelectedPaymentMethod){
              ///hipay/store?action=payment-confirm&test=false&transaction_id=6b86b3f8-c490-48d0-b126-a5ba1d36708e&status=0&status_description=success&access_type=onetime&date=2018-05-05T18%3A04%3A18%2B00%3A00&code=X48H3867&pricepoint_id=0&type=free&data=&merchant_transaction_id=&amount=0&paid=0&currency=EUR&reference_currency=EUR&reference_amount=0&reference_paid=0&reference_payout=0&payout_amount=0&payout_currency=EUR&customer_country=FR&site_id=346489&product_name=10+ROYAL+COINS&api_ts=1525543463&api_hash=sha1&api_key=0230a99e0fd4c4b8f7b06b3d38415089&api_sig=718d3a834cf228262865119379b919f0432a1ebd HTTP/1.1" 500 66869 "-" "-"
              return View::make('public.pages.coins.allopass', [
                'item' => $item,
                'userlocation' => GeoIP::getLocation(),
                'buyUrl' => $availablePaymentMethod["buyUrl"]."&data=".$sell->paymentId
              ]);
            }
          }
        }

      } else {
          return Redirect::route('coins.data', $slug)
          ->with('message', 'The following errors occurred: ')
          ->withErrors($validator)
          ->withInput();
      }
    }

    public function getHipaySuccess(){
      if (Allopass::checkSignature(Input::all()) == true){
        if (coinsSell::where('paymentId', Input::get('data'))->count() > 0){
          $sell = coinsSell::where('paymentId', Input::get('data'))->first();  // Temp TransID
            Log::info('SELL LOG: Allopass: ' . Input::get('data') . ' Transaction success. Code: ' . Input::get('code'));
            Log::info('Sell', ['context' => json_encode($sell)]);

          if (Input::get('status') == 0)
            $sell->status = "APPROVED";
          else
            $sell->status = "DENIED";

          $sell->log = "Allopass \nData: " . @Input::get('data') . "\nCode: " . @Input::get('code') . "\nCountry: " . @Input::get('customer_country') . "\nProduct name: " . @Input::get('product_name');
          $sell->itemCurrency = @Input::get('payout_currency');
          $sell->itemCost = @Input::get('payout_amount');
          $sell->paymentId = @Input::get('transaction_id');

          if (Input::get('status') == 0){
            $item = coinsItem::where('id', $sell->itemId)->first();

            if (Coin::where('uuid', $sell->uuid)->where('coin', $item->rewardCoin)->count() > 0){
              $coin = Coin::where('uuid', $sell->uuid)->where('coin', $item->rewardCoin)->first();
              $coin->balance = $coin->balance + $item->rewardQty;
            }else{
              $coin = new Coin;
              $coin->balance = $item->rewardQty;
              $coin->coin = $item->rewardCoin;
              $coin->uuid = $sell->uuid;
              $coin->nick = $sell->nick;
            }
            if ($coin->save()){
              $sell->status = "APPROVED";
              $sell->save();
            }else{
              $sell->status = "FAILED";
              $sell->save();
            }
          }
        }else {
          Log::error('SELL LOG: Allopass: ' . Input::get('data') . ' transsaction ID not found. Code: ' . Input::get('code'));
        }
      }else{
        Log::error('SELL LOG: Allopass: ' . Input::get('data') . '. SIGNATURE FAILED. Code: ' . Input::get('code'));
      }
      die;
    }

    public function getStatus(){

      return View::make('public.pages.coins.status');

    }

  }
