<?php
include("connMysql.php");
include("function_list.php");
session_unset();
session_start();

// header("Cache-Control: no-store, no-cache, must-revalidate");
// header("Cache-Control: post-check=0, pre-check=0", false);
// header("Pragma: no-cache");

if (isset($_POST['choice'])) {
    $schedule = "schedule" . $_POST['choice'];
    $_SESSION['temp_schedule'] = "schedule" . $_POST['choice'];
    // echo $schedule;
} elseif (isset($_SESSION['temp_schedule'])) {
    $schedule = $_SESSION['temp_schedule'];
} else {
    echo "沒東西";
}

?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script language="javascript">
        window.opener.document.location.reload()
    </script>
    <!-- <script language="javascript">
        var strcookie = document.cookie;
        var arrcookie = strcookie.spit("=")
        var statuscookie = arrcookie[1];
        if (statuscookie == "" || statuscookie == "0") {
            //retset flag  
            document.cookie = "statuscookie=1";
        } else {
            window.location.reload();
            document.cookie = "statuscookie=0";
        }
    </script> -->

    <style type="text/css">
        a {
            text-decoration: none;
            color: blue;
        }

        a:visited {
            color: blue;
        }
    </style>
</head>

<!-- <body> -->

<body onload="opener.location.reload()">
    <!-- --------------上一頁按鈕--------------- -->
    <p align="center">
        <input type="button" onclick="history.back()" value="回到上一頁"></input>
    </p>
    <p align="center">
        <a href="manage_schedule_0.php">回選單</a>
    </p>
    <!-- <p align="center">
        <input type="button" onclick="location.href='D9_刪schedule表_重建隨機預約.php'" value="建立全新資料"></input>
        <input type="button" onclick="location.href='D9_不刪表_清除舊預約資料_新增隨機預約資料.php'" value="不刪表重洗資料"></input>
    </p>
    <p align="center">
        <input type="button" onclick="location.href='D3_schedule_select_number.php'" value="顯示會員ID"></input>
    </p> -->


    <table border="1" align="center">
        <?php
        echo "<tr><td align='center' colspan='6'>點選<font color='blue'>日期</font>可<font color='red'>關閉</font>一整天</td>";
        echo "<td align='center' colspan='5'>點選<font color='blue'>空位</font>可新增預約</td>";
        echo "<td align='center' colspan='5'>點選<font color='red'>會員ID</font>可刪除預約</td></tr>";
        echo "<tr><td align='center'>date</td>";
        $_SESSION['schedule_now'] = $schedule;
        foreach ($time_array as $item => $value) {
            if (strlen($value) == 6) {
                $new_str = substr($value, 1, 4);
                echo "<td align='center' width='50px'>$new_str</td>";
            } else {
                echo "<td align='center' width='50px'>$value</td>";
            }
        }
        echo "</tr>";

        // 可用版本----------------------
        for ($j = 0; $j < count($date_array); $j++) {

            echo "<tr><td>";
            $date_title = $date_array[$j];
            $bigjj = str_pad($j, 8, '0', STR_PAD_LEFT);
            // echo $bigjj;
            // echo $date_title;
            echo "<form id='_form$bigjj' method='post' action='manage_schedule_date_closeallday.php'>";
            echo "<input type='hidden' name='index' value='$date_title' />";
            echo "<a onclick='document.getElementById(\"_form$bigjj\").submit();'>$date_title</a>";
            echo '</form>';
            for ($i = 0; $i < count($time_array); $i++) {
                $date = $date_array[$j];
                $date_time = $time_array[$i];
                $sql_cmd = "SELECT * FROM `$schedule` WHERE `date` = '$date' and $date_time";
                $t = "$j" . "$i";
                $date_name = $t . "date";
                $date_time_name = $t . "date_time";
                $schedule_name = $t . "schedule";
                $_SESSION[$schedule_name] = $schedule;

                $_SESSION[$date_name] = "$date";
                $_SESSION[$date_time_name] = "$date_time";
                $res = $db_link->query($sql_cmd);
                $res_row = $res->fetch_row();
                if ($res_row == null) {

                    //希望能將字變成連結_按下去能預約按下的時間
        ?>
                    <td align='center'>
                        <form id='_form<?php echo $t; ?>' method='post' action='manage_schedule_add.php'>
                            <input type="hidden" name="index" value="<?php echo $t; ?>" />
                            <a onclick='document.getElementById("_form<?php echo $t; ?>").submit();'>空位</a>
                        </form>
                    </td>
                <?php
                } else {
                    $i2 = $i + 2;
                    //直接連接刪除預約頁面
                ?>
                    <td align='center'>
                        <form id='_form<?php echo $j . $i; ?>' method='post' action='manage_schedule_delete.php'>
                            <input type="hidden" name="index" value="<?php echo $t; ?>" />
                            <a onclick='document.getElementById("_form<?php echo $t; ?>").submit();'>
                                <!-- <font color='red'>已預約</font> -->
                                <!-- <font color='red'><?php echo "i=>" . $i . "__j=>" . $j; ?></font> -->
                                <!-- <font color='red'><?php echo $res_row[$i + 2]; ?></font> -->
                                <?php if ($res_row[$i2] == 999) {
                                    echo "<font color='red'>不開放</font>";
                                } else {
                                    echo "<font color='red'> $res_row[$i2]</font>";
                                } ?>
                                <!-- <font color='red'><?php echo "<pre>" . print_r($res_row, TRUE) . "</pre>"; ?></font> -->


                            </a>
                        </form>
                    </td>
        <?php
                    // // 按下已預約後進入刪除這個預約的頁面
                    // echo "<td align='center'><font color='red'>已預約</font></td>";
                }
            }
            echo "</td></tr>";
        }
        ?>
    </table>

    <?php
    ?>
</body>

</html>


<!-- 123 -->