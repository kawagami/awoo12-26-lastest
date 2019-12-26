<?php
session_start();
require_once("connMysql.php");	

function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;
    case "email":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
      break;
    case "url":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
      break;      
  }
  return $theValue;
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: member_index.php");
}

///////////////////////////////////////////擷取公告內容
//預設每頁筆數
$pageRow_records = 5;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecBoard = "SELECT * FROM news1 ORDER BY news_time DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecBoard = $query_RecBoard." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecBoard 中
$RecBoard = $db_link->query($query_limit_RecBoard);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecBoard 中
$all_RecBoard = $db_link->query($query_RecBoard);
//計算總筆數
$total_records = $all_RecBoard->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);

///////////////////////////////////////////新增
if(isset($_POST["action"])&&($_POST["action"]=="add")){
	//若新增時無上傳新圖檔
	if($_FILES["picurl"]["name"]==""){
		$sql_add = "INSERT INTO news1 (news_subject ,news_content ,news_time,news_admin) VALUES ('".
		GetSQLValueString($_POST["subject"], "string")."', '".
		GetSQLValueString($_POST["content"], "string")."', NOW(),'{$_SESSION["id"]}');";
		
		$db_link->query($sql_add)or die("Unable to insert data");
		$db_link->close();
		//重新導向回到主畫面
		echo "<script>alert('新增公告成功!'); location ='admin_add.php';</script>";
	}
	else{
		if(move_uploaded_file($_FILES["picurl"]["tmp_name"] , "photos/".$_FILES["picurl"]["name"])){
			
			$sql_add = "INSERT INTO news1 (news_subject ,news_content ,news_time ,news_admin ,news_picurl) VALUES ('".
		GetSQLValueString($_POST["subject"], "string")."', '".
		GetSQLValueString($_POST["content"], "string")."', NOW(),'{$_SESSION["id"]}', '".
		GetSQLValueString($_FILES["picurl"]["name"], "string")."');";
		
		$db_link->query($sql_add)or die("Unable to insert data");
		$db_link->close();
		//重新導向回到主畫面
		echo "<script>alert('新增公告成功!'); location ='admin_add.php';</script>";
			
		}else{echo "圖檔上傳失敗！";}
	}
}

///////////////////////////////////////////修改公告內容
//擷取要修改的內容
if(isset($_GET["id"])){
	$sql_data = "SELECT * FROM news1 WHERE news_id = '{$_GET["id"]}'";
	$result_data = $db_link->query($sql_data);
	$news_data = $result_data->fetch_assoc();
	}

//修改內容
if(isset($_POST["action"])&&($_POST["action"]=="update")){
	//若更新時無上傳新圖檔
	if($_FILES["upicurl"]["name"]==""){
		$sql_update = "UPDATE news1 SET news_subject = '".
			GetSQLValueString($_POST["usubject"], "string").
			"', news_content = '".GetSQLValueString($_POST["ucontent"], "string").
 			"', news_admin = '{$_SESSION["id"]}' WHERE news_id='{$_GET["id"]}';";
			$db_link->query($sql_update) or die ("Unable to update data");
			echo "<script>alert('成功修改公告內容!'); location='admin_add.php';</script>";
	}
	else{
		if(move_uploaded_file($_FILES["upicurl"]["tmp_name"] , "photos/".$_FILES["upicurl"]["name"])){
			$sql_update = "UPDATE news1 SET news_subject = '".
			GetSQLValueString($_POST["usubject"], "string").
			"', news_content = '".GetSQLValueString($_POST["ucontent"], "string").
 			"', news_admin = '{$_SESSION["id"]}', news_picurl = '".
			GetSQLValueString($_FILES["upicurl"]["name"], "string").
			"' WHERE news_id='{$_GET["id"]}';";
			$db_link->query($sql_update) or die ("Unable to update data");
			echo "<script>alert('成功修改公告內容!'); location='admin_add.php';</script>";
		}else{echo "圖檔上傳失敗！";}
	}
	
}

