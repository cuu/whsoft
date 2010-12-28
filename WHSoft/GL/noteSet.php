<?php
include_once "header.php";
include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/function.php";
?>

<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<title>短信内容设置</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<script language="javascript">
   function TxtChange()
  {  
    document.getElementById("LabNumber").innerText=document.getElementById("noteContent").value.length;
  }
</script>
</head>
<body>
<?php
	$action      = getFormValue("action")     ;
	$noteContent = getFormValue("noteContent");
	$sfqy        = getFormValue("sfqy")       ;
	$id          = getFormValue("id")         ;

if($id == "" ) $id=0;
if($action == "save") save();
?>
<TABLE border=0 cellPadding=0 cellSpacing=0 width="800" align="center">
  <tr>
   <td background="images/admin_bg_1.gif"  height="25" colspan="5" class="biaoti">短信内容设置</td>
  </tr>
 </TABLE>
 <form action="noteSet.php" method="post" name="formSearch" style="margin:0px" target="saveframe">
 <TABLE border="1" cellspacing="0" width="800" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0" align="center">
  <tr bgcolor="#FFFFFF" height="30">
   <td>
<?php

$sql="select id,noteTitle from note";
$handle = openConn();
if ($handle == NULL) die();
$result = mysql_query($sql, $handle);
if($result != FALSE) 
{
	while( $row = mysql_fetch_array($result,MYSQL_NUM))
	{
		if( trim( $row[0]) == $id )
		{
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<font style='color:#FF0000; font-size:14px'>".trim($row[1])."短信内容设置</font>";
		}
		else
		{
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="?id='.trim($row[0]).'" class="link">'.trim($row[1]).'短信内容设置</a>';
		}
	}
	//mysql_free_result($result);
	closeConn($handle);	
} // output html items for Note set
?>
</td></tr>

<?php
	$sql="select id,noteContent,sfqy,noteTitle from note where id=".intval($id);
	$handle = openConn();
	if($handle == NULL) die();
	$result = mysql_query($sql,$handle);
	if($result != FALSE)
	{
		$row = mysql_fetch_array($result,MYSQL_NUM);
		$noteContent = trim( $row[1]);

?>	
    <tr bgcolor="#FFFFFF" height="30">
	 <td align="left" style="padding-left:10px">
	   <textarea name="noteContent" id="noteContent" cols="60" rows="10" onKeyDown="javascript:if(event.keyCode==13||event.keyCode==32)return false;" onpropertychange="TxtChange()"><?php echo $noteContent;?></textarea>&nbsp;&nbsp;*最多120字，当前共有（<SPAN id=LabNumber style="color:#FF0000"><?php echo strlen($noteContent); ?></SPAN>）字
	 </td>
	</tr>
	<tr bgcolor="#FFFFFF" height="30">
	 <td align="left" style="padding-left:10px">
	   <font style="color:#FF0000; font-size:14px"><?php echo trim($row[3])."短信设置"?> </font>&nbsp;&nbsp;&nbsp;&nbsp;
	   <select name="sfqy">
	    <option value="1" <?php if( intval($row[2])==1 )  echo "selected"; ?> >启用</option>
	    <option value="0" <?php if( intval($row[2])==0 )  echo "selected"; ?> >关闭</option>
	   </select>&nbsp;&nbsp;&nbsp;&nbsp;
	   <input type="hidden" value="save" name="action" />
	   <input type="hidden" value="<?php echo $id; ?>" name="id"/>
	   <input name="submit1" type="submit" class="buttonStyle" style="cursor:hand" value="确定修改">
	 </td>
	</tr>

<?php
		//mysql_free_result($result);
		closeConn($handle);
	}

?>





 </TABLE>
 </form>
 <iframe name="saveframe" id="saveframe" style="display:none"></iframe>
<?php

function save()
{
	global $noteContent;
	global $sfqy;
	global $id;
	$sql="update note set noteContent='".$noteContent."',sfqy=".$sfqy." where id=".$id;
	$handle = openConn();
	if($handle == NULL) return;
	$result = mysql_query($sql,$handle);
	if($result ==FALSE)
	{
		echo "<script language=javascript>alert('修改失败！');window.parent.location.reload();</script>";
		closeConn($handle);
		die();
	}
	else
	{
		echo "<script language=javascript>alert('修改成功！');window.parent.location.reload();</script>";
		//mysql_free_result($result);
		closeConn($handle);
		die();
	}
}


?>

</body>
</html>
