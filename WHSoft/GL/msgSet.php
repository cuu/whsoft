<?php
/// �ಥ��Ϣ���� for vip �û�
//mysql table layout:
/*
  $mysql_timestamp = date('Y-m-d H:i:s', $php_timestamp);

*/

?>
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
<title>�ಥ��Ϣ����</title>
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
#vip_out_list
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

?>
<?php
	$action      = getFormValue("action")     ;
	$noteContent = getFormValue("noteContent");
	$sfqy        = getFormValue("sfqy")       ;
	$id          = getFormValue("id")         ;
        $pg          = getFormValue("pg");
if($id == "" ) $id=0;
if($action == "save") vip_save();
else if($action == "del")  vip_del();
else if($action == "edit") vip_edit();
else
{

		  //list all managers
		  /*
		    [delete]---[motify]----content----time--sendornot---  

		   */
		$sql = "select * from vipmsg";
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
		    <span style="font-size:20px;" class="biaoti_guu" >�ಥ��Ϣ����</span>
</div>
<div>
<a style="font-family:simhei;width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_vip_msg.php" >�����µĶಥ��Ϣ</a>
</div>

<table  id ="vip_out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#fff" bordercolordark="#fff" style="border-collapse: collapse; table-layout:fixed;width:600px;WORD-BREAK: break-all;" bordercolor="#fff"  >
      <tr height='30' bgcolor='#ebeff9'  >
       <td width="150" class="tdbiaoti">ȷ�ϲ���</td>
       <td width="240" height="30"  class="tdbiaoti">��Ϣ����</td>
       <td width="120" class="tdbiaoti">��Ϣ��󷢲�ʱ��</td>
      <!--  <td width="120" class="tdbiaoti">ʹ������</td> -->

       <td></td>
     </tr>
<?php
                  $row = mysql_fetch_array($result,MYSQL_ASSOC); 
                  for( $i = 0; $i < $num; $i++)
                  {   
?>    
                      <tr height='25' style="overflow:hidden;border-bottom:1px solid #ccc;">
                       <td align="center"  >
                         <a  class="del" href="add_vip_msg.php?action=edit&id=<?php echo trim($row["id"]);?>" >�޸�</a>
		    &nbsp;&nbsp;
                         <a href="?action=del&id=<?php echo trim($row["id"]);?>" class="del" onClick="return confirm('ɾ���ù����ʺ�,��ȷ������ɾ��������')" target="delframe">ɾ��</a>
                       </td>
                       <td nowrap  style=" overflow:hidden;width:240px;height:25px;" align="center">
                            <?php echo trim($row["content"]); ?>
                       </td>
                       <td align="left">
                            <?php echo trim( date("Y��n��j��",strtotime($row["time"])) ); ?>
                       </td>
                       <td align="center"></td>
                      </tr>

<?php
                      $row = mysql_fetch_array($result,MYSQL_ASSOC); 
                  }
?>
</table>
<table style="margin-left:10px;" width="600" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#ebeff9>
       <tr><td>
<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; ҳ�Σ�".$a->pg."/".$a->page."&nbsp; ��".$a->countall."����¼&nbsp; ".$a->countlist."��/ҳ";
			
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
		    <span style="font-size:20px;" class="biaoti_guu" >Ŀǰû�жಥ��Ϣ,��������</span>
</div>
<div>
<a style="font-family:simhei;width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_vip_msg.php" >�����µĶಥ��Ϣ</a>
</div>

<?php
            
               }
}
?>

</body>
</html>

<?php

function js_show_error($str)
{
	echo "<script language=javascript>alert('".$str."��');window.parent.location.reload();</script>";
}
function vip_save()
{
  check_root();
  $txt_msg_body = trim($_POST["txt_msg_body"]);

  if( strlen($txt_msg_body) < 3)
  {
      js_show_error("�ಥ��Ϣ���ݳ��Ȳ���ȷ,��������д!");
      die();

  }
  $vip_putime = trim($_POST["vip_putime"]);
  $sql="";
  $title = "vipmsg";
  if( strlen($vip_putime)== 0) 
  {
       $vip_putime = 0;
       $sql = "insert into vipmsg(time,title,content) values(NOW(),'".$title."','".$txt_msg_body."')";
  }
  else
  {
    $vip_putime = date('Y-m-d H:i:s', strtotime($vip_putime));
    $sql = "insert into vipmsg(time,title,content) values('".$vip_putime."','".$title."','".$txt_msg_body."')";
  }
  
  $handle = openConn();
  if($handle ==NULL) die( "mysql error". mysql_error() );
  $result = mysql_query($sql,$handle);
  if($result ===false)
  {
      echo "Save mysql error()".mysql_error()."<br />";
      closeConn($handle);
      die();	
  }
  else
  {
      	echo "<script language=javascript>alert('������Ϣ�ɹ���');window.parent.location.reload();</script>";
        closeConn($handle);
  }
   
  return;
}
function vip_del()
{
    var_dump($_GET);
}

function vip_edit()
{
    check_root();
  
   
    $id = trim($_POST["edit_msg_id"]);
    $content = trim($_POST["txt_msg_body"]);
    if( strlen($content) < 3)
    {
       js_show_error("�ಥ��Ϣ���ݳ��Ȳ���ȷ,��������д!");
        die();
    }

  
    $time = trim($_POST["vip_putime"]);
    $vip_putime = date('Y-m-d H:i:s', strtotime($time));
    

    $sql = "update vipmsg set content='".$content."',time='".$vip_putime."'  where id=".$id;
  
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
      	echo "<script language=javascript>alert('�޸���Ϣ�ɹ���');window.parent.location.reload();</script>";
        closeConn($handle);
    }

  return;   
}
?>