/////////////////////////////////////////////執行刪除動作
if(isset($_POST["action"])&&($_POST["action"]=="delete")){
	$sql_delete = "DELETE FROM news1 WHERE news_id = '".$_POST["delete"]."'";
	$result_delete = $db_link->query($sql_delete);
	echo "<script>alert('刪除公告成功!'); location = 'admin_add.php';</script>";
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index2.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>	
		<script language="javascript">
function checkForm(){	
	if(document.formPost.subject.value==""){
		alert("請填寫公告主旨!");
		document.formPost.subject.focus();
		return false;
	}
	if(document.formPost.content.value==""){
		alert("請填寫公告內容!");
		document.formPost.content.focus();
		return false;
	}
	return confirm('確定新增嗎？');
}	
</script>
<script language="javascript">
function checkupman(){	
	if(document.formAdd.usubject.value==""){
		alert("請填寫公告主旨!");
		document.formAdd.usubject.focus();
		return false;
	}
	if(document.formAdd.ucontent.value==""){
		alert("請填寫公告內容!");
		document.formAdd.ucontent.focus();
		return false;
	}
	return confirm('確定修改嗎？');
}

	</script>
	</head>
    <body style="font-family: Microsoft JhengHei;">
		<div class="id_wrapper">
			<header class="header">
			<?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>
				<?php }
				elseif($_SESSION["loginMember"]=="admin"){ echo '<a href="member_admin.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>'; }
				else{ echo '<a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>'; }?></a>
				<input class="menu-btn" type="checkbox" id="menu-btn" />
				<label class="menu-icon" for="menu-btn"><span class="nav-icon"></span></label>
				<ul class="menu">
				<li><a href="about.php">品牌起源</a></li>
				<li><a href="kitchen_index.php">看看環境</a></li>
				<li><a href="#subscribe">?????</a></li>
				<?php if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){?>
				<li><a href="member_index.php">Sign in</a></li><br>
				<!--這段更改--><a href="member_admin.php"><?php }
				elseif($_SESSION["loginMember"]=="admin"){ echo "<li><a href='admin_add.php'>管理公告</a></li>"."<li><a href='member_admin.php'>系統管理</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>"; }
				else{ echo "<li><a href='member_center.php'>會員中心</a></li>"."<li><a href='?logout=true'>Sign out&nbsp[".$_SESSION["loginMember"]."]</a></li>"; }?></a>
			</ul>
			</header>
			<div class="id_content">
				<div class="container" style="margin: auto;width:100%;">
				    <!--修改公告-->
					<table width="80%" align="center" border="0" id="bdstyle" cellpadding="0" cellspacing="0">
					  <tr>
						<td><table width="587" cellpadding="5" cellspacing="10" height="30">
							<tr>
							  <td align="center" style="border-bottom:solid; border-color: #fbd9de; color:#ed8496; font-size:23px; font-weight:bold" colspan="2">修改公告</td>
							</tr>
							<?php if(!isset($_POST["udata"])||$_POST["udata"]==""){?>
							<tr>
							  <td align="center" colspan="2" style="font-size:16px; color:#F00"> --------------------請選擇要修改的公告--------------------</td>
							</tr>
							<?php }else{?>
							<form action="" method="post" name="formAdd" id="formAdd" enctype="multipart/form-data" >
							  <tr>
								<td align="center" width = "50%"><div><img src="photos/<?php echo $news_data["news_picurl"]?>" width="200" /></div>
								  <br>
								  <input type="file" name="upicurl" id="upicurl"/></td>
								<td width = "50%" style="border-color:#fbd9de; border-style:solid"><h4>
									<input style="border-color:#ed8496" size="20" type="text" name="usubject" id="usubject" value="<?php echo $news_data["news_subject"]?>" />
								  </h4>
								  <textarea style="border-color:#ed8496" name="ucontent" id="ucontent" cols="37" rows="8"><?php echo $news_data["news_content"]?></textarea></td>
							  </tr>
							  <tr>
								<td colspan="2" align="center" style="border-bottom:solid; border-color:#ed8496; border-width: thin"><input name="action" type="hidden" id="action" value="update">
								  <input type="submit" id="button" name="update" value="確認修改" onclick="return checkupman();"/>
								  &nbsp;&nbsp;&nbsp;&nbsp;
								  <input type="reset" id="button" name="reset" value="復原"/>
								  <br />
								  <br /></td>
							  </tr>
							</form>
							<?php }?>
						  </table></td>
					  </tr>
					</table>
					<table width="80%" align="center" border="0" id="bdstyle" cellpadding="0" cellspacing="0">
						<tr>
							<td><table width="587" cellpadding="5" cellspacing="10" height="30">
								<tr>
									<td align="center" style="border-bottom:solid; border-color: #fbd9de; color:#ed8496; font-size:23px; font-weight:bold" colspan="2">目前公告內容</td>
								</tr>
								<?php while($row_RecBoard=$RecBoard->fetch_assoc()){ ?>
									<tr>
									  <td align="center" width = "50%"><img src="photos/<?php echo $row_RecBoard["news_picurl"]?>" width="200" /></td>
									  <td width = "50%" style="border-style:ridge;"><h4><?php echo $row_RecBoard["news_subject"]?></h4>
								<?php echo $row_RecBoard["news_content"]?></td>
									  <tr><td align="center">發布時間:<?php echo $row_RecBoard["news_time"]?></td></tr>
									</tr>
									<tr>
									  <td align="center" colspan="2" ><table>
										<tr>
											<td><form method="post" action="admin_add.php?id=<?php echo $row_RecBoard["news_id"];?>">
												<input name="action" type="hidden" id="action" value="udata">
												<input type="submit" id="button" name="udata" value="修改"/>
											  </form></td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td><form method="post">
												<input name="action" type="hidden" id="action" value="delete">
												<button id="button" name="delete" value="<?php echo $row_RecBoard["news_id"];?>" onclick="return confirm('確定刪除嗎？(一旦刪除便無法進行復原!)');">刪除</button>
											  </form>
										<td>
										</tr>
									</table>
									<br /></td>
									</tr>
									<?php }?>
								</table></td>
						</tr>
					</table>
					<?php if ($total_pages > 1) { ?>
					<table width="80%" align="center" id="bdstyle" cellspacing="0" cellpadding="5px">
					  <tr>
						<td align="left" height="20" style="color:#ed8496; font-size:18px;"><b>第 <?php echo $num_pages;?> 頁 | 共 <?php echo $total_pages;?> 頁</b></td>
						<td align="right" width="280px" height="20" style="color:#ed8496; font-size:18px; font-weight:700">&nbsp;&nbsp;<a href="?page=1"></a>&nbsp;&nbsp;<a href="?page=<?php echo $num_pages-1;?>"></a>&nbsp;&nbsp;
						  <?php for($i=1;$i<=$total_pages;$i++){?>
						  &nbsp;<a style="font-size:18px" href="?page=<?php echo $i;?>"><?php echo $i;?></a>&nbsp;
						  <?php }?>
						  <a href="?page=<?php echo $num_pages+1;?>"></a>&nbsp;&nbsp;<a href="?page=<?php echo $total_pages;?>"></a>&nbsp;&nbsp;</td>
					  </tr>
					</table>
					<?php }else{}?>
				
				<!--新增公告-->
				<table width="80%" align="center" border="0" id="bdstyle" cellpadding="0" cellspacing="0">
				  <tr>
					<td><table width="100%" cellpadding="5" cellspacing="10" height="30" style="">
						<form action="" method="post" enctype="multipart/form-data"  name="formPost" id="formPost" onSubmit="return checkForm(); ">
						  <tr>
							<td align="center" style="border-bottom:solid; border-color: #fbd9de; color:#ed8496; font-size:23px; font-weight:bold">新增最新公告</td>
						  </tr>
						  <tr>
							<td>發布者： <?php echo $_SESSION["id"]="admin"?></td>
						  </tr>
						  <tr>
							<td>公告主旨：
							  <div>
								<input  size="20" type="text" name="subject" id="subject" />
							  </div></td>
						  </tr>
						  <tr>
							<td>公告內容：
							  <div>
								<textarea name="content" id="content" cols="50" rows="8"></textarea>
							  </div></td>
						  </tr>
						  <tr>
							<td>上傳圖片：
							  <div>
								<input type="file" name="picurl" id="picurl" />
							  </div></td>
						  </tr>
						  <tr>
							<td><input name="action" type="hidden" id="action" value="add">
							  <input type="submit" id="button" name="add" value="送出" class="btn"/>
							  &nbsp;&nbsp;&nbsp;&nbsp;
							  <input type="reset" id="button" name="button" value="重填" class="btn"/></td>
						  </tr>
						</form>
					  </table></td>
				  </tr>
				</table>
				</div>
			</div>

			<footer class="id_footer">
			  Footer Block
			</footer>
		</div>
	</body>
</html>
<?php
 $db_link->close();
?>