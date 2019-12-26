<?php



session_start();
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index2.php");
}
///////////////////////////////////////////擷取公告內容








require_once("connMysql.php");
setcookie("time", "", time() - 3600);
setcookie("date", "", time() - 3600);
require_once ("Rate.php");      //引入檔匯入
$rate = new Rate();             //建立一個新的Rate物件，命名為$rate
$result = $rate->getAllPost();  //取得所有廚房的評分紀錄
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
//若有分類關鍵字時未加限制顯示筆數的SQL敘述句
if(isset($_GET["cid"])&&($_GET["cid"]!="")){
	$query_RecProduct = "SELECT * FROM kitchen_data WHERE kit_county=? ORDER BY kit_id DESC";
	$stmt = $db_link->prepare($query_RecProduct);
	$stmt->bind_param("i", $_GET["cid"]);
//若有搜尋關鍵字時未加限制顯示筆數的SQL敘述句
}elseif(isset($_GET["keyword"])&&($_GET["keyword"]!="")){
	$query_RecProduct = "SELECT * FROM kitchen_data WHERE kit_title LIKE ? OR description LIKE ? ORDER BY kit_id DESC";
	$stmt = $db_link->prepare($query_RecProduct);
	$keyword = "%".$_GET["keyword"]."%";
	$stmt->bind_param("ss", $keyword, $keyword);	
//若有價格區間關鍵字時未加限制顯示筆數的SQL敘述句
}elseif(isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"]<=$_GET["price2"])){
	$query_RecProduct = "SELECT * FROM kitchen_data WHERE kit_price BETWEEN ? AND ? ORDER BY kit_id DESC";
	$stmt = $db_link->prepare($query_RecProduct);
	$stmt->bind_param("ii", $_GET["price1"], $_GET["price1"]);
//預設狀況下未加限制顯示筆數的SQL敘述句
}else{
	$query_RecProduct = "SELECT * FROM kitchen_data ORDER BY kit_id DESC";
	$stmt = $db_link->prepare($query_RecProduct);
}
$stmt->execute();
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecProduct 中
$all_RecProduct = $stmt->get_result();
//計算總筆數
$total_records = $all_RecProduct->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
//繫結產品目錄資料
$query_RecCategory = "SELECT citydata.c_id, citydata.c_name, count(kitchen_data.kit_id) as productNum FROM citydata LEFT JOIN kitchen_data ON citydata.c_name = kitchen_data.kit_county GROUP BY citydata.c_id, citydata.c_name ORDER BY citydata.c_id ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(kitchen_data.kit_id) as totalNum FROM kitchen_data";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
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
    <title>廚房場地購物系統</title>
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
    <div class='newdiv1'>
    <table align='center' border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
          <td width="100%" class="tdrline"><div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 廚房場地搜尋 <span class="smalltext"></span></p>
              <form name="form1" method="get" action="cartindex.php">
                <p>
                  <input name="keyword" type="text" id="keyword" value="請輸入關鍵字" size="12" onClick="this.value='';">
                  <input type="submit" id="button" value="查詢">
                </p>
              </form>
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 價格區間 <span class="smalltext"></span></p>
              <form action="cartindex.php" method="get" name="form2" id="form2">
                <p>
                  <input name="price1" type="text" id="price1" value="0" size="3">
                  -
                  <input name="price2" type="text" id="price2" value="0" size="3">
                  <input type="submit" id="button2" value="查詢"><br><br>
                  <input type="button" name="Submit" value="回上一頁" onClick="window.history.back();">
                </p>
              </form>
            </div>
            <hr width="100%" size="1" />
            <div class="categorybox">
              <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 廚房縣市分類 <span class="smalltext"></span></p>
              <ul>
                <li><a href="cartindex.php">所有地點廚房<span class="citydatacount">(<?php echo $row_RecTotal["totalNum"];?>)</span></a></li>
                <?php	while($row_RecCategory=$RecCategory->fetch_assoc()){ ?>
                <li><a href="cartindex.php?cid=<?php echo $row_RecCategory["c_id"];?>"><?php echo $row_RecCategory["c_name"];?> <span class="citydatacount">(<?php echo $row_RecCategory["productNum"];?>)</span></a></li>
                <?php }?>
              </ul>
            </div>
          <td><div class="subjectDiv"> <span class="heading"><img src="images/16-cube-green.png" width="16" height="16" align="absmiddle"></span>廚房場地列表</div>
            <div class="actionDiv"><a href="cart.php">我的購買清單</a> *點選廚房名稱或圖片了解廚房場地詳細內容</div>
            <table width='500' border='1'>
            <tr>
              <td align='center'>廚房名稱</td>
              <td align='center'>廚房圖片</td>
              <td align='center'>價格</td>
              <td align='center'>評價</td>  
            </tr>
            <?php
            //加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
            $query_limit_RecProduct = $query_RecProduct." LIMIT {$startRow_records}, {$pageRow_records}";
            //以加上限制顯示筆數的SQL敘述句查詢資料到 $RecProduct 中
            $stmt = $db_link->prepare($query_limit_RecProduct);
			      //若有分類關鍵字時未加限制顯示筆數的SQL敘述句
			      if(isset($_GET["cid"])&&($_GET["cid"]!="")){
				    $stmt->bind_param("i", $_GET["cid"]);
			      //若有搜尋關鍵字時未加限制顯示筆數的SQL敘述句
			      }elseif(isset($_GET["keyword"])&&($_GET["keyword"]!="")){
				    $keyword = "%".$_GET["keyword"]."%";
				    $stmt->bind_param("ss", $keyword, $keyword);	
			      //若有價格區間關鍵字時未加限制顯示筆數的SQL敘述句
			      }elseif(isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"]<=$_GET["price2"])){
				    $stmt->bind_param("ii", $_GET["price1"], $_GET["price2"]);
                  }
            
            $stmt->execute();            
            $RecProduct = $stmt->get_result();
            while($row_RecProduct=$RecProduct->fetch_assoc()){ 
            ?>
            <tr><div class="albumDiv">
           
            <td align='center'><div class="albuminfo"><a href="1221產品修改.php?id=<?php echo $row_RecProduct["kit_id"];?>"><?php echo $row_RecProduct["kit_title"];?></a><br /></td>
            <td align='center'><div width='20%' height='20%' class="picDiv"><a href="1221產品修改.php?id=<?php @$_GET['test']; echo $row_RecProduct["kit_id"];?>">
                <?php if(@$row_RecProduct["kit_picurl"]=="1"){
                    ?>
                
                <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
                <?php }else{?>
                <img src="photos/<?php echo $row_RecProduct["kit_picurl"];?>" alt="<?php echo $row_RecProduct["kit_title"];?>" width="135" height="135" border="0" />
                <?php }?>
                </a></td></div>
            <td align='center'><span class="smalltext">特價 </span><span class="redword"><?php echo $row_RecProduct["kit_price"];?></span><span class="smalltext"> 元</span> </div></td>
<?php
//若資料庫內有評分紀錄則運行，$product=每一個廚房的評分資料，$ratingResult=評分資料中的分數總合
if (! empty($result)) {
    $i = 0;
    $product = $rate->getProductidBYProduct($row_RecProduct["kit_id"]);
    $ratingResult = $product[0]["rating_total"];
    if (! empty($ratingResult)){  //算出評分總和，取到小數第一位
      $average = round(($product[0]["rating_total"] / $product[0]["rating_count"]), 1);  
    }  
?>
            <td id="demo-table" width="200px" align="center">
              <div id="product-<?php echo $product[0]["kit_id"]; ?>" class="star-rating-box">
              <input type="hidden" name="rating" id="rating" value="<?php echo $average; ?>" />
                <ul>
<?php   //建立5個<li>並根據資料庫內的分數紀錄添加"selected"類別
for ($i = 1; $i <= 5; $i ++) {
    $selected = "";
    if(! empty($ratingResult) && $i <= round($average,0)){
        $selected = "selected";
    } 
?>
                    <li class='<?php echo $selected; ?>'>&#9733;</li>  
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
            </td>
<?php
}
?>

          </div>
            <?php } ?>
            </tr>
            </table>
            <div class="navDiv">
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
    </td>
        </tr>
      </table></td>
      </table>
      </div>
			</div> 
      </div>
		  <div>
		<footer class="id_footer">
      Footer Block
    </footer>
  </div>
  </div>









