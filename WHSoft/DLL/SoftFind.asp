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
	//select * from vipmsg  where  ( DateDiff(time, '2011-01-27 11:22:22') = 0 ) order by time;
	

	$now_date = date("Y-m-d H:i:s");
//	$f_time = $now_date;
	$ret = "";
	$sql2 = "select id,yhlx from softsetup where diskid='".trim($f_DiskId)."'";
	$sql = "select * from vipmsg where ( DateDiff(time,'".$f_time."')=0 ) and sfqy=1 order by time";
	$handle = openConn();  if ($handle ==NULL) {  /* 数据库　连接失败　*/ return "3";}
	$result = mysql_query($sql2,$handle);
	if($result)
	{
		$num = mysql_num_rows($result);
		if($num > 0)
		{
			$rowid = mysql_fetch_array($result,MYSQL_ASSOC);
			$row_id = $rowid["id"];
			$row_yhlx = $rowid["yhlx"];
	//		echo "user id=".$row_id."\n";
	//		echo "yhlx=".$row_yhlx."\n";
		}else
		{
			return "-1"; // user does not existed
		}
	}else { closeConn($handle); return "3"; }
	$num = 0;
	$result = mysql_query($sql, $handle);
	$num = mysql_num_rows($result);
	$ret_array= array();
	if($num > 0)
	{
		for($i = 0; $i < $num ; $i++)
		{
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$t_in_group = trim( $row["ingroup"] );
			if( strstr($t_in_group,","))
			{
			//	echo "t_in_group=".$t_in_group."\n";
				$in_group_array = explode("," , $t_in_group);
				for($i2 = 0; $i2 < count($in_group_array); $i2++)
				{
					if( is_numeric($in_group_array[$i2]))
					{
						// search from usergroup ,this $in_group_array[$i2] is the group id
						//select * from usergroup where FIND_IN_SET('315',groupusers);
						// 如果 一个用户即是 vip又在某一群中,并且这个消息包括了allvip和这个群,这样,用户将重复收到两条一样的消息
						$sql6 = "select * from usergroup where FIND_IN_SET('".$row_id."', groupusers)";
						$result6 = mysql_query($sql6,$handle);
						if($result6)
						{
							$num6 = mysql_num_rows($result6);
							if($num6 > 0)
							{
								//$ret .= implode("|",$row); $ret .="\n";
								array_push($ret_array,$row);
							}
						}
						
					}else if( $in_group_array[$i2] =="allNOR" && intval($row_yhlx) == 0)
					{
						// the user is normal and there is a vipmsg for NORmal users
					//	$row["ingroup"]="";
					//	$ret .= implode("|",$row); $ret .="\n";
						array_push($ret_array,$row);
					}
					else if( $in_group_array[$i2] =="allVIP" && intval($row_yhlx) == 1)
					{
						// familiar, the user is VIP and there is a msg for VIP users...
					//	$ret .=implode("|",$row); $ret .="\n";
						array_push($ret_array,$row);						

					}else if( $in_group_array[$i2] =="allGRP")
					{
						//broadcast for all users
					//	$ret .= implode("|",$row); $ret .="\n";
						array_push($ret_array,$row);
					}
				}
			}else if( $t_in_group == "allNOR" &&  intval($row_yhlx) == 0)
			{
			//	$ret .= implode("|",$row); $ret .="\n";
				array_push($ret_array,$row);
			}
			else if( $t_in_group == "allVIP" && intval($row_yhlx) == 1)
			{
			//	$ret .= implode("|",$row); $ret .="\n";
				array_push($ret_array,$row);
			}
			else if($t_in_group == "allGRP")
			{
				//$ret .= implode("|",$row); $ret .="\n";
				array_push($ret_array,$row);
			}
			
		}//end for i=0; i< num...
		closeConn($handle);
		//检查是否有重复的消息,重新组织最后的消息内容
		$ret_array2 = array();	
		foreach($ret_array as $key=> $ret_value)
		{
			$gt=0;
			for($r=$key+1; $r < count($ret_array); $r++)
			{
				if( strcmp( $ret_value["content"] , $ret_array[$r]["content"]) == 0)
				{
					$gt=1;
				}
			}
			if($gt==0)
			{
				array_push($ret_array2, $ret_value);
			}
		}
		for($zx = 0; $zx < count($ret_array2); $zx++)
		{
			$ret_array2[$zx] = implode("|",$ret_array2[$zx]);
		}
		$ret = implode("\n",$ret_array2);
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
