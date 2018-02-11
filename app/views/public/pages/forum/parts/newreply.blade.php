<div class="col-md-6">
@if (!Auth::guest())
  @if ($topic->locked)
    <a class="btn btn-danger pull-left" href="#" role="button"><span class="fa fa-lock"></span> {{trans('forum.NewReply')}}</a>
  @else
    <a class="btn btn-primary pull-left" href="#newreply" role="button">{{trans('forum.NewReply')}}</a>
  @endif
@else
  <a class="btn btn-primary pull-left" href="/login/" role="button">{{trans('forum.NewReply')}}</a>
@endif
</div>
