<?php
include("connMysql.php");
include("function_list.php");
session_unset();
session_start();

if (isset($_POST['add'])) {
    header("Location: manage_schedule_main.php");
}

?>
<!DOCTYPE html>
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
                <li><a href="about.html">我悶的起源</a></li>
                <li><a href="kitchen_index.php">看看環境</a></li>
                <li><a href="orderdetail.php">訂單查詢</a></li>
                <?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<li><a href="member_index.php">Sign in</a></li><br>
				<!--這段更改--><a href="member_admin.php"><?php }else{ echo "<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>";?><?php }?></a>
            </ul>
        </header>
<body>
    <p align="center">
        <input type="button" onclick="history.back()" value="回到上一頁"></input>
    </p>
    <p align="center">
        <a href="manage_schedule_0.php">回選單</a>
    </p>


    <?php

    // 取得日期、時間、場地table名
    $date_name = $_POST['index'] . "date";
    $date_time_name = $_POST['index'] . "date_time";
    $schedule_name = $_POST['index'] . "schedule";
    $tar_date = $_SESSION[$date_name];
    if (strlen($_SESSION[$date_time_name]) == 6) {
        $tar_time = substr($_SESSION[$date_time_name], 1, 4);
    }
    $tar_schedule = $_SESSION[$schedule_name];
    // -------------------------------------------------------

    $cmd = "SELECT `m_id`,`m_name` FROM `memberdata`";
    $res = $db_link->query($cmd);
    echo "<form align='center'  method=\"POST\" action=\"\">";
    echo "<select name='add'>";
    echo "<option>選擇要預約的會員</option>";
    while ($result = $res->fetch_assoc()) {
        // echo $result['m_id'] . "=>" . $result['m_name'] . "<br>";
        // echo "<hr>";
        $a = $result['m_id'];
        $b = $result['m_name'];
        echo "<option value='$a'>姓名：$b</option>";
    }
    echo "</select>";
    echo "<input type=\"submit\" value=\"確定\"/>";
    echo "</form>";
    // echo "<pre>" . print_r($result, TRUE) . "</pre>";

    // // 日期
    // echo $tar_date;
    // echo "<br>";
    // // 時間
    // echo $tar_time;
    // echo "<br>";
    // // 場地名
    // echo $tar_schedule;
    // echo "<br>";
    // echo "update `$tar_schedule` set `$tar_time`=換成會員ID where `date`='$tar_date'";


    ?>

</body>

</html>
<!-- 123 -->