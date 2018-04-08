<?php

class Currency
{

  public static function fromCountryCode($code, $qty){
    $country = DB::table('helper_country')->where('code', $code)->first();

    $url = 'https://www.quandl.com/api/v3/datasets/CURRFX/EUR'.$country->currency_code.'/data.json?rows=1&api_key='.Config::get('apis.quandl');

    $rate = 1;
    $cCode = "EUR";

    if(Currency::get_http_response_code($url) == "200" && $country->currency_code != "EUR"){
      $json = file_get_contents($url);
      $obj = json_decode($json, true);
      $rate = $obj["dataset_data"]["data"][0][2];
      $cCode = $country->currency_code;
    }

    return round($qty*$rate,2). " " . $cCode;
  }


  static function get_http_response_code($url) {
      $headers = get_headers($url);
      return substr($headers[0], 9, 3);
  }

}
