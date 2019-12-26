<?php
require_once("connMysql.php");
setcookie("time", "", time() - 3600);
setcookie("date", "", time() - 3600);
require_once ("Rate.php");      //引入檔匯入
$rate = new Rate();             //建立一個新的Rate物件，命名為$rate
$result = $rate->getAllPost();  //取得所有廚房的評分紀錄
session_start();
	

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
	header("Location: about.php");
}
//預設每頁筆數
$pageRow_records = 8;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecAlbum = "SELECT * FROM `kitchen_data` LEFT JOIN `kitchen_photo` USING (kit_id) group by kit_id ";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecAlbum = $query_RecAlbum." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecAlbum 中
$RecAlbum = $db_link->query($query_limit_RecAlbum);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecAlbum 中
$all_RecAlbum = $db_link->query($query_RecAlbum);
//計算總筆數
$total_records = $all_RecAlbum->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
?>
<html>
    <head>
        <link rel="stylesheet" href="index2.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>	
		<script src="https://cdnjs.cloudflare.com/ajax/libs/imagehover.css/2.0.0/css/imagehover.min.css"></script>		
		<style>
		@charset "utf-8";
		ol, ul{
		margin:0px;padding:0px;
		}
		#mainRegion {
		}
		#mainRegion p {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 10pt;
			line-height: 20px;
			margin-top: 0px;
			margin-bottom: 5px;
		}

		#mainRegion form {
			margin: 0px;
		}

		#mainRegion .postname {
			font-size: 9pt;
			font-weight: bold;
			color: #0066CC;
		}

		#mainRegion .heading {
			font-family: "微軟正黑體";
			font-size: 13pt;
			color: #FF6600;
			line-height: 150%;
			font-weight: normal;
		}
		#mainRegion .smalltext {
			font-size: 15px;
			color: #6267ff;
			font-family: Georgia, "Times New Roman", Times, serif;
			vertical-align: middle;
			background-color: #d9edf7;
			padding-left: 6px;
			padding-right: 6px;
			font-style: italic;
		}
		#mainRegion .actionDiv {
			float: right;
			margin-top: -30px;
			font-size: 11pt;
			font-family: "微軟正黑體";
		}
		.titleDiv {
			padding-left: 30%;
			padding-top: 55px;
			font-family: "微軟正黑體";
			font-size: 24pt;
			font-weight: bolder;
			color: #378C47;
		}
		.menulink {
			float: right;
			margin-top: -48px;
			margin-right: 20px;
			font-family: "微軟正黑體";
			font-size: 11pt;
			font-weight: bold;
		}


		#mainRegion .actionDiv a:hover {
			color: #FFFFFF;
			background-color: #CC0000;
		}

		a {
			text-decoration: none;
		}
		a:link {
			color: #0066CC;
		}
		a:visited {
			color: #0066CC;
		}
		a:hover {
			color: #008844;
		}
		.itemheading {
			font-family: "微軟正黑體";
			font-size: 11pt;
			color: #CC0000;
			font-weight: bolder;
		}
		.trademark {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 8pt;
			color: #0099FF;
		}
		#mainRegion .picDiv {
			padding: 4px;
			background-color: #004a1040;
			height: 130px;
			width: 130px;
			border: 1px solid #fff;
			border-radius: 10px;
			margin-right: auto;
			margin-left: auto;
			margin-bottom: 8px;
			opacity: 1;
			display: block;
			transition: .5s ease;
			backface-visibility: hidden;
		}
		#mainRegion .albumDiv {
			float: left;
			height: 300px;
			width: 150px;
			text-align: center;
			margin-right: 22px;
			margin-bottom: 5px;
		}
		#mainRegion .albumDiv .albuminfo {
			font-family: "微軟正黑體";
			font-size: 11pt;
		}
		#mainRegion .navDiv {
			clear: both;
			text-align: center;
			font-family: "Courier New", Courier, monospace;
			font-size: 9pt;
			padding: 5px;
		}
		#mainRegion .normalDiv {
			clear: both;
			margin: 10px;
		}
		#mainRegion .subjectDiv {
			font-family: "微軟正黑體";
			font-size: 14pt;
			font-weight: bold;
			color: #996600;
			padding: 5px;
			clear: both;
			border-bottom-width: 1px;
			border-bottom-style: dotted;
			border-bottom-color: #666666;
			margin-bottom: 5px;
		}
		#mainRegion .photoDiv {
			text-align: center;
			padding: 10px;
		}
		.piccontainer {
		  position: relative;
		  width: 50%;
		}

		.picDiv {

		}

		.middle {
		  transition: .5s ease;
		  opacity: 0;
		  position: absolute;
		  top: 50%;
		  left: 85%;
		  transform: translate(-50%, -50%);
		  -ms-transform: translate(-50%, -50%);
		  text-align: center;
		}

		.piccontainer:hover .image {
		  opacity: 0.3;
		}

		.piccontainer:hover .middle {
		  opacity: 1;
		}

		.text {
		  background-color: #496b9ea6;
		  color: white;
		  font-size: 16px;
		  padding: 16px 32px;
		}
		.clear {
			clear: both;
		}
		img{
			width:100%;
			border-radius: 10px;
		}
		.selected {
    color: #F4B30A;
	text-shadow: 0 0 1px #F48F0A;
	display: inline-block;
}
		</style>
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
			<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
				  <tr>
				   <td height="80%" valign="top" background="images/album_r1_c1.jpg"><div class="titleDiv">~歡迎參觀廚房們~<br />
				   </div>
					</td>
				  </tr>
				  <tr>
				   <td background="images/album_r2_c1.jpg"><div id="mainRegion">
					 <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
					   <tr>
						 <td><div class="subjectDiv"> 廚房總覽 </div>
						   <div class="actionDiv">廚房總數: <?php echo $total_records;?></div>  
						   <div class="normalDiv"></div>
				<?php	while($row_RecAlbum=$RecAlbum->fetch_assoc()){ ?>
<?php
//若資料庫內有評分紀錄則運行，$product=每一個廚房的評分資料，$ratingResult=評分資料中的分數總合
if (! empty($result)) {
    $i = 0;
    $product = $rate->getProductidBYProduct($row_RecAlbum["kit_id"]);
    $ratingResult = $product[0]["rating_total"];
    if (! empty($ratingResult)){  //算出評分總和，取到小數第一位
      $average = round(($product[0]["rating_total"] / $product[0]["rating_count"]), 1);  
    }  
?>
						
					
						   <div class="albumDiv" align="center">
						   <div class="piccontainer">
						   <div class="picDiv"><a href="kit_show.php?id=<?php echo $row_RecAlbum["kit_id"];?>"><?php if($row_RecAlbum["kit_pid"]==0){?><img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" /><?php }else{?><img src="photos/<?php echo $row_RecAlbum["kit_picurl"];?>" alt="<?php echo $row_RecAlbum["kit_subject"];?>" width="120" height="120" border="0" /><?php }?></a></div>
							  <div class="middle">
								<div class="text">Welcome</div>
							  </div>
							</div>
						   <div class="albuminfo"><a href="kit_show.php?id=<?php echo $row_RecAlbum["kit_id"];?>"><?php echo $row_RecAlbum["kit_subject"];?></a><br />
							 <span class="smalltext"> <?php echo $row_RecAlbum["kit_price"];?> 元</span><br>
							 <div id="star-rating-count-<?php echo $product[0]["kit_id"]; ?>" class="star-rating-count">




            <!-- <td id="demo-table" width="200px" align="center">
			  <div id="product-<?php echo $product[0]["kit_id"]; ?>" class="star-rating-box"> -->
			  <!-- <div id="ass" style='
			  
				cursor: pointer;
				list-style-type: none;
				display: inline-block;
				color: #F0F0F0;
				text-shadow: 0 0 1px #666666;
				font-size: 20px;
			 
			 '> -->
              <input type="hidden" name="rating" id="rating" value="<?php echo $average; ?>" />
                <ul style='
			  
			  cursor: pointer;
			  list-style-type: none;
			  display: inline-block;
			  color: #F0F0F0;
			  text-shadow: 0 0 1px #666666;
			  font-size: 20px;'>
<?php   //建立5個<li>並根據資料庫內的分數紀錄添加"selected"類別
for ($i = 1; $i <= 5; $i ++) {
    $selected = "";
    if(! empty($ratingResult) && $i <= round($average,0)){
        $selected = "selected";
    } 
?>
					<li	style='display: inline-block;' class='<?php echo $selected; ?>'>&#9733;</li>  
<?php }  ?>
                </ul>
                <div id="star-rating-count-<?php echo $product[0]["kit_id"]; ?>" class="star-rating-count">
<?php
if (! empty($ratingResult)) {  //印出平均分數/資料筆數
  echo "平均分數: " . $average . " / 總共 " . $product[0]["rating_count"] . " 筆評分紀錄";
} else {
  echo "尚無評分紀錄";}
?>
                </div>
			  </div>
			  
            
<?php
}
?>
							 
						   </div>
						   </div>
						   <?php }?>
						   <div class="navDiv">
							 <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
							 <a href="?page=1">|&lt;</a> <a href="?page=<?php echo $num_pages-1;?>">&lt;&lt;</a>
							 <?php }else{?>
							 |&lt; &lt;&lt;
				  <?php }?>
				  <?php
					  for($i=1;$i<=$total_pages;$i++){
						  if($i==$num_pages){
							  echo $i." ";
						  }else{
							  echo "<a href=\"?page=$i\">$i</a> ";
						  }
					  }
					  ?>
				  <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
				  <a href="?page=<?php echo $num_pages+1;?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages;?>">&gt;|</a>
				  <?php }else{?>
				  &gt;&gt; &gt;|
				  <?php }?>
						   </div></td>
						 </tr>
					 </table>
				   </div></td>
				  </tr>
				</table>			
			</div> 
        </div>    		    
		<footer class="id_footer">
        © 2019
		</footer>

	</div>
	</body>
</html>