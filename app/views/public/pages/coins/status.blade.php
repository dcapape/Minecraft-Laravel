@extends('public.layouts.default')

@section('title', ' - '.trans('general.shop'))

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{trans('general.shop')}}</h3>
    </div>
    <div class="panel-body">
      <div class="row">
        @if (Session::get('status') == 'success')
          <div class="col-sm-8 text-center">
            <br><br>
            <h1>{{trans('shop.success')}}</h1>
            <br><br>
            <h2>{{trans('shop.successdetails')}}</h2>
            <br><br>
            <br><br>
            <p>{{trans('shop.redirect')}}</p>
          </div>
        @else
          <div class="col-sm-8 err404 text-center">
            <br><br>
            <h1>{{trans('shop.failed')}}</h1>
            <br><br>
            <h2>{{trans('shop.faileddetails')}}</h2>
            <br><br>
            <br><br>
            <p>{{trans('shop.redirect')}}</p>
          </div>
        @endif
        <div class="col-sm-4">
          @if (Session::get('status') == 'success')
            <img class="pull-right col-sm-12" src="/assets/img/success.png">
          @else
            <img class="pull-right col-sm-12" src="/assets/img/404.png">
          @endif
        </div>
      </div>
    </div>

@stop

@section('script')
var myFunction = function(){
    window.location.href = '/shop/';
};
setTimeout(myFunction, 7000);
@stop
