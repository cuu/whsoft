<?php
session_start();
include_once "header.php";
include_once "waibu.php";
include_once "cscheck.php";

include_once "../../function/conn.php";
include_once "../../function/md5.php"; // for crc32



$yusername = trim($_POST['yusername']);
$ypsw      = trim($_POST['ypsw']     );
$xusername = trim($_POST['xusername']);
$xpsw      = trim($_POST['xpsw']     );
//$add_username = trim($_POST["add_username"] );
//$add_psw      = trim($_POST["add_psw"]);
$add_new_user = trim($_POST["add_new_user"] );
var_dump($_POST);
if(strcmp($add_new_user,"add") == 0)
  {
    // add new user
    $add_username = trim($_POST["add_username"] );
    $add_psw      = trim($_POST["add_psw"]);
    $u_type       = trim($_POST["u_type"] );
    if( strlen( trim($add_username)) < 1)  
      	echo "<script language=javascript>alert('�ʺ����Ʋ���Ϊ�գ�');window.history.back();</script>";
    if( strlen( trim($add_psw)) < 1)
        echo "<script language=javascript>alert('�ʺ����벻��Ϊ�գ�');window.history.back();</script>";
    
    $handle = openConn();
    if($handle == NULL) die();
    $sql = "select * from admin where username='".$add_username."'";
    $result = mysql_query($sql,$handle);
    if($result)
      {
	$num = mysql_num_rows($result);
	if($num > 0)
	  {
	    echo "<script language=javascript>alert('�ʺ��Ѵ���,��ȷ�������Ƿ���ȷ��');window.history.back();</script>";
	  }
	else
	  {
	    $sql = "insert into admin(username,passwd,type) values('".$add_username."','".g_CRC32($add_psw)."',".intval($u_type).")";
	    $result1 = mysql_query($sql,$handle);
	    if($result1)
	      {
		closeConn($handle);
		echo "<script language=javascript>alert('����Ա�ʺ���ӳɹ�;���ס�������ʺ���Ϣ!');window.location.href='admin_user2.php'</script>";
		die();
	      }
	    else
	      {
		closeConn($handle);
		echo "<script language=javascript>alert('����Ա�ʺ�����д� '".mysql_error().");'</script>";
		die();
	      }
	  }
      }else die("mysql_query failed".mysql_error());
  
    return;
  }

if( strlen( trim($yusername)) < 1 ) 
{
	echo "<script language=javascript>alert('ԭ�ʺŲ���Ϊ�գ�');window.history.back();</script>";
	die();
}

if( strlen( trim($ypsw)) < 1)
{
	echo "<script language=javascript>alert('ԭ���벻��Ϊ��!');window.history.back();</script>";
	die();
}

if(strlen( trim($xpsw ) )< 1)
{
	echo "<script language=javascript>alert('�����벻��Ϊ��!');window.history.back();</script>";;
	die();
}

$sql = "select * from admin where username='".$yusername."'";

$handle = openConn();
if($handle ==NULL) die("DATA BASE ERROR".mysql_error());
$result = mysql_query($sql,$handle);
if($result === false) 
{
	closeConn($handle);
	echo "<script language=javascript>alert('ԭ�û�����������');window.history.back();</script>";
	die();
}
else
{
	$num = mysql_num_rows($result); 
	if($num <= 0)
	{
		closeConn($handle);
		echo "<script language=javascript>alert('ԭ�û��������ڣ�');window.history.back();</script>";
		die();
	}
	else
	{
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		if( strcmp( strval($row['type']), $_SESSION["zz"] ) == 0 && strcmp( $row['passwd'] , g_CRC32( $ypsw ) ) == 0) // yuan pass is right
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
				echo "<script language=javascript>alert('����Ա�ʺ������д� '".mysql_error().");'</script>";
			}
			else
			{
				closeConn($handle);
				echo "<script language=javascript>alert('����Ա�ʺ����óɹ�;���ס�������ʺ���Ϣ!');window.location.href='admin_user.php'</script>";
				die();
			}	

		}
		else if( intval($_SESSION["zz"]) > $row['type'] ) // IF USER IS BLEW  
		  {
		    closeConn($handle);
		    echo "<script language=javascript>alert('�ʺ���������');window.history.back();</script>";
		  }
		else
		{
			closeConn($handle);
			echo "<script language=javascript>alert('ԭ������������');window.history.back();</script>";
		}
	}
}

?>
