
@extends('public.layouts.default')

@section('title', ' - '.trans('shop.success'))

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{trans('shop.success')}}</h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-8 text-center">
          <h2>{{trans('shop.successtransaction')}}</h2>
          <br>
            <h1>{{$transactionid}}</h1>
          <br>
          <h2>{{trans('shop.successinstructions')}}</h2>
        </div>
        <div class="col-sm-4">
          <img class="pull-right" style="width:100%" src="/assets/img/tick.png">
        </div>
      </div>
    </div>
</div>
@stop
