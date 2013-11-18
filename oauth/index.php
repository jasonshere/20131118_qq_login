<?php
	$url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=217911&redirect_uri=".urlencode("http://www.sxlzrc.com/web/online/20131118_qq_login/oauth/callback.php")."&scope=get_user_info,list_album,upload_pic,do_like&state=lamplijie";
	
	header("location:".$url);
	