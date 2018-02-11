
@extends('public.layouts.default')

@section('title', ' - '.trans('general.Login'))

@section('content')
<div class="panel panel-default formbg">
  <div class="panel-heading">
    <h3 class="panel-title">{{trans('general.SignIn')}}</h3>
  </div>
  <div class="panel-body col-md-8 center">
    {{ Form::open(array('url'=>'user/signin', 'class'=>'form-signin', 'autocomplete'=>'new-password')) }}
        <br><br>
        <h2 class="form-signin-heading">{{trans('general.PleaseLogin')}}</h2>
        <br>
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        {{ Form::text('nick', null, array('class'=>'form-control', 'placeholder'=>trans('general.enterNick'))) }}
        {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>trans('general.Password'))) }}
        <br><br><br>
        {{ Form::submit(trans('general.Login'), array('class'=>'btn btn-large btn-primary btn-block'))}}
        <br><br><br>
    {{ Form::close() }}
  </div>
</div>
@stop
