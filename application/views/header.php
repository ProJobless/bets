<!DOCTYPE html>
<html lang="en">
<head>
		<base href="<?php echo base_url();?>"> 
    <meta charset="utf-8">
    <title>Make Some Bets</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
	  <link href="assets/css/DT_bootstrap.css" rel="stylesheet">
	  <link href="assets/css/jquery-ui-1.8.16.custom.css" rel="stylesheet">
	<link href="assets/css/jquery.ui.1.8.16.ie.css" rel="stylesheet">
    <link href="assets/css/bootstrap.css" rel="stylesheet">
     <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
	 <link href="assets/css/bets.css" rel="stylesheet">
	 
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
   

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="/assets/js/jquery.js"></script>
    <script src="assets/js/jquery-ui-1.10.1.custom.js"></script>
    <script src="assets/js/bootstrap.js"></script>
		
		<script src="assets/js/jquery.dataTables.js"></script>
		<script src="assets/js/DT_bootstrap.js"></script>
		<script type="text/javascript" src="/assets/js/noty/jquery.noty.js"></script>
	
		<script type="text/javascript" src="assets/js/noty/layouts/top.js"></script>
		<script type="text/javascript" src="assets/js/noty/layouts/topLeft.js"></script>
		<script type="text/javascript" src="assets/js/noty/layouts/topRight.js"></script>
		<script src="assets/js/dt_filtering.js"></script>
		<script src="assets/js/bets.js"></script>
	<!-- You can add more layouts if you want -->

<script type="text/javascript" src="assets/js/noty/themes/default.js"></script>

   
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
  <style type="text/css"></style></head>
  <body>
  <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
       <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
         <?php if($content == 'landing'): ?>
          <a class="brand" href="home">Bets</a>
          <?php else: ?>
          <a href="#newBet" class="btn btn-small" id="new-bet" data-toggle="modal">New Bet</a>
          
          <?php endif; ?>
          <div class="nav-collapse collapse">
             <?php if($content != 'landing'): ?>
            <ul class="nav">
              <li <?php if($content == 'user_home'): ?> class="active" <?php endif; ?>><a href="home">My Bets</a></li>
              <li <?php if($content == 'browse_open_bets'): ?> class="active" <?php endif; ?>><a href="browse">Browse</a></li>
            </ul>
           
            <ul class="nav pull-right">
	            <li><a href="#profile">Profile</a></li>
	            <li><a href="user\logout">Logout</a></li>
            </ul>
            <?php endif; ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
  </div>
	<div class="container">
			<?php $this->load->view('newBet'); ?>
		
	 		