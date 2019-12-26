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

if($_SESSION["memberLevel"]=="member"){
	header("Location: order_index.php");
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
			function deletesure(){
    if (confirm('\n您確定要取消這個預約嗎?\n取消後無法恢復!\n')) return true;
    return false;
}
		</script>	
		<style>
        /*------各區域尺寸語法開始------*/
        .m-adm {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: none;
        }

        .m-adm>.m-admbox {
            flex: none;
            width: 30%;
            height: auto;
            padding: 0px 0px;
            margin: 0px 2px;
        }

        .m-adm>.m-adm-list {
            flex: none;
            width: 100%;
            background-color: none;
        }

        /*------各區域尺寸語法結束------*/

        /*------網站會員管理表單語法開始------*/
        .m-adm a {
            text-decoration: none;
        }

        .m-adm .m-admbox label {
            font-size: 12pt;
            font-family: 微軟正黑體;
            font-weight: 900;
        }

        table.m-adm-list {
            width: auto;
            margin: 5px auto;
            border-collapse: collapse;
            border: 1px solid #cccccc;
        }

        table.m-adm-list p {
            font-size: 12pt;
            font-family: 微軟正黑體;
        }

        table.m-adm-list td:hover {
            background-color: #b6d9fc;
        }

        .m-adm-div {
            width: 100%;
            border: 1px solid #0066cc;
            padding: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            padding-left: 15px;
            padding-right: 15px;
        }

        td.m-adm-list-1,td.m-adm-list-2,td.m-adm-list-3,td.m-adm-list-4,td.m-adm-list-5,td.m-adm-list-6,td.m-adm-list-7,td.m-adm-list-8,td.m-adm-list-9,td.m-adm-list-10,td.m-adm-list-11 {
            font-weight: 900;
            text-align: center;
            color: azure;
            background-color: cornflowerblue;
            border-collapse: collapse;
            border: 1px solid #cccccc;
            
        }


        td.m-adm_list-1 {
            width: 5%;
        }
        td.m-adm-list-2 {
            width: 5%;
        }
        td.m-adm-list-3 {
            width: 12%;
        }
        td.m-adm-list-4 {
            width: 10%;
        }
        td.m-adm-list-5 {
            width: 10%;
        }
        td.m-adm-list-6 {
            width: 5%;
        }
        td.m-adm-list-7 {
            width: 10%;
        }
        td.m-adm-list-8 {
            width: 16%;
        }
        td.m-adm-list-9 {
            width: 8%;
        }
        td.m-adm-list-10 {
            width: 8%;
        }
        td.m-adm-list-11 {
            width: auto;
        }

        td.m-adm-list-odd {
            text-align: center;
            border-collapse: collapse;
            border: 1px solid #cccccc;
            background-color: #f8f2f2;
        }

        td.m-adm-list-even {
            text-align: center;
            border-collapse: collapse;
            border: 1px solid #cccccc;
            background-color: #ffffff;
        }

        table.m_adm_ctl {
            margin: auto;
        }

        table.m_adm_ctl,
        table.m_adm_ctl span {
            font-family: 微軟正黑體;
            font-size: 12pt;
            text-align: center;
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
			  <div style="clear:both;"></div>
			</div> 	

			<div class="nav2 media-m-hid media-l-center">
                <div class="location media-xl-10">
                    <ul class="breadcrumb">
                        <li><span>目前位置</span></li>
                        <li><span><a href="#">首頁</a></span></li>
                        <li><span>訂單查詢</span></li>
                    </ul>
                </div>
            </div>
            <div class="m-adm">
                <div class="m-admbox"><label>
                        <a href="order_admin.php">預約總覽</a> |
                        <a href="order_admin.php?stat=已付款">已付款</a> |
                        <a href="order_admin.php?stat=完成">完成</a> |
                        <a href="order_admin.php?stat=取消">取消</a>
                    </label></div>
                <div class="m-admbox"><label><h3>預約清單</h3></label></div>
                <div class="m-admbox"></div>
            </div>
            <div class="m-adm">
                <div class="m-adm-div">
                    <table class="m-adm-list">										
						<tr>	
<?php
if(isset($_GET["stat"]) && $_GET["stat"]=="已付款"){
	echo "<td class='m-adm-list-1'><p>操作</p></td>";
}
?>
							<td class="m-adm-list-2">
                                <p>編號</p>
                            </td>
                            <td class="m-adm-list-3">
                                <p>廚房</p>
                            </td>
                            <td class="m-adm-list-4">
                                <p>姓名</p>
                            </td>
                            <td class="m-adm-list-5">
                                <p>金額</p>
                            </td>
                            <td class="m-adm-list-6">
                                <p>時數</p>
                            </td>
                            <td class="m-adm-list-7">
                                <p>電話</p>
                            </td>
                            <td class="m-adm-list-8">
                                <p>信箱</p>
                            </td>
                            <td class="m-adm-list-9">
                                <p>方式</p>
                            </td>
                            <td class="m-adm-list-10">
                                <p>狀態</p>
                            </td>
                            <td class="m-adm-list-11">
                                <p>評分</p>
                            </td>
						</tr>

<?php
$i=0;
$memberid = $order->getAllMemberidByOrders();
$ordernum = count($orders);
for($j=0;$j<$ordernum;$j++){
	//$mid = $orders[$j]["m_id"];
    $id = $memberid[$j]["m_id"];
    if(isset($_GET["stat"]) && ($_GET["stat"])!=""){
		if($orders[$j]["stat"]==$_GET["stat"]){
            $m_detail = $orders[$j];
		}
    }else{
        $m_detail = $orders[$j];
    }
    if(!empty($m_detail)){
		$i+=1;		
		$o_id = $m_detail["orderid"];
?>
							<tr>
                            <div class="sexyborder">
<?php
if(isset($_GET["stat"]) && $_GET["stat"]=="已付款"){
	echo "<td class='m-adm-list-even'><a href='?action=delete&id=$o_id' onClick='return deletesure();'>取消預約</a></td>";
}
?>	
								<td class="m-adm-list-odd">
                                    <p><?php echo $m_detail['orderid']; ?></p>
                                </td>
                                <td class="m-adm-list-even">
                                    <p><?php echo $m_detail['productname']; ?></p>
                                </td>
                                <td class="m-adm-list-odd">
                                    <p><?php echo $m_detail['customername']; ?></p>
                                </td>
                                <td class="m-adm-list-even">
                                    <p><?php echo $m_detail['total']; ?></p>
                                </td>
                                <td class="m-adm-list-odd">
                                    <p><?php echo $m_detail['renthours']; ?></p>
                                </td>
                                <td class="m-adm-list-even">
                                    <p><?php echo $m_detail['customerphone']; ?></p>
                                </td>
                                <td class="m-adm-list-odd">
                                    <p><?php echo $m_detail['customeremail']; ?></p>
                                </td>
                                <td class="m-adm-list-even"><?php echo $m_detail['paytype']; ?></p>
                                </td>
                                <td class="m-adm-list-odd">
                                    <p><?php echo $m_detail['stat']; ?></p>
                                </td>
<?php
	//$productid = $m_detail["productid"];//$rate->getProductidBYProduct(1);
	$ratingResult = $rate->getRatingByOrderid($m_detail['orderid']);
	$score = $ratingResult[0]["rating"];
?>
								<td id="demo-table" class="m-adm-list-even">
                                    <div id="product-<?php echo $score; ?>" class="star-rating-box">
                                        <input type="hidden" name="rating" id="rating" value="<?php echo $score; ?>" />
                                        <ul>
<?php   //建立5個<li>並根據資料庫內的分數紀錄添加"selected"
	if(!empty($score) && isset($_GET["stat"]) && $_GET["stat"]=="完成" || $m_detail['stat']=="完成"){
		for ($i = 1; $i <= 5; $i ++) {
			$selected = "";
			if (! empty($score) && $i <= $score) {
				$selected = "selected";
			}
?>
		<li class='<?php echo $selected; ?>'>&#9733;</li>  
<?php 	
		}  
	}
?>
        </ul>
		<div id="star-rating-count-<?php echo $score; ?>" class="star-rating-count">
<?php
if((isset($_GET["stat"]) && $_GET["stat"]=="完成") || $m_detail['stat']=="完成"){
	if(empty($score)){
		echo "尚無評分紀錄" ;
	}else {
		echo "會員使用評分";	
	}
}else if((isset($_GET["stat"]) && $_GET["stat"]=="已付款") || $m_detail['stat']=="已付款"){
	echo "預約正在進行中";
}else{
	echo "會員已取消預約";
} 
?>
              </div>
            </td>
		</tr>
<?php            
		$m_detail ="";
	}
}
if($i==0){
	echo "<tr align='center'><td colspan='10'>沒有任何訂單紀錄</td></tr>";
}
?>                        
											</table>
											
			</div>
		</div>
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