@extends('public.layouts.default')

@section('title', ' - Shop')

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      @if (@$category)
        <h3 class="panel-title">{{$category->name}}</h3>
      @else
        <h3 class="panel-title">Shop</h3>
      @endif
    </div>

    <div class="panel-body">
      @if (Session::has('message'))
          <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
      @if ($errors->all())
          <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
      @endif

      @if (isset($category))
          {{ Form::model($category, array('route' => array('shop.shop.update', $category->id), 'method' => 'PUT', 'files' => true)) }}
      @else
          {{ Form::open(array('url' => 'shop', 'files' => true)) }}
      @endif

      <div class="form-group">
          {{ Form::label('name', 'Name') }}
          {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('slug', 'Slug') }}
          {{ Form::text('slug', Input::old('slug'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('description', 'Description') }}
          {{ Form::text('description', Input::old('description'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('image', 'Image') }}
          {{ Form::file('image', array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('weight', 'Weight') }}
          {{ Form::text('weight', Input::old('weight'), array('class' => 'form-control')) }}
      </div>

      {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

      {{ Form::close() }}

      @if (isset($category))
        {{ Form::open(array('url' => 'shop/' . $category->id, 'class' => 'pull-right')) }}
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
        {{ Form::close() }}
      @endif
    </div>
  </div>


@stop
