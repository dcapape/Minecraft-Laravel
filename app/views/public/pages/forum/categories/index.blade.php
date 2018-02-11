@extends('public.layouts.default')

@section('title', ' - '.trans('general.forums'))

@section('content')
@include('public.pages.forum.parts.breadcrumbs')
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{trans('forum.Categories')}}</h3>
  </div>

  <div class="panel-body">
    <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
    	<thead>
    		<tr>
    			<th>
    				{{trans('forum.Category')}}
    			</th>
    		</tr>
    	</thead>
    	<tbody>
        @foreach ($categories as $category)
        <tr>
    			<td>
    				<a href="/forum/{{$category->id}}">{{$category->name}}</a><br>
            {{$category->description}}
    			</td>
    		</tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
@include('public.pages.forum.parts.breadcrumbs')
@stop

@section('sidebar')

@stop
