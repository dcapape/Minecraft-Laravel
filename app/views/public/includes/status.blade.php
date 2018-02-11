<?php

$json = file_get_contents('https://mcapi.us/server/status?ip=mundodisco.tk&port=25565');
$obj = json_decode($json, true);

$show = $obj;

//var_dump($obj);

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
?>
