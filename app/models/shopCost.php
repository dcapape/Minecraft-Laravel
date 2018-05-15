<?php

class shopCost extends Eloquent{
    protected $table = "shopCosts";

    public static $rules = array(
        //'itemId' => 'required|numeric',
        'serverId' => 'required',
        'coin' => 'required|in:standard,premium',
        'price' => 'required|numeric',
        'discount' => 'numeric',
    );




}
