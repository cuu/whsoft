<?php
session_start();
include_once "header.php";
include_once "cscheck.php"; // for dev
include_once "../../function/conn.php";
include_once "../../function/function.php";



?>


<?php
	$action = getFormValue("action");
	if($action == "gcheck") group_check();
	if($action == "pcheck") proxy_check();



function group_check()
{
	$name = getFormValue("gname");
	//var_dump($_GET);
	$sql = "select count(id) from usergroup where groupname ='".$name."'";
	$handle = openConn();
	if($handle ==NULL) die( "mysql error". mysql_error() );
	$result = mysql_query($sql,$handle);
	if( $result !== false)
	{
		$row1 = mysql_fetch_array($result,MYSQL_NUM);
		if($row1[0] > 0){ closeConn($handle); echo "1"; return; }
		else { closeConn($handle); echo "0"; return; }
	}
	else {
		closeConn($handle);
		echo "2";
		return;
	}
}
function proxy_check()
{
	$name = getFormValue("pname");
	$sql = "select count(id) from proxy where name = '".$name."'";
	$t1 = 0;
	$t2 = 0;

	$handle = openConn();
	if($handle ==NULL) die( "mysql error". mysql_error() );
	$result = mysql_query($sql,$handle);
	if( $result !== false)
	{
		$row1 = mysql_fetch_array($result,MYSQL_NUM);
		if($row1[0] > 0){  $t1 = 1;  }
		else 
		{
			$sql2 = "select * from  admin where username='".$name."'";
			$res2 = mysql_query($sql2,$handle);
			if($res2!==false)
			{
				$num = mysql_num_rows($res2);
				if($num > 0)  $t2 = 1;
			}else echo "2"; 
		}
	}
	else
	{
		closeConn($handle);
		echo "2";
	}
	closeConn($handle);
	if( $t1 == 1 || $t2 ==1 ) 
		echo "1";
	if( $t1 == 0 && $t2 == 0)
		echo "0";
	return;
}

?>
