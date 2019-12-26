<?php
include("include_list.php");
// 在會員已有預約的狀況想要修改預約的時間
// 1.在想要的時間新增預約

// $new_cmd="UPDATE `schedule` SET `$new_time`='會員ID' WHERE `date` = '$new_date'";

// 2.刪除舊的預約
// $old_cmd="UPDATE `schedule` SET `$old_time`=null WHERE `date` = '$old_date'";

// $db_link->query($new_cmd);
// $db_link->query($old_cmd);

// request_sql($new_cmd);
// request_sql($old_cmd);

// $test="select * from `schedule`";
$test="select * from `memberdata`";

// request_sql($test,$res);
// print_r($res);
$class_test = new request_sql($test);
?>