<?php

class UPermsUser extends Eloquent{
    protected $table = "UltraPermissions_Users";
    protected $hidden = ["uuid"];

    public static function getByUUID($uuid = null){
        $uuid = (is_null($uuid)) ? @Auth::user()->uuid : $uuid;

        if (UPermsUser::where('uuid', $uuid)->leftJoin('UltraPermissions_Groups', 'UltraPermissions_Users.groupName', '=', 'UltraPermissions_Groups.name')->count() > 0){
          return UPermsUser::where('uuid', $uuid)
          ->leftJoin('UltraPermissions_Groups', 'UltraPermissions_Users.groupName', '=', 'UltraPermissions_Groups.name')
          ->first();
        }else{
          return null;
        }



        //return UPerms_User::where('uuid', $uuid)->first();
    }

    public static function getByName($name){
        return UPerms_User::where('name', $name)->first();
    }

}
