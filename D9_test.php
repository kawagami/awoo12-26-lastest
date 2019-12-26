<?php
include("include_list.php");
session_start();

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

    // $birthday = '1987-05-05';
    // if ($presta = $db_link->prepare("SELECT m_id,m_name,m_username,m_sex from `memberdata` where `m_birthday`>?")) {        
    //     // 指定?的條件
    //     $presta->bind_param("s", $birthday);
    //     $presta->execute();
    //     // 取得在select選定的欄位內的值
    //     $presta->bind_result($id, $name, $username, $sex);
    //     while($presta->fetch()){
    //         echo "ID：{$id}<br>";
    //         echo "name：{$name}<br>";
    //         echo "username：{$username}<br>";
    //         echo "sex：{$sex}<br>";
    //         echo "<hr>";
    //     }
    // }
    // $presta->colse();

    // for ($i = 0; $i < 13; $i++) {
    //     $arr[] = "\$var" . $i;
    // }
    // $result_arr = join(",", $arr);

    $cmd = "SELECT * from `memberdata`";
    $test = new request_sql($cmd);


    ?>
</body>

</html>