<?php
	
	function template(){
		isset($_SESSION['username']) ? $user = '<a>you are signed in as: ' . $_SESSION['username'] . '</a>' : $user = '';
		echo '<html lang="en"> 
			  <head> <!-- sprawdzić to wszystko walidatorem -->
				<meta charset="utf-8">
				<title>memeBase</title>
				<link rel="stylesheet" href="style.css">
				<script src="validation.js"></script>
			  </head>
			  <body>
				<div class="navbar">
				  <a href="memeBase.php" class="active">memeBase</a>
				  <a href="memeUpload.php">upload meme</a>
				  <a href="signUp.php">sign up</a>
				  <a href="signIn.php">sign in</a>' . $user . 
				  '<a href="logOut.php">log out</a>
				</div>
				<div class="main">';
	}
	
	function numbersValidation($number){
		ctype_digit($number) ? $returnValue = $number : $returnValue = "1";
				
		return $returnValue; 
	}

	function lettersValidation($string){
		preg_match('/[^a-zA-Z0-9_]/', $string) ? $string = "" : $string = $string;
		
		return $string;
	}
?>