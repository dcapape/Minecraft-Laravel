@extends('public.layouts.default')

@section('title', ' - '.trans('forum.forumTopics'))

@section('content')
@include('public.pages.forum.parts.breadcrumbs')
<div class="new-line" style="padding-bottom: 15px;">
  @include('public.pages.forum.parts.newtopic')
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{trans('forum.Topics')}}</h3>
  </div>

  <div class="panel-body">
    @if (Session::get('message'))
    <div class="alert alert-warning">
      {{ Session::get('message') }}
      {{ HTML::ul($errors->all()) }}
    </div>
    @endif
    <table class="table table-bordered table-striped table-hover table-condensed table-responsive" style="margin-bottom: 0px;">
    	<thead>
    		<tr>
    			<th>
    				{{trans('forum.Topic')}}
    			</th>
          <th class="small">
    				{{trans('forum.LastMessage')}}
    			</th>
          <th class="small">
    				{{trans('forum.Replies')}}
    			</th>
    		</tr>
    	</thead>
    	<tbody>
        @foreach ($topics as $topic)
        <tr>
    			<td>
    				<a href="/forum/topic/{{$topic->id}}">{{$topic->subject}}</a><br>
            <abbr class="small">{{date_format(date_create($topic->date), 'd-m-Y H:i');}} / <a href="/profile/{{User::id($topic->userId)->nick}}">{{User::id($topic->userId)->nick}}</a></abbr>
    			</td>
          <td class="col-lg-3 col-md-3 col-sm-2 col-xs-2 small lead">
            {{$topic->lastPostUser}}<br>
            {{Convert::elapsedTimeString($topic->lastPostDate, null, "1 day",false,trans('langs.ago'), "d-m-Y H:i");}}
          </td>
          <td class="col-lg-1 col-md-1 col-sm-1 col-xs-1 small lead">
            {{$topic->length}}
          </td>
    		</tr>
        @endforeach
      </tbody>
    </table>

  </div>
</div>
<div class="new-line" style="padding-bottom: 15px;">
  @include('public.pages.forum.parts.newtopic')
</div>
@include('public.pages.forum.parts.breadcrumbs')
@stop

@section('sidebar')

@stop
