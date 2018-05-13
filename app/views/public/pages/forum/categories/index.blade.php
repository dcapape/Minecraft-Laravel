@extends('public.layouts.default')

@section('title', ' - '.trans('general.forums'))

@section('content')
@include('public.pages.forum.parts.breadcrumbs')

@foreach ($languages as $language)
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{trans('forum.forumsIn')}} {{trans('langs.'.$language->language)}}</h3>
  </div>

  <div class="panel-body">
      <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
      	<thead>
      		<tr>
      			<th>
      				{{trans('forum.Category')}}
      			</th>
            <th class="col-md-1 hidden-sm hidden-xs">
      				{{trans('forum.Topics')}}
      			</th>
            <th>
      				{{trans('forum.LastMessage')}}
      			</th>
      		</tr>
      	</thead>
      	<tbody>
          @foreach ($categories as $category)
            @if($category->language == $language->language)
              <tr>
          			<td>
          				<a href="/forum/{{$category->id}}">{{$category->name}}</a><br>
                  {{$category->description}}
          			</td>
                <td class="col-md-1 hidden-sm hidden-xs">
          				{{$category->topics}}
          			</td>
                <td class="col-lg-4 col-md-3 col-sm-3 col-xs-2 small lead">
                  @if ($category->last)
                    <abbr>
                      <a href="/forum/topic/{{$category->last->id}}">{{$category->last->subject}}</a><br>
                      {{Convert::elapsedTimeString($category->last->lastPostDate, null, "1 day",false,trans('langs.ago'), "d-m-Y H:i");}}<br>
                      <a href="/profile/{{$category->last->lastPostUser}}">{{$category->last->lastPostUser}}</a>
                    </abbr>
                  @endif
          			</td>
          		</tr>
            @endif
          @endforeach
        </tbody>
      </table>

  </div>
</div>
@endforeach

@include('public.pages.forum.parts.breadcrumbs')
@stop

@section('sidebar')

@stop
