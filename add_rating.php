<?php
// Here the member id is harcoded.
// You can integrate your authentication code here to get the logged in member id
//$member_id = 1;
if (! empty($_POST["rating"]) && ! empty($_POST["orderid"])) {
    require_once ("Rate.php");
    $rate = new Rate();
    
    $ratingResult = $rate->getRatingByOrderid($_POST["orderid"]);
    
    $rate->updateRating($_POST["rating"], $ratingResult[0]["o_id"]);
    
    //$postRating = $rate->getRatingByProduct($_POST["productid"]);
    /*
    if (! empty($postRating[0]["rating_total"])) {
        $average = round(($postRating[0]["rating_total"] / $postRating[0]["rating_count"]), 1);
        echo "平均分數: " . $average . " / 總共" . $postRating[0]["rating_count"] . " 筆評分紀錄";
    } else {
        echo "尚無評分紀錄";
    }
    */
}
?>