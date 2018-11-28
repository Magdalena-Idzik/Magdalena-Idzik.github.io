<?php
	session_start();
	session_unset();
	session_destroy();
	unset($_COOKIE['username']);
	setcookie('username','',time() - 3600);
	unset($_COOKIE['PHPSESSID']);
	setcookie('PHPSESSID','',time() - 3600);
	header('Location: memeBase.php');
	die();
?>