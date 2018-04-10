<?php

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class UuidGen extends Eloquent{

  public static function generateUuid($nickname){
    try {
        $uuid4 = Uuid::uuid4( ( "OfflinePlayer:" + $nickname ) );
        return $uuid4->toString();
    } catch (UnsatisfiedDependencyException $e) {
        return false;
        //dd('Caught exception: ' . $e->getMessage() . "\n");
    }
  }

}
