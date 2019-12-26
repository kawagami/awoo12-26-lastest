<?php
include("include_list.php");
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

?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        $total_fields = $res->field_count;
        $extra_field = $total_fields + 1;

        if ($result = $db_link->query("SELECT DATABASE()")) {
            $row = $result->fetch_row();
            echo "<tr><td align='center' colspan=$total_fields>";
            printf("目前連接的資料庫是「%s」<br>", $row[0]);
            echo "</tr></td>";
            $result->close();
        }

        echo "<tr><td align='center' colspan=$total_fields>可預約時段</tr></td>";
        $sql_cmd = "SELECT * FROM `schedule` WHERE `date` = '$got'";
        ?>
    </table>

    <p align="center">
        <select id="mylist" name="mylist">
            <?php

            // -------將有值的欄位剔除掉-----------
            for ($i = 0; $i < $total_fields - 2; $i++) {
                $b = mysqli_field_name($res, $i + 2);
                $check_cmd = "SELECT `$b` FROM `schedule` WHERE `date` = '$got'";
                $check_time = $db_link->query($check_cmd);
                $check_time_res = $check_time->fetch_row();

                foreach ($check_time_res as $item => $value) {
                    // 判斷有沒有人預約了
                    // 沒人預約($value == '')才會列出選項
                    if ($value == '') {
                        echo "<option value='$b'>$b</option>";
                    }
                }
            }

            ?>
        </select>
        <input type="button" value="確定" onclick="change(curTarget.options[curTarget.selectedIndex]);">
    </p>

    <script type="text/javascript">
        var curTarget = document.getElementById("mylist");
        var change = function(target) {
            // alert(target.text);
            var dd = "<?= $got; ?>";
            location.href = "D3_schedule_add3.php?value=" + dd + "&value2=" + target.text;
            // <a href='D1_member_update2.php?id="+tt+"'>修改</a>
        }
    </script>

</body>

</html>