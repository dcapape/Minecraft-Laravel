@extends('public.layouts.default')

@section('title', ' - '.trans('general.shop'))

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{trans('general.shop')}}</h3>
    </div>

    <div class="panel-body">
      <div class="col-md-12">
        @if(@$items)
          @foreach ($items as $item)
            <div class="col-md-4  videoItem" data-href="/coins/{{$item->slug}}" id="vid-{{$item->id}}" data-cost="{{$item->id}}" data-price="{{$item->price}}"  data-name="{{$item->name}}">
                <video class="video-background" no-controls  muted playsinline poster="/assets/img/{{$item->image}}.png" id="ctrlvid-{{$item->id}}">
                    <source src="/assets/img/{{$item->image}}.mp4" type="video/mp4" muted>
                    <source src="/assets/img/{{$item->image}}.webm" type="video/webm" muted>
                    <source src="/assets/img/{{$item->image}}.ogv" type="video/ogv" onerror="fallback(parentNode)" muted>
                    {{$item->description}}
                </video>

                <!--<a href="/coins/{{$item->slug}}"><img src="/img/{{$item->image}}" alt="{{$item->description}}" title="{{$item->description}}"></a>-->
                <h2 class="bg-white">
                  {{$item->name}}
                </h2>

                {{-- Paypal Prices --}}
                @if (!$item->price == null)
                  <div>
                    <div class="coin coin-xl" >
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
                    <div class="coin coin-xl">
                      <img src="/assets/img/allopass/{{$payment['type']}}.png"> {{$payment['amount']}} {{$payment['currency']}}
                    </div>
                  @endforeach
                @endif

                {{--Admin --}}
                @if (User::imAdmin())
                  <a href="/coins/{{$item->id}}/edit">Edit</a>
                @endif
                <img class="image-background" src="/assets/img/{{$item->image}}.png">
            </div>
          @endforeach
        @endif

      </div>
    </div>
  </div>


  @if (!Auth::guest())
  <div class="modal fade" id="resumeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exampleModalLabel">{{$item->name}}</h4>
        </div>
        <iframe id="allopassIframe" class="allopassIframe" style="height: 500px; width: 100%; border: 0px solid transparent;"></iframe>
        <div class="modal-body modalForm">

          <form class="">
            <div class="form-group">
              <label for="recipient-name" class="control-label">Recipient:</label>
              @if (Auth::user()->premium)
              <input type="text" class="form-control" id="recipient-name" value="{{Auth::user()->nick}} (Mojang Premium Secure Account)" disabled>
              @else
              <input type="text" class="form-control" id="recipient-name" value="{{Auth::user()->nick}} (Non-Premium Insecure Account)" disabled>
              @endif
            </div>
            <!--
            <div class="form-group">
              <label class="control-label">Cost:</label>
              <div class="coin coin-xl coin-modal"  id="cost-modal" disabled></div>
            </div>
          -->
            <!-- Real money payment -->
            <div class="form-group">
              <label class="control-label">Payment mode:</label>
              <div class="clearfix"></div>
                <input type="radio" class="paymentmode" name="paymentmode" id="payment-paypal" value="true">
                <label class="radio-inline coin coin-xl radiopayment" for="payment-paypal">
                  @if ($item->price <= 0)
                    {{trans('shop.Free')}}
                  @else
                    <img src="/assets/img/paypalButton.png"> {{$item->price + 0}} EUR
                  @endif
                </label>
              @if (isset($item->allopass))
                @foreach ($item->allopass as $payment)
                  <input type="radio" class="paymentmode" name="paymentmode" id="payment-{{$payment['type']}}" value="true">
                  <label class="radio-inline coin coin-xl radiopayment" for="payment-{{$payment['type']}}">
                    <img src="/assets/img/allopass/{{$payment['type']}}.png"> {{$payment['amount']}} {{$payment['currency']}}
                  </label>
                @endforeach
              @endif
            </div>
            <!-- ---------------- -->
            <div id="personaldata" style="visibility:hidden; position: absolute;">
              <hr class="featurette-divider">
              <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('realname', 'Name') }}
                    {{ Form::text('realname', Input::old('name'), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('surname', 'Surname') }}
                    {{ Form::text('surname', Input::old('surname'), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('email', 'E-mail') }}
                    {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('address', 'Address') }}
                    {{ Form::text('address', Input::old('address'), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('postalcode', 'Postal Code') }}
                    {{ Form::text('postalcode', Input::old('postalcode'), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-4">
                {{ Form::label('country', 'Country') }}
                {{ Form::text('country', @$userlocation["country"], array('class' => 'form-control', 'disabled' => 'disabled')) }}
              </div>
              <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('city', 'City') }}
                    {{ Form::text('city', Input::old('city'), array('class' => 'form-control')) }}
                </div>
              </div>
            </div>

            <hr class="featurette-divider">
            <div class="form-group">
              <label for="agreement-checkbox" style="display: block;" class="control-label">Terms and Conditions:</label>
              <input type="checkbox"  id="agreement-checkbox" value="true">
              <label for="agreement-checkbox" class="checkbox-inline">I agree the General Terms and Conditions. <a href="/general-terms-and-conditions" target="_blank">Read more</a></label>
            </div>
            @if (Auth::user()->premium)
              <input type="hidden"  id="nonpremium-checkbox" value="true">
            @else
            <div class="form-group">
              <input type="checkbox"  id="nonpremium-checkbox" value="true">
              <label for="nonpremium-checkbox" class="checkbox-inline">I understand the risks using an unofficial Mojang Account. <a href="/risks-agreement-unofficial-accounts" target="_blank">Read more</a></label>
            </div>
            @endif
          </form>
        </div>
        <div class="modal-footer modalForm">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('shop.Close')}}</button>
          <button type="button" class="btn btn-primary">{{trans('shop.Buy')}}</button>
        </div>
      </div>
    </div>
  </div>

  @endif

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


$(document).ready(function() {

    var lastVid = "vid-1";
    $(".videoItem").hover( function(e){
      console.log(e.target.id);
      lastVid = e.target.id;
    });
    $(".videoItem").hover( hoverVideo, hideVideo );

    function hoverVideo(e) {
      try {
      e.target.play();
      }
      catch(error) {
      //console.error(error);
      console.log("--EMERGENCY SYSTEM--");
      console.log(e);
      console.log(lastVid);
      setTimeout(function(){ $('#ctrl'+lastVid).trigger('play') }, 200);
      }

    }

    function hideVideo(e) {
      /*e.target.pause();
      e.target.currentTime = 0;
      v=e.target.currentSrc;
      e.target.src='';
      e.target.src=v;*/
    }


  $(".videoItem, .shopCategory").click(function() {
    window.location.href = $(this).data('href');
  });
});

$( document ).ready(function() {
  $(".coin, .videoItem").on("click", function(){
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


  $('#resumeModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var server = button.data('server'); // Extract info from data-* attributes
    var coin   = button.data('coin');
    var price   = button.data('price');
    var url   = button.data('url');

    if (url != null){
      $('.allopassIframe').attr('src',url).show();
      $('.modalForm').hide();
    }else{
      $('.allopassIframe').hide();
      $('.modalForm').show();
    }

    var modal = $(this);
    //modal.find('.modal-title').text('');
    modal.find('.modal-body #server-name').val(server);

    // Modal cost
    $('#cost-modal').removeClass('premium-xl').removeClass('real-xl').removeClass('standard-xl').addClass(coin+'-xl');
    modal.find('#cost-modal').html(" "+parseFloat(price));
  })
});


@stop
