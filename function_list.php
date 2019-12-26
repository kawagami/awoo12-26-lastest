<?php
#--------------------------------------------------------------------
function show_table($sql_cmd)
{
    include("connect_db_awoo.php");
    $res = $db_link->query($sql_cmd);
    $result = $res->fetch_assoc();
    echo '<table border="1" align="center">';
    echo "<tr>";
    foreach ($result as $item => $value) {
        echo "<td><b>" . $item . "</b></td>";
    }
    echo "</tr>";

    $res = $db_link->query($sql_cmd);
    while ($result = $res->fetch_assoc()) {

        echo "<tr>";
        foreach ($result as $item => $value) {
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo '</table>';
}
#--------------------------------------------------------------------
// -----------取得欄位名稱--的函數-------------
function mysqli_field_name($result, $field_offset)
{
    $properties = mysqli_fetch_field_direct($result, $field_offset);
    return is_object($properties) ? $properties->name : null;
}
#--------------------------------------------------------------------
function time_array()
{
    // $time_array=以下這些
    // 0=>`0800`
    // 1=>`0900`
    // 2=>`1000`
    // 3=>`1100`
    // 4=>`1200`
    // 5=>`1300`
    // 6=>`1400`
    // 7=>`1500`
    // 8=>`1600`
    // 9=>`1700`
    // 10=>`1800`
    // 11=>`1900`
    // 12=>`2000`
    // 13=>`2100`
    // 14=>`2200`
    for ($i = 8; $i < 10; $i++) {
        global $time_array;
        $time_array[] = "`0" . $i . "00`";
    }
    for ($i = 10; $i < 23; $i++) {
        $time_array;
        $time_array[] = "`" . $i . "00`";
    }
}

time_array();
#--------------------------------------------------------------------
function date_array()
{
    global $date_array;
    for ($i = 0; $i < 30; $i++) {
        $d = strtotime("+$i days");
        $date = date('Y-m-d', $d);
        $date_array[] = "$date";
    }
    // foreach($date_array as $item=>$value){
    //     echo $item."=>".$value."<br>";
    // }
}
date_array();
#--------------------------------------------------------------------
// function schedule_select()
// {
//     include("include_list.php");
//     echo "<tr><td align='center'>date</td>";
//     foreach ($time_array as $item => $value) {
//         echo "<td align='center'>$value</td>";
//     }
//     echo "</tr>";

//     for ($j = 0; $j < count($date_array); $j++) {

//         echo "<tr>";
//         echo "<td>";
//         echo $date_array[$j];
//         for ($i = 0; $i < count($time_array); $i++) {
//             $date = $date_array[$j];
//             $date_time = $time_array[$i];
//             $sql_cmd = "SELECT * FROM `schedule` WHERE `date` = $date and $date_time is null";
//             $res = $db_link->query($sql_cmd);
//             $res_row = $res->fetch_row();
//             if (!$res_row == '') {
//                 // echo $i . "<br>";
//                 echo "<td align='center'>空位</td>";
//             } else {
//                 // echo $i . "<br>";
//                 echo "<td align='center'><font color='red'>已預約</font></td>";
//             }
//         }
//         echo "</tr>";
//         echo "</td>";
//     }
// }
#--------------------------------------------------------------------
class request_sql
{
    var $request;
    var $assoc;
    var $row;
    var $all;
    var $i = 0;

    function __construct($cmd)
    {
        include("connect_db_awoo.php");
        $this->request = $db_link->query($cmd);
        // echo $this->request->field_count;
        while ($res = $this->request->fetch_row()) {
            foreach ($res as $item => $value) {
                echo $item . "=>" . $value;
                echo "<br>";
            }
            echo "<hr>";
        }

        // $this->show_data($this->all);
    }
    function show_data($array)
    {
        if (is_array($array)) {
            foreach ($array as $item => $value) {
                echo $item . "<br>";
                $this->show_data($value);
                // echo "</tr>";
            }
        } else {
            echo $array . "<br>";
        }
    }
}
#--------------------------------------------------------------------

#--------------------------------------------------------------------
