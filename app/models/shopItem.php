<?php

class shopItem extends Eloquent{
    protected $table = "shopItems";

    public static $rules = array(
        'name' => 'required',
        'slug' => 'required',
        'description' => 'required',
        'image' => 'required|image|mimes:jpg,png',
        'categoryId' => 'required|numeric',
        'allopassId' => 'numeric',
        'command' => 'required',
        'weight' => 'required|between:0,9000',
        //'sellable' => 'boolean',
    );

    public static $rulesUpdate = array(
        'name' => 'required',
        'slug' => 'required',
        'description' => 'required',
        'image' => 'image|mimes:jpg,png',
        'categoryId' => 'required|numeric',
        'allopassId' => 'numeric',
        'command' => 'required',
        'weight' => 'required|between:0,9000',
        //'sellable' => 'boolean',
    );

}
