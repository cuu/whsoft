<?php
session_start();
include_once "header.php";
include_once "waibu.php";

function CodeIsTrue( $b)
{
	if( strval( $_SESSION['GetCode'])  != strval( trim( $_POST['code'])))
	{
		$_SESSION['GetCode'] = rand();
	
		if ($b == "")
		{
			echo "<script language=JavaScript>" . chr(13) . "alert('验证码错误，请重新输入！');" . "window.location.href='login.asp'" . "</script>" ;
	
		}else
		{
			echo  "<script language=JavaScript>" . chr(13) . "alert('验证码错误，请重新输入！');"."window.location.href = '".$b."'"." </script>";
		}
		die();
	}
} // end  - func

	CodeIsTrue("");
	if( trim( $_REQUEST["adminname"] ) =="") {
		echo "<script language=javascript>alert('管理员不能为空');window.location.href='login.asp';</script>";
		die();
	}
	if( trim( $_REQUEST["adminpsw"]) =="") {
		echo "<script language=javascript>alert('密码不能为空');window.location.href='login.asp';</script>";
		die();
	}
?>
<?php
include_once "../../function/conn.php";
include_once "../../function/md5.php"; // for crc32 encrypt
$handle = openConn();
if ($handle == NULL) die();


$sql = "select * from admin where username='".trim($_POST['adminname'])."'";
$result = mysql_query($sql,$handle);
$res = mysql_num_rows($result);
if($res > 0)
{
	//$psw = sprintf("%u",crc32( strval( $_POST['adminpsw']."jc")));
	$psw = g_CRC32($_POST['adminpsw']);

	while ($row = mysql_fetch_array($result, MYSQL_NUM)) 
	{
		//must %u 
		if ( strcmp( $psw,  $row[1] ) == 0)
		{	
			$_SESSION["yhgl"] = trim( $_POST['adminname'] );
			//ini_set('session.gc_maxlifetime', 60*20);
			setcookie("yhgl", "jcok"  , time()+60*20);
			setcookie("zz"  , $row[2] , time()+60*20); // user type
			// set session and cookie expire time
				
			//mysql_free_result($result);
			closeConn($handle);
			echo "<script language=javascript>window.location.href='main.asp';window.parent.mainFrame.location.href='index.php';</script>";
			//echo "echo main.php";
			return;
			
		}
	}
	//mysql_free_result($result);
	//closeConn($handle);
	echo "<script language=javascript>alert('密码不正确！');window.location.href='login.asp';</script>";
	//mysql_free_result($result);
	//echo "not right passwd";
	closeConn($handle);
	die();
}else
{	
	closeConn($handle);
	echo "<script language=javascript>alert('管理员不正确！');window.location.href='login.asp';</script>";
	die('Invalid query: ' . mysql_error());
}

closeConn($handle);
?>



