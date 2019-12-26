<?php
//這是一個對資料庫內容做詳細控制的類別檔selected
require_once "DBController.php"; // 匯入資料庫控制檔

class Rate extends DBController  //繼承父類別DBController
{
    //取得資料庫中所有評分紀錄的函式
    function getAllPost()
    {
        //陳列方式: id title description rating_count(評分紀錄的數量) rating_total(評分紀錄的總和)，productid相同的為同一組
        $query = "SELECT kitchen_data.kit_id,kitchen_data.kit_title,kitchen_data.kit_dese, COUNT(tbl_member_rating.rating) as rating_count, SUM(tbl_member_rating.rating) as rating_total FROM kitchen_data LEFT JOIN tbl_member_rating ON kitchen_data.kit_id = tbl_member_rating.productid GROUP BY kitchen_data.kit_id";
        $postResult = $this->getDBResult($query);
        return $postResult;
    }
    function getProductidBYProduct($productid)
    {
        $query = "SELECT kitchen_data.kit_id,kitchen_data.kit_title,kitchen_data.kit_dese, COUNT(tbl_member_rating.rating) as rating_count, SUM(tbl_member_rating.rating) as rating_total FROM kitchen_data LEFT JOIN tbl_member_rating ON kitchen_data.kit_id = tbl_member_rating.productid WHERE kitchen_data.kit_id= ? GROUP BY kitchen_data.kit_id ";
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $productid
            )
        );

        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }
    //根據傳入的productid從資料庫中抓出相對應的評分紀錄
    function getRatingByProduct($productid)
    {
        //陳列方式: id title description rating_count(評分紀錄的數量) rating_total(評分紀錄的總和)
        $query = "SELECT kitchen_data.kit_id,kitchen_data.kit_title,kitchen_data.kit_dese, COUNT(tbl_member_rating.rating) as rating_count, SUM(tbl_member_rating.rating) as rating_total FROM kitchen_data LEFT JOIN tbl_member_rating ON kitchen_data.kit_id = tbl_member_rating.productid WHERE tbl_member_rating.productid = ? GROUP BY tbl_member_rating.productid";
        //$params陣列=[["param_type":"i", "param_value":$productid]]
        $params = array(  
            array(
                "param_type" => "i", //參數的型別:int
                "param_value" => $productid //參數的值:傳入的$productid
            )
        );
        
        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }
    function getRatingByOrderid($orderid)
    {
        //陳列方式: id title description rating_count(評分紀錄的數量) rating_total(評分紀錄的總和)
        $query = "SELECT * FROM tbl_member_rating WHERE o_id = ? ";
        //$params陣列=[["param_type":"i", "param_value":$productid]]
        $params = array(  
            array(
                "param_type" => "i", //參數的型別:int
                "param_value" => $orderid //參數的值:傳入的$productid
            )
        );
        
        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }
    //從資料庫中取出同時吻合$productid與$member_id的評分紀錄
    function getRatingByProductForMember($productid, $member_id)
    {
        $query = "SELECT * FROM tbl_member_rating WHERE productid = ? AND member_id = ?";
        //$params=[["param_type":"i", "param_value":$productid],["param_type":"i","param_value":$member_id]]
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $productid
            ),
            array(
                "param_type" => "i",
                "param_value" => $member_id
            )
        );
        
        $ratingResult = $this->getDBResult($query, $params);
        return $ratingResult;
    }
    //新增評分紀錄，需要的引數:項目的id,評分的值,評分者的id
    function addRating($productid, $rating, $member_id)
    {
        $query = "INSERT INTO tbl_member_rating (productid,rating,member_id) VALUES (?, ?, ?)";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $productid
            ),
            array(
                "param_type" => "i",
                "param_value" => $rating
            ),
            array(
                "param_type" => "i",
                "param_value" => $member_id
            )
        );
        
        $this->updateDB($query, $params);
    }
    //修改已存在的評分紀錄
    function updateRating($rating, $rating_id)
    {
        $query = "UPDATE tbl_member_rating SET  rating = ? WHERE o_id= ?";
        
        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $rating
            ),
            array(
                "param_type" => "i",
                "param_value" => $rating_id
            )
        );
        
        $this->updateDB($query, $params);
    }

    
}
