<?php
include("connect_db_awoo.php");

if (isset($_POST['submitButton'])) {
    // $sql_query = "UPDATE `schedule` SET `$date2`='$date3' WHERE `date` = '$date'";
    // $sql_query = "UPDATE `schedule` SET `$date2`=$date3 WHERE `date` = '$date'";
    // $db_link->query($sql_query);
    session_destroy();
    header("Location: D3_schedule_select.php");
}

if (isset($_POST["action"]) && ($_POST["action"] == "add")) {
    echo $date;
    echo $date2;

    // $sql_query = "UPDATE `member` SET m_name=?, m_tel=?, m_regi_date=?, m_buy_time=?, m_mail=?, m_gender=? WHERE m_id=?";
    // $stmt = $db_link -> prepare($sql_query);
    // $stmt -> bind_param("ssssssi", $_POST["m_name"], $_POST["m_tel"], date ('Y-m-d'), $_POST["m_buy_time"], $_POST["m_mail"], $_POST["m_gender"], $_POST["m_id"]);
    // $stmt -> execute();
    // $stmt -> close();
    // $db_link -> close();
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
    session_start();
    $date = $_SESSION["value"];
    $date2 = $_SESSION["value2"];
    $date3 = $_GET["value3"];

    // $sql_cmd="SELECT `$date2` FROM `schedule` WHERE `date` = '$date'";
    // $res = $db_link->query($sql_cmd);

    $update_cmd = "UPDATE `schedule` SET `$date2`='會員ID' WHERE `date` = '$date'"

    ?>

    <!-- --------------上一頁按鈕--------------- -->
    <p align="center"><input type="button" onclick="history.back()" value="回到上一頁"></input></p>
    <p align="center"><a href="D0_main.php">回主選單</a></p>
    <table border="1" align="center">

        <?php
        $sql_query = "UPDATE `schedule` SET `$date2`=$date3 WHERE `date` = '$date'";
        $db_link->query($sql_query);
        echo "<tr align='center'><td>日期</td><td>時間</td><td>會員ID</td></tr>";
        echo "<tr align='center'>";
        echo "<td>" . $date . "</td>";
        echo "<td>" . $date2 . "</td>";
        echo "<td>" . $date3 . "</td>";
        echo "</tr>";

        ?>




    </table>

    <form align="center" action="D3_schedule_update4.php" method="post">
        <p><input type="submit" name="submitButton" value="確認" /></p>
        <form action="PHP_Form.php" method="post">

</body>

</html>