<?php
include("include_list.php");
session_unset();
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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

<body>

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center">
        <input type="button" onclick="history.back()" value="回到上一頁"></input>
    </p>
    <p align="center">
        <a href="D0_main.php">回主選單</a>
    </p>
    <p align="center">
        <input type="button" onclick="location.href='D3_schedule_select_delete.php'" value="建立全新資料"></input>
        <input type="button" onclick="location.href='D3_schedule_select_clear.php'" value="不刪表重洗資料"></input>
    </p>
    <p align="center">
        <input type="button" onclick="location.href='D3_schedule_select_number.php'" value="顯示會員ID"></input>
    </p>


    <table border="1" align="center">
        <?php
        echo "<tr><td align='center' colspan='6'>未來30天預約狀況</td>";
        echo "<td align='center' colspan='5'>點選空位可預約時間</td>";
        echo "<td align='center' colspan='5'>點選已預約可刪除預約</td></tr>";
        echo "<tr><td align='center'>date</td>";
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
            echo $date_array[$j];
            for ($i = 0; $i < count($time_array); $i++) {
                $date = $date_array[$j];
                $date_time = $time_array[$i];
                $sql_cmd = "SELECT * FROM `schedule` WHERE `date` = '$date' and $date_time is null";
                $t = "$j" . "$i";
                $date_name = $t . "date";
                $date_time_name = $t . "date_time";

                $_SESSION[$date_name] = "$date";
                $_SESSION[$date_time_name] = "$date_time";
                $res = $db_link->query($sql_cmd);
                $res_row = $res->fetch_row();
                if (!$res_row == '') {

                    //希望能將字變成連結_按下去能預約按下的時間
                    ?>
                    <td align='center'>
                        <form id='_form<?php echo $t; ?>' method='post' action='D3_schedule_add3.php'>
                            <input type="hidden" name="index" value="<?php echo $t; ?>" />
                            <a onclick='document.getElementById("_form<?php echo $t; ?>").submit();'>空位</a>
                        </form>
                    </td>
                <?php
                        } else {
                            //直接連接刪除預約頁面
                            ?>
                    <td align='center'>
                        <form id='_form<?php echo $j . $i; ?>' method='post' action='D3_schedule_delete.php'>
                            <input type="hidden" name="index" value="<?php echo $t; ?>" />
                            <a onclick='document.getElementById("_form<?php echo $t; ?>").submit();'>
                                <font color='red'>已預約</font>
                            </a>
                        </form>
                    </td>
        <?php
                    // // 按下已預約後進入刪除這個預約的頁面
                    // echo "<td align='center'><font color='red'>已預約</font></td>";
                }
            }
            echo "</tr></td>";
        }
        ?>
    </table>
    <?php
    ?>
</body>

</html>