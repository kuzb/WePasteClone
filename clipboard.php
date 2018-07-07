<?php
	session_start();
	$myurl = $_SESSION["myurl"];
	$mytext = $_SESSION["mytext"];
	$db = mysqli_connect('localhost', 'root', '', 'wepaste');
	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$sqlSaves = "SELECT * FROM saves WHERE url ='$myurl' ORDER BY SaveDate DESC LIMIT 10";
	$mysqli_saves = mysqli_query($db, $sqlSaves);
	ob_start();

	if(isset($_POST['save'])) {
		$db = mysqli_connect('localhost', 'root', '', 'wepaste');
		
		if($db->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
		}
		$expires = $_POST['expires'];		
		$now = date('Y-m-d');		
		$lastsave = date('Y-m-d H:i:s');
		$_SESSION["lastsave"] = $lastsave;
		$now = date('Y-m-d', strtotime($now. ' + '.$expires.' days'));
		
		$content = $_POST['content'];
		
		$sql = "UPDATE clipboards SET text = '".$content."', last_save= '".$lastsave."', dropdate = '".$now."' WHERE url ='".$myurl."'";
		$mysqli_result = mysqli_query($db, $sql);
		
		$sqlDel = "DELETE FROM saves WHERE url ='".$myurl."'";
		$mysqli_resultDel = mysqli_query($db, $sqlDel);	
		
		$sql2 = 'INSERT INTO saves (url, savedate, text) VALUES ("' .$myurl. '", "' .$lastsave. '", "' .$content. '")';
		$mysqli_result2 = mysqli_query($db, $sql2);
		
		$_SESSION["mytext"] = $content;
		header('Location: clipboard.php');
	}

	if(isset($_POST['set-password'])) {
		$db = mysqli_connect('localhost', 'root', '', 'wepaste');
		
		$password = $_POST['password_set'];
		$password2 = $_POST['password_set2'];
		
		$expires = 60;
		$now = date('Y-m-d');		
		$lastsave = date('Y-m-d H:i:s');
		$_SESSION["lastsave"] = $lastsave;
		$now = date('Y-m-d', strtotime($now. ' + '.$expires.' days'));
		
		if($db->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
		}

		if($password == $password2){				
			$options = [
				'cost' => 11,
			];
			$hash = password_hash($password, PASSWORD_BCRYPT, $options);
			$sql = "UPDATE clipboards SET haspassword = 'true', password = '".$hash."', last_save= '".$lastsave."', dropdate = '".$now."' WHERE url ='".$myurl."'";
			$mysqli_result = mysqli_query($db, $sql);
		}
	}
	if(isset($_POST['emailandsave']))
	{
		$email = $_POST['email'];
		$content = $_POST['contentModal'];
		$db = mysqli_connect('localhost', 'root', '', 'wepaste');

		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($content,70);
		
		$headers = 'From: furkankemikli@sabanciuniv.edu' . "\r\n" .
					'Reply-To: furkankemikli@sabanciuniv' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
				
		//to send email it requires smtp server
		//mail($email,"We Paste",$msg, $headers);

		if($db->connect_errno > 0){
				die('Unable to connect to database [' . $db->connect_error . ']');
		}

		if($email != ""){
			$_SESSION["mytext"] = $content;
			$sql = "UPDATE clipboards SET text = '".$content."' WHERE url ='".$myurl."'";
			$mysqli_result = mysqli_query($db, $sql);		
			header('Location: clipboard.php');
		}
	}
	if(isset($_POST['formhistory'])){
		header('Location: history.php');
		
	}
	ob_end_flush();
