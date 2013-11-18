<?php
	header("content-type:text/html;charset=utf-8");
	session_start();
	
	if(empty($_SESSION['id'])){
?>
<a href="oauth/index.php"><img src="http://qzonestyle.gtimg.cn/qzone/vas/opensns/res/img/Connect_logo_3.png" /></a>
<?php
	}else{
		echo "欢迎您回来，".$_SESSION['nickname']."，<a href='logout.php'>退出</a>";
	}
?>