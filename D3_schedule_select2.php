<?php

use function PHPSTORM_META\type;

include("include_list.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
    <table border="1" align="center">
        <?php
        session_start();
        if (isset($_POST["index"])) {
            $ind = $_POST["index"];            
            print_r($_POST["index"]);
        } else {
            echo "<tr><td>失敗</tr></td>";
        }

        ?>
    </table>
</body>

</html>