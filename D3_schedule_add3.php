<?php
include("connect_db_awoo.php");
session_start();
if (isset($_POST["action"]) && ($_POST["action"] == "add")) {
    echo $date;
    echo $date2;

    header("Location: D3_schedule_select.php");
}

function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <?php

    if (isset($_GET["value"])) {
        $date = $_GET["value"];
        $date2 = $_GET["value2"];
    }
    // 抓到select傳來的index後才執行
    if (isset($_POST["index"])) {
        $ind = $_POST["index"];
        $date_name = $ind . "date";
        $date_time_name = $ind . "date_time";
        if (isset($_SESSION[$date_name])) {
            $date = $_SESSION[$date_name];
            $date_time = $_SESSION[$date_time_name];
        } else {
            echo "session ind fail";
        }
    }
    if (isset($_GET["value"])) {
        $update_cmd = "UPDATE `schedule` SET `$date2`='會員ID' WHERE `date` = '$date'";
    }

    ?>

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>

    <?php

    if (isset($_GET["value"])) {
        $_SESSION['value'] = $_GET["value"];
        $_SESSION['value2'] = $_GET["value2"];
    } elseif (isset($_POST["index"])) {
        $ind = $_POST["index"];
        $date_name = $ind . "date";
        $date_time_name = $ind . "date_time";
        if (isset($_SESSION[$date_name])) {
            $a = $_SESSION[$date_name];
            $_SESSION['value']="'$a'";
            $b = $_SESSION[$date_time_name];
            $_SESSION['value2']=$b;
        } else {
            echo "session ind fail";
        }
    }

    ?>

    <form action="D3_schedule_add4.php" method="get" align="center">
        　輸入會員ID：<input type="add" name="value3">
        　<input type="submit" value="預約">
    </form>


    <script type="text/javascript">
        var curTarget = document.getElementById("mylist");
        var change = function(target) {
            var v1 = "<?= $date; ?>";
            var v2 = "<?= $date2; ?>";
            location.href = "D3_schedule_add4.php?value=" + v1 + "&value2=" + v2 + "&value3=" + target.text;
        }
    </script>

</body>

</html>