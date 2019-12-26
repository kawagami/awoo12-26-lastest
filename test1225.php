<?php
//若資料庫內有評分紀錄則運行，$product=每一個廚房的評分資料，$ratingResult=評分資料中的分數總合
if (! empty($result)) {
    $i = 0;
    $product = $rate->getProductidBYProduct($row_RecProduct["kit_id"]);
    $ratingResult = $product[0]["rating_total"];
    if (! empty($ratingResult)){  //算出評分總和，取到小數第一位
      $average = round(($product[0]["rating_total"] / $product[0]["rating_count"]), 1);  
    }  
?>
            <td id="demo-table" width="200px" align="center">
              <div id="product-<?php echo $product[0]["kit_id"]; ?>" class="star-rating-box">
              <input type="hidden" name="rating" id="rating" value="<?php echo $average; ?>" />
                <ul>
<?php   //建立5個<li>並根據資料庫內的分數紀錄添加"selected"類別
for ($i = 1; $i <= 5; $i ++) {
    $selected = "";
    if(! empty($ratingResult) && $i <= round($average,0)){
        $selected = "selected";
    } 
?>
                    <li class='<?php echo $selected; ?>'>&#9733;</li>  
<?php }  ?>
                </ul>
                <div id="star-rating-count-<?php echo $product[0]["kit_id"]; ?>" class="star-rating-count">
<?php
if (! empty($ratingResult)) {  //印出平均分數/資料筆數
  echo "平均分數: " . $average . " / 總共 " . $product[0]["rating_count"] . " 筆評分紀錄";
} else {
  echo "尚無評分紀錄";}
?>
                </div>
              </div>
            </td>
<?php
}
?>

          </div>