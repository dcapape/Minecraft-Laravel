@extends('public.layouts.default')

@section('title', ' - '.trans('general.shop'))

@section('content')

  @if (User::imAdmin())
    <div class="new-line" style="padding-bottom: 15px;">
      <div class="col-md-12">
        <div class="col-md-2"><a class="btn btn-primary pull-left" href="/{{LaravelLocalization::getCurrentLocale()}}/shop/create" role="button">New Category</a></div>
        <div class="col-md-2"><a class="btn btn-primary pull-left" href="/{{LaravelLocalization::getCurrentLocale()}}/shop/item/create" role="button">New Item</a></div>
      </div>
    </div>
  @endif

  <div class="panel panel-default">
    <div class="panel-heading">
      @if (@$category)
        <h3 class="panel-title">{{$category->name}}</h3>
      @else
        <h3 class="panel-title">{{trans('general.shop')}}</h3>
      @endif
    </div>

    <div class="panel-body">
      <div class="col-md-4">
        <div class="grid">
            @foreach ($categories as $category)
                <div class="row shopCategory" data-href="/shop/{{$category->slug}}">
                  <a href="/shop/{{$category->slug}}"><img src="/img/{{$category->image}}" alt="{{$category->description}}" title="{{$category->description}}"></a>
                  <a href="/shop/{{$category->slug}}">{{$category->name}}</a>
                  @if (User::imAdmin())
                    <a href="/shop/{{$category->id}}/edit">Edit</a>
                  @endif
                </div>
            @endforeach
        </div>
      </div>
      <div class="col-md-8">
        @if(@$items)
          @foreach ($items as $item)
            <div class="col-md-4 shopItem" data-href="/shop/item/{{$item->slug}}">
                <a href="/shop/item/{{$item->slug}}"><img src="/img/{{$item->image}}" alt="{{$item->description}}" title="{{$item->description}}"></a>
              <div>
                {{$item->name}}
              </div>
{{--dd($item->servers);--}}
              @if (!$item->costs->isEmpty())
                <div>
                  @foreach ($item->servers as $server)
                  <div class="shopServer">
                    {{ $server }}
                  </div>
                    @foreach ($item->costs as $cost)
                      @if ($cost->serverId == array_search($server, $item->servers))
                        <div class="coin {{$cost->coin}}">
                          {{$cost->price + 0}}
                        </div>
                      @endif
                    @endforeach
                  @endforeach
                </div>
                <div>

                </div>
              @else
                {{trans('shop.comingsoon')}}
              @endif
              @if (User::imAdmin())
                <a href="/shop/item/{{$item->id}}/edit">Edit</a>
              @endif
            </div>
          @endforeach
        @endif

      </div>
    </div>
  </div>


@stop

@section('script')
$(document).ready(function() {
  $(".shopItem, .shopCategory").click(function() {
    window.location.href = $(this).data('href');
  });
});
@stop
