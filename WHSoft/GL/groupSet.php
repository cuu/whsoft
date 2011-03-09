<?php
session_start();
if(isset($_COOKIE["gmemb"]))
{
	setcookie("gmemb","");
}

include_once "header.php";
//include_once "cscheck.php"; // for dev
include_once "../../function/conn.php";
include_once "../../function/function.php";

include_once "../../function/xdownpage.php";

?>


<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="images/all-ie.css" />
<![endif]-->

<title>用户群编制设置</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>

<script language="javascript">
	$(document).ready(
		function() 
		{
			$("#page_table_bar").width( $("#group_out_list").width() );
		}
	);

	$(function() 
	{
 

	});

function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value= encodeURIComponent( String(value) ) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function edit_a_click(str1,str2)
{

//  String(value);
	setCookie("gname", str1);
	setCookie("gmemb", str2);
}
</script>

<style type="text/css">
#group_out_list
{
  margin:8px;
  border:1px solid #bbb;
  border-bottom:none;
}
#group_out_list td
{
  border-bottom:1px solid #ccc;
  text-overflow:ellipsis;
  overflow:hidden;white-space: nowrap;
}
</style>

</head>
<body>

<?php
	$action      = getFormValue("action")     ;
	$id          = getFormValue("id")         ;
        $pg          = getFormValue("pg");

if($id == "" ) $id=0;
if($action == "save") group_save();
else if($action == "del")  group_del();
else if($action == "edit") group_edit();
else if($action == "show") group_show();
else
{
		  //list all groups
		  /*
		    [delete]---[motify]----name---members----time---  

		   */
		$sql = "select * from usergroup";
                if( $pg!="")
                {
                  $sql2 = $sql;
                  $sql .= " LIMIT ".((intval($pg)-1)*20).", 20";	
	 
                }
                else
                {
                  $sql2 = $sql; 
                  $sql .= " LIMIT 0 , 20";
                }
	
		$handle = openConn();
		if($handle ==NULL) die( "mysql error". mysql_error() );
                $result = mysql_query($sql2,$handle);
                if( $result !== false)
                {
                    $all = mysql_num_rows($result);
                    $all_num = $all;
                }
                else { die("mysql error".mysql_error()); }

		$result = mysql_query($sql,$handle);
		if($result ===false)
		{
		    echo "Search mysql error()".mysql_error()."<br />";
		    closeConn($handle);
		    die();	
		}
		$num = mysql_num_rows($result);	
		if($num > 0)
		{
	    
?>
<br />
  <div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		      <span style="font-size:20px;" class="biaoti_guu" >用户群编制设置</span>
  </div>
  <div>
  <a style="width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_group.php" >创建新的群</a>
  </div>

<table  id ="group_out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#fff" bordercolordark="#fff" style="border-collapse: collapse; table-layout:fixed;width:600px;WORD-BREAK: break-all;" bordercolor="#fff"  >
      <tr height='30' bgcolor='#000000'  >
       <td width="150" class="tdbiaoti">确认操作</td>
       <td width="240" height="30"  class="tdbiaoti">群名子</td>
       <td width="120" class="tdbiaoti">群成员</td>
       <td width="120" class="tdbiaoti">更新时间</td>
      <!--  <td width="120" class="tdbiaoti">使用设置</td> -->

       <td></td>
	</tr>
<?php

			$row = mysql_fetch_array($result,MYSQL_ASSOC); 
			for( $i = 0; $i < $num; $i++)
			{   
?>
                      <tr height='25' style="overflow:hidden;border-bottom:1px solid #ccc;">
                       <td align="center"  >
			<a class="del"  onClick="return confirm('删除该群,您确定进行删除操作吗？')"   href="groupSet.php?action=del&id=<?php  echo trim($row["id"]); ?>">删除</a> &nbsp;
                         <a  class="edit" style="color:blue;"  onclick="edit_a_click('<?php echo $row["groupname"];?>','<?php echo $row["groupusers"]; ?>')" id="edit_a"  href="edit_group.php?action=edit&id=<?php echo trim($row["id"]);?>" >修改</a>
		    &nbsp;&nbsp;
                         <a href="groupSet.php?action=show&id=<?php echo trim($row["id"]); ?>" id="show_memb" >查看成员</a>
                       </td>
                       <td nowrap  style=" overflow:hidden;width:240px;height:25px;" align="center">
                            <?php echo trim($row["groupname"]); ?>
                       </td>
		      <td align="center">
		      <?php
			      $array = explode(",",$row["groupusers"]);
				echo count($array)."人"; 
		      ?>
		      </td>

                       <td align="center">
                            <?php echo trim( date("Y年n月j日",strtotime($row["date"])) ); ?>
                       </td>
                       <td align="center"></td>
                      </tr>


<?php
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
			}
?>
     </table>
     <table id='page_table_bar' style="margin-left:8px;margin-right:8px;" width="100%" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#ebeff9>
       <tr><td>
<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; 页次：".$a->pg."/".$a->page."&nbsp; 共".$a->countall."条记录&nbsp; ".$a->countlist."条/页";
			
?></td></tr>
     </table>
<?php
		}// end if($num > 0)
		else
		{
?>
<br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >目前没有群,请您添加</span>
</div>
<div>
<a style="font-family:simhei;width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_group.php" >创建新的群</a>
</div>
<?php
		}// end else 
?>
<?php
}//end 1 else
?>
</body>
</html>
<?php
function group_save()
{
	//array(4) { ["action"]=> string(4) "save" ["sub_name"]=> string(6) "第一群" ["sub_memb"]=> string(43) "287,281,279,294,299,303,325,322,272,273,401" ["g_checkbox"]=> string(0) "" }

	$sub_name = getFormValue("sub_name");
	$sub_memb = getFormValue("sub_memb");
	$sql = "insert into usergroup(groupname,groupusers,date) values('".$sub_name."','".$sub_memb."',NOW() ) ";
	$handle = openConn();
	if($handle == NULL) die( "mysql_error".mysql_error());
	$result = mysql_query($sql,$handle);
	if($result === false)
	{
		echo "insert to group db error".mysql_error();
		closeConn($handle);
		die();
	}else
	{
      		echo "创建群成功！";
	//	$_COOKIE["gname"] ="";
	//	$_COOKIE["gmemb"] ="";
//		setcookie("gname", "");
//		setcookie("gmemb", "");

        	closeConn($handle);
		die();
	}
	return; 
}

