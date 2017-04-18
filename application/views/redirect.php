<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>DragonFrame</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Page description">
<meta name="keywords" content="Page keywords">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
<style>
	body, html { height: 100%; margin:0; padding:0; background: #000;}
	.container{ width: 100%; display: block; text-align:center; height:100vh; display: -ms-flexbox; display: -webkit-flex; display: flex; -ms-flex-align: center;	-webkit-align-items: center; -webkit-box-align: center;	align-items: center; }
	.item{ max-width: 60%; width:100%; margin: 0 auto; }
	.logo-wrap{ width: 200px; height: auto; display: inline-block; }
	.logo-wrap img{ max-width: 100%; height: auto; margin-bottom: 20px;}
	.message{ color:#fff; font-family: 'Open Sans', sans-serif; font-size: 18px; font-weight: 300; }
	.message p{ margin:0; line-height: 30px; }
</style>
</head>
<body>
	<div class="container">
		<div class="item">
			<div class="logo-wrap">
				<img src="<?php echo APP_URL?>img/logo.png" alt="">
			</div>
			<div class="message">
				<p>You have successfully registered</p>
				<p>Enjoy using Dragon Frame!</p>
			</div>
		</div>
	</div>
</body>
</html>
