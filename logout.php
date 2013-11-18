<?php
	session_start();
	$_SESSION = array();
	session_destroy();
	setcookie(session_name(),'',time()-1,"/");
	header("location:index.php");