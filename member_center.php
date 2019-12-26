<?php
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: member_index.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: member_index.php");
}
//繫結登入會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_username = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);
$row_RecMember=$RecMember->fetch_assoc();

?>
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
				<div class="newdiv"> 
				<table border="0" align="center" cellpadding="4" cellspacing="0">
				  <tr>
					<td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
					  <tr valign="top">
						<td>
						<div class="boxtl"></div><div class="boxtr"></div>

						<div class="regbox" align="center">
								  <h1 class="heading"><strong>會員系統</strong></h1>
									<p><strong><?php echo $row_RecMember["m_name"];?></strong> 您好。</p>
									<p>您總共登入了 <?php echo $row_RecMember["m_login"];?> 次。<br>
									本次登入的時間為：<br>
									<?php echo $row_RecMember["m_logintime"];?></p>
									<?php $_SESSION['m_id'] = $row_RecMember["m_id"];?>
									<h1></h1>
									<p align="center">
									<input type="button" class="button button1" value="預約場地" onclick="location.href='kitchen_index.php'">
									<input type="button" class="button button1" value="廚房列表" onclick="location.href='kit_index.php'">
									<input type="button" class="button button1" value="修改資料" onclick="location.href='member_update.php'">
									<input type="button" class="button button1" value="訂單查詢" onclick="location.href='order_index.php'">
									<input type="button" class="button button1" value="登出系統" onclick="location.href='?logout=true'">
									
									</p>
								<div class="boxbl"></div><div class="boxbr"></div></td>
							  </tr>
							  </table></td>
						  </tr>
						  </table>
						  </div> 
				  </div>
			</div>
	<div>
		<footer class="id_footer">
		© 2019
		</footer>
	</div>
</div>
</body>
</html>
<?php
	$db_link->close();
?>