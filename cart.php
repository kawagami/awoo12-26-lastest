<?php
require_once("connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
// 更新購物車內容
if(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="update")){
	if(isset($_POST["updateid"])){
		$i=count($_POST["updateid"]);
		for($j=0;$j<$i;$j++){
			$cart->edit_item($_POST['updateid'][$j],$_POST['qty'][$j]);
		}
	}
	header("Location: cart.php");
}
// 移除購物車內容
if(isset($_GET["cartaction"]) && ($_GET["cartaction"]=="remove")){
	$rid = intval($_GET['delid']);
	$cart->del_item($rid);
	header("Location: cart.php");	
}
// 清空購物車內容
if(isset($_GET["cartaction"]) && ($_GET["cartaction"]=="empty")){
	$cart->empty_cart();
	header("Location: cart.php");
}
if(isset($_GET["cartaction"]) && ($_GET["cartaction"]=="back")){
	header("Location: kitchen_index.php");
}
//購物車結束
//繫結產品目錄資料
$query_RecCategory = "SELECT citydata.c_id, citydata.c_name, count(kitchen_data.kit_id) as productNum FROM citydata LEFT JOIN kitchen_data ON citydata.c_name = kitchen_data.kit_county GROUP BY citydata.c_id, citydata.c_name ORDER BY citydata.c_id ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(kit_id)as totalNum FROM kitchen_data";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
?>

<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>廚房場地購物系統</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>		
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
		
        <td class="tdbline">
        <table align="center" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
         <td><div class="subjectDiv"> <span class="heading"><p align="left">租借廚房場地資料：</p></div>
          <div class="normalDiv">
		  <?php if($cart->itemcount > 0) {?>
          <form action="" method="post" name="cartform" id="cartform">
          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
              <tr>
                <th bgcolor="#ECE1E1"><p align="center">刪除</p></th>
                <th bgcolor="#ECE1E1"><p align="center">廚房場地名稱</p></th>
                
                <th bgcolor="#ECE1E1"><p align="center">單價</p></th>
                <th bgcolor="#ECE1E1"><p align="center">小計</p></th>
              </tr>
          <?php	foreach($cart->get_contents() as $item) { ?>              
              <tr>
                <td align="center" bgcolor="#F6F6F6" class="tdbline"><p><a href="?cartaction=remove&delid=<?php echo $item['id'];?>">移除</a></p></td>
                <td align="center" bgcolor="#F6F6F6" class="tdbline"><p><?php echo $item['info'];?></p></td>  
                <td align="center" bgcolor="#F6F6F6" class="tdbline"><p>$ <?php echo number_format($item['price']);?></p></td>
                <td align="center" bgcolor="#F6F6F6" class="tdbline"><p>$ <?php echo number_format($item['subtotal']);?></p></td>
              </tr>
          <?php }?>
              <tr>
                <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>總計</p></td>
                <td valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                <td align="center" valign="baseline" bgcolor="#F6F6F6"><p class="redword">$ <?php echo number_format($cart->total);?></p></td>
              </tr>          
            </table><br>
            <p>租借時間：</p><br>
            <table width="100%" border="0" align="center">
                <tr align="center">
                <td bgcolor="#ECE1E1">日期</td>
                <td bgcolor="#F6F6F6" align="center"><?php
                  echo $_SESSION['date'];
                  $date_add = $_SESSION['date']; 
                  echo "</td><td>";
                  // echo $_SESSION['time'];
                    ?></td>
                      </tr>
                      <tr>
                      <td bgcolor="#ECE1E1" align="center">時間</td><td align="center" bgcolor="#F6F6F6">
                      <?php
                      echo $_SESSION['time'];
                      // $item['id']是場地的primarykey
                      // $_SESSION['m_id']是會員ID
                      // echo $_SESSION['time'];
                      $time_add = $_SESSION['time'];
                      $memberid = $_SESSION['m_id'];
                      $schedule_name = "schedule" . $item['id'];
                      $sql_cmd = "UPDATE `$schedule_name` SET `$time_add`='$memberid' WHERE `date` = '$date_add'";
                      // echo $sql_cmd;
                      $_SESSION['sql_cmd'] = $sql_cmd;
                      // echo $_SESSION['sql_cmd'];
                      ?></td>       
                        </tr>
                        </table>
                      <div class="" align="center">
                <!-- <?php
                      if(isset($_POST['time'])){

                        echo $_POST['time'];

                      }else{
                          
                        echo '時間<select id="time" name="time">';
                        for ($i = 8; $i < 23; $i++) {
                        $ii = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $j = str_pad($ii, 4, '0');
                        $arr[] = $j;
                        }
                        foreach ($arr as $a => $b) {
                        echo "<option value='$b'>$b</option>";
                        }
                        }
                      ?>
                        </select>
                        <button type="submit">選擇時間</button> -->
                        </div>
                        <br>
            <p align="center">
              <input name="cartaction" type="hidden" id="cartaction" value="update">
              <input type="submit" class="button button1" name="updatebtn" id="button3" value="更新購物車">
              <input type="button" class="button button1" name="emptybtn" id="button5" value="清空購物車" onClick="window.location.href='?cartaction=empty'">
              <input type="button" class="button button1" name="button" id="button6" value="前往結帳" onClick="window.location.href='checkout.php';">
              </p>
          </form>
          </div>          
            <?php }else{ ?>
            <div class="infoDiv">目前購物車是空的。</div>
            <input type="button" name="button10" id="button10" value="回上一頁" onClick="window.location.href='?cartaction=back'">                            
          <?php } ?></td>
        </tr>
    </table></td>
    </div>
    </div>
		<footer class="id_footer">
      Footer Block
    </footer>
	</body>
</html>