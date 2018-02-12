<?php

class Allopass
{
  protected $api;
  /*$api = new AllopassApiKit\Api\AllopassAPI(Config::get('allopass.api_key'),Config::get('allopass.secret'));
  $response = $api->getProduct('1518532', array('format' => 'json'), false);
  echo "<pre>";
  var_dump($response);
  echo "</pre>";*/
  public function __construct()
  {
      $this->api = new AllopassApiKit\Api\AllopassAPI(Config::get('allopass.api_key'),Config::get('allopass.secret'));
      $this->api->setConfigurationEmailAccount(Config::get('allopass.email'));
  }


  public function getOnetimePrices($productId)
  {
//dd($this->api->getOnetimePricing(['site_id' => Config::get('allopass.site_id'), 'product_id' => $productId,  'customer_ip' => Allopass::getIP(), 'only4ip' => true, 'format' => 'json']));
    if (Allopass::getIP() != null)
      $response = $this->api->getOnetimePricing(['site_id' => Config::get('allopass.site_id'), 'product_id' => $productId,  'customer_ip' => Allopass::getIP(), 'only4ip' => true, 'format' => 'json']);
    else
      return [];

    $markets = [];
    foreach ($response->getMarkets() as $market ) {
      $marketArr = Array();

      foreach ($market->getPricepoints() as $pricepoints) {
        $marketArr['type'] = $pricepoints->getType();
        $marketArr['buyUrl'] = $pricepoints->getBuyUrl();
        $marketArr['currency'] = $pricepoints->getPrice()->getCurrency();
        $marketArr['amount'] = $pricepoints->getPrice()->getAmount();
        $marketArr['reference_currency'] = $pricepoints->getPrice()->getReferenceCurrency();
        $marketArr['reference_amount'] = $pricepoints->getPrice()->getReferenceAmount();
        //var_dump($pricepoints->getBuyUrl());
        //echo "<br><br><br><br>";
        array_push($markets, $marketArr);
      }

    }
    //echo "<br><br><br><hr>";
    //dd($markets);
    return $markets;
  }


  public static function getIP() {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = null;
      return $ipaddress;
  }

}

?>
