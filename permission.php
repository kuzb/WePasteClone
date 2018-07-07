<?php
	session_start();
	$myurl = $_SESSION["myurl"];
	ob_start();
	if(isset($_POST['go'])) {
		$password = $_POST['login'];
		$myurl = $_SESSION['myurl'];
		$db = mysqli_connect('localhost', 'root', '', 'wepaste');

		if($db->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
			}

		$sql = "SELECT * FROM clipboards WHERE url ='$myurl'";
		$mysqli_result = mysqli_query($db, $sql);
		$count = mysqli_num_rows($mysqli_result);
		
		$row = mysqli_fetch_assoc($mysqli_result);
		echo $password;
		if(password_verify($password,$row["Password"])){		
			$_SESSION["mytext"] = $row["Text"];
			$_SESSION["lastsave"] = $row["last_save"];
			header("Location: clipboard.php");
			exit;
		}
	}
	ob_end_flush();
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js ie6 oldie" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js ie7 oldie" lang="en"><![endif]-->
<!--[if IE 8]><html class="no-js ie8 oldie" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en" style="text-align:center;"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<base href="http://localhost/wepaste/"></base>
	<title>Furkan Kemikli- WePasteClone</title>
	<meta name="description" content="">
	<meta name="author" content="Furkan Kemikli">
	<meta name="viewport" content="width=device-width"/>
	<link rel="stylesheet" href="min/?g=css">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
	<script src="js/libs/modernizr.js"></script>
	<script src="js/libs/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/js/libs/jquery.min.js"><\/script>')</script>	
	<script>var url='http://localhost/wepaste/';</script>
	<meta name="google-site-verification" content="VhqYcLLQ0P6KjLnPY4FZbtp_3GPYaXkAeglCtamkbpA" />
</head>

<body>
	<header class="main count_header">
		<h1><a href="./"><img src="images/logo2.png"></a></h1>
	</header>

	<div class="notice">
		<b>You don't have permission</b>
		<em>You cannot access this page because you do not have the correct credentials. Fill in the form below if you think otherwise.</em>
	</div>
	<section class="noaccess">
		<header>
			<h1 class="l">http://www.wepaste.com/<?php echo $_SESSION["myurl"] ?>/</h1>
		</header>
		<div class="clearfix"></div>
		<br />		
		<form method="post" name="login">
			<label>Password: </label>
			<input type="password" name="login">
			<input type="submit" name="go" class="button" value="Login" style="padding: 4px 4px ">
		</form>

	</section>	
</body>
</html>