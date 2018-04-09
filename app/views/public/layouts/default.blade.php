<!DOCTYPE html>
<html>
	<head>
    @include('public.includes.head')
	</head>

	<body>

		<!--div id="snow"><link href="/assets/css/snow.css" rel="stylesheet"></div-->

		<div class="container">
			<div class="row">
				<div class="col-md-12">

						<div class="row floating-accountbar">

              <div class="col-md-12">
								<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
									@if (Auth::guest())
										<ul class="nav navbar-nav head-accountbar pull-right">
		                  <li><a href="{{ route('register') }}"><i class="fa fa-home"></i> {{trans('general.register');}}</a></li>
		                  <li><a href="{{ route('login') }}"><i class="fa fa-comments"></i> {{trans('general.login');}}</a></li>
		                </ul>
									@else
										<ul class="nav navbar-nav head-accountbar pull-right">
											<li><a href="{{ route('profile') }}"><div class="premium" style="background-position: 3px 2px;">{{ Coin::getBalance(null,"premium")+0 }}</div></a></li>
											<li><a href="{{ route('profile') }}"><div class="standard" style="background-position: 3px 2px;">{{ Coin::getBalance()+0 }}</div></a></li>
											<li class="dropdown">
							          <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->nick }}<b class="caret"></b></a>
							          <ul class="dropdown-menu pull-right">
													<li><a href="{{ route('profile') }}"><i class="fa fa-user"></i> {{trans('general.profile');}}</a>
														<li class="divider"></li>
							            <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> {{trans('general.logout');}}</a>
							          </ul>
							        </li>
										</ul>
									@endif
								</div>
              </div>
            </div>


					<div class="bitDown">
						<img src="/assets/img/minegamers.png" class="text-center center-block img-responsive">
					</div>

					@include('public.includes.navbar')

					<?php
					if (false)	{
						echo '
						<div class="alert alert-success text-center"><i class="fa fa-star"></i> Latest news: </div>
						';
					}
					?>

					<div class="wrapper wrapperbg">
						<div class="row">
							@if(@$sidebar)
								<div class="col-md-8">
									@yield('content')
								</div>
								<div class="col-md-4">
									@yield('sidebar')
								</div>
							@else
								<div class="col-md-12">
									@yield('content')
								</div>
							@endif
						</div>
					</div>

					@include('public.includes.footer')

				</div>
			</div>
		</div>
		<script>
		@yield('script')
		</script>
	</body>
</html>
