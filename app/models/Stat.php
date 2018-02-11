<?php

class Stat extends Eloquent{
    protected $table = "mc_stats";
    protected $hidden = ["uuid"];

    public static function getStats($server, $uuid = null){
        $uuid = (is_null($uuid)) ? @Auth::user()->uuid : $uuid;
        return Stat::where('uuid', $uuid)->where('server', $server)->first();
    }

    public static function getServerListByUuid($uuid){
        return Stat::select('server')->where('uuid', $uuid)->get();
    }

}
