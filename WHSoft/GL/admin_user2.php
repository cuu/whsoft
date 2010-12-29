<?php
/// this is for root opers, add normal users, delete ,change passwd...
//session_start();
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
<TABLE border=0 cellPadding=0 cellSpacing=0 width="800" align="center">
  <tr>
   <td background="images/admin_bg_1.gif"  height="25" colspan="5" class="biaoti">用户管理</td>
  </tr>
</TABLE>
		    <?php  
		    if( intval($_SESSION["zz"]) == 1 ) // super user 
		    {
?>
<TABLE border=0 cellPadding=0 cellSpacing=0 width="800" align="left">
  <tr>
   <td  height="25" colspan="5" class="biaoti">
       <a href="add_user.php" >添加管理员</a>
   </td>
  </tr>
</TABLE>
<?php
		    }
?>

<TABLE border="1" cellspacing="0" width="1600" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0">
      <tr height='30' bgcolor='#F1F3F5'>
       <td width="100" class="tdbiaoti">确认操作</td>
       <td width="100"  class="tdbiaoti">用户名称</td>
       <td width="100" class="tdbiaoti">用户类型</td>
     </tr>
<?php
	        $row = mysql_fetch_array($result,MYSQL_ASSOC); 
		for( $i = 0; $i < $num; $i++)
		{   
?>
<tr height='25' bgcolor="#f1f3f5">
 <td align="center">
         <a href="http://" class="del" onClick="JavaScript:openScript('admin_user.php?name=<?php echo trim($row["username"]);?>','注册用户<?php echo trim($row["username"]);?>',500,383,'no')">修改</a>
         <a href="?action=del&name=<?php echo trim($row["username"]);?>" class="del" onClick="return confirm('删除该管理帐号,您确定进行删除操作吗？')" target="delframe">删除</a>
        </td>
<td align="center">
		    <?php echo trim($row["username"]); ?>
</td>
<td align="center">
		    <?php
		    switch( trim($row["type"]))
		      {
		      case "1": echo "Super user";break;
		      case "2": echo "Normal user"; break;
		      case "0": echo "Invalid user"; break;
		      default:break;
		      }
		    ?>
</td>
</tr>
<?php		}
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
