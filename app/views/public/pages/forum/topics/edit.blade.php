@extends('public.layouts.default')

@section('title', ' - Forum Topics')

@section('content')
@include('public.pages.forum.parts.breadcrumbs')



<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Create a new topic for {{$category->name}}</h3>
  </div>

  <div class="panel-body">
    @if (!Auth::guest())
      @if (Session::get('message'))
      <div class="alert alert-warning">
        {{ Session::get('message') }}
        {{ HTML::ul($errors->all()) }}
      </div>
      @endif
      
      @if (isset($topic))
          {{ Form::model($item, array('route' => array('forum.topic.update', $item->id), 'method' => 'PUT')) }}
      @else
          {{ Form::open(array('route' => array('forum.topic.store'))) }}
      @endif
      {{ Form::hidden('categoryId', $category->id) }}
      <div class="form-group">
          {{ Form::label('subject', 'Subject') }}
          {{ Form::text('subject', Input::old('subject'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('content', 'Content') }}
          <textarea id="editor" name="content" class="textarea400" >{{Input::old('content')}}</textarea>
      </div>

      @if(User::imAdmin())
      <div class="form-group">
          {{ Form::label('home', 'Homepage') }}
          {{ Form::checkbox('home', true, Input::old('home'), array('class' => 'form-control')) }}
      </div>
      @endif

      <div style="text-align: center;">
      {{ Form::submit('Submit', array('class' => 'btn btn-primary', 'style' => 'margin: 10px; font-family: mcsmall;')) }}
      </div>
      {{ Form::close() }}
    @endif
  </div>
</div>



@include('public.pages.forum.parts.breadcrumbs')
@stop

@section('script')

@include('public.includes.scripts.editor', ['height' => "800"])
@include('public.includes.scripts.mcformat')

@stop
