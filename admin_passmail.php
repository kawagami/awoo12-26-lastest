<?php
function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;
    case "email":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
      break;
    case "url":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
      break;      
  }
  return $theValue;
}
require_once("connMysql.php");
session_start();
//函式：自動產生指定長度的密碼
function MakePass($length) { 
	$possible = "0123456789!@#$%^&*()_+abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
	$str = ""; 
	while(strlen($str)<$length){ 
	  $str .= substr($possible, rand(0, strlen($possible)), 1); 
	}
	return($str); 
}
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
	//若帳號等級為 member 則導向會員中心
	if($_SESSION["memberLevel"]=="member"){
		header("Location: member_center.php");
	//否則則導向管理中心
	}else{
		header("Location: member_admin.php");	
	}
}
//檢查是否為會員
if(isset($_POST["m_username"])){
	$muser = GetSQLValueString($_POST["m_username"], 'string');
	//找尋該會員資料
	$query_RecFindUser = "SELECT m_username, m_email FROM memberdata WHERE m_username='{$muser}'";
	$RecFindUser = $db_link->query($query_RecFindUser);	
	if ($RecFindUser->num_rows==0){
		header("Location: admin_passmail.php?errMsg=1&username={$muser}");
	}else{	
	//取出帳號密碼的值
		$row_RecFindUser=$RecFindUser->fetch_assoc();
		$username = $row_RecFindUser["m_username"];
		$usermail = $row_RecFindUser["m_email"];	
		//產生新密碼並更新
		$newpasswd = MakePass(10);
		$mpass = password_hash($newpasswd, PASSWORD_DEFAULT);
		$query_update = "UPDATE memberdata SET m_passwd='{$mpass}' WHERE m_username='{$username}'";
		$db_link->query($query_update);
		//補寄密碼信
		$mailcontent ="您好，<br />您的帳號為：{$username} <br/>您的新密碼為：{$newpasswd} <br/>";
		$mailFrom="=?UTF-8?B?" . base64_encode("會員管理系統") . "?= <service@e-happy.com.tw>";
		$mailto=$usermail;
		$mailSubject="=?UTF-8?B?" . base64_encode("補寄密碼信"). "?=";
		$mailHeader="From:".$mailFrom."\r\n";
		$mailHeader.="Content-type:text/html;charset=UTF-8";
		if(!@mail($mailto,$mailSubject,$mailcontent,$mailHeader)) die("郵寄失敗！");
		header("Location: admin_passmail.php?mailStats=1");
	}
}
?>
<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index4.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>		
	</head>
    <body style="font-family: Microsoft JhengHei;">
	<div class="id_wrapper">
		<header class="header">
            <a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>
            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn"><span class="nav-icon"></span></label>
            <ul class="menu">
				<li><a href="about.php">品牌起源</a></li>
				<li><a href="kitchen_index.php">看看環境</a></li>
				<li><a href="#subscribe">?????</a></li>
				<?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<li><a href="member_index.php">Sign in</a></li><br>
				<!--這段更改--><a href="member_admin.php"><?php }
				elseif($_SESSION["loginMember"]=="admin"){ echo "<li><a href='admin_add.php'>管理公告</a></li>"."<li><a href='member_admin.php'>系統管理</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>"; }
				else{ echo "<li><a href='member_center.php'>會員中心</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>"; }?></a>
			</ul>
        </header>
		<div class="id_content">
			<div class="container" style="margin: auto;width:100%;"> 
			  <div id="myCarousel" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
				  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
				  <li data-target="#myCarousel" data-slide-to="1"></li>
				  <li data-target="#myCarousel" data-slide-to="2"></li>
				  <li data-target="#myCarousel" data-slide-to="3"></li>
				</ol>

				<!-- Wrapper for slides -->
				<div class="carousel-inner">
				  <div class="item active">
					<img src="images/01.jpeg" alt="Los Angeles" style="width:100%;">
					<div class="carousel-caption">
					  <h3>採買新鮮食材</h3>
					  <p>Buy fresh ingredients</p>
					</div>
				  </div>

				  <div class="item">
					<img src="images/02.jpg" alt="Chicago" style="width:100%;">
					<div class="carousel-caption">
					  <h3>變身特級廚師</h3>
					  <p>Turn into a master chef</p>
					</div>
				  </div>
				
				  <div class="item">
					<img src="images/03.jpg" alt="New york" style="width:100%;">
					<div class="carousel-caption">
					  <h3>加上酸甜苦辣</h3>
					  <p>Add some seasoning</p>
					</div>
				  </div>
				  
				  <div class="item">
					<img src="images/04.jpg" alt="New york" style="width:100%;">
					<div class="carousel-caption">
					  <h3>大家一起來吃飯吧</h3>
					  <p>Let's eat together</p>
					</div>
				  </div>
				</div>
				


				<!-- Left and right controls -->
				<a class="left carousel-control" href="#myCarousel" data-slide="prev">
				  <span class="glyphicon glyphicon-chevron-left"></span>
				  <span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#myCarousel" data-slide="next">
				  <span class="glyphicon glyphicon-chevron-right"></span>
				  <span class="sr-only">Next</span>
				</a>
			  </div>
    <div class="id_content">
<?php if(isset($_GET["mailStats"]) && ($_GET["mailStats"]=="1")){?>
<script>alert('密碼信補寄成功！');window.location.href='member_index.php';</script>
<?php }?>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td width="200">
        <div class="boxtl"></div><div class="boxtr"></div><div class="regbox">
          <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv">帳號「 <strong><?php echo $_GET["username"];?></strong>」沒有人使用！</div>
          <?php }?>
          <p class="heading">忘記密碼？</p>
          <form name="form1" method="post" action="">
            <p>請輸入您申請的帳號，系統將自動產生一個十位數的密碼寄到您註冊的信箱。</p>
            <p><strong>帳號</strong>：<br>
              <input name="m_username" type="text" class="logintextbox" id="m_mail"></p>
            <p align="center">
              <input type="submit" name="button" id="button" value="寄密碼信">
              <input type="button" name="button2" id="button2" value="回上一頁" onClick="window.history.back();">
            </p>
            </form>
          <p class="heading">還沒有會員帳號?</p>
          <p>註冊帳號免費又容易</p>
          <p align="right"><a href="member_join.php">馬上申請會員</a></p></div>
          </div>
        <div class="boxbl"></div><div class="boxbr"></div></td>
      </tr>
    </table></td>
    </table>
    </div>
    </div>
    </div>
 <footer class="id_footer"  align="center">

<tr>
  <td background="images/album_r2_c1.jpg" class="trademark">© 2019</td>
</tr>
</div>

</footer>

</body>
</html>