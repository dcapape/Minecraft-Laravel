<?php

class shopCategory extends Eloquent{
    protected $table = "shopCategories";

    public static $rules = array(
        'name' => 'required',
        'slug' => 'required',
        'description' => 'required',
        'image' => 'required|image|mimes:jpg,png',
        'weight' => 'required|between:0,9000',
    );

    public static $rulesUpdate = array(
        'name' => 'required',
        'slug' => 'required',
        'description' => 'required',
        'image' => 'image|mimes:jpg,png',
        'weight' => 'required|between:0,9000',
    );
}
