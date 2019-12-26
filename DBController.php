<?php
//這是一個資料庫控制用的類別檔
class DBController
{

    private $host = "localhost";

    private $user = "admin";

    private $password = "admin";

    private $database = "phpmember";

    private static $conn;
    //產生新物件時會自動運行的建構式，用來連接資料庫
    /*
    function __construct()
    {
        $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
    }
    */
    function __construct()
    {
        $this->conn = @new mysqli($this->host, $this->user, $this->password, $this->database);
        if($this->conn->connect_error != ""){
            echo "資料庫連結失敗！";
        }else{
            $this->conn->query("SET NAMES 'utf8'");
        }
    }    

    public static function getConnection()
    {
        if (empty($this->conn)) {
            new Database();
        }
    }
    //取得資料庫資料的函式，需要參數:$query=>SQL語法、$params=>要綁定的參數，預設為空陣列
    function getDBResult($query, $params = array())
    {
        $sql_statement = $this->conn->prepare($query); //建立預備語法
        if (! empty($params)) {
            $this->bindParams($sql_statement, $params); //若$params不為空則綁定參數
        }
        $sql_statement->execute(); //執行SQL語法
        $result = $sql_statement->get_result(); //獲取結果
        
        if ($result->num_rows > 0) {  //若有取得資料則逐行取出資料並放入$resultset[]
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;  
            }
        }
        
        if (! empty($resultset)) {  //若$resultset[]內有資料就回傳
            return $resultset;
        }
    }
    //更新資料庫資料的函式
    function updateDB($query, $params = array())
    {
        $sql_statement = $this->conn->prepare($query);
        if (! empty($params)) {
            $this->bindParams($sql_statement, $params);
        }
        $sql_statement->execute();
    }
    //綁定SQL參數用的函式
    function bindParams($sql_statement, $params)
    {
        $param_type = "";  //建立變數$param_type，預設為空字串，用來存放要綁定的參數型別
        foreach ($params as $query_param) {  //利用foreach將$params[]中的內容取出，例:$query_param=["param_type":"i", "param_value":$tutorial_id]
            $param_type .= $query_param["param_type"];  //將$query_param[]中key="param_type"的內容與$param_type串接，例如"iii"
        }
        
        $bind_params[] = & $param_type;
        foreach ($params as $k => $query_param) {
            $bind_params[] = & $params[$k]["param_value"];
        }
        
        call_user_func_array(array(
            $sql_statement,
            'bind_param'
        ), $bind_params);
    }
}
