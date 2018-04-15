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


         {{-- Form::open(array('route' => 'coins.update', 0, 'method' => 'PUT')) --}}
         {{-- Form::model($item, array('route' => array('coins.update', $item->rewardQty+0), 'method' => 'PUT')) --}}
         {{ Form::open(array('route' => array('coins.buy', $slug))) }}

            <input type="hidden" name="itemId" value="{{$item->id}}">
            <input type="hidden" name="nonpremiumagreement" value="{{$nonpremiumagreement}}">
            <input type="hidden" name="agreement" value="{{$agreement}}">
            <input type="hidden" name="paymentmode" value="{{$paymentmode}}">

            <div id="personaldata" style="">
              <hr class="featurette-divider">
              <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('realname', trans('shop.name')) }}
                    {{ Form::text('realname', Input::old('realname', Auth::user()->realname), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('surname', trans('shop.surname')) }}
                    {{ Form::text('surname', Input::old('surname', Auth::user()->surname), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('email', trans('shop.email')) }}
                    {{ Form::text('email', Input::old('email', Auth::user()->email), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('address', trans('shop.address')) }}
                    {{ Form::text('address', Input::old('address', Auth::user()->address), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('postalcode', trans('shop.postalcode')) }}
                    {{ Form::text('postalcode', Input::old('postalcode', Auth::user()->postalcode), array('class' => 'form-control')) }}
                </div>
              </div>
              <div class="col-md-4">
                {{ Form::label('country', trans('shop.country')) }}
                {{ Form::text('country', @$userlocation["country"], array('class' => 'form-control', 'disabled' => 'disabled')) }}
              </div>
              <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('city', trans('shop.city')) }}
                    {{ Form::text('city', Input::old('city', Auth::user()->city), array('class' => 'form-control')) }}
                </div>
              </div>
            </div>


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

    $("#payment-paypal").click();
});

@stop
