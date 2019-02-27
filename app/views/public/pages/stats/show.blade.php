
@extends('public.layouts.default')

@section('title', ' - '.trans('statistics.Statistics'))

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title pull-left">{{$stats->name}} {{trans('statistics.on')}} {{$stats->server}}</h3>
    <form class="form-inline pull-right" style="margin-top: -6px;">
      <select class="form-control input-sm" id="server">
          <option value="global">{{trans('statistics.Global')}}</option>
          @foreach ($servers as $server)
          <option value="{{$server->server}}" {{ ($stats->server==$server->server) ? 'selected':''}}>{{$server->server}}</option>
          @endforeach
      </select>
    </form>
  </div>
  <div class="panel-body">
    <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
    	<thead>
    		<tr>
    			<th>
    				{{trans('statistics.Time')}}
    			</th>
    			<th>

    			</th>
    		</tr>
    	</thead>
    	<tbody>
        <tr>
    			<td>
    				{{trans('statistics.TimePlayed')}}
    			</td>
    			<td>
    				{{Convert::toHours($stats->PLAY_ONE_TICK/20)}}
    			</td>
    		</tr>
        <tr>
          <td>
            {{trans('statistics.TimeHidden')}}
          </td>
          <td>
            {{Convert::toHours($stats->SNEAK_TIME)}}
          </td>
        </tr>
        <tr>
    			<td>
            {{trans('statistics.TimesPlayed')}}
    			</td>
    			<td>
    				{{$stats->LEAVE_GAME}}
    			</td>
    		</tr>
        <tr>
    			<td>
            {{trans('statistics.Timescrafting')}}
    			</td>
    			<td>
    				{{$stats->CRAFTING_TABLE_INTERACTION}}
    			</td>
    		</tr>
        <tr>
    			<td>
            {{trans('statistics.Timesopeningchests')}}
    			</td>
    			<td>
    				{{$stats->CHEST_OPENED}}
    			</td>
    		</tr>
        <tr>
    			<td>
            {{trans('statistics.Timessleeping')}}
    			</td>
    			<td>
    				{{$stats->SLEEP_IN_BED}}
    			</td>
    		</tr>
      </tbody>
    </table>


    <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
      <thead>
        <tr>
          <th>
            {{trans('statistics.ELO')}}
          </th>
          <th>
            {{$stats->PLAYER_KILLS}} / {{$stats->MOB_KILLS}} / {{$stats->DEATHS}}
          </th>
        </tr>
      </thead>
      <tbody>
    		<tr>
    			<td>
    				{{trans('statistics.DamageDealt')}}
    			</td>
    			<td>
    				{{$stats->DAMAGE_DEALT}} {{trans('statistics.points')}}
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.DamageTaken')}}
    			</td>
    			<td>
    				{{$stats->DAMAGE_TAKEN}} {{trans('statistics.points')}}
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.PlayerKills')}}
    			</td>
    			<td>
    				{{$stats->PLAYER_KILLS}} {{trans('statistics.kills')}}
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.MobKills')}}
    			</td>
    			<td>
    				{{$stats->MOB_KILLS}} {{trans('statistics.kills')}}
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.Deaths')}}
    			</td>
    			<td>
    				{{$stats->DEATHS}} {{trans('statistics.deaths')}}
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.AnimalsBred')}}
    			</td>
    			<td>
    				{{$stats->ANIMALS_BRED}} {{trans('statistics.bred')}}
    			</td>
    		</tr>
    	</tbody>
    </table>


    <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
    	<thead>
    		<tr>
    			<th>
    				{{trans('statistics.Distances')}}
    			</th>
    			<th>
    			</th>
    		</tr>
    	</thead>
    	<tbody>
        <tr>
    			<td>
            {{trans('statistics.Walked')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->WALK_ONE_CM)}} kms
    			</td>
    		</tr>
        <tr>
          <td>
            {{trans('statistics.Crouched')}}
          </td>
          <td>
            {{Convert::toKms($stats->CROUCH_ONE_CM)}} kms
          </td>
        </tr>
        <tr>
          <td>
            {{trans('statistics.Sprinting')}}
          </td>
          <td>
            {{Convert::toKms($stats->SPRINT_ONE_CM)}} kms
          </td>
        </tr>
    		<tr>
    			<td>
    				{{trans('statistics.Swiming')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->SWIM_ONE_CM)}} kms
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.Falling')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->FALL_ONE_CM)}} kms
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.Climbing')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->CLIMB_ONE_CM)}} kms
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.Diving')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->DIVE_ONE_CM)}} kms
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.InaMinecart')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->MINECART_ONE_CM)}} kms
    			</td>
    		</tr>
        <tr>
    			<td>
    				{{trans('statistics.InaBoat')}}
    			</td>
    			<td>
    				{{Convert::toKms($stats->BOAT_ONE_CM)}} kms
    			</td>
    		</tr>
    	</tbody>
    </table>
    {{-- var_dump($stats) --}}
  </div>
</div>
@stop


@section('sidebar')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"></h3>
  </div>

  <div class="panel-body">
    <form class="form-inline" id="searchPlayerForm">
      <div class="form-group">
        <input type="text" class="form-control" id="searchPlayer" name="searchPlayer" placeholder="{{trans('statistics.searchPlayer')}}">
      </div>
      <button type="submit" class="btn btn-default">{{trans('statistics.Go')}}</button>
    </form>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{$stats->name}}</h3>
  </div>
  <div class="panel-body">
    <center>
  @if (User::isPremium($stats->uuid))
  <!--<img src="https://minecraft-api.com/api/skins/skins.php?player={{$stats->name}}" style="width:50px" />-->
  <img class="flip" src="https://visage.surgeplay.com/full/400/{{$stats->uuid}}" title="{{$stats->name}} avatar" />
  @else
  <!--<img src="https://lh3.googleusercontent.com/kcEh6LtwvYN1dUrh1d-ctvtFLbkVdT6ba-8Tr7ePYz6FCmHcuTA5K14Sm1CgEbuKHuqI-gWlifb7XdEKlG2zTw=s400" style="width:50px" />-->
  <img class="flip" src="https://visage.surgeplay.com/full/400/X-Steve" title="{{$stats->name}} avatar" />
  @endif
    </center>
  </div>
</div>
@stop


@section('script')
  $('#server').change(function() {
    $(location).attr('href',"/stats/{{$stats->name}}/"+$(this).val());
  });

  $('#searchPlayerForm').submit(function( event ) {
    $(location).attr('href',"/profile/"+$('#searchPlayer').val());
    event.preventDefault();
    return false;
  });
@stop
