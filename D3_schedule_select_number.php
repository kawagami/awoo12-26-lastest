<?php
include("include_list.php");
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
        <input type="button" onclick="location.href='D9_刪schedule表_重建隨機預約.php'" value="建立全新資料"></input>
        <input type="button" onclick="location.href='D9_不刪表_清除舊預約資料_新增隨機預約資料.php'" value="不刪表重洗資料"></input>
    </p>
    <p align="center">
        <input type="button" onclick="location.href='D3_schedule_select.php'" value="顯示簡表"></input>
    </p>



    <table border="1" align="center">
        <?php
        echo "<tr><td align='center' colspan='16'>未來30天預約狀況</td></tr>";
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

        // ------------列出欄位內容-------------
        $sql_cmd = "SELECT * FROM `schedule`";
        $res = $db_link->query($sql_cmd);
        $total_fields = $res->field_count;
        $ttt = 0;
        while ($row_res = $res->fetch_assoc()) {
            echo "<tr>";
            for ($i = 1; $i < $total_fields; $i++) {
                if ($row_res[mysqli_field_name($res, $i)]) {
                    if ($i == 1) {
                        echo "<td align='center'><font color='black'>" . $row_res[mysqli_field_name($res, $i)] . "</font></td>";
                    } else {
                        echo "<td align='center'><font color='red'>" . $row_res[mysqli_field_name($res, $i)] . "</font></td>";
                    }
                } else {
                    echo "<td align='center'><font color='black'>空位</font></td>";
                }
            }
            echo "</tr>";
            $ttt++;
            if ($ttt > 29) {
                break;
            }
        }

        // // 可用版本----------------------
        // for ($j = 0; $j < count($date_array); $j++) {

        //     echo "<tr><td>";
        //     echo $date_array[$j];
        //     for ($i = 0; $i < count($time_array); $i++) {
        //         $date = $date_array[$j];
        //         $date_time = $time_array[$i];
        //         $sql_cmd = "SELECT * FROM `schedule` WHERE `date` = '$date' and $date_time is not null";
        //         $t = $j . $i;
        //         $date_name = $t . "date";
        //         $date_time_name = $t . "date_time";

        //         $_SESSION[$date_name] = "$date";
        //         $_SESSION[$date_time_name] = "$date_time";
        //         $res = $db_link->query($sql_cmd);
        //         $res_row = $res->fetch_row();
        //         $res_assoc = $res->fetch_assoc();
        //         if ($res_row == '') {

        //             //希望能將字變成連結_按下去能預約按下的時間

        //             echo "<td align='center'><font color='blue'>空位</font></td>";
        //         } else {
        //             //直接連接刪除預約頁面
        //             // echo print_r($res_row);
        //             // echo "<td align='center'><font color='red'>$res_assoc </font></td>";
        //             // // 按下已預約後進入刪除這個預約的頁面
        //             echo "<td align='center'><font color='red'>已預約</font></td>";
        //         }
        //     }
        //     echo "</tr></td>";
        // }
        ?>
    </table>
    <?php
    ?>
</body>

</html>