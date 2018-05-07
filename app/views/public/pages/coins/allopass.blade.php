@extends('public.layouts.default')

@section('title', $item->name .' - '.trans('general.shop'))

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      @if (@$item)
        <h3 class="panel-title">{{$item->name}}</h3>
      @else
        <h3 class="panel-title">{{trans('general.shop')}}</h3>
      @endif
    </div>

    <div class="panel-body cont">
      <div class="col-md-3 left-col shopSidebar">
        <h3>{{trans('shop.SelectYourPaymentMode')}}:</h3>
        {{-- Paypal Prices --}}
        @if (!$item->price == null)
          <div>
            <div class="coin coin-xl left-payment-button" id="left-payment-paypal" data-button="payment-paypal">
              <img src="/assets/img/paypalButton.png"> {{Currency::fromCountryCode($userlocation['isoCode'], $item->price)}}
            </div>
          </div>
          <div>

          </div>
        @else
          {{trans('shop.comingsoon')}}
        @endif

        {{-- Allopass Prices --}}
        @if (isset($item->allopassId))
          <?php
          $allopass = new Allopass;
          $item->allopass = $allopass->getOnetimePrices($item->allopassId);
          ?>
          @foreach ($item->allopass as $payment)
            <div class="coin coin-xl left-payment-button" id="left-payment-{{$payment['type']}}" data-button="payment-{{$payment['type']}}">
              <img src="/assets/img/allopass/{{$payment['type']}}.png"> {{$payment['amount']}} {{$payment['currency']}}
            </div>
          @endforeach
        @endif


      </div>
      <div class="col-md-9 right-col">
        <div class="col-md-8 paymentWindow">
          <h4>{{trans('shop.billingData')}}:</h4>
          @if (Session::has('message'))
              <div class="alert alert-info">{{ Session::get('message') }}</div>
          @endif
          @if ($errors->all())
              <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
          @endif


         <iframe style="width:100%;height:540px;border:0px" src="{{$buyUrl}}">IFRAMES and Javascript must be enabled in your browser</iframe>

        </div>
        <div class="col-md-4 videoItem" style="border: 0px">
          <video class="video-background" no-controls autoplay muted playsinline poster="/assets/img/{{$item->image}}.png" id="ctrlvid-{{$item->id}}">
              <source src="/assets/img/{{$item->image}}.mp4" type="video/mp4" muted>
              <source src="/assets/img/{{$item->image}}.webm" type="video/webm" muted>
              <source src="/assets/img/{{$item->image}}.ogv" type="video/ogv" onerror="fallback(parentNode)" muted>
              {{$item->description}}
          </video>
          <img class="image-background" src="/assets/img/{{$item->image}}.png" alt="{{$item->description}}" title="{{$item->description}}">
        </div>
      </div>

    </div>
  </div>



@stop

@section('script')


    function fallback(video)
    {
      var img = video.querySelector('img');
      if (img)
        video.parentNode.replaceChild(img, video);
    }

    function scaleToFill() {
      $('video.video-background').each(function(index, videoTag) {
         var $video = $(videoTag),
             videoRatio = videoTag.videoWidth / videoTag.videoHeight,
             tagRatio = $video.width() / $video.height(),
             val;

         if (videoRatio < tagRatio) {
             val = tagRatio / videoRatio * 1.02; <!-- size increased by 2% because value is not fine enough and sometimes leaves a couple of white pixels at the edges -->
         } else if (tagRatio < videoRatio) {
             val = videoRatio / tagRatio * 1.02;
         }

         $video.css('transform','scale(' + val  + ',' + val + ')');

      });
  }

  $(function () {
      scaleToFill();

      $('.video-background').on('loadeddata', scaleToFill);

      $(window).resize(function() {
          scaleToFill();
      });
  });


  $( document ).ready(function() {

    $(".coin").on("click", function(){
      @if (Auth::guest())
        var auth = false;
      @else
        var auth = true;
      @endif

      if (auth){
        //alert($(this).data('cost'));
      }else{
        window.location.href = "/login";
      }

    });

    $(".paymentmode").on("click", function(){
      var value = $(this).attr('id');
      $("#cost").val($(this).data('price'));
      $( "div[id^='left']" ).css("background-color", "whitesmoke");
      $("#left-"+value).css("background-color", "#bcdcc1");
    });

    $(".left-payment-button").on("click", function(){
      var value = $(this).data('button');
      $("#"+value).click();
    });

});

@stop
