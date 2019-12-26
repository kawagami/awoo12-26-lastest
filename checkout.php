<?php
require_once("connMysql.php");
//購物車開始
include("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
//購物車結束
//繫結產品目錄資料
$query_RecCategory = "SELECT citydata.c_id, citydata.c_name, count(product.productid) as productNum FROM citydata LEFT JOIN product ON citydata.c_id = product.c_id GROUP BY citydata.c_id, citydata.c_name ORDER BY citydata.c_id ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid) as totalNum FROM product";
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
<script language="javascript">
function checkForm(){	
	if(document.cartform.customername.value==""){
		alert("請填寫姓名!");
		document.cartform.customername.focus();
		return false;
	}
	if(document.cartform.customeremail.value==""){
		alert("請填寫電子郵件!");
		document.cartform.customeremail.focus();
		return false;
	}
	if(!checkmail(document.cartform.customeremail)){
		document.cartform.customeremail.focus();
		return false;
	}	
	if(document.cartform.customerphone.value==""){
		alert("請填寫電話!");
		document.cartform.customerphone.focus();
		return false;
	}
	if(document.cartform.customeraddress.value==""){
		alert("請填寫地址!");
		document.cartform.customeraddress.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
	return false;
}
</script>
<style>

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
<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="80" align="center" background="" class=""></td>
  </tr>
  <tr>
          <div class="subjectDiv"><span class="heading">購物結帳</div>
            <div class="normalDiv">
              <?php if($cart->itemcount > 0) {?>
              <p class="heading">購物內容</p>
              <table width="90%" border="0" align="left" cellpadding="2" cellspacing="1">
                <tr>
                  <th bgcolor="#ECE1E1"><p align="center">編號</p></th>
                  <th bgcolor="#ECE1E1"><p align="center">產品名稱</p></th>
                  <th bgcolor="#ECE1E1"><p align="center">單價</p></th>
                  <th bgcolor="#ECE1E1"><p align="center">小計</p></th>
                </tr>
                <?php		  
		  	$i=0;
			foreach($cart->get_contents() as $item) {
			$i++;
		  ?>
                <tr>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p><?php echo $i;?>.</p></td>
                  <td bgcolor="#F6F6F6" class="tdbline"><p><?php echo $item['info'];?></p></td>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p>$ <?php echo number_format($item['price']);?></p></td>
                  <td align="center" bgcolor="#F6F6F6" class="tdbline"><p>$ <?php echo number_format($item['subtotal']);?></p></td>
                </tr>
                <?php }?>

                <tr>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>總計</p></td>
                  <td valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p>&nbsp;</p></td>
                  <td align="center" valign="baseline" bgcolor="#F6F6F6"><p class="redword">$ <?php echo number_format($cart->grandtotal);?></p></td>
                </tr>
              </table><br></br>
              <br>
              <br>
              <br>
              <br>
              <p class="heading" padding-right="10px">租借時間</p>
              <p>
              <?php
                echo "<table cellpadding='10px' width='40%' border='0' align='left' padding='10px'><tr><td bgcolor='#ECE1E1' align='center'>日期</td><td bgcolor='#ECE1E1' align='center'>時間</td></tr><tr>";
                echo "<td bgcolor='#F6F6F6' align='center'>".$_SESSION["date"]."</td><td bgcolor='#F6F6F6' align='center'>".$_SESSION["time"]."</td>";
                echo "</tr></table>";
              ?></p>
              <hr width="100%" size="1" />
              <p class="heading">客戶資訊</p>
              <form action="cartreport.php" method="post" name="cartform" id="cartform" onSubmit="return checkForm();">
                <table width="90%" border="0" align="left" cellpadding="4" cellspacing="1">
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>姓名</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input type="text" name="customername" id="customername">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>電子郵件</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input type="text" name="customeremail" id="customeremail">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>電話</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input type="text" name="customerphone" id="customerphone">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>住址</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <input name="customeraddress" type="text" id="customeraddress" size="40">
                        <font color="#FF0000">*</font></p></td>
                  </tr>
                  <tr>
                    <th width="20%" bgcolor="#ECE1E1"><p>付款方式</p></th>
                    <td bgcolor="#F6F6F6"><p>
                        <select name="paytype" id="paytype">
                          <option value="ATM匯款" selected>ATM匯款</option>
                          <option value="線上刷卡">線上刷卡</option>
                          <option value="貨到付款">貨到付款</option>
                        </select>
                      </p></td>
                  </tr>
                  <tr>
                    <td colspan="2" bgcolor="#F6F6F6"><p><font color="#FF0000">*</font>表示為必填的欄位</p></td>
                  </tr>
                </table>
                <hr width="100%" size="1" />
                <p align="center">
                  <input name="cartaction" type="hidden" id="cartaction" value="update">
                  <input type="submit" class="button button1" name="updatebtn" id="button3" value="送出訂購單">
                  <input type="button" class="button button1" name="backbtn" id="button4" value="回上一頁" onClick="window.history.back();">
                  <input type="button" class="button button1" name="button5" id="button5" value="回到廚房場地出租系統" onClick="window.location.href='cartindex.php';">
                </p>
              </form>
            </div>
            <?php }else{ ?>
            <div class="infoDiv">目前購物車是空的。</div>
            <?php } ?></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</div>
<footer class="id_footer">
      Footer Block
</footer>
</div>
</html>
<?php $db_link->close();?>