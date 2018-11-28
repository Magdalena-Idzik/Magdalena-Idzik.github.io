<?php

	function logOut(){
		session_start();
		session_unset();
		session_destroy();
		unset($_COOKIE['username']);
		setcookie('username','',time() - 3600);
		unset($_COOKIE['PHPSESSID']);
		setcookie('PHPSESSID','',time() - 3600);
		header('Location: membase.html');
		die();
	}
	
	
	function checkSession(){
		session_start();
		if(!isset($_SESSION['uname']
?>