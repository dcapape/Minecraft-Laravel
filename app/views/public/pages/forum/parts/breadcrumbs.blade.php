<div class="panel panel-default">
  <a href="/forum">{{trans('general.forums')}}</a>
  @if (@$category)
  >> <a href="/forum/{{$category->id}}">{{$category->name}}</a>
  @endif
  @if (@$topic)
  >> <a href="/forum/{{$topic->id}}">{{$topic->subject}}</a>
  @endif
</div>
