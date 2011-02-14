<?php
include_once "../../function/conn.php";
include_once "../../function/sendNote.php";

$action = lcase( getFormValue("action"));
$yhmc = getFormValue("D_yhmc"); $lxdz = getFormValue("D_lxdz"); $gddh = getFormValue("D_gddh"); $yddh  = getFormValue("D_yddh"); 
$DiskId = lcase( getFormValue("DiskId"));
$zhmc = getFormValue("D_zhmc"); $zh   = getFormValue("D_zh"  ); $zhlx = getFormValue("D_zhlx"); $zcfsm = getFormValue("D_zcfsm");
$serverame = getFormValue("D_serverame");  $zhye = getFormValue("D_zhye"); $rjbb = getFormValue("D_rjbb");
//zh is mt4 trader account number
$msgtime = getFormValue("D_msgtime"); // vip msg timestamp,like xxxx-xx-xx xx:xx:xx


$nowDate = Format_Date( time() );
$nowTime = Format_Time( time() );
$temDate= Format_Date( time() + 86400 *15 ); //默认试用期限15天

$returnValue = 0;
if ( strcmp( $action, "vipnews"  ) == 0)
{
	$returnMsg = VipMsg($DiskId,$msgtime);
	echo $returnMsg;
}
if ( strcmp( $action, "softfind" ) == 0)
{
	$returnValue = SoftFind($DiskId);
	echo strval($returnValue);
}
 
if ( strcmp( $action, "SoftSX"   ) == 0)
{
	$returnValue = SoftSX($DiskId,$rjbb,$zhmc,$zh,$zhlx,$zcfsm,$serverame,$zhye,$nowDate,$nowTime);
	echo strval($returnValue);
}

if ( strcmp( $action, "softin"   ) == 0)
{
	$returnValue = softIn($DiskId,$yhmc,$lxdz,$gddh,$yddh,$nowDate,$nowDate,2,"",$temDate,$nowTime,$rjbb,$zh,$zhlx,$zcfsm,$serverame,$zhye);
	echo strval($returnValue);
}

function VipMsg( $f_DiskId,$f_time )
{
	$ret = "";
	$sql = "select * from vipmsg order by time";
	$handle = openConn();  if ($handle ==NULL) {  /* 数据库　连接失败　*/ return "3";}
	
	$result = mysql_query($sql, $handle);
	$num = mysql_num_rows($result);
	if($num > 0)
	{
		for($i = 0; $i < $num ; $i++)
		{
			$row = mysql_fetch_array($result,MYSQL_NUM);
		
			$ret .= implode($row);
		}
		closeConn($handle);
		return $ret;	
	}else 
	{
		closeConn($handle);	
		return $ret;
	}
 
}// end VipMsg( $f_DiskId )

function SoftFind( $f_DiskId )
{
	$sql = "select zt from softsetup where diskid='".$f_DiskId."'";
	$handle = openConn();  if ($handle ==NULL) {  /* 数据库　连接失败　*/ return 3;}

	$result = mysql_query($sql,$handle);
	$res = mysql_num_rows($result);
	if($res > 0)
	{
		$row = mysql_fetch_array($result,MYSQL_NUM);
		//mysql_free_result($result);
		closeConn($handle);
		return $row[0];	// 返回状态，　1完全使用　2帐户锁定	
		
	}
	//mysql_free_result($result);
	closeConn($handle);	
	return 0; // 未注册
}

