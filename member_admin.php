<?php 
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: member_index.php");
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
	header("Location: member_center.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: member_index.php");
}
//刪除會員
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
	$query_delMember = "DELETE FROM memberdata WHERE m_id=?";
	$stmt=$db_link->prepare($query_delMember);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: member_admin.php");
}
//刪除廚房場地
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
	$query_delMember = "DELETE FROM kitchen_data WHERE kit_id=?";
	$stmt=$db_link->prepare($query_delMember);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: member_admin.php");
}
//選取管理員資料
$query_RecAdmin = "SELECT m_id, m_name, m_logintime FROM memberdata WHERE m_username=?";
$stmt=$db_link->prepare($query_RecAdmin);
$stmt->bind_param("s", $_SESSION["loginMember"]);
$stmt->execute();
$stmt->bind_result($mid, $mname, $mlogintime);
$stmt->fetch();
$stmt->close();
//選取所有一般會員資料
//預設每頁筆數
$pageRow_records = 6;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecMember = "SELECT * FROM memberdata WHERE m_level<>'admin' ORDER BY m_jointime DESC";
$query_product = "SELECT kitchen_data.kit_id, kitchen_data.kit_title, kitchen_data.kit_price, kitchen_data.kit_dese, kitchen_data.kit_county, kitchen_data.kit_capacity, kitchen_data.kit_city,kitchen_data.kit_add, citydata.c_name ,kitchen_photo.kit_picurl FROM kitchen_data left JOIN (citydata,kitchen_photo) on kitchen_data.kit_county=citydata.c_name AND kitchen_data.kit_id=kitchen_photo.kit_id ORDER BY kitchen_data.kit_id";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecMember = $query_RecMember." LIMIT {$startRow_records}, {$pageRow_records}";
$query_limit_product = $query_product." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $resultMember 中
$RecMember = $db_link->query($query_limit_RecMember);
$product = $db_link->query($query_limit_product);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_resultMember 中
$all_RecMember = $db_link->query($query_RecMember);
//計算總筆數
$total_records = $all_RecMember->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
//返回 URL 參數
function keepURL(){
	$keepURL = "";
	if(isset($_GET["keyword"])) $keepURL.="&keyword=".urlencode($_GET["keyword"]);
	if(isset($_GET["price1"])) $keepURL.="&price1=".$_GET["price1"];
	if(isset($_GET["price2"])) $keepURL.="&price2=".$_GET["price2"];	
	if(isset($_GET["cid"])) $keepURL.="&cid=".$_GET["cid"];
	return $keepURL;
}
?>
<html>
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index2.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>		

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網站會員系統</title>
<link href="index2.css" rel="stylesheet" type="text/css">
<script language="javascript">
function deletesure(){
    if (confirm('\n您確定要刪除這個會員嗎?\n刪除後無法恢復!\n')) return true;
    return false;
}
function deleteproduct(){
    if (confirm('\n您確定要刪除這個廚房場地資料嗎?\n刪除後無法恢復!\n')) return true;
    return false;
}
</script>
</head>

