<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UuidGen extends Eloquent{

  public static function generateUuid($nickname = null){
    if ($nickname = null)
      $string = Convert::random(32);
    else
      $string = "OfflinePlayer:" + $nickname;

    try {
        $uuid4 = Uuid::uuid4( ( $string ) );
        return $uuid4->toString();
    } catch (UnsatisfiedDependencyException $e) {
        return false;
        //dd('Caught exception: ' . $e->getMessage() . "\n");
    }
  }

}
