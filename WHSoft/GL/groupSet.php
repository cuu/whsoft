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

<title>�û�Ⱥ��������</title>
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
	$noteContent = getFormValue("noteContent");
	$sfqy        = getFormValue("sfqy")       ;
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
		      <span style="font-size:20px;" class="biaoti_guu" >�û�Ⱥ��������</span>
  </div>
  <div>
  <a style="width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_group.php" >�����µ�Ⱥ</a>
  </div>

<table  id ="group_out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#fff" bordercolordark="#fff" style="border-collapse: collapse; table-layout:fixed;width:600px;WORD-BREAK: break-all;" bordercolor="#fff"  >
      <tr height='30' bgcolor='#000000'  >
       <td width="150" class="tdbiaoti">ȷ�ϲ���</td>
       <td width="240" height="30"  class="tdbiaoti">Ⱥ����</td>
       <td width="120" class="tdbiaoti">Ⱥ��Ա</td>
       <td width="120" class="tdbiaoti">����ʱ��</td>
      <!--  <td width="120" class="tdbiaoti">ʹ������</td> -->

       <td></td>
  

<?php
		}// end if($num > 0)
		else
		{
?>
<br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >Ŀǰû��Ⱥ,�������</span>
</div>
<div>
<a style="font-family:simhei;width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_vip_msg.php" >�����µ�Ⱥ</a>
</div>
<?php
		}// end else 
?>
</body>
</html>
<?php
function group_save()
{

}

function group_edit()
{

}
function group_del()
{

}
?>
