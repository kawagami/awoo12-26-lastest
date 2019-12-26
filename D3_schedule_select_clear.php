<?php
include("include_list.php");

// D9_不刪表_清除舊預約資料_新增隨機預約資料

// ----刪除目前預約記錄---------------
$del_cmd = "DELETE FROM `schedule`";
$res_del=$db_link->query($del_cmd);
// 隨機新增預約資料
$time_list = array("`0800`", "`0900`", "`1000`", "`1100`", "`1200`", "`1300`", "`1400`", "`1500`", "`1600`", "`1700`", "`1800`", "`1900`", "`2000`", "`2100`", "`2200`");
for ($i = 0; $i < 100; $i++) {
    $re = rand(0, 100) . "<br>";
    $d = strtotime("+$i days");
    $tim = date('Y-m-d', $d);
    if ($re < 10) {
        // echo "<font color='red'>null</font><br>";
        $sql_cmd = "INSERT INTO `schedule` (`date`) VALUES ('$tim');";
        // echo $sql_cmd;
        $res = $db_link->query($sql_cmd);
    } elseif (9 < $re && $re < 101) {
        $ti_sta = $time_list[array_rand($time_list, 1)];
        $rand_member=rand(2,13);
        $sql_cmd2 = "INSERT INTO `schedule` (`date`,$ti_sta) VALUES ('$tim',$rand_member);";
        // echo $sql_cmd2;
        $res = $db_link->query($sql_cmd2);
    }
}
header("Location: D3_schedule_select.php");

// ----------------切換資料庫後顯示目前資料庫-----------
// $db_link->select_db("awoo");
// if($result=$db_link->query("SELECT DATABASE()")){
//     $row=$result->fetch_row();
//     printf("目前連接的資料庫是「%s」<br>", $row[0]);
//     $result->close();
// }

// echo date ('Y-m-d');
// $sql_cmd="INSERT INTO `schedule` (`date`) VALUES ('2019-11-24');"


// // ----刪除目前預約記錄---------------
// $del_cmd = "DELETE FROM `schedule`";
// $res_del=$db_link->query($del_cmd);

// // -------在schedule新增日期列--從今天往後加100天------------
// for ($i = 0; $i < 100; $i++) {
//     $d = strtotime("+$i days");
//     $tim = date('Y-m-d', $d);
//     $sql_cmd = "INSERT INTO `schedule` (`date`) VALUES ('$tim');";
//     $res = $db_link->query($sql_cmd);
// }
// "INSERT INTO `schedule`(`date`, `0800`, `0900`, `1000`, `1100`, `1200`, `1300`, `1400`, `1500`, `1600`, `1700`, `1800`, `1900`, `2000`, `2100`, `2200`) 
//                 VALUES ([value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14],[value-15],[value-16],[value-17])"

// $d = strtotime("+99 days");
// $tim = date('Y-m-d', $d);
// echo $tim;

// $sql_cmd = "SELECT * FROM `schedule`";
// $res = $db_link->query($sql_cmd);

// $sql_cmd2 = "SELECT count(`date`) FROM `schedule`";
// $res2 = $db_link->query($sql_cmd2);
// echo $res2;


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

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>
    <!-- <table border="1" align="center">

        <?php


        // $d=strtotime("+1 days");
        // echo date ('Y-m-d', $d)."<br>";


        // $res="物件"->query("sql指令")
        // ----計算欄位數--------
        $total_fields = $res->field_count;
        $extra_field = $total_fields + 1;

        if ($result = $db_link->query("SELECT DATABASE()")) {
            $row = $result->fetch_row();
            echo "<tr><td align='center' colspan=$total_fields>";
            printf("目前連接的資料庫是「%s」<br>", $row[0]);
            echo "</tr></td>";
            $result->close();
        }

        echo "<tr><td align='center' colspan=$total_fields>欄位數量：" . $total_fields . "</tr></td>";

        // mysqli_field_name($res物件變數,第x-1欄位) 回傳 第x欄位名稱
        // echo mysqli_field_name($res,0);
        // $row = $res2->fetch_array();
        // echo $row[0];

        // -----依照欄位的數量echo 所有的欄位名稱--------
        // for($i=0;$i<$total_fields;$i++){
        //     echo mysqli_field_name($res,$i)."<br>";    
        // }

        // ------------列出欄位標題-------------
        echo "<tr align='center'>";
        for ($i = 0; $i < $total_fields; $i++) {
            echo "<td>" . mysqli_field_name($res, $i) . "</td>";
        }
        echo "</tr>";
        // ------------列出欄位內容-------------
        while ($row_res = $res->fetch_assoc()) {
            echo "<tr>";
            for ($i = 0; $i < $total_fields; $i++) {
                echo "<td>" . $row_res[mysqli_field_name($res, $i)] . "</td>";
            }
            echo "</tr>";
        }

        ?>
    </table> -->
</body>

</html>