?>
<!doctype html>
	<html class="no-js" lang="en" style="text-align:center;">
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
	<style>
		body {font-family: Arial, Helvetica, sans-serif;}

		/* Full-width input fields */
		input[type=text], input[type=password] {
			width: 100%;
			padding: 12px 20px;
			margin: 8px 0;
			display: inline-block;
			border: 1px solid #ccc;
			box-sizing: border-box;
		}

		/* Set a style for all buttons */
		button {
			background-color: #4CAF50;
			color: white;
			padding: 14px 20px;
			margin: 8px 0;
			border: none;
			cursor: pointer;
			width: 100%;
		}

		button:hover {
			opacity: 0.8;
		}

		/* Extra styles for the cancel button */
		.cancelbtn {
			width: auto;
			padding: 10px 18px;
			background-color: #f44336;
		}

		/* Center the image and position the close button */
		.imgcontainer {
			text-align: center;
			margin: 24px 0 12px 0;
			position: relative;
		}

		img.avatar {
			width: 40%;
			border-radius: 50%;
		}

		.container {
			padding: 16px;
		}

		span.psw {
			float: right;
			padding-top: 16px;
		}

		/* The Modal (background) */
		.modal {
			display: none; /* Hidden by default */
			position: fixed; /* Stay in place */
			z-index: 1; /* Sit on top */
			left: 0;
			top: 0;
			width: 50%; /* Full width */
			height: 100%; /* Full height */
			overflow: auto; /* Enable scroll if needed */
			padding-top: 90px;
			padding-left:400px;
		}

		/* Modal Content/Box */
		.modal-content {
			background-color: #fefefe;
			margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
			border: 1px solid #888;
			width: 80%; /* Could be more or less, depending on screen size */
		}

		/* The Close Button (x) */
		.close {
			position: absolute;
			right: 25px;
			top: 0;
			color: #000;
			font-size: 35px;
			font-weight: bold;
		}

		.close:hover,
		.close:focus {
			color: red;
			cursor: pointer;
		}

		/* Add Zoom Animation */
		.animate {
			-webkit-animation: animatezoom 0.6s;
			animation: animatezoom 0.6s
		}

		@-webkit-keyframes animatezoom {
			from {-webkit-transform: scale(0)} 
			to {-webkit-transform: scale(1)}
		}
			
		@keyframes animatezoom {
			from {transform: scale(0)} 
			to {transform: scale(1)}
		}

		/* Change styles for span and cancel button on extra small screens */
		@media screen and (max-width: 300px) {
			span.psw {
			   display: block;
			   float: none;
			}
			.cancelbtn {
			   width: 100%;
			}
		}
	</style>
		
		</head>

	<body>

		<header class="main count_header">
			<h1><a href="./"><img src="images/logo2.png"></a></h1>
		</header>

		<div class="success">
			<b><?php echo $_SESSION["message"] ?></b>
			<em><small>Paste in any text you want into the textarea and click save. Then on any other computer or mobile device enter http://localhost/wepaste/<?php echo $myurl ?>/</small></em>
		</div>
		<section class="note" data-role="content" data-theme="c">
			<header>
				<h1 class="l">http://localhost/wepaste/<?php echo $_SESSION["myurl"] ?>/</h1>
				<div class="r" style="padding-top: 10px;" id="last_saved">
					Last saved: <?php echo $_SESSION["lastsave"] ?>
				</div>
				<div class="clearfix"></div>
			</header>
			<div class="clearfix"></div>
			<form method="post" action="" name="note">
				<div class="expires row l" style="margin:0;">
					<label class="expires">This note will expire in: </label>
					<input type="number" name="expires" value="60" class="expires"> days

				</div>

				<input type="submit" name="save" class="button" method="post" value="Save it" style="margin-right:0;">
				<input type="button" id="password" name="password" class="button" value="Set a password" style="margin-right:10px;">
				
				<div class="clearfix" style="margin-bottom:4px"></div>
				<div id="show_content" class="content" style="display:none;"></div>

				<textarea name="content" id="note" value="" style="width:1000px; height:400px; margin:10px 10px" ><?php echo $mytext?></textarea>
				
				<script>var name = '<?php echo $myurl ?>';</script>
			</form>
		</section>
		<small><input type="button" href="" id="save-email" name="save-email" class="button" value="Save and e-mail it"></input></small>
		<small><input type="button" onclick="showHistory()" value="Watch history"></input></small>
		
		<div id="history" style="display:none;">
			<form name="formhistory" action="" method="post">
				<?php while($save = mysqli_fetch_assoc($mysqli_saves)){
					echo '<button class="button" type="submit" name="formhistory" style="background: rgba(54, 25, 25, .1); color:black;">' .$save["SaveDate"]. '</button>';
				} ?>
			</form>
		</div>
		
		<div id="dialog-form" class="modal">
			<form name="emailandsave" class="modal-content animate" action="" method="post">
				<span onclick="document.getElementById('dialog-form').style.display='none'" class="close" title="Close Modal">&times;</span>
				<h2 style="margin: 3px 0 0 0;">Save and e-mail</h2>
				<div class="container">		
					<label for="email"><b>Email Address</b></label>
					<input type="text" name="email" id="email" value="" placeholder="Enter email address" required />
					<input name="contentModal" id="noteModal" value="" style="width:1000px; height:400px; margin:10px 10px" hidden></input>
					<button type="submit" name="emailandsave">Save & Send Email</button>
					<div class="container" style="background-color:#f1f1f1">
						<button type="button"  onclick="document.getElementById('dialog-form').style.display='none'" name="cancel-email" class="cancelbtn">Cancel</button>
					</div>
				</div>
			</form>
		</div>
		
		<div id="dialog-password" class="modal">
			<form name="password" class="modal-content animate" action="" method="post">
				<span onclick="document.getElementById('dialog-password').style.display='none'" class="close" title="Close Modal">&times;</span>
				<h2 style="margin: 3px 0 0 0;">Set/Change Password</h2>	
				<div class="container">	
					<label for="password"><b>Password</b></label>
					<input type="password" name="password_set" id="password" placeholder="Enter password" required />
						
					<label for="password"><b>Repeat password</b></label>
					<input type="password" name="password_set2" id="password2" placeholder="Repeat password" required/>
						
					<button type="submit" name="set-password">Set Password</button>					
					
					<div class="container" style="background-color:#f1f1f1">
						<button type="button"  onclick="document.getElementById('dialog-password').style.display='none'" name="cancel-password" class="cancelbtn">Cancel</button>
					</div>
				</div>
			</form>
		</div>	
	<script>
		function showHistory() {
			var x = document.getElementById("history");
			if (x.style.display === "none") {
				x.style.display = "block";
			} else {
				x.style.display = "none";
			}
		}
	
		// Get the modal
		var passmodal = document.getElementById('dialog-password');

		var emailmodal = document.getElementById('dialog-form'); 

		// Get the button that opens the modal
		var passbtn = document.getElementById("password");

		var emailbtn = document.getElementById("save-email");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks the button, open the modal 
		passbtn.onclick = function() {
			passmodal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			passmodal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				passmodal.style.display = "none";
			}
		}

		emailbtn.onclick = function() {
			document.getElementById("noteModal").value = document.getElementById('note').value;
			emailmodal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			emailmodal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				emailmodal.style.display = "none";
			}
		}
	</script>

	</body>
</html>