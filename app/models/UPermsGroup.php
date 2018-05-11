<?php

class UPermsGroup extends Eloquent{
    protected $table = "GroupDataStorage";

    public static function getByName($name){
        return UPermsGroup::where('groupName', $name)->first();
    }

}
