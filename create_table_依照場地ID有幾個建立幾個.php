<?php
include("connMysql.php");
set_time_limit(0);
$cmd = "SELECT `kit_id` FROM `kitchen_data`";
$res = $db_link->query($cmd);

while ($result = $res->fetch_assoc()) {
    foreach ($result as $a => $b) {
        // echo "schedule" . $b . "<br>";
        $arr[] = "schedule" . $b;
    }
}
// echo "<pre>" . print_r($arr , TRUE) . "</pre>";
// print_r($arr, TRUE);
foreach ($arr as $item => $value) {
    $sql_cmd1 = "DROP TABLE `$value`;";

    $sql_cmd2 = "CREATE TABLE `$value` (
    `table_id` int(5) NOT NULL,
    `date` date NOT NULL,
    `0800` int(11) DEFAULT NULL,
    `0900` int(11) DEFAULT NULL,
    `1000` int(11) DEFAULT NULL,
    `1100` int(11) DEFAULT NULL,
    `1200` int(11) DEFAULT NULL,
    `1300` int(11) DEFAULT NULL,
    `1400` int(11) DEFAULT NULL,
    `1500` int(11) DEFAULT NULL,
    `1600` int(11) DEFAULT NULL,
    `1700` int(11) DEFAULT NULL,
    `1800` int(11) DEFAULT NULL,
    `1900` int(11) DEFAULT NULL,
    `2000` int(11) DEFAULT NULL,
    `2100` int(11) DEFAULT NULL,
    `2200` int(11) DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $sql_cmd3 = "ALTER TABLE `$value` ADD PRIMARY KEY (`table_id`);";

    $sql_cmd4 = "ALTER TABLE `$value` MODIFY `table_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;";

    $cmd_array = array();
    $cmd_array[] = $sql_cmd1;
    $cmd_array[] = $sql_cmd2;
    $cmd_array[] = $sql_cmd3;
    $cmd_array[] = $sql_cmd4;
    foreach ($cmd_array as $i => $j) {
        $db_link->query($j);
    }

    // $db_link->query($sql_cmd1);
    // $db_link->query($sql_cmd2);
    // $db_link->query($sql_cmd3);
    // $db_link->query($sql_cmd4);

    // 隨機新增預約資料
    $time_list = array("`0800`", "`0900`", "`1000`", "`1100`", "`1200`", "`1300`", "`1400`", "`1500`", "`1600`", "`1700`", "`1800`", "`1900`", "`2000`", "`2100`", "`2200`");
    for ($i = 0; $i < 100; $i++) {
        $re = rand(0, 100) . "<br>";
        $d = strtotime("+$i days");
        $tim = date('Y-m-d', $d);
        if ($re < 10) {
            // echo "<font color='red'>null</font><br>";
            $sql_cmd = "INSERT INTO `$value` (`date`) VALUES ('$tim');";
            // echo $sql_cmd;
            $res = $db_link->query($sql_cmd);
        } elseif (9 < $re && $re < 101) {
            $ti_sta = $time_list[array_rand($time_list, 1)];
            $rand_member = rand(2, 13);
            $sql_cmd2 = "INSERT INTO `$value` (`date`,$ti_sta) VALUES ('$tim',$rand_member);";
            // echo $sql_cmd2;
            $res = $db_link->query($sql_cmd2);
        }
    }
}
echo "建立完成";
?>

<!-- 123 -->