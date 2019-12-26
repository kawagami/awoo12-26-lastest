<?php
include("include_list.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- <script type="text/javascript">
        $(".a_post").on("click", function(event) {
            event.preventDefault(); //使a自带的方法失效，即无法调整到href中的URL(http://www.baidu.com)
            $.ajax({
                type: "POST",
                url: "url地址",
                contentType: "application/json",
                data: JSON.stringify({
                    param1: param1,
                    param2: param2
                }), //参数列表
                dataType: "json",
                success: function(result) {
                    //请求正确之后的操作
                    $_SESSION["sql_cmd_array"] = $sql_cmd_array;
                    echo count($_SESSION["sql_cmd_array"]);
                },
                error: function(result) {
                    //请求失败之后的操作
                }
            });
        });
    </script> -->
    <style type="text/css">
        a {
            text-decoration: none;
        }

        a:visited {
            color: blue;
        }
    </style>
</head>

<body>

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>

    <form id="_form" method="post" action="D3_schedule_select2.php">
        <input type="hidden" name="index" value="value" />
        <a onclick="document.getElementById('_form').submit();">点击提交</a>
    </form>

    <table border="1" align="center">
        <?php



        echo "<tr><td align='center'>date</td>";
        foreach ($time_array as $item => $value) {
            echo "<td align='center'>$value</td>";
        }
        echo "</tr>";

        for ($j = 0; $j < count($date_array); $j++) {

            echo "<tr><td>";
            echo $date_array[$j];
            for ($i = 0; $i < count($time_array); $i++) {
                $date = $date_array[$j];
                $date_time = $time_array[$i];
                $sql_cmd = "SELECT * FROM `schedule` WHERE `date` = '$date' and $date_time is null";
                $res = $db_link->query($sql_cmd);
                $res_row = $res->fetch_row();
                if (!$res_row == '') {
                    //希望能將字變成連結_按下去能預約按下的時間
                    // session_start();
                    global $sql_cmd_array;
                    $ind = "index$j$i";
                    $sql_cmd_array[$ind] = $sql_cmd;
                    echo "<td align='center'><a href='#' class='a_post'>空位</a></td>";
                    // echo "<td align='center'>空位</td>";
                } else {
                    echo "<td align='center'><font color='red'>已預約</font></td>";
                }
            }
            echo "</tr></td>";
        }
        // $_SESSION["sql_cmd_array"] = $sql_cmd_array;
        // echo count($_SESSION["sql_cmd_array"]);
        ?>
    </table>
</body>

</html>