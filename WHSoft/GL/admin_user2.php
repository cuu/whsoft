<?php
/// this is for root opers, add normal users, delete ,change passwd...
session_start();
include_once "header.php";
//include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/function.php";
include_once "../../function/xdownpage.php";
?>

<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<title>后台用户权限管理</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>

<?php
include "jq_ui.php";
?>
<script  language="javascript"  >


 $(function() {
  $("#btg_adduser").button();

});
</script>


<style type="text/css">
#out_list
{
  margin:8px;
  border:1px solid #bbb;
  border-bottom:none;
}
#out_list td
{
  border-bottom:1px solid #ccc;
}
</style>
</head>
<body>

<?php
	$action = getFormValue("action");
	switch($action)
	{
		case "Search":
			adu2_Search();
		break;
		case "del":
			adu2_del();
		break;
		default:
		{
?>
<?php
		  //list all managers
		  /*
		    [delete]---[motify]----uname----  ---user type---  

		   */
		$sql = "select * from admin";
		$handle = openConn();
		if($handle ==NULL) die( "mysql error". mysql_error() );
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
<div style="background:#fff;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" >帐户修改</span>
</div>


		    <?php  
		    if( intval($_SESSION["zz"]) == 1 || intval($_SESSION["zz"]) == 3 ) // super user or temp super user 
		    {
?>
<div style="margin-left:10px;">
<span id="btg_adduser"  style="border:1px solid #ccc;padding:0px;margin-bottom:10px;font-size:12px;" ><a href="add_user.php" >添加管理员</a></span>
</div>

<?php
		    }
?>

<TABLE  id ="out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#fff" bordercolordark="#fff" style="border-collapse: collapse; table-layout:fixed;width:600px;" bordercolor="#fff"  >
      <tr height='30' bgcolor='#ebeff9'  >
       <td width="150" class="tdbiaoti">确认操作</td>
       <td width="120"  class="tdbiaoti">管理员名称</td>
       <td width="120" class="tdbiaoti">管理员类型</td>
       <td width="120" class="tdbiaoti">使用状态</td>

       <td></td>
     </tr>
<?php
	        
		for( $i = 0; $i < $num; $i++)
		{   
		  $row = mysql_fetch_array($result,MYSQL_ASSOC); 
?>
<tr height='25' style="border-bottom:1px solid #ccc;">
 <td align="center"  >
         <a href="" class="del" onClick="JavaScript:openScript('admin_user.php?jzrq=<?php echo trim($row["jzrq"]); ?>&zt=<?php echo trim( $row["zt"]); ?>&type=<?php echo trim($row["type"]); ?>&edit=1&name=<?php echo trim($row["username"]);?>','注册用户<?php echo trim($row["username"]);?>',500,399,'no');return false">修改</a>
		    &nbsp;&nbsp;
         <a href="?action=del&name=<?php echo trim($row["username"]);?>" class="del" onClick="return confirm('删除该管理帐号,您确定进行删除操作吗？')" target="delframe">删除</a>
        </td>
<td align="center">
		    <?php echo trim($row["username"]); ?>
</td>
<td align="center">
		    <?php
		    switch( trim($row["type"]))
		      {
		      case "1": echo "超级管理员";break;
		      case "2": echo "普通管理员"; break;
		      case "3": echo "临时管理员"; break;
		      case "0": echo "不明用户"; break;
		      default:break;
		      }
		    ?>
</td>
<td align="center">
		  <?php 
		  switch( trim($row["zt"]))
		    {
		    case "1": echo "使用中"; break;
		    case "0": echo "被禁用"; break;
		    default:break;
		    }
		  ?>
</td>
<td></td>
</tr>
<?php	
               
               	}
?>

</table>

<?php		  
		}
?>

<?php          }break; //end default;
	}; /// end switch 
?>

<?php 
function adb2_del()
{

  $id = getFormValue("name");
  $sql = "delete from admin where username='".$id."'";
  $handle = openConn();
  if($handle ==NULL) die();
  $result = mysql_query($sql,$handle);
  if($result === false)
  {
    closeConn($handle);
    echo "<script language=javascript>alert('删除失败！管理员不存在');window.parent.location.reload();</script>";
  }
  else
  {
      closeConn($handle);
      echo "<script language=javascript>alert('删除成功！');window.parent.location.reload();</script>";
      die();
  }

}

?>
