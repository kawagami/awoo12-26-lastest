<?php
require_once("connMysql.php");
require_once("mycart.php");
session_start();

$cart = &$_SESSION['cart']; // 將購物車的值設定為 Session
if (!is_object($cart)) $cart = new myCart();
if (isset($_POST["action"]) && ($_POST["action"] == "add")) {
	$cart->add_item($_POST['id'], $_POST['qty'], $_POST['price'], $_POST['name']);
	header("Location: cart.php");
}


function GetSQLValueString($theValue, $theType)
{
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
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index2.php");
}

$query_RecProduct = "SELECT kitchen_data.kit_id,kitchen_data.kit_county, kitchen_data.kit_title,kitchen_data.kit_price, kitchen_photo.kit_picurl,kitchen_data.kit_dese,citydata.c_name,kitchen_data.kit_capacity,kitchen_data.kit_city,kitchen_data.kit_add FROM kitchen_data JOIN (citydata,kitchen_photo) on kitchen_data.kit_county=citydata.c_name AND kitchen_data.kit_id=kitchen_photo.kit_id WHERE kitchen_data.kit_id=?";
$stmt = $db_link->prepare($query_RecProduct);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$RecProduct = $stmt->get_result();
$row_RecProduct = $RecProduct->fetch_assoc();


