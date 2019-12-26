<?php
session_start();
require_once("connMysql.php");	

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
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index2.php");
}
///////////////////////////////////////////擷取公告內容
//預設每頁筆數
$pageRow_records = 5;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecBoard = "SELECT * FROM news1 ORDER BY news_time DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecBoard = $query_RecBoard." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecBoard 中
$RecBoard = $db_link->query($query_limit_RecBoard);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecBoard 中
$all_RecBoard = $db_link->query($query_RecBoard);
//計算總筆數
$total_records = $all_RecBoard->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);

?>
<html>

    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index2.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>		
		<link rel="stylesheet" href="css/styles.css" />
		<link rel="stylesheet" href="dist/aos.css" />
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
			
			<br>

			<div class="banner" data-aos="fade-up">
			<span>WELCOME TO !!!<span> 
			</div>
			<br>
			<hr class="line">
				<p class="News_Title">最新消息<br><span style="padding:0px;font-size:20px;">News</span></p>			 
			<hr class="line"> 
			<br>
			<!-- 　<div style="margin:0 auto;border: 2px solid blue; width:80%"> -->
			<?php while($row_RecBoard=$RecBoard->fetch_assoc()){ ?>
			<div class="DIV1" data-aos="fade-right" align="center"><img src="photos/<?php echo $row_RecBoard["news_picurl"]?>" width="100%" height="100%"></div>
			<div class="DIV2" data-aos="fade-left"><h2><?php echo $row_RecBoard["news_subject"]?></h2>發布時間:<?php echo $row_RecBoard["news_time"]?><hr><?php echo $row_RecBoard["news_content"]?></td></div><?php }?>
			<hr>
			<div style="clear:both;"></div>
			<!-- <div class="DIV1">這是並排在左邊的 DIV 區塊</div>
			<div class="DIV2">這是並排在右邊的 DIV 區塊</div>
			<br>
			<div style="clear:both;"></div>
			<div class="DIV1">這是並排在左邊的 DIV 區塊</div>
			<div class="DIV2">這是並排在右邊的 DIV 區塊</div>
			<br>
			<div style="clear:both;"></div>
			</div> -->
			</div> 
						<?php if ($total_pages > 1) { ?>
					<table width="100%" id="bdstyle" cellspacing="0" cellpadding="5px">
					  <tr>
						<td align="left" height="20" style="color:#ed8496; font-size:18px;"><b>第 <?php echo $num_pages;?> 頁 | 共 <?php echo $total_pages;?> 頁</b></td>
						<td align="right" width="280px" height="20" style="color:#ed8496; font-size:18px; font-weight:700">&nbsp;&nbsp;<a href="?page=1"></a>&nbsp;&nbsp;<a href="?page=<?php echo $num_pages-1;?>"></a>&nbsp;&nbsp;
						  <?php for($i=1;$i<=$total_pages;$i++){?>
						  &nbsp;<a style="font-size:18px" href="?page=<?php echo $i;?>"><?php echo $i;?></a>&nbsp;
						  <?php }?>
						  <a href="?page=<?php echo $num_pages+1;?>"></a>&nbsp;&nbsp;<a href="?page=<?php echo $total_pages;?>"></a>&nbsp;&nbsp;</td>
					  </tr>
					</table>
					<?php }else{}?>
		</div>

		


		<footer class="id_footer">
		  Footer Block
		</footer>
	</div>
	<script src="dist/aos.js"></script>
    <script>
      AOS.init({
        easing: 'ease-in-out-sine'
      });
    </script>
	</body>
</html>