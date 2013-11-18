<?php
	//接受参数
	$username = $_POST['username'];
	
	$password = $_POST['password'];
	
	$openid = $_POST['openid'];
	
	//第一步，判断用户名和密码是否正确
	mysql_connect("localhost","root","123123");
	
	mysql_set_charset("utf8");
	
	mysql_select_db("test");
	
	$sql = "select * from user where username='$username' and password='$password'";
	
	$result = mysql_query($sql);
	
	if($result && mysql_num_rows($result)){
		//用户名密码正确
		$user = mysql_fetch_assoc($result);
		$sql = "update user set openid='$openid' where username='$username'";
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()){
			//完成登录操作
			session_start();
			$_SESSION = $user;
			$_SESSION['openid'] = $openid;
			$_SESSION['nickname'] = $_POST['nickname'];
			header("location:index.php");
		}
	}