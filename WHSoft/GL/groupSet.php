<?php
session_start();
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
  $(function() {
 

});
</script>

<style type="text/css">
#group_out_list
{
  margin:8px;
  border:1px solid #bbb;
  border-bottom:none;
}
#vip_out_list td
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
                         <a  class="del" href="edit_group.php?action=edit&id=<?php echo trim($row["id"]);?>" >修改</a>
		    &nbsp;&nbsp;
                         <a href="">查看成员</a>
                       </td>
                       <td nowrap  style=" overflow:hidden;width:240px;height:25px;" align="center">
                            <?php echo trim($row["groupname"]); ?>
                       </td>
		      <td align="center">
		      <?php
			      $array = explode(",",$row["groupusers"]);
				echo count($array); 
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
      		echo "<script language=javascript>alert('创建群成功！');window.parent.location.reload();</script>";
		$_COOKIE["gname"] ="";
		$_COOKIE["gmemb"] ="";
        	closeConn($handle);
	}
	return; 
}

function group_edit()
{

}
function group_del()
{

}

?>

