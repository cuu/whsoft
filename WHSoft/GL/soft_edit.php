<?php
session_start();
include_once "header.php";
include_once "cscheck.php";

include_once "../../function/conn.php";
include_once "../../function/sendNote.php";

?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<title>ע���û�����</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/jquery-1.4.4.min.js" type="text/javascript"></script>
<script language="javascript">


</script>
<style type="text/css">
  .selectedRow {
      background-color: blue;
      cursor: pointer;
   }

</style>
</head>
<body>

<?php

	$action="";
	$id="";
	$action = getFormValue("action");
	$id     = getFormValue("id"    );

	if($action=="save")  soft_edit_save();
	else
	{
		$handle = openConn();
		if($handle == NULL) die();
		$sql = "select id,yhmc,yhlx,lxdz,gddh,yddh,diskid,zcrq,rjjsrq,zt,bz,zhsxrq,zhsxsj from softsetup where id=".intval($id);
		$result = mysql_query($sql,$handle);
		if ($result === false) {  closeConn($handle); die();}
		$num  = mysql_num_rows($result);
		if($num > 0)
		{
			$row = mysql_fetch_array($result,MYSQL_ASSOC);	
?>

 <TABLE border=0 cellPadding=0 cellSpacing=0 width="500" align="center">
  <tr>
   <td  height="25" colspan="5" class="biaoti">ע���û�����</td>
  </tr>
 </TABLE>
 <form name="form1" method="post" action="soft_edit.php" style="margin:0px">
  <input type="hidden" value="save" name="action" />
  <input type="hidden" value="<?php echo $id; ?>" name="id" />
  <table border="0" cellspacing="0" width="500" cellpadding="2" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0"  align="center"><tbody>
    <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">���ע��ţ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;<?php echo trim($row["diskid"]);?>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�û����ƣ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	<input name="yhmc" type="text" class="logininput" value="<?php echo trim($row["yhmc"]);?>" size="20" maxlength="15"/>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�̶��绰��</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	<input name="gddh" type="text" class="logininput" value="<?php echo trim($row["gddh"]);?>" size="20" maxlength="18"/>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�ƶ��绰��</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	<input name="yddh" type="text" class="logininput" value="<?php echo trim($row["yddh"]);?>" size="20" maxlength="18"/>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">��ϵ��ַ��</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	<input name="lxdz" type="text" class="logininput" value="<?php echo trim($row["lxdz"]);?>" size="50" maxlength="100" />
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">ע�����ڣ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;<?php echo trim($row["zcrq"]);?>
	</td>
   </tr>   
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�����ֹ���ڣ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	<input name="rjjsrq" type="text" class="logininput" value="<?php echo trim($row["rjjsrq"]);?>" size="20" maxlength="10" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""/>
	</td>
   </tr>
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�û����ͣ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	 <input type="hidden" value="<?php echo intval($row["yhlx"]);?>" name="yhlx1">
	 <select name="yhlx">
         <?php
	    switch( intval($row["yhlx"]))
	      {
		case 0:
		{
	 ?>
		<option value="0" >��ͨ�û�</option>
		<option value="1"> VIP�û�</option>
	 <?php
		}break;
		case 1:
		{
	 ?>
		<option value="1"> VIP�û�</option>
		<option value="0" >��ͨ�û�</option>
	 <?php
		}break;

	      }
?>

	 </select>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">��ǰ״̬��</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	 <input type="hidden" value="<?php echo intval($row["zt"]);?>" name="zt1">
	 <select name="zt">
	  <option value="1" <?php if( intval($row["zt"])==1) echo "selected";?>>��ȫʹ��</option>
	  <option value="2" <?php if( intval($row["zt"])==2) echo "selected";?>>�����˻�</option>
	 </select>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">��ע��</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	 <input name="bz" type="text" class="logininput" value="<?php echo trim($row["bz"]);?>" size="50" maxlength="100"/>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">����������ڣ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;<?php echo trim($row["zhsxrq"]);?>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�������ʱ�䣺</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;<?php echo trim($row["zhsxsj"]);?>
	</td>
   </tr>
   
   <tr height=25 bgcolor="">
    <td width="100" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0; padding-right:10px" align="right">�Ƿ����ߣ�</td>
    <td align="left" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">&nbsp;
	<?php
	  if(DateDiff("n",trim($row["zhsxrq"])." ".trim($row["zhsxsj"]),now())<=60) 
	    echo "����";
	  else
	    echo "����";
	 
	?>
	</td>
   </tr>
   <tr style="padding-top:10px; padding-bottom:10px" bgcolor="" height="30">
    <td align="center" colspan="4" style="border-left-width: 0px; border-right-width: 0px; border-top: 1px solid #C0C0C0;">
     <input style="width:80px;"  name="submit1" type="submit" class="buttonStyle" style="cursor:hand" value="ȷ���޸�">    </td>
   </tr>
   
   
</tbody>   
  </table>
 </form>

<?php
			closeConn($handle);
		}
		else
		{
			closeConn($handle);
			echo "<script language=javascript>alert('��ע���û������ڣ�');window.close();window.opener.location.reload();</script>";
			die();

		}
	}

?>
<?php

function soft_edit_save()
{
	global $id;
	$yhmc = ""; $gddh=""; $yddh=""; $lxdz=""; $rjjsrq="";$zt=""; $bz=""; $zt1="";
	$yhmc = getFormValue("yhmc");
	$yhlx = getFormValue("yhlx");
	$gddh = getFormValue("gddh");
	$yddh = getFormValue("gddh");
	$lxdz = getFormValue("lxdz");
	$rjjsrq = getFormValue("rjjsrq");
	$zt     = getFormValue("zt"    );
	$zt1    = getFormValue("zt1"   );
	$bz     = getFormValue("bz"    );
	

	$sql="update softsetup set yhmc='".$yhmc."',yhlx=".$yhlx.",gddh='".$gddh."',yddh='".$yddh."',lxdz='".$lxdz."',rjjsrq='".$rjjsrq."',zt=".$zt.",bz='".$bz."' where id=".intval($id);
	$handle = openConn();
	if($handle == NULL) return;
	$result = mysql_query($sql,$handle);
	if($result === false) 
	{
		closeConn();
		echo "<script language=javascript>alert('�޸�ʧ��!');window.history.back();</script>";	
		return;
	}
	else
	{
		if( intval($zt) != intval($zt1))
		{
			$sql = "select sfqy,noteContent from note where id=".(intval($zt)+1);
			$result = mysql_query($sql,$handle);
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			if( intval($row["sfqy"]) ==1 )
			{
				sendMsg($yddh, urlencode( trim( $row["noteContent"])));
			}
			
		}

		
		closeConn($handle);
		echo "<script language=javascript>alert('�޸ĳɹ�!');window.close();window.opener.location.reload();</script>";
		return;

	}
		
}

?>

</body>
</html>

