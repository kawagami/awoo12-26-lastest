<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index4.css">
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
                <li><a href="about.html">我悶的起源</a></li>
                <li><a href="kitchen_index.php">看看環境</a></li>
                <li><a href="orderdetail.php">訂單查詢</a></li>
                <?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<li><a href="member_index.php">Sign in</a></li><br>
				<!--這段更改--><a href="member_admin.php"><?php }else{ echo "<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>";?><?php }?></a>
            </ul>
        </header>

        <div class="id_content">
<?php
include("connMysql.php");
$cmd = "SELECT `productid`,`productname` FROM `product`";
$res = $db_link->query($cmd);

while ($result = $res->fetch_assoc()) {
    $arr[]=array($result['productid']=>$result['productname']);
}
// echo "<pre>" . print_r($arr , TRUE) . "</pre>";

// 場地時間表arr
echo "<form action='manage_schedule_main.php' method='post' align='center'>";
echo "<select name='choice'>";
echo "<option value=''>選擇要修改的場地</option>";
foreach ($arr as $l2) {
    foreach($l2 as $a=>$b){
        echo "<option value='$a'>$a"."："."$b</option>";
    }
}
echo "</select>";
echo "<input type='submit' value='確認'>";
echo "</form>";



?>
</div>
</html>
<!-- 123 -->