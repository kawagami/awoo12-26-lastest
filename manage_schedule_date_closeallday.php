<?php
include("connMysql.php");
include("function_list.php");
session_unset();
session_start();


if (isset($_POST['index'])) {
    // echo $_POST['index'];
    $tar_date = $_POST['index'];
} else {
    echo "沒東西";
}
$tar_field = $_SESSION['schedule_now'];

?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

</head>

<body>
    <p align="center">
        <input type="button" onclick="history.back()" value="回到上一頁"></input>
    </p>
    <p align="center">
        <a href="manage_schedule_0.php">回選單</a>
    </p>


    <?php

    // -----------------------取得時間arr--------------------
    $tar_date;
    for ($i = 8; $i < 23; $i++) {
        $time1[] = str_pad($i, 2, '0', STR_PAD_LEFT);
    }
    foreach ($time1 as $a => $b) {
        $time2[] = str_pad($b, 4, '0');
    }
    // echo "<pre>" . print_r($time2, TRUE) . "</pre>";
    // -----------------------------------------------------

    // 將$tar_date那一天全部設定成999
    foreach ($time2 as $a => $b) {
        $cmd = "update `$tar_field` set `$b`=999 where `date`='$tar_date'";
        // echo $cmd."<br>";
        $db_link->query($cmd);
    }
    header("Location: manage_schedule_main.php");


    ?>

</body>

</html>


<!-- 123 -->