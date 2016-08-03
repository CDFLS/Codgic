<?php
if(!isset($_SESSION)) session_start();
if(!isset($_SESSION['user'])){
	require_once 'inc/cookie.php';
	if(!check_cookie()) {
		if($require_auth) {
		  header("location: /login.php");
		  exit;
		}
	}else{
		require_once 'inc/database.php';
		require_once 'inc/userlogin.php';
		if(TRUE===login($_SESSION['user'], TRUE))
			write_cookie(1);
		mysqli_close($con);
	}
}
