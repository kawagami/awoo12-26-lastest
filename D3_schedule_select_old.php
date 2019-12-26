<?php
include("connect_db_awoo.php");
include("function_list.php");
// ----------------切換資料庫後顯示目前資料庫-----------
// $db_link->select_db("awoo");
// if($result=$db_link->query("SELECT DATABASE()")){
//     $row=$result->fetch_row();
//     printf("目前連接的資料庫是「%s」<br>", $row[0]);
//     $result->close();
// }

$sql_cmd = "SELECT * FROM `schedule`";

// -----------顯示欄位full資料的指令------------
// $sql_cmd="SHOW FULL COLUMNS FROM `students`";

// ----------顯示欄位數量的sql指令-------------------
// $sql_col_num="SELECT count(*) FROM information_schema.columns WHERE table_schema='z_traning' && table_name='students'";

$res = $db_link->query($sql_cmd);

// // -----------取得欄位名稱--的函數-------------
// function mysqli_field_name($result, $field_offset){
//     $properties = mysqli_fetch_field_direct($result, $field_offset);
//     return is_object($properties) ? $properties->name : null;
// }

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
    <table border="1" align="center">

        <?php
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
        $time_fields = $total_fields - 2;
        echo "<tr><td align='center' colspan=$total_fields>時段有：" . $time_fields . " 個</tr></td>";

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
            echo "<td align='center'>" . mysqli_field_name($res, $i) . "</td>";
        }
        echo "</tr>";
        // ------------列出欄位內容-------------
        while ($row_res = $res->fetch_assoc()) {
            echo "<tr>";
            for ($i = 0; $i < $total_fields; $i++) {
                echo "<td align='center'>" . $row_res[mysqli_field_name($res, $i)] . "</td>";
            }
            echo "</tr>";
        }

        ?>
    </table>
</body>

</html>