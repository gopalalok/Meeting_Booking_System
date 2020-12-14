<?php
	$filepath = realpath(dirname(__FILE__));
	include_once($filepath.'/lib/Session.php');
	
	Session::checkLogin();
	
?>

<html>
<head>
	<meta charset="utf-8">
	<title> Meeting Booking System </title>
	<link rel="stylesheet" href="inc/bootstrap.min.css" media="screen" title="no-title" />
	<script type="text/javascript" src="inc/jquery.min.js"></script>
	<script type="text/javascript" src="inc/bootstrap.min.js"></script>
</head>
<style type="text/css">
	body
	{
  		background-image: url("image/Simple-Background-Images-25.png");
	}
</style>
<body>
	<div class="container">
		<div class="well text-center">
			<img src="Image/icons/banner2.jpg" height="200px" width="100%">
			<h2>Meeting Booking System</h2>
		</div>
<style type="text/css">
	h3{
		height: 20px;
		width: 500px;
		margin-left: 300px;
		margin-top: 30px;

	}

</style>
		<div class="panel panel-default">
			<div class="panel-heading">
		
			</div>

			<div class="panel-body" style="background-image: url('Image/icons/2.jpg');background-repeat: repeat-x;">
				
				<h3>
					<a class="btn btn-success" href="UserRegister.php">User Registration</a>
					<a class="btn btn-info pull-right" href="UserLogin.php">User Login</a>
				</h3>
				
			</div>
		</div>

<?php include "inc/footer.php"; ?>