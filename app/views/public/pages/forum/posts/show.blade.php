@extends('public.layouts.default')

@section('title', ' - '.$topic->subject . ' - ' .trans('general.forums'))

@section('content')
@include('public.pages.forum.parts.breadcrumbs')


<div class="new-line" style="padding-bottom: 15px;">
  @include('public.pages.forum.parts.newreply')
</div>

@if (Session::get('message'))
<div class="alert alert-warning">
  {{ Session::get('message') }}
  {{ HTML::ul($errors->all()) }}
</div>
@endif

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{$topic->subject}}</h3>
  </div>

  <div class="panel-body">
    <div class="grid row-striped">
        @foreach ($posts as $post)

        			<div class="row cont">
        				<div class="col-md-2 left-col row-border-right">
                  <h5><a href="/profile/{{User::id($post->userId)->nick}}">{{User::id($post->userId)->nick}}</a></h5>
                  <h6>{{Convert::parseMCtoHTML(@UPermsUser::getByUUID(User::id($post->userId)->uuid)->prefix)}}<h6>
                  @if (User::isPremium(User::id($post->userId)->uuid))
                  <img src="https://visage.surgeplay.com/full/150/{{User::id($post->userId)->uuid}}" title="{{User::id($post->userId)->nick}} avatar" />
                  <!--<img src="https://minecraft-api.com/api/skins/skins.php?player={{User::id($post->userId)->nick}}" style="width:50px" />-->
                  @else
                  <img class="flip" src="https://visage.surgeplay.com/full/150/X-Steve" title="{{User::id($post->userId)->nick}} avatar" />
                  <!--<img src="https://lh3.googleusercontent.com/kcEh6LtwvYN1dUrh1d-ctvtFLbkVdT6ba-8Tr7ePYz6FCmHcuTA5K14Sm1CgEbuKHuqI-gWlifb7XdEKlG2zTw=s400" style="width:50px" />-->
                  @endif
    			     </div>
               <div class="col-md-10 right-col">
                 <button class="quotebtn btn btn-secondary btn-sm pull-right" data-author="{{User::id($post->userId)->nick}}" data-quote="{{$post->content}}">{{trans('forum.Quote')}}</button>
                 @include('public.includes.helpers.parseHTML', ['content' => $post->content])
              </div>
            </div>
            <div class="row">
              <div class="col-md-2 row-border-right">
                <span style="font-size:10px">{{date_format(date_create($post->date), 'd-m-Y H:i');}}</span>
              </div>
              <div class="col-md-10">
              </div>
            </div>

        @endforeach
      </div>
  </div>
</div>


<div id="newreply" class="new-line" style="padding-bottom: 15px;">
    @include('public.pages.forum.parts.newreply')
</div>


@if (!Auth::guest())
  @if (Session::get('message'))
  <div class="alert alert-warning">
    {{ Session::get('message') }}
    {{ HTML::ul($errors->all()) }}
  </div>
  @endif
  @if ($topic->locked && !User::imAdmin())
    <div class="alert alert-info center">{{trans('forum.topicClosed')}}</div><br><br><br>
  @else
    {{ Form::open(array('url' => 'forum/post')) }}
    {{ Form::hidden('topicId', $topic->id) }}
    <textarea id="editor" name="content" class="textarea200" ></textarea>
    <div style="text-align: center;">
    {{ Form::submit(trans('forum.SendReply'), array('class' => 'btn btn-primary', 'style' => 'margin: 10px; font-family: mcsmall;')) }}
    </div>
    {{ Form::close() }}
  @endif
@endif

@include('public.pages.forum.parts.breadcrumbs')
@stop

@section('script')

  @include('public.includes.scripts.editor', ['height' => "800"])
  @include('public.includes.scripts.mcformat')

@stop
