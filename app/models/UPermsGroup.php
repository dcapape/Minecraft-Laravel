<?php

class UPermsGroup extends Eloquent{
    protected $table = "UltraPermissions_Groups";

    public static function getByName($name){
        return UPermsGroup::where('name', $name)->first();
    }

}
