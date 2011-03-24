<?php
session_start();
include_once("header.php");
include_once "cscheck.php"; // for dev
include_once "../../function/conn.php";
include_once "../../function/function.php";
include_once "../../function/xdownpage.php";




?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="images/all-ie.css" />
<![endif]-->

<title>代理商设置</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>

<script language="javascript">
$(function() {
	 
	$("#page_table_bar").width(  $("#pro_out_list").width());



});
</script>
<style type="text/css">
#pro_out_list
{
  margin:8px;
  border:1px solid #bbb;
  border-bottom:none;
}
#pro_out_list td
{
  border-bottom:1px solid #ccc;
text-overflow:ellipsis;
overflow:hidden;white-space: nowrap;
}

.popup {
    position: absolute;
    display: none; 
}
.hightlight
{
	background:#b5d5ff;
}

</style>

</head>
<body>
<?php
	$action      = getFormValue("action");
	$sfqy        = getFormValue("sfqy")  ;
	$id          = getFormValue("id")    ;
        $pg          = getFormValue("pg")    ;


if($id == "" ) $id=0;
if($action == "save") proxy_save();
else if($action == "del")  pro_del();
else
{

		$sql = "select * from proxy";
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
		    <span style="font-size:20px;" class="biaoti_guu" >代理商设置</span>
</div>
<div>
<a style="width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_proxy.php" >添加新的代理商</a>
</div>

<table  id ="pro_out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#fff" bordercolordark="#fff" style="border-collapse: collapse; table-layout:fixed;width:600px;WORD-BREAK: break-all;" bordercolor="#fff"  >
      <tr height='30' bgcolor='#000000'  >
       <td width="150" class="tdbiaoti">确认操作</td>
       <td width="240" height="30"  class="tdbiaoti">代理商编号</td>
       <td width="120" class="tdbiaoti">创建日期</td>
       <td width="120" class="tdbiaoti">状态</td>
       <td></td>
     </tr>
<?php
                  $row = mysql_fetch_array($result,MYSQL_ASSOC); 
                  for( $i = 0; $i < $num; $i++)
                  {   
?> 
                      <tr height='25' style="overflow:hidden;border-bottom:1px solid #ccc;">
                       <td align="center"  >
                         <a href="?action=del&id=<?php echo trim($row["id"]);?>" class="del" onClick="return confirm('删除该代理商,您确定进行删除操作吗？')" target="delframe">删除</a>
                       </td>
                       <td nowrap  style=" overflow:hidden;width:240px;height:25px;" align="center">
                            <?php echo trim($row["name"]); ?>
                       </td>
                       <td align="center">
                            <?php echo trim( date("Y年n月j日",strtotime($row["date"])) ); ?>
                       </td>
		      <td align="center">
		      <?php
			      if( intval( $row["zt"] ) ==1)
				  echo "正常使用";
			      else
				  echo "禁用状态"; 
		      ?>
		      </td>
                       <td align="center"></td>
                      </tr>

<?php
                      $row = mysql_fetch_array($result,MYSQL_ASSOC); 
                  }
?>
</table>
<table id="page_table_bar"  style="margin-left:10px;" width="600" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#ebeff9>
       <tr><td>
<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; 页次：".$a->pg."/".$a->page."&nbsp; 共".$a->countall."条记录&nbsp; ".$a->countlist."条/页";
			
?></td></tr>
    </tbody>
     </table>
	 <iframe name="delframe" id="delframe" style="display:none"></iframe>

<?php
               }else
               {
?>

<br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >目前没有代理商,请您添加</span>
</div>
<div>
<a style="font-family:simhei;width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_proxy.php" >添加新的代理商</a>
</div>

<?php
            
               }
}
?>

</body>
</html>

<?php

function  pro_del()
{
    check_root();
    $id = trim($_GET["id"]);
    $sql = "delete from  proxy  where id=".$id;
  
    $handle = openConn();
    if($handle ==NULL) die( "mysql error". mysql_error() );
    $result = mysql_query($sql,$handle);
    if($result ===false)
    {
        echo "Edit mysql error()".mysql_error()."<br />";
        closeConn($handle);
        die();	
    }
    else
    {
	
      	echo "<script language=javascript>alert('删除成功！');window.parent.location.reload();</script>";
        closeConn($handle);
    }

  return;     
}

function  proxy_save()
{
	//array(3) { ["action"]=> string(4) "save" ["proxy_body"]=> string(7) "0572833" ["proxy_zt"]=> string(1) "1" }
	$name = getFormValue("proxy_body");
	$zt =   getFormValue("proxy_zt");
	$pass = getFormValue("proxy_pass");

	$sql = "insert into proxy(name,zt) values('".$name."','".$zt."')";
	$handle = openConn();
	if($handle ==NULL) die( "mysql error". mysql_error() );
	$result = mysql_query($sql,$handle);
	if($result ===false)
	{
		echo "proxy save mysql error()".mysql_error()."<br />";
		closeConn($handle);
		die();		
	}else
	{
		// username 	passwd 	type 	jzrq zt
		$sql2 = "insert into admin(username,passwd,type,jzrq,zt) values('".$name."','".g_CRC32($pass)."',2,'0',".intval($zt).")";
		$result2 = mysql_query($sql2,$handle);
		if($result2 === false)
		{
			echo "proxy save admin mysql error()".mysql_error()."<br />";
			closeConn($handle);
			die();			
		}
		else
		{
			echo "添加成功";
			
		}
	}

	closeConn($handle);
}


?>