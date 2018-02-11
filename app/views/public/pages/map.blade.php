
@extends('public.layouts.default')

@section('title', ' - ' . $worldid . ' - ' .trans('general.maps'))

@section('content')
<div class="panel panel-default formbg">
  <div class="panel-heading">
    <h3 class="panel-title">{{$worldid}}</h3>
  </div>
  <iframe src="/map/survival4/" style="border:0px #ffffff none;" name="map" scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" height="500px" width="100%" allowfullscreen></iframe>
</div>
@stop
