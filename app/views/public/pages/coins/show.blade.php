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

          @if (Session::has('message'))
              <div class="alert alert-info">{{ Session::get('message') }}</div>
          @endif
          @if ($errors->all())
              <div class="alert alert-info">{{ HTML::ul($errors->all()) }}</div>
          @endif


         {{ Form::open(array('route' => array('coins.data', $slug))) }}

            <input type="hidden" name="itemId" value="{{$item->id}}">

            <div class="form-group">
              <label for="recipient-name" class="control-label">{{trans('shop.Recipient')}}:</label>
              @if (Auth::user()->premium)
              <input type="text" class="form-control" id="recipient-name" value="{{Auth::user()->nick}} ({{trans('shop.mojangpremium')}})" disabled>
              @else
              <input type="text" class="form-control" id="recipient-name" value="{{Auth::user()->nick}} ({{trans('shop.nonpremium')}})" disabled>
              @endif
            </div>
            <div class="form-group">
              <label for="server-name" class="control-label">{{trans('shop.Server')}}:</label>
              <input type="text" class="form-control" id="server-name" value="{{trans('shop.globalaccount')}}" disabled>
            </div>

            <div class="form-group">
              <label class="control-label">{{trans('shop.Cost')}}:</label>
              <input type="text" class="form-control" id="cost" value="{{Currency::fromCountryCode($userlocation['isoCode'], $item->price)}}" disabled>
            </div>

            @if (!$item->price == null)
              <input type="radio" class="paymentmode" name="paymentmode" id="payment-paypal" data-price="{{Currency::fromCountryCode($userlocation['isoCode'], $item->price)}}" value="payment-paypal">
              <label class="radio-inline coin coin-xl radiopayment" for="payment-paypal">
                  <img src="/assets/img/paypalButton.png">
              </label>
            @endif
            @if (isset($item->allopass))
              @foreach ($item->allopass as $payment)
                <input type="radio" class="paymentmode" name="paymentmode" id="payment-{{$payment['type']}}" data-price="{{$payment['amount']}} {{$payment['currency']}}" value="payment-{{$payment['type']}}">
                <label class="radio-inline coin coin-xl radiopayment" for="payment-{{$payment['type']}}">
                  <img src="/assets/img/allopass/{{$payment['type']}}.png">
                </label>
              @endforeach
            @endif


            <hr class="featurette-divider">
            <div class="form-group">
              <label for="agreement-checkbox" style="display: block;" class="control-label">{{trans('shop.terms')}}:</label>
              <input type="checkbox"  id="agreement-checkbox" name="agreement" value="true">
              <label for="agreement-checkbox" class="checkbox-inline" >{{trans('shop.agreement')}} <a href="/general-terms-and-conditions" target="_blank">{{trans('shop.readmore')}}</a></label>
            </div>
            @if (Auth::user()->premium)
              <input type="hidden"  id="nonpremium-checkbox" name="nonpremiumagreement" value="true">
            @else
            <div class="form-group">
              <input type="checkbox"  id="nonpremium-checkbox" name="nonpremiumagreement" value="true">
              <label for="nonpremium-checkbox" class="checkbox-inline">I understand the risks using an unofficial Mojang Account. <a href="/risks-agreement-unofficial-accounts" target="_blank">{{trans('shop.readmore')}}</a></label>
            </div>
            @endif

            <input type="submit" class="btn btn-primary pull-right" role="button" value="{{trans('shop.continue')}}">
          </form>


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

    $("#payment-paypal").click();

});

@stop
