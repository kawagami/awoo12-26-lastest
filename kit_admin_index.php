<?php 
require_once("connMysql.php");
session_start();

//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: member_index.php");
}

//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index2.php");
}

//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
	header("Location: member_center.php");
}

//刪除廚房
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
  $query_delkitchen = "DELETE kitchen_data,kitchen_photo FROM kitchen_data LEFT JOIN kitchen_photo ON kitchen_data.kit_id=kitchen_photo.kit_id WHERE kitchen_data.kit_id=?";
  $stmt=$db_link->prepare($query_delkitchen);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: kit_admin_index.php");
}

//選取管理員資料
$query_RecAdmin = "SELECT m_id, m_name, m_logintime FROM memberdata WHERE m_username=?";
$stmt=$db_link->prepare($query_RecAdmin);
$stmt->bind_param("s", $_SESSION["loginMember"]);
$stmt->execute();
$stmt->bind_result($mid, $mname, $mlogintime);
$stmt->fetch();
$stmt->close();
//選取所有一般會員資料
//預設每頁筆數
$pageRow_records = 20;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecKitchen = $query_RecKitchen = "SELECT md.m_name,md.m_username,kd.*,kp.* 
  FROM kitchen_data AS kd 
  LEFT JOIN kitchen_photo AS kp ON kd.kit_id=kp.kit_id 
  LEFT JOIN memberdata AS md ON kd.m_id=md.m_id GROUP BY kd.kit_id";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecKitchen = $query_RecKitchen." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $resultMember 中
$RecKitchen = $db_link->query($query_limit_RecKitchen);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_resultMember 中
$all_RecKitchen = $db_link->query($query_RecKitchen);
//計算總筆數
$total_records = $all_RecKitchen->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
?>

<html>
    <head>
        <link rel="stylesheet" href="index2.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0 shrink-to-fit=no">
		    <meta charset="utf-8">
		    <title>管理廚房</title>
		    <meta name="viewport" content="width=device-width, initial-scale=1">
		    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		    <script language="javascript">
		    function deletesure(){
            if (confirm('\n您確定要刪除這個廚房嗎?\n刪除後無法恢復!\n')) return true;
            return false;
          }
        </script>
 
        <style type="text/css">

        .title th{
          text-align: center;
          background-color:#CCCCCC;
          padding:5px;
        }

        .title td{
          text-align:center; 
          background-color: #FFFFFF;
        }

        .kit_info td{
          padding:3px;
        }    
        
        .fix img{
          margin:5px;
          width:18px;
        }

        @media only screen and (max-width: 500px) {
          
          .none{
            display:none;
          }
        }
        
        </style>
   
	</head>
    <body style="font-family: Microsoft JhengHei;">
	    <div class="id_wrapper">
		    <header class="header">
          <a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>
          <input class="menu-btn" type="checkbox" id="menu-btn" />
          <label class="menu-icon" for="menu-btn"><span class="nav-icon"></span></label>
          <ul class="menu">
				<li><a href="about.php">品牌起源</a></li>
				<li><a href="kitchen_index.php">看看環境</a></li>
				<li><a href="#subscribe">?????</a></li>
				<?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<li><a href="member_index.php">Sign in</a></li><br>
				<!--這段更改--><a href="member_admin.php"><?php }else{ echo "<li><a href='member_center.php'>會員中心</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>";?><?php }?></a>
			</ul>
        </header>
		<div class="id_content">
			<div class="container" style="margin: auto;width:100%;"> 
			
			  <table class="top" width="100%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr><td align="center"><h1>廚房列表</h1></td></tr>
          <tr><td> <a href="kit_add.php">[新增廚房]</a></td></tr> 
          <table class="title" width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
            <tr>              
              <th>廚房主人</th>
              <th>廚房名稱</th>
              <th class="none">環境照片</th>
              <th class="none">開放日期</th>
              <th class="none">開放時間</th>
              <th class="none">容納人數</th>
              <th>價格/清潔費</th>
              <th>修改/刪除</th>
            </tr>
			      <?php while($row_RecKitchen=$RecKitchen->fetch_assoc()){ ?>
            <tr class="kit_info">
              <td><?php echo $row_RecKitchen["m_username"];?></td>
              <td><a href="kit_show.php?id=<?php echo $row_RecKitchen["kit_id"];?>"><?php echo $row_RecKitchen["kit_title"];?></td>
              <td class="none" width="50px"><p><img width="100%" src="photos/<?php echo $row_RecKitchen["kit_picurl"];?>"></td>            
              <td class="none"><?php echo $row_RecKitchen["kit_startdate"];?>~<?php echo $row_RecKitchen["kit_enddate"];?></td>
              <td class="none"><?php echo substr($row_RecKitchen["kit_starttime"],0,-3);?>~<?php echo substr($row_RecKitchen["kit_endtime"],0,-3);?></td>
              <td class="none"><?php echo $row_RecKitchen["kit_capacity"];?></td>
              <td><?php echo $row_RecKitchen["kit_price"];?>/<?php echo $row_RecKitchen["kit_cleanfee"];?></td>
              <td class="fix" width="10%"><a href="kit_update.php?id=<?php echo $row_RecKitchen["kit_id"];?>"><img src="images/fix.png"></a><a href="?action=delete&id=<?php echo $row_RecKitchen["kit_id"];?>" onClick="return deletesure();"><img src="images/delete.png"></a></td> 
            </tr>
			      <?php }?>
          </table>
          <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr>
              <td valign="middle">廚房總數：<?php echo $total_records;?></td>
              <td align="right">
                <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
                <a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
                <?php }?>
                <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
                <a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
                <?php }?>
              </td>
            </tr>
          </table>               
        </table>        

			  </div> 
      </div>    		    
		<footer class="id_footer">
        © 2019
		</footer>

	</div>
	</body>
</html>