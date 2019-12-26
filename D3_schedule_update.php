<?php
include("include_list.php");

$today = date('Y-m-d');

$sql_cmd = "SELECT `date` FROM `schedule`";
$res = $db_link->query($sql_cmd);
// $sql_cmd_="SELECT `date` FROM `schedule` WHERE `date`>=$today";
$res_ = $db_link->query($sql_cmd);

$sql_cmd2 = "SELECT count(`date`) FROM `schedule`";
$res2 = $db_link->query($sql_cmd2);

$date_num = $res2->fetch_row();
$date_num = $date_num[0];


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


</body>

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

    echo "<tr><td align='center' colspan=$total_fields>請選擇日期</tr></td>";

    ?>

</table>


<p align="center">
    <select id="mylist" name="mylist">
        <?php

        // #-------------將日期列成選項-------------------
        // while ($date_data = $res_->fetch_row()) {
        //     $a = $date_data[0];
        //     echo "<option value='$a'>$a</option>";
        // }
        
        #-------------將日期列成選項-------------------
        foreach($date_array as $item=>$value){
            $a = $value;
            echo "<option value='$a'>$a</option>";
        }

        ?>
    </select>
    <input type="button" value="確定" onclick="change(curTarget.options[curTarget.selectedIndex]);">

</p>



<!-- <script type="text/javascript">
    var curTarget=document.getElementById("mylist");
    var change=function(target){
    
      alert(target.text);
    }
</script> -->

<script type="text/javascript">
    var curTarget = document.getElementById("mylist");
    var change = function(target) {
        // alert(target.text);
        // var tt=target.text;
        location.href = "D3_schedule_update2.php?value=" + target.text;
        // <a href='D1_member_update2.php?id="+tt+"'>修改</a>
    }
</script>

</html>