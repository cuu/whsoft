<?php
include_once "header.php";
include_once "waibu.php";
include_once "cscheck.php";

include_once "../../function/conn.php";
include_once "../../function/md5.php"; // for crc32



$yusername = trim($_POST['yusername']);
$ypsw      = trim($_POST['ypsw']     );
$xusername = trim($_POST['xusername']);
$xpsw      = trim($_POST['xpsw']     );

if( strlen($yusername) < 1 ) 
{
	"<script language=javascript>alert('原帐号 不能为空！');window.history.back();</script>";
	die();
}

if( strlen( trim($ypsw)) < 1)
{
	echo "<script language=javascript>window.history.back();</script>";
	die();
}

if(strlen( trim($xpsw ) )< 1)
{
	echo "<script language=javascript>window.history.back();</script>";;
	die();
}

$sql = "select * from admin where username='".$yusername."'";

$handle = openConn();
if($handle ==NULL) die("DATA BASE ERROR".mysql_error());
$result = mysql_query($sql,$handle);
if($result === false) 
{
	closeConn($handle);
	echo "<script language=javascript>alert('原用户名输入有误！');window.history.back();</script>";
	die();
}
else
{
	$num = mysql_num_rows($result); 
	if($num <= 0)
	{
		closeConn($handle);
		echo "<script language=javascript>alert('原用户名输入有误！');window.history.back();</script>";
		die();
	}
	else
	{
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		if( strcmp( $row['passwd'] , g_CRC32( $ypsw ) ) == 0) // yuan pass is right
		{
			if($xusername == "")
				$xusername = $yusername;
			if($xpsw !="")
				$xpsw = g_CRC32($xpsw);
			else
				$xpsw = $ypsw;

			$sql2 = "update admin set username='".$xusername."', passwd='".$xpsw."' where username='".$yusername."'";
			$result = mysql_query($sql2,$handle);
			if($result===false)
			{  
				closeConn($handle);
				echo "<script language=javascript>alert('管理员帐号设置有错 '".mysql_error().");'</script>";
			}
			else
			{
				closeConn($handle);
				echo "<script language=javascript>alert('管理员帐号设置成功;请记住您的新帐号信息!');window.location.href='admin_user.php'</script>";
				die();
			}	

		}
		else
		{
			closeConn($handle);
			echo "<script language=javascript>alert('原密码输入有误！');window.history.back();</script>";
		}
	}
}

?>
