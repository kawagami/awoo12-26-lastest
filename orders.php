<?php
require_once "Rate.php";

class Order extends Rate{

    function getAllOrders()
    {
        $query = "SELECT orderdetail.orderid, orderdetail.productid, orderdetail.productname,orderdetail.renthours, orders.m_id, orders.total, orders.customername, orders.customeremail, orders.customerphone, orders.paytype, orders.stat FROM orders LEFT JOIN orderdetail ON orders.orderid = orderdetail.orderid GROUP BY orders.orderid";

        $postResult = $this->getDBResult($query);
        return $postResult;
    }

    function getOrdersByMember($memberid)
    {
        $query = "SELECT orderdetail.orderid, orderdetail.productid, orderdetail.productname,orderdetail.renthours, orders.m_id, orders.total, orders.customername, orders.customeremail, orders.customerphone, orders.paytype, orders.stat FROM orders LEFT JOIN orderdetail ON orders.orderid = orderdetail.orderid WHERE orders.m_id = ? GROUP BY orders.orderid";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $memberid
            )
        );

        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }

    function getOrdersByStat($stat)
    {
        $query = "SELECT orderdetail.orderid, orderdetail.productname,orderdetail.renthours, orders.m_id, orders.total, orders.customername, orders.customeremail, orders.customerphone, orders.paytype, orders.stat FROM orders LEFT JOIN orderdetail ON orders.orderid = orderdetail.orderid WHERE orders.stat = ? GROUP BY orders.orderid";

        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $stat
            )
        );

        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }

    function getOrdersByStatForMember($memberid,$stat)
    {
        $query = "SELECT orderdetail.orderid, orderdetail.productname,orderdetail.renthours, orders.m_id, orders.total, orders.customername, orders.customeremail, orders.customerphone, orders.paytype, orders.stat FROM orders LEFT JOIN orderdetail ON orders.orderid = orderdetail.orderid WHERE orders.m_id = ? AND orders.stat = ? GROUP BY orders.orderid";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $memberid
            ),
            array(
                "param_type" => "s",
                "param_value" => $stat
            )
        );

        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }

    function getAllMemberidByOrders()
    {
        $query = "SELECT orders.m_id From orders GROUP BY orders.orderid";

        $postResult = $this->getDBResult($query);
        return $postResult;
    }

    function getMemberidByUsername($username)
    {
        $query = "SELECT memberdata.m_id FROM memberdata WHERE memberdata.m_username =?";

        $params = array(
            array(
                "param_type" => "s",
                "param_value" => $username
            )
        );

        $postResult = $this->getDBResult($query, $params);
        return $postResult;
    }

    function deleteOrder($orderid)
    {
        $query = "UPDATE orders SET stat ='取消' WHERE orderid=?";

        $params = array(
            array(
                "param_type" => "i",
                "param_value" => $orderid
            )
        );

        $this->updateDB($query, $params);
    }
}

?>