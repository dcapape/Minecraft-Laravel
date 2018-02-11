<!DOCTYPE html>

<html>

	<head>

		<!--META-->

		<title>{{title}}</title>

		<meta name="description" content="<?php echo meta['description']; ?>">

		<meta name="keywords" content="<?php echo meta['keywords']; ?>">

		<meta name="charset" content="<?php echo meta['charset']; ?>">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!--StyleSheets-->

		<link href="assets/css/bootstrap.min.css" rel="stylesheet">

		<link href="assets/css/style.css" rel="stylesheet">

		<link href="assets/css/fonts.css" rel="stylesheet">

		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

		<!--JavaScript-->

		<script src="//code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

		<script src="assets/js/bootstrap.min.js"></script>

	</head>

	<body>

		<div id="snow"><link href="assets/css/snow.css" rel="stylesheet"></div>

		<div class="container">

			<div class="row">

				<div class="col-md-12">

					<div class="bitDown">

						<img src="assets/img/logo.png" class="text-center center-block img-responsive">

					</div>

					<?php include 'core/includes/navbar.php'; ?>

					<?php

					if (config['showNews'] == true)	{

						echo '
						<div class="alert alert-success text-center"><i class="fa fa-star"></i> Latest news: '.config['newsMessage'].'</div>
						';

					}

					?>

					<div class="wrapper">

						<div class="row">

							<div class="col-md-8">


								<div class="panel panel-default">

									<div class="panel-heading">

										<h3 class="panel-title">Welcome to Aggroth</h3>

									</div>

									<div class="panel-body">

										<?php include 'core/includes/tabs.php'; ?>

									</div>

								</div>

								<div class="panel panel-default">

									<div class="panel-heading">

										<h3 class="panel-title">Latest updates</h3>

									</div>

									<div class="panel-body">

										<b>December of 2016</b>

										<ul>

											<ul>

												<li>We've created a discord channel.</li>
												<li>Click <a href="https://discord.gg/sJFYZcP" target="blank">here</a> to instantly join.</li>
												<hr>
												<li>Created a spawn</li>
												<li>Finished the shop with these sections:</li>
												<ul>
													<li>Minerals</li>
													<li>Nether</li>
													<li>The End</li>
													<li>Food</li>
													<li>Armour</li>
													<li>Tools</li>
													<li>Weapons</li>
													<li>Wood</li>
													<li>Building blocks</li>
													<li>Spawners</li>
													<ul>
														<li>Passive</li>
														<li>Aggressive</li>
													</ul>
												</ul>
												<li>Added a world border of 14.000*14.000 blocks</li>
												<li>Created an enchantment place</li>
												<li>Created a basic tutorial for new players</li>
												<li>Added the following plugins:</li>
												<ul>
													<li>WorldBorder</li>
													<li>SignEdit</li>
													<li>GroupManager</li>
													<li>WorldEdit</li>
													<li>Vault</li>
													<li>Multiverse-Core</li>
													<li>ProtocolLIB</li>
													<li>CommandWatcher</li>
													<li>Votifier</li>
													<li>HolographicDisplays</li>
													<li>mcMMO</li>
													<li>GAListener</li>
													<li>Essentials</li>
													<li>EssentialsSpawn</li>
													<li>WhatIsIt</li>
													<li>EssentialsChat</li>
													<li>Factions</li>
													<li>floAuction</li>
												</ul>

											</ul>

										</ul>

										<b>More updates will soon follow</b>

									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="panel panel-default">

									<div class="panel-heading">

										<h3 class="panel-title"><?php echo config['serverIP']; ?></h3>

									</div>

									<div class="panel-body">

										<?php include 'core/includes/status.php'; ?>

									</div>

								</div>

								<div class="panel panel-default">

									<div class="panel-heading">

										<h3 class="panel-title">Discord</h3>

									</div>

									<div class="panel-body">

										<iframe class="center-block" src="https://discordapp.com/widget?id=265577893747359745&theme=light" width="200px;" max-width="200px" height="500" allowtransparency="true" frameborder="0"></iframe>

									</div>

								</div>

							</div>

						</div>

					</div>

					<div class="footer">

						<div class="container-fluid footerText">

							<b><?php echo config['footerText']; ?></b>

						</div>

					</div>

				</div>

			</div>

		</div>

	</body>

</html>
