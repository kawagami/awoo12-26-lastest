<?php
session_start();
require_once("connMysql.php");	
require_once "Rate.php";
require_once "orders.php";
include("check.php");
$rate = new Rate();
$order = new Order();
$result = $rate->getAllPost();
$orders = $order->getAllOrders();


if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: member_index.php");
}

if($_SESSION["memberLevel"]=="admin"){
	header("Location: order_admin.php");
}

if(isset($_GET["action"])&&($_GET["action"]=="delete")){
	//$order->deleteOrder($_GET["id"]);
	$query_delOrder = "UPDATE orders SET stat='取消' WHERE orderid=?";
	$stmt=$db_link->prepare($query_delOrder);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: order_index.php?stat=已付款");
}

if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	logOut();
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
		<script>
function highlightStar(obj,productid) {
    removeHighlight(productid);		
    //找出程式中被包在<table class="demo-table"><div id="productid">內的<li>標籤，並加上class='highlight'
	$('.demo-table #product-'+productid+' li').each(function(index) {
		$(this).addClass('highlight');
		if(index == $('.demo-table #product-'+productid+' li').index(obj)) {
			return false;	
		}
	});
}
/*移除<table class="demo-table"><div id="productid"><li>內的class='selected','highlight'
  讓被選中的星星變回預設的效果
*/
function removeHighlight(productid) {
	$('.demo-table #product-'+productid+' li').removeClass('selected');
	$('.demo-table #product-'+productid+' li').removeClass('highlight');
}

function addRating(obj,orderid) {
    //遍歷每個<table class="demo-table"><div id="productid"><li>，並加上class='selected'
	$('.demo-table #product-'+orderid+' li').each(function(index) {
        $(this).addClass('selected');
        //將<div id="productid">中id='rating'的元素的value改為index+1，value用於紀錄評分數值
        $('#product-'+orderid+' #rating').val((index+1)); 
		if(index == $('.demo-table #product-'+orderid+' li').index(obj)) {  //若迴圈次數=被選中的星星數量則中止
			return false;	
		}
    });
    //使用jquery.ajax()方法
	$.ajax({
	url: "add_rating.php",  //要發送請求的頁面URL
	data:'orderid='+orderid+'&rating='+$('#product-'+orderid+' #rating').val(),  //要送出去的數據，id=被選中的星星id,rating=評分紀錄
	type: "POST",  //發送數據的類型
    success: function(data) {  //請求成功時要運行的函式
        $("#star-rating-count-"+orderid).html(data);    //將<div id='star-rating-count-id'>里的所有內容換成 data
        }
	});
}
//若未重新選擇，將星星樣式回復成資料表紀錄
function resetRating(productid) {
	if($('#product-'+productid+' #rating').val() != 0) {
		$('.demo-table #product-'+productid+' li').each(function(index) {
			$(this).addClass('selected');
			if((index+1) == $('#product-'+productid+' #rating').val()) {
				return false;	
			}
		});
	}
}
function deletesure(){
    if (confirm('\n您確定要取消這個預約嗎?\n取消後無法恢復!\n')) return true;
    return false;
}
		</script>
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
					  <h3>採賣新鮮食材</h3>
					  <p>LA is always so much fun!</p>
					</div>
				  </div>

				  <div class="item">
					<img src="images/02.jpg" alt="Chicago" style="width:100%;">
					<div class="carousel-caption">
					  <h3>變身特級廚師</h3>
					  <p>LA is always so much fun!</p>
					</div>
				  </div>
				
				  <div class="item">
					<img src="images/03.jpg" alt="New york" style="width:100%;">
					<div class="carousel-caption">
					  <h3>加上酸甜苦辣</h3>
					  <p>LA is always so much fun!</p>
					</div>
				  </div>
				  
				  <div class="item">
					<img src="images/04.jpg" alt="New york" style="width:100%;">
					<div class="carousel-caption">
					  <h3>大家一起來吃飯吧</h3>
					  <p>LA is always so much fun!</p>
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
		
			<div class="newdiv1">
				<table align="center" border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
					<tbody>
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="10">
									<tbody>
									<tr valign="top">
										<td width="100%" class="tdrline">
											<div class="category"> 
												<p>預約紀錄</p>
												<ul>
													<li><a href="order_index.php">總覽</a></li>
													<li><a href="order_index.php?stat=已付款">已付款</a></li>
													<li><a href="order_index.php?stat=完成">完成</a></li>
													<li><a href="order_index.php?stat=取消">取消</a></li>
												</ul>
											</div>
											<hr width="100%" size="1">
											
										</td>
										<td >
											<div class="subjectDiv">預約清單</div>
											<div class="actionDiv"><a href="cart.php">返回購物車</a></div>
											<div class="albumDiv"></div>
											<table class="demo-table" width="800" border="1">
												<tbody>
