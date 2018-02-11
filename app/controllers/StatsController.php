<?php

class StatsController extends BaseController {
    public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {


        return View::make('public.pages.stats.index', ['sidebar' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $name
     * @param  string  $server
     * @return Response
     */
    public function show($name, $server = null)
    {
        $server = (is_null($server)) ? 'survival4' : $server;
        $uuid = (is_null($name)) ? null : User::getuuidByNick($name)->uuid;
        $stats = Stat::getStats($server, $uuid);
        $servers = Stat::getServerListByUuid($uuid);

        return View::make('public.pages.stats.show', ['sidebar' => true, 'stats' => $stats, 'servers' => $servers]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $server
     * @return Response
     */
    public function showme($server = null)
    {
      $server = (is_null($server)) ? 'survival4' : $server;
      return StatsController::show(Auth::user()->nick, $server);
    }
}
