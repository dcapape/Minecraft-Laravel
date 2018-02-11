@extends('public.layouts.default')

@section('title', ' - Shop')

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      @if (@$cost)
        <h3 class="panel-title">Editing cost</h3>
      @else
        <h3 class="panel-title">Create new cost</h3>
      @endif
    </div>

    <div class="panel-body">
      @if (Session::has('message'))
          <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
      @if ($errors->all())
          <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
      @endif

      @if (isset($cost))
          {{ Form::model($cost, array('route' => array('shop.item.cost.update', $item->id, $cost->id), 'method' => 'PUT')) }}
      @else
          {{ Form::open(array('route' => array('shop.item.cost.store', $item->id))) }}
      @endif

      <div class="form-group">
          {{ Form::label('item', 'Item') }}
          {{ Form::text('itemName', $item->name, array('class' => 'form-control', 'disabled')) }}
          {{-- Form::hidden('item', $item->id, array('class' => 'form-control')) --}}
      </div>

      <div class="form-group">
          {{ Form::label('serverId', 'Server') }}
          {{ Form::select('serverId', $servers, Input::old('serverId'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('coin', 'Coin') }}
          {{ Form::select('coin', array('real' => "Euro", 'standard' => "MineCoins", 'premium' => "Gold Diamonds"), Input::old('coin'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('price', 'Price') }}
          {{ Form::text('price', Input::old('price'), array('class' => 'form-control')) }}
      </div>

      <div class="form-group">
          {{ Form::label('discount', 'Discount') }}
          {{ Form::text('discount', Input::old('discount'), array('class' => 'form-control')) }}
      </div>


      {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}

      {{ Form::close() }}

      @if (isset($cost))
        {{ Form::open(array('route' => array('shop.item.cost.destroy', $item->id, $cost->id), 'class' => 'pull-right')) }}
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::submit('Delete', array('class' => 'btn btn-warning')) }}
        {{ Form::close() }}
      @endif
    </div>
  </div>


@stop
