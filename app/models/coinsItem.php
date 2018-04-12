<?php

class coinsItem extends Eloquent{
    protected $table = "coinsItems";

    public static $rules = array(
      'name' => 'required',
      'slug' => 'required',
      'image' => 'required|image|mimes:jpg,png',
      'description' => 'required',
      'allopassId' => 'numeric',
      'rewardCoin' => 'required|in:standard,premium',
      'rewardQty' => 'required|numeric',
      'price' => 'required|numeric',
      'sales' => 'required|numeric',
      'weight' => 'required|between:0,9000',
    );

    public static $step1 = array(
      'agreement' => 'accepted',
      'nonpremiumagreement' => 'accepted',
      'paymentmode' => 'required',
      'itemId' => 'required|numeric'
    );

    public static $step2 = array(
      'agreement' => 'accepted',
      'nonpremiumagreement' => 'accepted',
      'paymentmode' => 'required',
      'itemId' => 'required|numeric',
      'realname' => 'required',
      'surname' => 'required',
      'email' => 'required|email|min:6',
      'address' => 'required',
      'postalcode' => 'required|numeric',
      'city' => 'required'
    );

}
