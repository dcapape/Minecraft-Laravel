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
        //'sellable' => 'boolean',
    );

    public static $step1 = array(
        'agreement' => 'accepted',
        'nonpremium-agreement' => 'accepted',
        'paymentmode' => 'required|required',
        'itemId' => 'required|numeric'
    );

}
