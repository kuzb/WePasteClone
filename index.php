<?php
ob_start();
if(isset($_POST['Submit'])) {
	session_start();
	$myurl = $_POST['myurl'];
	$db = mysqli_connect('localhost', 'root', '', 'wepaste');

	if($db->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

	$sql = "SELECT * FROM clipboards WHERE url ='$myurl'";
	$mysqli_result = mysqli_query($db, $sql);
	$count = mysqli_num_rows($mysqli_result);
	$_SESSION["myurl"] = $myurl;
	
	if($count > 0){
		$row = mysqli_fetch_assoc($mysqli_result);
		$_SESSION["message"] = "You have opened a Note!";
		if($row["HasPassword"] == "true"){
			header("Location: permission.php");
			exit;
		}
		else{			
			$_SESSION["mytext"] = $row["Text"];
			$_SESSION["lastsave"] = $row["last_save"];
			header("Location: clipboard.php");
			exit;
		}
	}
	else{
		$_SESSION["mytext"] = "";
		$_SESSION["message"] = "You have created a Note!";
		$now = date('Y-m-d');
		$lastsave = date('Y-m-d H:i:s');
		$_SESSION["lastsave"] = $lastsave;
		$now = date('Y-m-d', strtotime($now. ' + 60 days'));
		$sql_ins = 'INSERT INTO clipboards (url, dropdate, last_save) VALUES ("' .$myurl. '", "' .$now. '", "' .$lastsave. '")';
		$mysqli_result = mysqli_query($db, $sql_ins);
		if($mysqli_result){
			$sql2 = 'INSERT INTO saves (url, savedate) VALUES ("' .$myurl. '", "' .$lastsave. '")';
			$mysqli_result2 = mysqli_query($db, $sql2);
			header("Location: clipboard.php");
			exit;
		}
	}
}
ob_end_flush();
?>
	<!DOCTYPE html>
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

	
<div class="success">Type in any word after the url to make an new note.</div>
<div style="font-size: 14px;">
	<br />
	<p>Welcome to the <b>Internet Clipboard</b>. This clipboard is clone of WePaste. Here you can very simply create your own page, paste your text, save it and open it anywhere!</p>
	<p>Just try the link below or make your own!</p>
	<div style="padding-top: 6px; padding-right: 4px;" class="l">Or make your own: </div>
	<form action="" method="post">
		<input type="text" name="myurl" id="myurl" placeholder="Type a name here" style="font-size: 15px;" required>
		<button type="submit" name="Submit" class="button add" style="padding-top: 6px; padding-bottom: 6px;">Create page</button>
	</form>
</div>

</body>
</html>