<body style="font-family: Microsoft JhengHei;">
	<div class="id_wrapper">
		<header class="header">
		<?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>
				<?php }
				elseif($_SESSION["loginMember"]=="admin"){ echo '<a href="member_admin.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>'; }
				else{ echo '<a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>'; }?></a>

            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn"><span class="nav-icon"></span></label>
            <ul class="menu">
				<li><a href="about.php">品牌起源</a></li>
				<li><a href="kitchen_index.php">看看環境</a></li>
				<li><a href="#subscribe">?????</a></li>
				<?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<li><a href="member_index.php">Sign in</a></li><br>
				<!--這段更改--><a href="member_admin.php"><?php }
				elseif($_SESSION["loginMember"]=="admin"){ echo "<li><a href='admin_add.php'>管理公告</a></li>"."<li><a href='member_admin.php'>系統管理</a></li>"."<li><a href='order_admin.php'>訂單查詢</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>"; }
				else{ echo "<li><a href='member_center.php'>會員中心</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>"; }?></a>
			</ul>
        </header>
		
		<div class="id_content">
			<div class="container" style="margin: auto;width:100%;"> 
				<table border="0" align="center" cellpadding="4" cellspacing="0">
				  <tr>
				  <table width="100%" border="0" cellspacing="0" cellpadding="10">
					  <tr valign="top">
						<p class="title"><h1>會員資料列表 <h1></p>
						  <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
							<tr>
							  <th width="10%" bgcolor="#CCCCCC">&nbsp;</th>
							  <th width="20%" bgcolor="#CCCCCC"><p align="center">姓名</p></th>
							  <th width="20%" bgcolor="#CCCCCC"><p align="center">帳號</p></th>
							  <th width="20%" bgcolor="#CCCCCC"><p align="center">加入時間</p></th>
							  <th width="20%" bgcolor="#CCCCCC"><p align="center">上次登入</p></th>
							  <th width="10%" bgcolor="#CCCCCC"><p align="center">登入</p></th>
							</tr>
							<?php while($row_RecMember=$RecMember->fetch_assoc()){ ?>
							<tr>
							  <td width="10%" align="center" bgcolor="#FFFFFF"><p><a href="member_adminupdate.php?id=<?php echo $row_RecMember["m_id"];?>">修改</a><br>
								<a href="?action=delete&id=<?php echo $row_RecMember["m_id"];?>" onClick="return deletesure();">刪除</a></p></td>
							  <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_name"];?></p></td>
							  <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_username"];?></p></td>
							  <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_jointime"];?></p></td>
							  <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_logintime"];?></p></td>
							  <td width="10%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_RecMember["m_login"];?></p></td>
							</tr>
							<?php }?>
						</table>
						  <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
							<tr>
							  <td valign="middle"><p>資料筆數：<?php echo $total_records;?></p></td>
							  <td align="right"><p>
								  <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
								  <a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
								<?php }?>
								  <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
								  <a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
								  <?php }?>
							  </p></td>
							</tr>
						  </table><p>&nbsp;</p>
						  </td>

						  <tr>
					  <td class="tdrline"><h1><p class="title">租借資料列表 </p></h1>
						  <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
						  <tr>
							  <th bgcolor="#CCCCCC">&nbsp;</th>
							  <th bgcolor="#CCCCCC"><p align="center">場地名稱</p></th>
							  <th bgcolor="#CCCCCC"><p align="center">價錢</p></th>
							  <th bgcolor="#CCCCCC"><p align="center">場地圖片</p></th>
							  <th bgcolor="#CCCCCC"><p align="center">場地介紹</p></th>
							  <th bgcolor="#CCCCCC"><p align="center">人數上限</p></th>
							  <th bgcolor="#CCCCCC"><p align="center">廚房場地地址</p></th>
							</tr>
					  <?php while($row_product=$product->fetch_assoc()){ ?>
							<tr>
							  <td width="5%" align="center" bgcolor="#FFFFFF"><p><a href="kit_update".php?id=<?php echo $row_product["kit_id"];?>">修改</a><br>
								<a href="?action=delete&id=<?php echo $row_product["productid"];?>" onClick="return deleteproduct();">刪除</a></p></td>
							  <td width="15%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_product["kit_title"];?></p></td>
							  <td width="10%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_product["kit_price"];?></p></td>
							  <td width="15%" align="center" bgcolor="#FFFFFF"><p><?php if($row_product["kit_picurl"]==""){?>
								<img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
								<?php }else{?>
								<img src="photos/<?php echo $row_product["kit_picurl"];?>" alt="<?php echo $row_product["kit_title"];?>" width="135" height="135" border="0" />
								<?php }?></p></td>
							  <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_product["kit_dese"];?></p></td>
							  <td width="10%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_product["kit_capacity"];?></p></td>
							  <td width="20%" align="center" bgcolor="#FFFFFF"><p><?php echo $row_product["c_name"].$row_product["kit_city"].$row_product["kit_add"];?></p></td>
							</tr>
							<?php }?>
				</td></tr> 
						  </table>
						  <div class="navDiv" align="center">
              <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
              <a href="?page=1<?php echo keepURL();?>">|&lt;</a> <a href="?page=<?php echo $num_pages-1;?><?php echo keepURL();?>">&lt;&lt;</a>
              <?php }else{?>
              |&lt; &lt;&lt;
              <?php }?>
              <?php
  	          for($i=1;$i<=$total_pages;$i++){
  	  	          if($i==$num_pages){
  	  	  	          echo $i." ";
  	  	          }else{
  	  	              $urlstr = keepURL();
                      echo "<a href=\"?page=$i$urlstr\">$i</a> ";}
              }
  	          ?>
              <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
              <a href="?page=<?php echo $num_pages+1;?><?php echo keepURL();?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages;?><?php echo keepURL();?>">&gt;|</a>
              <?php }else{?>
              &gt;&gt; &gt;|
              <?php }?>
    			</div>
						<td>
						<div class="boxtl"></div><div class="boxtr"></div>
						<div class="regbox" align="center" style="width:100%">
							<h3><strong><?php echo $mname;?></strong>您好。<br>
							本次登入的時間為：<br><?php echo $mlogintime;?></h3>
							<p align="center"><a href="cart_admincreate.php">新增廚房場地資料</a>  <p align="center"><a href="member_adminupdate.php?id=<?php echo $mid;?>">修改資料</a> | <a href="?logout=true">登出系統</a></p>
						</div>
						<div class="boxbl"></div><div class="boxbr"></div></td>
					  </tr>
					</table></td>
				  </tr>
			</table>
		</div> 
  </div>

<footer class="id_footer">
  <tr>
    <td align="center" background="images/album_r2_c1.jpg" class="trademark">© 2019</td>
  </tr>
</footer>

</div>
</body>
</html>
<?php
	$db_link->close();
?>