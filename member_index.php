<?php
require_once("connMysql.php");
session_start();
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
//執行會員登入
if(isset($_POST["username"]) && isset($_POST["passwd"])){
	//繫結登入會員資料
	$query_RecLogin = "SELECT m_username, m_passwd, m_level FROM memberdata WHERE m_username=?";
	$stmt=$db_link->prepare($query_RecLogin);
	$stmt->bind_param("s", $_POST["username"]);
	$stmt->execute();
	//取出帳號密碼的值綁定結果
	$stmt->bind_result($username, $passwd, $level);	
	$stmt->fetch();
	$stmt->close();
	//比對密碼，若登入成功則呈現登入狀態
	if(password_verify($_POST["passwd"],$passwd)){
		//計算登入次數及更新登入時間
		$query_RecLoginUpdate = "UPDATE memberdata SET m_login=m_login+1, m_logintime=NOW() WHERE m_username=?";
		$stmt=$db_link->prepare($query_RecLoginUpdate);
	    $stmt->bind_param("s", $username);
	    $stmt->execute();	
	    $stmt->close();
		//設定登入者的名稱及等級
		$_SESSION["loginMember"]=$username;
		$_SESSION["memberLevel"]=$level;
		//使用Cookie記錄登入資料
		if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
			setcookie("remUser", $_POST["username"], time()+365*24*60);
			setcookie("remPass", $_POST["passwd"], time()+365*24*60);
		}else{
			if(isset($_COOKIE["remUser"])){
				setcookie("remUser", $_POST["username"], time()-99999);
				setcookie("remPass", $_POST["passwd"], time()-99999);
			}
		}
		//若帳號等級為 member 則導向會員中心
		if($_SESSION["memberLevel"]=="member"){
			header("Location: member_center.php");
		//否則則導向管理中心
		}else{
			header("Location: member_admin.php");	
		}
	}else{
		header("Location: member_index.php?errMsg=1");
	}
}
?>
<!-- <link href="index3.css" rel="stylesheet" type="text/css"> -->
<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index2.css">
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
					<?php }
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
</div>
  <div class="id_content">
<table  border="0" align="center" cellpadding="4" cellspacing="0" bgcolor='white'>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td>
        <div class="boxtl"></div><div class="boxtr"></div>
<div class="regbox" align='center'><?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div>
          <?php }?>
          <p class="heading" align="center">登入會員系統</p>
          <form name="form1" method="post" action="">
            <p align="center">帳號：
              <br>
              <input name="username" type="text" class="logintextbox" id="username" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
            </p>
            <p align="center">密碼：<br>
              <input name="passwd" type="password" class="logintextbox" id="passwd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
            </p>
            <p align="center">
              <input name="rememberme" type="checkbox" id="rememberme" value="true" checked >
記住我的帳號密碼。</p>
            <p align="center">
              <input type="submit" name="button" id="button" value="登入系統">
            </p>
            </form>
          <p align="center"><a href="admin_passmail.php">忘記密碼，補寄密碼信。</a></p>
          <p class="heading" align="center">還沒有會員帳號?</p>
          <p align="center"><a href="member_join.php" >馬上申請會員</a></p>
		</div>
        <div class="boxbl"></div><div class="boxbr"></div></td>
      </tr>
    </td>
  </tr>

</table>
</table>
</div>
</div>

<footer class="id_footer">	 	
  <tr>
    <td background="images/album_r2_c1.jpg" class="trademark">© 2019 </td>
  </tr>

</body>
</html>
<?php
	$db_link->close();
?>