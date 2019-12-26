<?php 
require_once("connMysql.php");
if(isset($_POST["customername"]) && ($_POST["customername"]!="")){
	//購物車開始
	require_once("mycart.php");
	session_start();
	$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
	if(!is_object($cart)) $cart = new myCart();
	//購物車結束	
	//新增訂單資料
	$sql_query = "INSERT INTO orders (m_id, total ,customername ,customeremail ,customeraddress ,customerphone ,paytype) VALUES (";
    $sql_query .= "'".$_SESSION["m_id"]."',";
    $sql_query .= "'".$cart->total."',";
    $sql_query .= "'".$_POST["customername"]."',";
    $sql_query .= "'".$_POST["customeremail"]."',";
    $sql_query .= "'".$_POST["customeraddress"]."',";
    $sql_query .= "'".$_POST["customerphone"]."',";
	$sql_query .= "'".$_POST["paytype"]."')";
	mysqli_query($db_link, $sql_query);
	
	//$sql_query = "INSERT INTO orders (m_id, total ,customername ,customeremail ,customeraddress ,customerphone ,paytype) VALUES (?, ?, ?, ?, ?, ?, ?)";
	//$stmt = $db_link->prepare($sql_query);
	//$stmt->bind_param("iisssss",$_SESSION["m_id"], $cart->total, $_POST["customername"], $_POST["customeremail"], $_POST["customeraddress"], $_POST["customerphone"], $_POST["paytype"]);
	//$stmt->execute();
	//取得新增的訂單編號
	//$o_pid = $stmt->insert_id;
	//$stmt->close();
	//新增訂單內貨品資料
	if($cart->itemcount > 0) {
		foreach($cart->get_contents() as $item) {
			$sql_query="INSERT INTO orderdetail (orderid ,productid ,productname ,unitprice ,quantity) VALUES (?, ?, ?, ?, ?)";
			
			$stmt = $db_link->prepare($sql_query);
			$stmt->bind_param("iisii", $o_pid, $item['id'], $item['info'], $item['price'], $item['qty']);
			$stmt->execute();
			$stmt->close();
		}
	}
	//郵寄通知
	$cname = $_POST["customername"];
	$cmail = $_POST["customeremail"];
	$ctel = $_POST["customerphone"];
	$caddress = $_POST["customeraddress"];
	$cpaytype = $_POST["paytype"];
    $gdtotal = $cart->grandtotal;
    $gddeliverfee =  $cart->deliverfee;
    $total = $gdtotal - $gddeliverfee;
	$cart->empty_cart();
	
	
}	
?>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>廚房場地購物系統</title>
<script language="javascript">
alert("感謝您的租借，我們將儘快進行處理。");
</script>
<h1>租借明細表單</h1>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
    <tr>
		<td align="center" bgcolor="#ECE1E1">會員編號</td>
        <td align="center" bgcolor="#ECE1E1">姓名(租借人)</td>
        <td align="center" bgcolor="#ECE1E1">電子信箱</td>
        <td align="center" bgcolor="#ECE1E1">電話號碼</td>
        <td align="center" bgcolor="#ECE1E1">地址</td>
        <td align="center" bgcolor="#ECE1E1">付款方式</td>
        <td align="center" bgcolor="#ECE1E1">金額</td>
        <td align="center" bgcolor="#ECE1E1">廚房場地名稱</td>
        <td align="center" bgcolor="#ECE1E1">租借時間</td>
    </tr>
	
		<td align="center" bgcolor="#F6F6F6"><?php echo $_SESSION['m_id'];?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $cname;?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $cmail;?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $ctel;?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $caddress;?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $cpaytype;?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $total;?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $item['info'];?></td>
        <td align="center" bgcolor="#F6F6F6"><?php echo $_SESSION["date"];?><br><?php echo $_SESSION["time"];?></td>
</table>
<p>備註：租借時間0800是上午八點、2000是下午八點</p>

<input type="button" name="button" id="button" value="回到廚房場地出租系統" onClick="window.location.href='index2.php';">


	
	
	
	
    
    
    
    
