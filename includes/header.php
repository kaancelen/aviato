<?php
	require_once 'core/init.php';
	$user = new User();//get user object
?>
<html>
	<head>
		<meta charset="utf-8" />
		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Aviato</title>
		<meta name="description" content="" />
		<meta name="author" content="Kaan Çelen" />
		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<!-- <link rel="stylesheet" type="text/css" href="css/main.css"> -->
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript .js import order is important-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</head>
	<body style="padding-top:70px;background:url('images/bg.png') repeat;">
	<?php include('includes/navigationBar.php'); ?>