//顯示廚房資訊SQL敘述句
$kit_id = 0;
if (isset($_GET["id"]) && ($_GET["id"] != "")) {
	$kit_id = GetSQLValueString($_GET["id"], "int");
}
$query_RecKitchen = "SELECT * FROM kitchen_data JOIN memberdata ON kitchen_data.m_id=memberdata.m_id WHERE kit_id='{$kit_id}'";
//顯示照片SQL敘述句
$query_RecKitchen_Photo = "SELECT * FROM kitchen_photo WHERE kit_id='{$kit_id}' ORDER BY kit_date";
//將二個SQL敘述句查詢資料到 $RecKitchen_data、$RecKitchen_Photo 中
$RecKitchen = $db_link->query($query_RecKitchen);
$RecKitchen_Photo = $db_link->query($query_RecKitchen_Photo);
//計算照片總筆數
$total_records = $RecKitchen_Photo->num_rows;
//取得相簿資訊
$row_RecKitchen = $RecKitchen->fetch_assoc();
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
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script type="text/javascript">
		function functionname(option) {
			document.cookie = "date=" + option;
			window.location.reload()
		}

		function functiontime(option) {
			document.cookie = "time=" + option;
			window.location.reload()
		}
	</script>
	<style>
		.kit_show {
			width: 90%;
			margin: auto;
		}


		.kit_photo {
			width: 100%;
			margin: auto;
			display: inline-block;
			text-align: center;
			padding-top: 50px;
		}

		.kit_photo img {
			height: 150px;
			margin-right: auto;
			margin-left: auto;
			margin-bottom: 5px;
			vertical-align: middle;
		}

		.kit_info {
			margin: auto;
			padding: 10px;
			display: flex;
		}		

		.kit_location h3 {
			font-size: 30px;
			margin-bottom: 10px;
		}

		.kit_location p {
			font-size: 14px;
		}

		.kit_location img {
			width: 25px;
		}

		.kit_dese {
			margin: 5px;
		}

		.kit_ctl {
			margin: 5px;
			margin-left: 10px;
		}

		.kit_dese p {
			line-height: 1.2;
			font-size: 16px;
		}

		.reserve p {
			line-height: 1.2;
			font-size: 16px;
			font-weight: bold;
		}

		input {
			width: 80%;
			height: 50px;
			font-size: 20px;
			color: #fff;
			background-color: royalblue;
			margin: 0px 20px;
			border-radius: 10px;
		}
		@media only screen and (min-width: 1024px) {
			.kit_location {
				width: 25%;
			}
			.kit_dese {
				width: 50%;
			}

			.kit_ctl{
				width: 25%;
			}
		}
		@media only screen and (max-width: 1023px) {
			.kit_info {
				flex-direction: column;
				width: 100%;
			}

			.kit_location {
				width: 100%;
			}
			

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
				<div class="kit_show">
					<div class="kit_photo">
						<?php while ($row_RecKitchen_Photo = $RecKitchen_Photo->fetch_assoc()) { ?>
							<a data-fancybox="gallery" data-fancybox data-caption="<?php echo $row_RecKitchen_Photo["kit_subject"]; ?>" href="photos/<?php echo $row_RecKitchen_Photo["kit_picurl"]; ?>" title="<?php echo $row_RecKitchen_Photo["kit_subject"]; ?>" rel="lightbox[g1]">
								<img src="photos/<?php echo $row_RecKitchen_Photo["kit_picurl"]; ?>" alt="<?php echo $row_RecKitchen_Photo["kit_subject"]; ?>" /></a>
						<?php } ?>
					</div>
					<hr style="border: 0; height: 1px; background: #333; background-image: linear-gradient(to right, #ccc, #333, #ccc);">
					<div class="kit_info">

						<div class="kit_location">
							<h3><strong><?php echo $row_RecKitchen["kit_title"]; ?></strong></h3>
							<p><img src="images/location.png"><?php echo $row_RecKitchen["kit_county"]; ?><?php echo $row_RecKitchen["kit_city"]; ?></p>
							<p><img src="images/owner.png"><?php echo $row_RecKitchen["m_username"]; ?></p>
						</div>

						<!-- <form name="form3" method="GET" action="1221產品修改.php"> -->
						<div class="kit_dese">
								<p><?php echo $row_RecKitchen["kit_dese"]; ?></p>
								<hr style="border: 0; height: 1px; background: #333; background-image: linear-gradient(to right, #ccc, #333, #ccc);">
								<p>開放日期　<?php echo $row_RecKitchen["kit_startdate"]; ?>~<?php echo $row_RecKitchen["kit_enddate"]; ?></p>
								<p>開放時間　<?php echo substr($row_RecKitchen["kit_starttime"], 0, -3); ?>~<?php echo substr($row_RecKitchen["kit_endtime"], 0, -3); ?></p>
								<p>容納人數　<?php echo $row_RecKitchen["kit_capacity"]; ?> 人</p>
								<p>價　　格　<?php echo $row_RecKitchen["kit_price"]; ?> 元</p>
								<p>清潔費用　<?php echo $row_RecKitchen["kit_cleanfee"]; ?> 元</p>
								<iframe width="100%" height="300" frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src="https://maps.google.com.tw/maps?f=q&hl=zh-TW&geocode=&q='<?php echo $row_RecKitchen["kit_county"]; ?><?php echo $row_RecKitchen["kit_city"]; ?>'&z=16&output=embed&t=">
								</iframe>
						</div>

						<div class="kit_ctl">
							<div class="reserve">
								<p>租借時間</p>
								<?php
								if (isset($_COOKIE["date"])) {
									echo "<table border='0'><tr><td bgcolor='#ECE1E1'>您所選擇的日期</td><td bgcolor='#F6F6F6'>";
									echo $_COOKIE["date"] . "</td></tr></table>";
									$_SESSION["date"] = $_COOKIE["date"];
								}
								if (isset($_COOKIE["time"])) {
									echo "<table border='0'><tr><td bgcolor='#ECE1E1'>您所選擇的時間</td><td bgcolor='#F6F6F6'>";
									echo $_COOKIE["time"] . "</td></tr></table>";
									$_SESSION["time"] = $_COOKIE["time"];
								}
								?>
								<?php
								echo "<select id='date' name='date' onchange='functionname(this.options[this.options.selectedIndex].value)'>";
								// 目前的select區塊
								echo "<option value=''>請選擇日期</option>";
								for ($i = 0; $i < 7; $i++) {
									$dat = date("Y-m-d", strtotime("+$i day"));
									echo "<option value='$dat'>$dat</option>";
								}
								echo "</select>";
								for ($i = 8; $i < 23; $i++) {
									$ii = str_pad($i, 2, '0', STR_PAD_LEFT);
									$j = str_pad($ii, 4, '0');
									$arr[] = $j;
								}
								// echo "<pre>" . print_r($arr, TRUE) . "</pre>";
								if (isset($_COOKIE["date"])) {
									echo "<br>";
									$_SESSION["date"] = $_COOKIE["date"];
									$check_date = $_SESSION["date"];
									// echo $_SESSION["date"];
									echo "<br>";
									$_SESSION["kit_id"] = $row_RecKitchen["kit_id"];
									$field_id = "schedule" . $row_RecKitchen["kit_id"];
									// echo $_SESSION["productid"];
									foreach ($arr as $a => $b) {
										$cmd[] = "select `$b` from `$field_id` where `date`='$check_date'";
									}
								}
								if (isset($cmd)) {
									echo "<select id='time' name='time' onchange='functiontime(this.options[this.options.selectedIndex].value)'>";
									echo "<option value=''>請選擇時間</option>";
									// 分解cmd array
									foreach ($cmd as $aa => $bb) {
										// 發出sql語句要求
										$res = $db_link->query($bb);
										// 取得陣列
										while ($response = $res->fetch_assoc()) {
											foreach ($response as $z => $x) {
												// 當時間表上沒有預約的話列出選項
												if ($x == null) {
													echo "<option value='$z'>$z</option>";
													// echo $z."<br>";
												}
											}
										}
									}
									echo "</select>";
								}
								?>
								<form name="form3" method="post" action="">
									<!-- <select id="time" name="time">
                                <?php
								for ($i = 8; $i < 23; $i++) {
									$ii = str_pad($i, 2, '0', STR_PAD_LEFT);
									$j = str_pad($ii, 4, '0');
									$arr[] = $j;
								}
								foreach ($arr as $a => $b) {
									echo "<option value='$b'>$b</option>";
								}
								?>
								</select> -->
								</form>

							</div>
							<div class="ctl">
								<form name="form5" method="post" action="">
									<input name="id" type="hidden" id="id" value="<?php echo $row_RecKitchen["kit_id"]; ?>">
									<input name="name" type="hidden" id="name" value="<?php echo $row_RecKitchen["kit_title"]; ?>">
									<input name="price" type="hidden" id="price" value="<?php echo $row_RecKitchen["kit_price"]; ?>">
									<input name="qty" type="hidden" id="qty" value="1">
									<input name="action" type="hidden" id="action" value="add">
									<input id="reserve" name="reserve" type="submit" value="預訂">
									<input type="button" name="reserve" id="reserve" value="回上一頁" onClick="window.history.back();">
								</form>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<footer class="id_footer">
			© 2019
		</footer>
	</div>
</body>

</html>
<?php
$stmt->close();
$db_link->close();
?>