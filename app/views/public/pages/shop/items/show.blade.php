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
      <div class="col-md-9 left-col">
        <div class="col-md-12">
          @if (Session::get('message'))
          <div class="alert alert-warning">
            {{ Session::get('message') }}
            {{ HTML::ul($errors->all()) }}
          </div>
          @endif
        </div>
        <div class="col-md-4">
          <img src="/img/{{$item->image}}" alt="{{$item->description}}" title="{{$item->description}}">
        </div>
        <div class="col-md-8">
          <h3>{{$item->name}}</h3>
          <h5>{{trans('shop.Description')}}:</h5>
          @include('public.includes.helpers.parseHTML', ['content' => $item->description])

          @if (User::imAdmin())
            <a href="/shop/item/{{$item->id}}/edit">Edit</a>
          @endif
        </div>
      </div>
      <div class="col-md-3 right-col shopSidebar">

       @if (!$item->costs->isEmpty())
        <div>
         @foreach ($item->servers as $server)
           <?php $server->name = trans('general.InvalidServer'); ?>
           @if ($server->serverId == 0)
             <h4>{{trans('shop.getForYourAccount')}}</h4>
             <?php $server->name = trans('general.GlobalAccount'); ?>
           @else
             <?php $server->name = Server::find($server->serverId)->name; ?>
             <h4>{{trans('shop.getForServer', ['servername' => $server->name ] ) }}</h4>
           @endif
           @foreach ($item->costs as $cost)
             @if ($cost->serverId == $server->serverId)

             <div class="coin coin-xl {{$cost->coin}}-xl click2pay" data-server="{{$server->name}}" data-serverId="{{$server->serverId}}" data-cost="{{$cost->id}}" data-price="{{$cost->price}}" data-coin="{{$cost->coin}}" data-name="{{$item->name}}" >
               @if ($cost->price <= 0)
                 {{trans('shop.Free')}}
               @else
                 {{$cost->price + 0}}
               @endif
             </div>

             @endif
           @endforeach
         @endforeach

        </div>
        @else
        {{trans('shop.NotAvailable')}}
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
        <div class="modal-body modalForm">

          <!--form class=""-->
          {{ Form::open(array('route' => array('shop.buy', $item->id))) }}
            <input type="hidden" name="serverId" id="serverId" value=""></input>
            <input type="hidden" name="coin" id="coin" value=""></input>
            <input type="hidden" name="price" id="price" value=""></input>
            <input type="hidden" name="itemId" id="itemId" value="{{$item->id}}"></input>

            <div class="form-group">
              <label for="recipient-name" class="control-label">Recipient:</label>
              @if (Auth::user()->premium)
              <input type="text" class="form-control" id="recipient-name" value="{{Auth::user()->nick}} (Mojang Premium Secure Account)" disabled>
              @else
              <input type="text" class="form-control" id="recipient-name" value="{{Auth::user()->nick}} (Non-Premium Insecure Account)" disabled>
              @endif
            </div>
            <div class="form-group">
              <label for="server-name" class="control-label">Server:</label>
              <input type="text" class="form-control" id="server-name" value="" disabled>
            </div>

            <div class="form-group">
              <label class="control-label">Cost:</label>
              <input type="text" class="form-control coin" id="cost-modal" disabled value=""></input>
            </div>

            <hr class="featurette-divider">
            <div class="form-group">
              <label for="agreement-checkbox" style="display: block;" class="control-label">Terms and Conditions:</label>
              <input type="checkbox"  id="agreement-checkbox" name="agreement" value="true">
              <label for="agreement-checkbox" class="checkbox-inline">I agree the General Terms and Conditions. <a href="/general-terms-and-conditions" target="_blank">Read more</a></label>
            </div>
            @if (Auth::user()->premium)
              <input type="hidden"  id="nonpremium-checkbox" name="nonpremiumagreement" value="true">
            @else
            <div class="form-group">
              <input type="checkbox"  id="nonpremium-checkbox" name="nonpremiumagreement" value="true">
              <label for="nonpremium-checkbox" class="checkbox-inline">I understand the risks using an unofficial Mojang Account. <a href="/risks-agreement-unofficial-accounts" target="_blank">{{trans('shop.readmore')}}</a></label>
            </div>
            @endif

        </div>
        <div class="modal-footer modalForm">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('shop.Close')}}</button>
          <button type="submit" class="btn btn-primary">{{trans('shop.Buy')}}</button>
        </div>
          </form>
      </div>
    </div>
  </div>

  @endif
@stop

@section('script')

$( document ).ready(function() {
  $(".click2pay").on("click", function(){
    var redir = false;
    if ($(this).data('coin') == 'premium'){
      @if (Auth::guest())
        redir = true;
      @else
        var premium = {{Coin::getBalance(null,"premium")+0}};
        if (premium >= $(this).data('price')){
            redir = false;
        }else{
            redir = true;
        }
      @endif
    }else{
      @if (Auth::guest())
        window.location.href = "/login";
      @else
        var standard = {{Coin::getBalance(null,"standard")+0}};
        if (standard >= $(this).data('price')){
            // modal
        }else{
            alert("No suficiente");
        }
      @endif
    }
      if (redir){
        window.location.href = "/coins/?cost="+$(this).data('price');
      }else{
        $('#resumeModal').modal('show');
        var serverId = $(this).data('serverid');
        var server = $(this).data('server');
        var coin   = $(this).data('coin');
        var price  = $(this).data('price');
        $('#server-name').val(server);
        $('#coin').val(coin);
        $('#serverId').val(serverId);
        $('#price').val(parseFloat(price));

        $('#cost-modal').removeClass('premium-xl').removeClass('real-xl').removeClass('standard-xl').addClass(coin+'-xl');
        $('#cost-modal').val(parseFloat(price));
      }

  });

});

@stop
