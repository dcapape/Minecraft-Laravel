<?php

class Coin extends Eloquent{
    protected $table = "coinsBalance";
    protected $hidden = ["uuid"];
    public static $rules = array(
        'uuid' => 'required|between:36,36|unique:coinsBalance',
        'nick' => 'required|alpha_num',
        'balance' => 'required|num|between:0,1000000',
        'lastlogin' => 'num'
    );
    public static function getBalance($uuid = null, $coin = null){
        if ($uuid){
          if ($coin == "premium" || $coin == "real")
            $balance = Coin::select('balance')->where('uuid', $uuid)->where('coin', $coin)->first();
          else
            $balance= Coin::select('balance')->where('uuid', $uuid)->where('coin', 'standard')->first();
        }else{
          if ($coin == "premium" || $coin == "real")
            $balance = Coin::select('balance')->user()->where('coin', $coin)->first();
          else
            $balance = Coin::select('balance')->user()->where('coin', 'standard')->first();
        }
        return ($balance != null) ? $balance->balance : 0;
    }
    public function scopeUser($query) {
        return $query->where('uuid', '=', Auth::user()->uuid);
    }
    public function setBalance($value) {
        $this->balance = $value;
    }
}
