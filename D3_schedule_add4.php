<?php
include("include_list.php");

if (isset($_POST['submitButton'])) {
    session_destroy();
    header("Location: D3_schedule_select.php");
}

if (isset($_POST["action"]) && ($_POST["action"] == "add")) {
    echo $date;
    echo $date2;
    header("Location: D3_schedule_add.php");
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
    session_start();
    // date欄位的值
    $date = $_SESSION["value"];
    // time欄位的值
    $date2 = $_SESSION["value2"];
    // 會員ID的值
    $date3 = $_GET["value3"];

    $update_cmd = "UPDATE `schedule` SET `$date2`='會員ID' WHERE `date` = '$date'"

    ?>

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>
    <table border="1" align="center">

        <?php
        if (strlen($date2) == 4) {
            $sql_query = "UPDATE `schedule` SET `$date2`=$date3 WHERE `date` = '$date'";
            $db_link->query($sql_query);
            echo "<tr align='center'><td>日期</td><td>時間</td><td>會員ID</td></tr>";
            echo "<tr align='center'>";
            echo "<td>" . $date . "</td>";
            echo "<td>" . $date2 . "</td>";
            echo "<td>" . $date3 . "</td>";
            echo "</tr>";
        } else {
            $sql_query = "UPDATE `schedule` SET $date2=$date3 WHERE `date` = $date";
            $db_link->query($sql_query);
            echo "<tr align='center'><td>日期</td><td>時間</td><td>會員ID</td></tr>";
            echo "<tr align='center'>";
            if (strlen($date) == 12) {
                $date = substr($date, 1, 10);
            }
            echo "<td>" . $date . "</td>";
            if (strlen($date2) == 6) {
                $date2 = substr($date2, 1, 4);
            }
            echo "<td>" . $date2 . "</td>";
            echo "<td>" . $date3 . "</td>";
            echo "</tr>";
        }


        ?>




    </table>

    <form align="center" action="D3_schedule_add4.php" method="post">
        <p><input type="submit" name="submitButton" value="確認" /></p>
        <form action="PHP_Form.php" method="post">

</body>

</html>