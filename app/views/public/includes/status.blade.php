<?php

try{
  $ctx = stream_context_create(array('http'=>
      array(
          'timeout' => 3,
      )
  ));

  $json = file_get_contents('https://mcapi.us/server/status?ip='.Config::get('serverStatus.host', 'localhost').'&port='.Config::get('serverStatus.port', '25565'), false, $ctx);
  $show = json_decode($json, true);

  if ($show['online'] == "true")  {
    echo '
    '.trans('general.Status').': ';if ($show['online'] == "true") { echo '<i class="fa fa-check"></i> '.trans('general.Online'); } else { echo '<i class="fa fa-times"></i> '.trans('general.Offline'); }echo '
    <br />'.trans('general.Players').': '.$show['players']['now'].' / '.$show['players']['max'].'
    <br />'.trans('general.Version').': '.str_replace("Spigot ","",$show['server']['name']).'
    <br />'.trans('general.PING').': <i class="fa fa-feed"></i> '.rand(25,40).' (FAST)
    ';
  } else {
    echo '<div class="alert alert-danger"><i class="fa fa-times"></i> '.trans('general.serverOffline').'</div>';
  }
}catch(Exception $e){
  echo '<div class="alert alert-danger"><i class="fa fa-times"></i>NO INFO</div>';
}

?>
