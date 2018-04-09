<nav class="navbar navbar-default">

  <div class="container-fluid">

    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">{{trans('general.toogle_navigation');}}</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      @if (Auth::guest())
        <ul class="nav navbar-nav visible-xs" style="display: inline-flex; margin: 0px">
          <li style="display: inline-flex; padding: 9px;"><a href="{{ route('register') }}"><i class="fa fa-home"></i> {{trans('general.register');}}</a></li>
          <li style="display: inline-flex; padding: 9px;"><a href="{{ route('login') }}"><i class="fa fa-comments"></i> {{trans('general.login');}}</a></li>
        </ul>
      @else
        <ul class="nav navbar-nav visible-xs" style="margin: 0px">
          <li style="float: left;padding: 9px;"><a href="{{ route('profile') }}"><div class="premium" style="background-position: 3px 2px;">{{ Coin::getBalance(null,"premium")+0 }}</div></a></li>
          <li style="float: left;padding: 9px;"><a href="{{ route('profile') }}"><div class="standard" style="background-position: 3px 2px;">{{ Coin::getBalance()+0 }}</div></a></li>
          <li style="float: left;padding: 9px;" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->nick }}<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i> {{trans('general.profile');}}</a>
                    <li class="divider"></li>
                  <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> {{trans('general.logout');}}</a>
                </ul>
          </li>
        </ul>
      @endif
      <!--a class="navbar-brand" href="/"><i class="fa fa-leaf"></i> How to play</a-->

    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">

        <li><a href="/"><i class="fa fa-home"></i> {{trans('general.news');}}</a></li>
        <!--<li><a href="/"><i class="fa fa-dollar"></i> Servers</a></li>-->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-map"></i> {{trans('general.maps');}}<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/maps/survival4"><i class="fa fa-map"></i> Survival 4</a>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bar-chart"></i> {{trans('general.stats');}}<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/stats/"><i class="fa fa-bar-chart"></i> {{trans('general.global_stats');}}</a>
            <li class="divider"></li>
            <li><a href="/stats/survival4"><i class="fa fa-bar-chart"></i> Survival 4</a>
          </ul>
        </li>
        <li><a href="/forum/"><i class="fa fa-comments"></i> {{trans('general.forums');}}</a></li>
        <li><a href="/shop/"><i class="fa fa-diamond"></i> {{trans('general.shop');}}</a></li>

      </ul>

      <ul class="nav navbar-nav navbar-right">

        <li><a href="#">{{trans('general.mcurl');}}</a></li>

      </ul>

    </div>

  </div>

</nav>
