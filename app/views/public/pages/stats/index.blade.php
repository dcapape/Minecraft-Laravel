
@extends('public.layouts.default')

@section('title', ' - '.trans('statistics.Statistics'))

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title pull-left">{{trans('statistics.Statistics')}}</h3>
    <form class="form-inline pull-right" style="margin-top: -6px;">
      <select class="form-control input-sm" id="server">
          <option value="global">{{trans('statistics.Global')}}</option>
          <option value="survival4">Survival 4</option>
      </select>
    </form>
  </div>
  <div class="panel-body col-md-12">

  </div>
</div>
@stop
