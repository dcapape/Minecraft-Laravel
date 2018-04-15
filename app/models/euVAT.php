<?php

class euVAT extends Eloquent{
    protected $table = "euVAT";

    public static function calculate($code, $qty, $mode=true){

        $vat = euVAT::where('code', $code)->first()->vat;

        if ($mode){
          return $qty * (($vat/100) + 1);
        }else{
          return ($qty/100) * $vat;
        }
    }



}
