<?php
include("connMysql.php");
include("function_list.php");
session_start();

if (isset($_POST["button"])) {
    $delete_cmd = $_SESSION["target"];
    $db_link->query($delete_cmd);
    header("Location: manage_schedule_main.php");
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>
    <?php

    // ---------------------------------------------temp----------
    //                      要改的時間                 要改的日期 
    //                          ↓                         ↓
    // UPDATE `$fix_schedule` SET `0800`=null WHERE `date`='2019-11-23'

    if (isset($_POST["index"])) {
        $index = $_POST["index"];
        // 日期
        $date_name = $index . "date";
        $fix_date = $_SESSION[$date_name];
        // echo $fix_date;
        // 時間
        $date_time_name = $index . "date_time";
        $fix_time = $_SESSION[$date_time_name];
        // schedule
        $schedule_name = $index . "schedule";
        $fix_schedule = $_SESSION[$schedule_name];
        // 取得修改資料庫的語句
        $sql_cmd = "UPDATE `$fix_schedule` SET $fix_time=null WHERE `date`='$fix_date'";
        $_SESSION["target"] = "UPDATE `$fix_schedule` SET $fix_time=null WHERE `date`='$fix_date'";
        // echo $sql_cmd;
    } else {
        echo "fail";
    }

    ?>
    <table border="1" align="center">
        <tr align="center">
            <td width="80px">日期</td>
            <td width="80px">時間</td>
            <td width="80px">會員ID</td>
        </tr>
        <tr align="center">
            <td><?php echo $fix_date; ?>
            </td>
            <td><?php if (strlen($fix_time) == 6) {
                    $fix_time = substr($fix_time, 1, 4);
                    echo $fix_time;
                } else {
                    echo $fix_time;
                }
                ?>
            </td>
            <td><?php
                $d_cmd = "SELECT `$fix_time` FROM `$fix_schedule` WHERE `date`='$fix_date';";
                $ree = $db_link->query($d_cmd);
                $ree_result = $ree->fetch_row();
                if($ree_result[0]==999){
                    echo "不開放";
                }else{
                    echo $ree_result[0]; 
                }
                ?>
            </td>
        </tr>
    </table>
    <form action="" method="post" align="center">
        <input type="submit" name="button" value="確認刪除" />
    </form>

    <!-- <button type="sumit" name="button" value="確認">確認</button> -->
</body>

</html>