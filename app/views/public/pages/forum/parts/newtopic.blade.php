<div class="col-md-6">
@if ($category->locked)
  <a class="btn btn-danger  pull-left"
  @if (!User::imAdmin())
    href="#"
  @else
    href="/forum/{{$category->id}}/topic/create"
  @endif
  role="button"><span class="fa fa-lock"></span> {{trans('forum.NewTopic')}}</a>
@else
  <a class="btn btn-primary  pull-left" href="/forum/{{$category->id}}/topic/create" role="button">{{trans('forum.NewTopic')}}</a>
@endif
</div>
