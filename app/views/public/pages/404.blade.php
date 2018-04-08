
@extends('public.layouts.default')

@section('title', ' - '.trans('general.error404'))

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{trans('general.pagenotfound')}}</h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-8 err404 text-center">
          <br><br>
          <h1>ERROR 404</h1>
          <br><br>
          <h2>{{trans('general.errormessage')}}</h2>
        </div>
        <div class="col-sm-4">
          <img class="pull-right" src="/assets/img/404.png">
        </div>
      </div>
    </div>
</div>
@stop