function  softIn($f_DiskId,$f_yhmc,$f_lxdz,$f_gddh,$f_yddh,$f_zcrq,$f_rjjsrq,$f_zt,$f_bz,$f_zhsxrq,$f_zhsxsj,$f_rjbb,$f_zh,$f_zhlx,$f_zcfsm,$f_servername,$f_zhye)
{
	$res = softfind( $f_DiskId);
	if ( $res !=  0 )
	{
		return 2;
	}

	$sql = "insert into softsetup(diskid,yhmc,lxdz,gddh,yddh,zcrq,rjjsrq,zt,bz,zhsxrq,zhsxsj) values('".$f_DiskId."','".$f_yhmc."','".$f_lxdz."','".$f_gddh."','".$f_yddh."','".$f_zcrq."','".$f_rjjsrq."',".$f_zt.",'".$f_bz."','".$f_zhsxrq."','".$f_zhsxsj."')";
	
	$handle = openConn();
	if ($handle ==NULL)  return 0;
	$result = mysql_query($sql, $handle);
	if($result == FALSE) { closeConn($handle); return 0; }
	$sql = "insert into userzhb(diskid,rjbb,zhmc,zh,zhlx,zcfsm,serverame,zhye,zhsxrq,zhsxsj) values('".$f_DiskId."',".intval($f_rjbb).",'".$f_yhmc."','".$f_zh."',".$f_zhlx.",'".$f_zcfsm."','".$f_servername."',".$f_zhye.",'".$f_zhsxrq."','".$f_zhsxsj."')";
	$result = mysql_query($sql, $handle);
	if($result == FALSE) { closeConn($handle); return 0; }
	else
	{
		//mysql_free_result($result);
		//send success sms to user
		$sql="select * from note where id=1";
		$result = mysql_query($sql, $handle);
		$res = mysql_num_rows($result);
		if($res > 0)
		{
			$row = mysql_fetch_array($result,MYSQL_NUM);
			if(  intval( $row[3] ) ==1)
			{
				sendMsg($f_yddh,urlencode( $row[2]));
			}
		}
		
		//mysql_free_result($result);
		closeConn($handle);
		return 1;
	} 	



	
}

function SoftSX($f_DiskId,$f_rjbb,$f_zhmc,$f_zh,$f_zhlx,$f_zcfsm,$f_serverame,$f_zhye,$f_zhsxrq,$f_zhsxsj)
{
	global $nowDate;
	$ssx =0;
	$endDate = "";
	$sql = "select zt,rjjsrq from softsetup where diskid='".$f_DiskId."'";
	$handle =openConn();
	if($handle == NULL) return 0;
	$result = mysql_query($sql,$handle);
	if($result != FALSE)
	{
		$num = mysql_num_rows($result);
		if($num > 0 )
		{
			$row = mysql_fetch_array($result, MYSQL_NUM);
			$ssx = -($row[0]+1);	// 1 in use 2 locked
			$endDate = trim( $row[1] );	
		}
		else
		{
			$ssx = -4; // not registered
		}
	}else $ssx = 0;	
	if( $ssx != -2) 
	{
		closeConn($handle);
		return $ssx;
	}

	$sql="select count(id) from userzhb where diskid='".$f_DiskId."' and zh='".$f_zh."'";
	$result = mysql_query($sql,$handle);
	if($result != FALSE)
	{
		$row = mysql_fetch_array($result, MYSQL_NUM);
		if($row > 0)
		{
			$sql="update userzhb set diskid='".$f_DiskId."',rjbb=".intval($f_rjbb).",zhmc='".$f_zhmc."',zh='".$f_zh."',zhlx=".$f_zhlx.",zcfsm='".$f_zcfsm."',serverame='".$f_serverame."',zhye=".$f_zhye.",zhsxrq='".$f_zhsxrq."',zhsxsj='".$f_zhsxsj."' where diskid='".$f_DiskId."' and zh='".$f_zh."'";
		}else
		{
			$sql="insert into userzhb(diskid,rjbb,zhmc,zh,zhlx,zcfsm,serverame,zhye,zhsxrq,zhsxsj) values('".$f_DiskId."',".intval($f_rjbb).",'".$f_zhmc."','".$f_zh."',".$f_zhlx.",'".$f_zcfsm."','".$f_serverame."',".$f_zhye.",'".$f_zhsxrq."','".$f_zhsxsj."')";
		}
		$result = mysql_query($sql,$handle);
		if($result != FALSE)
		{
			$sql="update softsetup set zhsxrq='".$f_zhsxrq."',zhsxsj='".$f_zhsxsj."' where diskid='".$f_DiskId."'";
			$result = mysql_query($sql, $handle);
			if( $result == FALSE) return 0;
			else
			{
				if ( DateDiff("d",$nowDate,$endDate) <= 0) return -1;
				else 
				{
					//mysql_free_result($result);
					closeConn($handle);
					return intval(DateDiff("d",$nowDate,$endDate));	
				}
			}
		} else return 0;
		
	
	}
	else
	{
		closeConn($handle); return 0;
	}
	
}

?>
