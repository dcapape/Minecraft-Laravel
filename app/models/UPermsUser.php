<?php

class UPermsUser extends Eloquent{
    protected $table = "UserDataStorage";
    protected $hidden = ["uuid"];

    public static function getByUUID($uuid = null){
        $uuid = (is_null($uuid)) ? @Auth::user()->uuid : $uuid;

        if (UPermsUser::where('uuid', $uuid)->leftJoin('GroupDataStorage', 'UserDataStorage.groupId', '=', 'GroupDataStorage.id')->count() > 0){
          return UPermsUser::where('uuid', $uuid)
          ->leftJoin('GroupDataStorage', 'UserDataStorage.groupId', '=', 'GroupDataStorage.id')
          ->first();
        }else{
          return null;
        }

    }

    public static function getByName($name){
        return UPerms_User::where('playerName', $name)->first();
    }

}
