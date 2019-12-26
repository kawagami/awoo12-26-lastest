<?php
include("include_list.php");
// ----------------切換資料庫後顯示目前資料庫-----------
// $db_link->select_db("awoo");
// if($result=$db_link->query("SELECT DATABASE()")){
//     $row=$result->fetch_row();
//     printf("目前連接的資料庫是「%s」<br>", $row[0]);
//     $result->close();
// }

// echo date ('Y-m-d');
// $sql_cmd="INSERT INTO `schedule` (`date`) VALUES ('2019-11-24');"

// // -------在schedule新增日期列--------------
// for ($i = 1; $i <= 100; $i++) {
//     $d = strtotime("+$i days");
//     $tim = date('Y-m-d', $d);
//     $sql_cmd = "INSERT INTO `schedule` (`date`) VALUES ('$tim');";
//     $res = $db_link->query($sql_cmd);
// }

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