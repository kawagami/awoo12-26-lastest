<?php
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

if(isset($_POST["action"])&&($_POST["action"]=="join")){
	require_once("connMysql.php");
	//找尋帳號是否已經註冊
	$query_RecFindUser = "SELECT m_username FROM memberdata WHERE m_username='{$_POST["m_username"]}'";
	$RecFindUser=$db_link->query($query_RecFindUser);
	if ($RecFindUser->num_rows>0){
		header("Location: member_join.php?errMsg=1&username={$_POST["m_username"]}");
	}else{
	//若沒有執行新增的動作	
		$query_insert = "INSERT INTO memberdata (m_name, m_username, m_passwd, m_sex, m_birthday, m_email, m_phone, m_address, m_jointime) VALUES (?, ?, ?, ?, ?, ?, ?, ?,  NOW())";
		$stmt = $db_link->prepare($query_insert);
		$stmt->bind_param("ssssssss", 
			GetSQLValueString($_POST["m_name"], 'string'),
			GetSQLValueString($_POST["m_username"], 'string'),
			password_hash($_POST["m_passwd"], PASSWORD_DEFAULT),
			GetSQLValueString($_POST["m_sex"], 'string'),
			GetSQLValueString($_POST["m_birthday"], 'string'),
			GetSQLValueString($_POST["m_email"], 'email'),
			GetSQLValueString($_POST["m_phone"], 'string'),
			GetSQLValueString($_POST["m_address"], 'string'));
		$stmt->execute();
		$stmt->close();
		$db_link->close();
		header("Location: member_join.php?loginStats=1");
	}
}
?>
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
			if(document.formJoin.m_username.value==""){		
				alert("請填寫帳號!");
				document.formJoin.m_username.focus();
				return false;
			}else{
				uid=document.formJoin.m_username.value;
				if(uid.length<5 || uid.length>12){
					alert( "您的帳號長度只能5至12個字元!" );
					document.formJoin.m_username.focus();
					return false;}
				if(!(uid.charAt(0)>='a' && uid.charAt(0)<='z')){
					alert("您的帳號第一字元只能為小寫字母!" );
					document.formJoin.m_username.focus();
					return false;}
				for(idx=0;idx<uid.length;idx++){
					if(uid.charAt(idx)>='A'&&uid.charAt(idx)<='Z'){
						alert("帳號不可以含有大寫字元!" );
						document.formJoin.m_username.focus();
						return false;}
					if(!(( uid.charAt(idx)>='a'&&uid.charAt(idx)<='z')||(uid.charAt(idx)>='0'&& uid.charAt(idx)<='9')||( uid.charAt(idx)=='_'))){
						alert( "您的帳號只能是數字,英文字母及「_」等符號,其他的符號都不能使用!" );
						document.formJoin.m_username.focus();
						return false;}
					if(uid.charAt(idx)=='_'&&uid.charAt(idx-1)=='_'){
						alert( "「_」符號不可相連 !\n" );
						document.formJoin.m_username.focus();
						return false;}
				}
			}
			if(!check_passwd(document.formJoin.m_passwd.value,document.formJoin.m_passwdrecheck.value)){
				document.formJoin.m_passwd.focus();
				return false;}	
			if(document.formJoin.m_name.value==""){
				alert("請填寫姓名!");
				document.formJoin.m_name.focus();
				return false;}
			if(document.formJoin.m_birthday.value==""){
				alert("請填寫生日!");
				document.formJoin.m_birthday.focus();
				return false;}
			if(document.formJoin.m_email.value==""){
				alert("請填寫電子郵件!");
				document.formJoin.m_email.focus();
				return false;}
			if(!checkmail(document.formJoin.m_email)){
				document.formJoin.m_email.focus();
				return false;}
			return confirm('確定送出嗎？');
		}
		function check_passwd(pw1,pw2,uid){
			if(pw1==''){
				alert("密碼不可以空白!");
				return false;}
			for(var idx=0;idx<pw1.length;idx++){
				if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
					alert("密碼不可以含有空白或雙引號 !\n");
					return false;}
				if(pw1.length<5 || pw1.length>10){
					alert( "密碼長度只能5到10個字母 !\n" );
					return false;}
				if(pw1!= pw2){
					alert("密碼二次輸入不一樣,請重新輸入 !\n");
					return false;}
				if(pw1==document.formJoin.m_username.value){
					alert("帳號與密碼不可相同 !\n")
					return false;
				}
			}
			return true;
		}
		function checkmail(myEmail) {
			var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-z\-])+\.)+([a-z]{2,4})+$/;
			if(filter.test(myEmail.value)){
				return true;}
			alert("電子郵件格式不正確");
			return false;
		}
		</script>
	</head>
    <body style="font-family: Microsoft JhengHei;">
	<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
	<script language="javascript">
	alert('會員新增成功\n請用申請的帳號密碼登入。');
	window.location.href='member_index.php';		  
	</script>
	<?php }?>
	<div class="id_wrapper">
		<header class="header">
            <a href="index2.php" class="logo" style="text-decoration:none;color:#fff;">來吃飯</a>
            <input class="menu-btn" type="checkbox" id="menu-btn" />
            <label class="menu-icon" for="menu-btn"><span class="nav-icon"></span></label>
			<div class="myNav">
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
			</div>
        </header>
		<div class="id_content" align="center" style="background-color: #7b7b7b33;">
			<div class="container" style="margin: auto;width:100%;"> 
			<!-- 　<div style="margin:0 auto;border: 2px solid blue; width:80%"> -->
				<table>
				  <tr>
					<td><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
					<h1 class="title" align="center">加入會員</h1>
					  <hr style="border: 0;    height: 1px;    background: #333;    background-image: linear-gradient(to right, #ccc, #333, #ccc);">
					  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
					  <div class="errDiv">帳號 <?php echo $_GET["username"];?> 已經有人使用！</div>
					  <?php }?>
					  <div class="dataDiv">
					  <br>
						<p class="heading"><span class="heading-style">帳號資料</span></p>
						<p><strong>使用帳號</strong>：
						<input name="m_username" type="text" class="normalinput" id="m_username">
						<font color="#FF0000">*</font><br><span class="smalltext">請填入5~12個字元以內的小寫英文字母、數字、以及_ 符號。</span></p>
						<p><strong>使用密碼</strong>：
						<input name="m_passwd" type="password" class="normalinput" id="m_passwd">
						<font color="#FF0000">*</font><br><span class="smalltext">請填入5~10個字元以內的英文字母、數字、以及各種符號組合，</span></p>
						<p><strong>確認密碼</strong>：
						<input name="m_passwdrecheck" type="password" class="normalinput" id="m_passwdrecheck">
						<font color="#FF0000">*</font> <br><span class="smalltext">再輸入一次密碼</span></p>
						<br>
						<p class="heading"><span class="heading-style">個人資料</span></p>
						<p><strong>真實姓名</strong>：
						<input name="m_name" type="text" class="normalinput" id="m_name">
						<font color="#FF0000">*</font></p>
						<p><strong>性　　別</strong>：
						<input name="m_sex" type="radio" value="女" checked>女
						<input name="m_sex" type="radio" value="男">男
						<font color="#FF0000">*</font></p>
						<p><strong>生　　日</strong>：
						<input name="m_birthday" type="date" class="normalinput" id="m_birthday">
						<font color="#FF0000">*</font> <br>
						<span class="smalltext"></span></p>
						<p><strong>電子郵件</strong>：
						<input name="m_email" type="text" class="normalinput" id="m_email">
						<font color="#FF0000">*</font><br><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span></p>

						<p><strong>電　　話</strong>：
						<input name="m_phone" type="text" class="normalinput" id="m_phone"></p>
						<p><strong>住　　址</strong>：
						<input name="m_address" type="text" class="normalinput" id="m_address" size="40"></p>
						<p> <font color="#FF0000">*</font> 表示為必填的欄位</p>
					  </div>
					  <p align="center">
						<input name="action" class="button button1" type="hidden" id="action" value="join">
						<input type="submit" class="button button1"  name="Submit2" value="送出申請">
						<input type="reset" class="button button1" name="Submit3" value="重設資料">
						<input type="button" class="button button1" name="Submit" value="回上一頁" onClick="window.history.back();">
					  </p>
					</form>
					</td>
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