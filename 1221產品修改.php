<?php
require_once("connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
// 新增購物車內容
if(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="add")){
	$cart->add_item($_POST['id'],$_POST['qty'],$_POST['price'],$_POST['name']);
	header("Location: cart1221.php");
}
//購物車結束
//繫結產品資料
$query_RecProduct = "SELECT kitchen_data.kit_id,kitchen_data.kit_county, kitchen_data.kit_title,kitchen_data.kit_price, kitchen_photo.kit_picurl,kitchen_data.kit_dese,citydata.c_name,kitchen_data.kit_capacity,kitchen_data.kit_city,kitchen_data.kit_add FROM kitchen_data JOIN (citydata,kitchen_photo) on kitchen_data.kit_county=citydata.c_name AND kitchen_data.kit_id=kitchen_photo.kit_id WHERE kitchen_data.kit_id=?";
$stmt = $db_link->prepare($query_RecProduct);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$RecProduct = $stmt->get_result();
$row_RecProduct = $RecProduct->fetch_assoc();

//繫結產品目錄資料
$query_RecCategory = "SELECT citydata.c_id, citydata.c_name, count(kitchen_data.kit_id) as productNum FROM citydata LEFT JOIN kitchen_data ON citydata.c_name = kitchen_data.kit_county GROUP BY citydata.c_id, citydata.c_name ORDER BY citydata.c_id ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(kit_id) as totalNum FROM kitchen_data";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
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
                    <table width="780" align="center" border="0" align="center" cellpadding="4" cellspacing="0"
                        bgcolor="#FFFFFF">
                        <tr>
                            <td height="80" align="center" background="" class=""></td>
                        </tr>
                        <tr>
                            <td class="tdbline">
                                <table width="100%" border="0" cellspacing="0" cellpadding="10">
                                    <tr valign="top">
                                        <td width="200" class="tdrline">
                                            <div class="boxtl"></div>
                                            <div class="boxtr"></div>
                                            <div class="categorybox">
                                                <p class="heading"><img src="images/16-cube-green.png" width="16"
                                                        height="16" align="absmiddle"> 廚房場地搜尋 <span
                                                        class="smalltext"></span></p>
                                                <form name="form1" method="get" action="cartindex.php">
                                                    <p>
                                                        <input name="keyword" type="text" id="keyword" value="請輸入關鍵字"
                                                            size="12" onClick="this.value='';">
                                                        <input type="submit" id="button" value="查詢">
                                                    </p>
                                                </form>
                                                <p class="heading"><img src="images/16-cube-green.png" width="16"
                                                        height="16" align="absmiddle"> 價格區間 <span
                                                        class="smalltext"></span></p>
                                                <form action="cartindex.php" method="get" name="form2" id="form2">
                                                    <p>
                                                        <input name="price1" type="text" id="price1" value="0" size="3">
                                                        -
                                                        <input name="price2" type="text" id="price2" value="0" size="3">
                                                        <input type="submit" id="button2" value="查詢">
                                                    </p>
                                                </form>
                                            </div>
                                            <div class="boxbl"></div>
                                            <div class="boxbr"></div>
                                            <hr width="100%" size="1" />
                                            <div class="boxtl"></div>
                                            <div class="boxtr"></div>
                                            <div class="boxbl"></div>
                                            <div class="boxbr"></div>
                                        <div class="categorybox">
                                        <p class="heading"><img src="images/16-cube-orange.png" width="16" height="16" align="absmiddle"> 廚房縣市分類 <span class="smalltext"></span></p>
                                        <ul>
                                        <li><a href="cartindex.php">所有廚房<span class="citydatacount">(<?php echo $row_RecTotal["totalNum"];?>)</span></a></li>
                                        <?php	while($row_RecCategory=$RecCategory->fetch_assoc()){ ?>
                                        <li><a href="cartindex.php?cid=<?php echo $row_RecCategory["c_id"];?>"><?php echo $row_RecCategory["c_name"];?> <span class="citydatacount">(<?php echo $row_RecCategory["productNum"];?>)</span></a></li>
                                        <?php }?>
                                        </ul>
                                        </div>
                                        </td>
                                        <td>
                                            <div class="subjectDiv"> <span class="heading"><img
                                                        src="images/16-cube-orange.png" width="16" height="16"
                                                        align="absmiddle"></span> 廚房場地資料</div>
                                            <div class="actionDiv"><a href="cart.php">我的購物車</a></div>
                                            <div class="albumDiv">
                                                <div class="picDiv">
                                                    <?php if($row_RecProduct["kit_picurl"]==""){?>
                                                    <img src="images/nopic.png" alt="暫無圖片" width="120" height="120"
                                                        border="0" />
                                                    <?php }else{?>
                                                    <img src="photos/<?php echo $row_RecProduct["kit_picurl"];?>"
                                                        alt="<?php echo $row_RecProduct["kit_title"];?>" width="135"
                                                        height="135" border="0" />
                                                    <?php }?>
                                                </div>
                                                <div class="albuminfo"><span class="smalltext">特價</span>
                                                <span class="redword"><?php echo $row_RecProduct["kit_price"];?></span>
                                                <span class="smalltext"> 元</span> </div>
                                            </div>
                                            <div class="titleDiv">
                                            <span class="smalltext">廚房場地名稱：<?php echo $row_RecProduct["kit_title"];?></span></div>
                                            <div class="dataDiv">
                                            <p><span class="smalltext">人數上限：<?php echo nl2br($row_RecProduct["kit_capacity"]);?></span></p>
                                                <p><span class="smalltext">廚房地址：<?php echo nl2br($row_RecProduct["kit_county"]);?><?php echo nl2br($row_RecProduct["kit_city"]);?><?php echo nl2br($row_RecProduct["kit_add"]);?></span></p>    
                                            <p><span class="smalltext">廚房場地簡介：<br><?php echo nl2br($row_RecProduct["kit_dese"]);?></span></p>
                                            <p><span class="smalltext">地圖：</span></p>
                                            <iframe width='100%' height='100%' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src="https://maps.google.com.tw/maps?f=q&hl=zh-TW&geocode=&q=高雄市'<?php echo $row_RecProduct["kit_city"];?><?php echo $row_RecProduct["kit_add"];?>'&z=16&output=embed&t=">
                                                </iframe>
                                                <hr width="100%" size="1" />
                                                <hr width="100%" size="1" />
                                                <div>
                                                <p>租借時間：</p>
                                                <?php
                                                    if (isset($_COOKIE["date"])) {
                                                        echo "<table border='0'><tr><td bgcolor='#ECE1E1'>您所選擇的日期</td><td bgcolor='#F6F6F6'>";
                                                        echo $_COOKIE["date"] . "</td></tr></table>";
                                                        $_SESSION["date"]=$_COOKIE["date"];
                                                    }
                                                    if (isset($_COOKIE["time"])) {
                                                        echo "<table border='0'><tr><td bgcolor='#ECE1E1'>您所選擇的時間</td><td bgcolor='#F6F6F6'>";
                                                        echo $_COOKIE["time"] . "</td></tr></table>";
                                                        $_SESSION["time"]=$_COOKIE["time"];
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
                                                        $_SESSION["kit_id"] = $row_RecProduct["kit_id"];
                                                        $field_id = "schedule" . $row_RecProduct["kit_id"];
                                                        // echo $_SESSION["productid"];
                                                        foreach ($arr as $a => $b) {
                                                            $cmd[] = "select `$b` from `$field_id` where `date`='$check_date'";
                                                        }
                                                    }
                                                    if(isset($cmd)){
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
                                                <form name="form3" method="post" action="">
                                                    <input name="id" type="hidden" id="id"
                                                        value="<?php echo $row_RecProduct["kit_id"];?>">
                                                    <input name="name" type="hidden" id="name"
                                                        value="<?php echo $row_RecProduct["kit_title"];?>">
                                                    <input name="price" type="hidden" id="price"
                                                        value="<?php echo $row_RecProduct["kit_price"];?>">
                                                    <input name="qty" type="hidden" id="qty" value="1">
                                                    <input name="cartaction" type="hidden" id="cartaction" value="add">
                                                    <input type="submit" name="button3" id="button3" value="加入購物車">
                                                    <input type="button" name="button4" id="button4" value="回上一頁"
                                                        onClick="window.history.back();">
                                                    <input type="button" name="button5" id="button5" value="回到廚房場地出租系統" onClick="window.location.href='cartindex.php';">
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
              </div>
            </div>
          <footer class="id_footer">
              Footer Block
          </footer>
</body>
</html>
<?php
$stmt->close();
$db_link->close();
?>

