
@extends('public.layouts.default')

@section('title', ' - '.trans('auth.Register'))

@section('content')
<div class="panel panel-default formbg">
  <div class="panel-heading">
    <h3 class="panel-title">{{trans('auth.registerYourAccount')}}</h3>
  </div>
  <div class="panel-body col-md-12 center">
    {{ Form::open(array('url'=>'user/create', 'class'=>'form-signup', 'autocomplete'=>'new-password')) }}
        <br><br>
        <h2 class="form-signup-heading">{{trans('auth.PleaseRegister')}}</h2>
        <br>
        @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
        @endif
        @if ($errors->all())
            <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
        @endif
        {{ Form::hidden('premium', '1')}}
        <div class="panel-body col-md-6">
          {{ Form::button(trans('auth.premiumAccount'), array('class'=>'btn btn-large btn-block premium btn1'))}}
        </div>
        <div class="panel-body col-md-6">
          {{ Form::button(trans('auth.nonPremiumAccount'), array('class'=>'btn btn-large btn-block btn2'))}}
        </div>
        {{ Form::text('nick', null, array('class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>trans('auth.Nick'))) }}
        {{ Form::text('email', null, array('class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=>trans('auth.emailAddress'))) }}
        {{ Form::password('password', array('class'=>'form-control', 'autocomplete'=>'new-password', 'placeholder'=>trans('auth.Password'))) }}
        {{ Form::password('password_confirmation', array('class'=>'form-control', 'autocomplete'=>'new-password', 'placeholder'=>trans('auth.confirmPassword'))) }}
        <br><br><br>
        {{ Form::submit(trans('auth.Register'), array('class'=>'btn btn-large btn-primary btn-block'))}}
        <br><br><br>
    {{ Form::close() }}
  </div>
</div>
@stop


@section('script')
  // PREMIUM
  $(".btn1").click(function () {
      $(".btn1").attr('style', 'background-color: #7E88BF !important');
      $(".btn2").attr('style', 'background-color: #6F6F6F !important');
      $("input[name*='premium']").val("1");

      $("input[name='email']").attr("placeholder", "{{trans('auth.yourMojangEmail')}}");
      $("input[name='password']").attr("placeholder", "{{trans('auth.youtMojangPassword')}}");
      $("input[name='password_confirmation']").hide();
      $("input[name='nick']").hide();
      document.location.hash = 'premium';
  });

  // NON-PREMIUM
  $(".btn2").click(function () {
      $(".btn1").attr('style', 'background-color: #6F6F6F !important');
      $(".btn2").attr('style', 'background-color: #7E88BF !important');
      $("input[name*='premium']").val("0");

      $("input[name='email']").attr("placeholder", "{{trans('auth.emailAddress')}}");
      $("input[name='password']").attr("placeholder", "{{trans('auth.Password')}}");
      $("input[name='nick']").show();
      $("input[name='password_confirmation']").show();
      document.location.hash = 'non-premium';
  });

  //if ($("input[name*='premium']").val() == 1){
  //  $(".btn1").click();
  //}else{
  //  $(".btn2").click();
  //}

  if (window.location.hash == "#non-premium"){
    $(".btn2").click();
  }else{
    $(".btn1").click();
  }
@stop
