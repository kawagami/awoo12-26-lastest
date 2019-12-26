<?php
include("connect_db_awoo.php");
if (isset($_POST["action"]) && ($_POST["action"] == "add")) {
    $sql_query = "UPDATE `member` SET m_name=?, m_tel=?, m_regi_date=?, m_buy_time=?, m_mail=?, m_gender=? WHERE m_id=?";
    $stmt = $db_link->prepare($sql_query);
    $stmt->bind_param("ssssssi", $_POST["m_name"], $_POST["m_tel"], date('Y-m-d'), $_POST["m_buy_time"], $_POST["m_mail"], $_POST["m_gender"], $_POST["m_id"]);
    $stmt->execute();
    $stmt->close();
    $db_link->close();
    //重新導向回到主畫面
    header("Location: D3_schedule_add.php");
}
// $sql_select = "SELECT * FROM `schedule` WHERE `date` = ? ORDER BY `date`";
// $stmt = $db_link -> prepare($sql_select);
// $stmt -> bind_param("i", $_GET["id"]);
// $stmt -> execute();
// $stmt -> bind_result(`date`,`0800`);
// $stmt -> fetch();

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

    $got = $_GET["value"];
    $sql_cmd = "SELECT * FROM `schedule` WHERE `date` = '$got'";
    $res = $db_link->query($sql_cmd);

    ?>

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>
    <table border="1" align="center">

        <?php
        // $res="物件"->query("sql指令")


        // ----計算欄位數--------
        $sql_cmd2 = "SELECT `date` FROM `schedule`";
        $res_ = $db_link->query($sql_cmd2);
        $date_data = $res_->fetch_row();
        $total_fields = $res_->field_count;
        $extra_field = $total_fields + 1;

        if ($result = $db_link->query("SELECT DATABASE()")) {
            $row = $result->fetch_row();
            echo "<tr><td align='center' colspan=$total_fields>";
            printf("目前連接的資料庫是「%s」<br>", $row[0]);
            echo "</tr></td>";
            $result->close();
        }

        echo "<tr><td align='center' colspan=$total_fields>可預約時段</tr></td>";

        // mysqli_field_name($res物件變數,第x-1欄位) 回傳 第x欄位名稱
        // echo mysqli_field_name($res,0);
        // $row = $res2->fetch_array();
        // echo $row[0];

        // -----依照欄位的數量echo 所有的欄位名稱--------
        // for($i=0;$i<$total_fields;$i++){
        //     echo mysqli_field_name($res,$i)."<br>";    
        // }

        // // ------------列出欄位標題-------------
        // echo "<tr align='center'>";
        // for($i=0;$i<$total_fields;$i++){
        //     echo "<td>".mysqli_field_name($res,$i)."</td>";
        // }
        // echo "</tr>";  
        // // ------------列出欄位內容-------------
        // while($row_res=$res->fetch_assoc()){    
        //     echo "<tr>";
        //     for($i=0;$i<$total_fields;$i++){
        //         echo "<td>".$row_res[mysqli_field_name($res,$i)]."</td>";        
        //     }
        //     echo "</tr>";
        // }



        ?>
    </table>

    <p align="center"><select id="mylist" name="mylist">
            <?php
            // for ($i = 0; $i < $total_fields - 2; $i++) {
            for ($i = 0; $i < 15; $i++) {
                $b = mysqli_field_name($res, $i + 2);
                echo "<option value='$b'>$b</option>";
            }

            ?>
        </select>
        <!-- <input type="button" value="確定" onclick="change(curTarget.options[curTarget.selectedIndex-1]);" > -->
        <input type="button" value="確定" onclick="change(curTarget.options[curTarget.selectedIndex]);">
    </p>


    <!-- ------------php變數轉換成js變數---------- -->
    <!-- <script language="javascript">
var ip = "<?= $ip; ?>"; // here, look!
var result = "You IP address is: " + ip;
document.write(result);
</script> -->

    <script type="text/javascript">
        var curTarget = document.getElementById("mylist");
        var change = function(target) {
            // alert(target.text);
            var dd = "<?= $got; ?>";
            location.href = "D3_schedule_update3.php?value=" + dd + "&value2=" + target.text;
            // <a href='D1_member_update2.php?id="+tt+"'>修改</a>
        }
    </script>

</body>

</html>