<?php		?> 											
													<tr>
<?php
if(isset($_GET["stat"]) && $_GET["stat"]=="已付款"){
	echo "<td width='5%' align='center'></td>";
}
?>
														<td width="5%" align="center">訂單編號</td>
														<td width="10%" align="center">廚房名稱</td>
														<td width="10%" align="center">姓名</td>
														<td width="10%" align="center">總金額</td>
														<td width="5%" align="center">預約時數</td>
														<td width="10%" align="center">連絡電話</td>
														<td width="10%" align="center">電子信箱</td>
														<td width="10%" align="center">付款方式</td>
														<td width="10%" align="center">狀態</td>
														<td width="*" align="center">評分</td>
													</tr>

<?php
$i=0;
$m_id = $order->getMemberidByUsername($_SESSION["loginMember"])[0]["m_id"];
$allorders = $order->getOrdersByMember($m_id);
if(!empty($allorders)){
foreach($allorders as $o_detail){
    if(isset($_GET["stat"]) && ($_GET["stat"])!=""){
		if($o_detail["stat"]==$_GET["stat"]){
			$m_detail = $o_detail;
		}
    }else{
		$m_detail = $o_detail;
	}
    if(!empty($m_detail)){
		$i+=1;		
		$o_id = $m_detail["orderid"];

	
?>
													<tr align="center">														
<?php
if(isset($_GET["stat"]) && $_GET["stat"]=="已付款"){
	echo "<td><a href='?action=delete&id=$o_id' onClick='return deletesure();'>刪除</a></td>";
}

?>																	
														<td align="center"><?php echo $m_detail['orderid']; ?></td>
														<td align="center"><?php echo $m_detail['productname']; ?></td>	
														<td align="center"><?php echo $m_detail['customername']; ?></td>
														<td align="center"><?php echo $m_detail['total']; ?></td>
														<td align="center"><?php echo $m_detail['renthours']; ?></td>
														<td align="center"><?php echo $m_detail['customerphone']; ?></td>
														<td align="center"><?php echo $m_detail['customeremail']; ?></td>
														<td align="center"><?php echo $m_detail['paytype']; ?></td>
														<td align="center"><?php echo $m_detail['stat']; ?></td>
														<?php
//若資料庫內資料不為空則運行，$ratingResult=每一筆評分紀錄，$ratingVal=分數
	//$productid = $m_detail["productid"];//$rate->getProductidBYProduct(1);
	$ratingResult = $rate->getRatingByOrderid($o_id);
	$score = $ratingResult[0]["rating"];
?>
		<td id="demo-table" width="200px" align="center">
		<div id="product-<?php echo $o_id; ?>" class="star-rating-box">
		<input type="hidden" name="rating" id="rating" value="<?php echo $score; ?>" />
		<ul onMouseOut="resetRating(<?php echo $o_id; ?>);">
<?php   //建立5個<li>並根據資料庫內的分數紀錄添加"selected"
if ((isset($_GET["stat"]) && $_GET["stat"]=="完成") || $m_detail['stat']=="完成") {
	for ($i = 1; $i <= 5; $i ++) {
		$selected = "";
		if (! empty($score) && $i <= $score) {
			$selected = "selected";
		}
?>
		<li class="<?php echo $selected; ?>"
			onmouseover="highlightStar(this,<?php echo $o_id ?>);"
			onmouseout="removeHighlight(<?php echo $o_id ?>);"
			onClick="addRating(this,<?php echo $o_id ?>);">&#9733;
        </li>  
<?php 
	}  
}
?>
		</ul>
		<div id="star-rating-count-<?php echo $o_id; ?>" class="star-rating-count">
<?php
if((isset($_GET["stat"]) && $_GET["stat"]=="完成") || $m_detail['stat']=="完成"){
	if(empty($score)){
		echo "請對這次的服務打分數" ;
	}else {
		echo "之前的評分";	
	}
}else if(isset($_GET["stat"]) && $_GET["stat"]=="已付款" || $m_detail['stat']=="已付款"){
	echo "預約進行中，尚無評分";
} else{
	echo "預約已取消";
}}
?>
                </div>
              </div>
            </td>
		</tr>
<?php            
		$m_detail ="";
	}
}
if($i==0){
	echo "<tr align='center'><td colspan='11'>沒有任何訂單紀錄</td></tr>";
}
?>                        
												</tbody>
											</table>
											<div class="navDiv"></div>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
			<div>
				<footer class="id_footer">
				Footer Block
				</footer>
			</div>
		</div>
	</body>
</html>