function group_edit()
{
//	var_dump($_POST);
	$sub_name = getFormValue("sub_name");
	$sub_memb = getFormValue("sub_memb");
	$sql = "update usergroup set groupname='".$sub_name."', groupusers='".$sub_memb."', date= NOW()";
	$handle = openConn();
	if($handle == NULL) die( "mysql_error".mysql_error());
	$result = mysql_query($sql,$handle);
	if($result === false)
	{
		echo "Edit group db error ".mysql_error();
		closeConn($handle);
		die();
	}else
	{
	//	$_COOKIE["gname"] ="";
	//	$_COOKIE["gmemb"] ="";		
      		echo "修改群内容成功!";

        	closeConn($handle);
	}
	return; 	
}

function group_del()
{
	$id = getFormValue("id");
	$sql = "delete from usergroup where id=".$id;
	$handle = openConn();
	if($handle == NULL) die( "mysql_error".mysql_error());
	$result = mysql_query($sql,$handle);
	if($result === false)
	{
		echo "Delete group db error ".mysql_error();
		closeConn($handle);
		die();
	}else
	{
	//	$_COOKIE["gname"] ="";
	//	$_COOKIE["gmemb"] ="";		
      	//	echo "<script language=javascript>alert('删除成功！');</script>";
		echo "<span style='font-size:15px;' >&nbsp;删除成功</span>";
        	closeConn($handle);
		die();
	}
	return; 	
}

function group_show()
{
	global $pg;
	global $id;
	//$id = getFormValue("id");
	$sql = "select * from usergroup where id=".$id;
	$handle = openConn();
	if($handle ==NULL) die( "mysql error". mysql_error() );
	$result = mysql_query($sql,$handle);
	if( $result !== false)
	{
		$all = mysql_num_rows($result);
		if($all > 0 )
		{
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
		}
	}
	//echo $row["groupusers"]."<br />";
	$membs = explode(",", $row["groupusers"]); // xxx,xxxx,xx,xxx
	
	$sql = "select * from softsetup where ";
	$len = count($membs);
	for($i=0; $i< $len; $i++)
	{
		if($i == $len -1)
		{
			$sql .=" id =".$membs[$i];
			break;
		}
		else
		{
			$sql .=" id =".$membs[$i]." or ";
		}
	}
//	echo $sql."<br />";
	
	if( $pg!="")
	{
		$sql2 = $sql;
		$sql .= " LIMIT ".((intval($pg)-1)*20).", 20";	
	 
        }
	else
	{
		$sql2 = $sql; 
		$sql .= " LIMIT 0 , 20";
	}
	
//	$handle = openConn();
//	if($handle ==NULL) die( "mysql error". mysql_error() );
	$result = mysql_query($sql2,$handle);
	if( $result !== false)
	{
		$all = mysql_num_rows($result);
		$all_num = $all;
	}
	else { die("mysql error".mysql_error()); }

	$result = mysql_query($sql,$handle);
	if($result ===false)
	{
		echo "Search mysql error()".mysql_error()."<br />";
		closeConn($handle);
		die();	
	}
	$num = mysql_num_rows($result);	
	if($num > 0)
	{
		echo '<div style="border:1px solid #ccc;padding:7px;padding-bottom:0px;border-bottom:none;"> <ul  id="table_list" style="width:600px;list-style-type:square;">';
	}else
	{
		echo "no more result";
	}
	
	for($i=0; $i<$num; $i++)
	{
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		if( intval($row["yhlx"]) == 0)
		{
			$yhlx = "普通用户";
		}else
		{
			$yhlx = "VIP用户";
		}
		if( intval($row["zt"])==1) 
		{
			$zt = "完全使用";
		}else 
		{
			$zt = "帐户锁定";
		}
		echo "<li style='cursor:pointer;border-bottom:1px solid #aaa; margin-bottom:12px;line-height:17px;'><span style='font-size:15px;color:blue;font-weight:bold;'>".$row["yhmc"]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:12px;color:#9d8080;'>".$row["lxdz"]."</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:10px;color:#333;'>".$row["diskid"]."</span>&nbsp;&nbsp;&nbsp;<span style='font-size:12px;color:#ccc;'>".$yhlx."<span>&nbsp;&nbsp;&nbsp;<span style='font-size:12px; color:#ddd;'>".$zt."</span></li>";
	}
	echo "</ul></div>";
	echo "<div id='page_bar' style='margin:19px;'>";
//	echo "<hr size=1 width=100px  align='left' style='margin-left:18px;' >";
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; 页次：".$a->pg."/".$a->page."&nbsp; 共".$a->countall."条记录&nbsp; ".$a->countlist."条/页";
	echo "</div>";
	closeConn($handle);

}

?>

