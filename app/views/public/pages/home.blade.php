
@extends('public.layouts.default')

@section('title', ' - '.trans('general.home'))

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{trans('home.welcome')}}</h3>
  </div>
  <div class="panel-body">
    @include('public.includes.tabs')
  </div>
</div>


<div class="panel panel-default">
  @foreach ($topics as $topic)
    <div class="panel-heading">
      <h3 class="panel-title"><a href="/forum/topic/{{$topic->id}}">{{$topic->subject}}</a></h3>
    </div>
    <div class="panel-body">

       @include('public.includes.helpers.parseHTML', ['content' => $topic->content])
    </div>
    <div class="panel-footer">
      <a href="/forum/topic/{{$topic->id}}">{{$topic->length}} @if ($topic->length != 1){{trans('home.comments')}} @else {{trans('home.comment')}} @endif</a> / {{date_format(date_create($topic->date), 'd-m-Y H:i');}} / <a href="/profile/{{User::id($topic->userId)->nick}}">{{User::id($topic->userId)->nick}}</a>
    </div>
  @endforeach
</div>
@stop

@section('sidebar')
  @include('public.includes.sidebar')
@stop
