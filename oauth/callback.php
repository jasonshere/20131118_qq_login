<?php
	header("content-type:text/html;charset=utf-8");

	function get_contents($url){
		//使用curl来进行模拟请求获取字符串
		$ch = curl_init();
		
		//配置选项
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);//允许请求的内容以文件流的形式返回
		
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);//禁用https
		
		curl_setopt($ch,CURLOPT_URL,$url);//设置请求的url地址
		
		$str = curl_exec($ch);//执行发送
		
		curl_close($ch);
		
		return $str;
	}

	//接收参数
	$code = $_GET['code'];
	
	//通过code获取access_token
	$url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=217911&client_secret=b68b1fe2abdf241466a0eae7731ab45f&code=".$code."&redirect_uri=".urlencode("http://www.sxlzrc.com/web/online/20131118_qq_login/oauth/callback.php");
	
	//header("location:".$url);
	if(ini_get("allow_fopen_url")=="1"){
		$str = file_get_contents($url);
	}else{
		$str = get_contents($url);
	}
	
	//echo $str;
	//获取到了access_token的字符串
	parse_str($str);
	
	//echo $access_token;
	
	//通过access_token来获取openid(用户的id号)
	$url = "https://graph.qq.com/oauth2.0/me?access_token=".$access_token;
	
	//请求url
	$callback = get_contents($url);
	
	$callback = str_replace("(","('",$callback);
	
	$callback = str_replace(")","')",$callback);
	
	eval('$data='.$callback);//$data = callback('{.....}');
	
	function callback($data){
		return json_decode($data,true);
	}
	
	/*echo "<pre>";
	var_dump($data);
	echo "</pre>";*/
	
	//获取到的内容
	//access_token
	//openid
	//通过access_token和openid来获取用户的信息
	$url = "https://graph.qq.com/user/get_user_info?access_token=".$access_token."&oauth_consumer_key=217911&openid=".$data['openid'];
	//var_dump($data['openid']);
	$user_info = get_contents($url);
	
	$user_info = json_decode($user_info,true);
	
	/*echo "<pre>";
	var_dump($user_info);
	echo "</pre>";*/
	//判断当前登录的qq是否已经绑定本站用户
	mysql_connect("localhost","root","123123");
	
	mysql_set_charset("utf8");
	
	mysql_select_db("test");
	
	$sql = "select openid from user where openid='".$data['openid']."'";
	
	$result = mysql_query($sql);
	
	if($result&&mysql_num_rows($result)){
	
		//已经绑定，执行登录，直接跳转到首页（个人中心）
		$sql = "select * from user where openid='".$data['openid']."'";
		
		$result = mysql_query($sql);
		
		$user = mysql_fetch_assoc($result);
		
		session_start();
		
		$_SESSION = $user;
		
		$_SESSION['nickname'] = $user_info['nickname'];
		
		header("location:../index.php");
		
	}else{
	//如果没有绑定，执行绑定
		echo "<img src='".$user_info['figureurl_qq_2']."' /><hr />";
		echo "<form action='../bind.php' method='post'>";
		echo "<input type='hidden' name='openid' value='".$data['openid']."' />";
		echo "用户名:<input type='text' name='username' /><br />";
		echo "昵称:<input type='text' name='nickname' value='".$user_info['nickname']."' /><br />";
		echo "密码:<input type='password' name='password' /><br />";
		echo "<input type='submit' value='绑定' />";
		echo "</form>";
	}
	