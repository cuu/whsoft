<?php
session_start();
include_once "header.php";
include_once "waibu.php";
include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/function.php";
include_once "../../function/xdownpage.php";

?>
<?php
      $content="";
      $date = "";
      $status = "";
      if(isset($_GET["id"]))
      {
          $sql = "select *  from vipmsg where id=".trim($_GET["id"]);
          $handle = openConn(); 
          if($handle == NULL) die("mysql_error!".mysql_error());
          $result = mysql_query($sql,$handle);
          if($result !== false)
          {
                $num = mysql_num_rows($result);
                if($num > 0)
                {
                     $row = mysql_fetch_array($result,MYSQL_ASSOC);
                     $content = trim( $row["content"]);
                     $date =  trim( date("Y-m-d",strtotime($row["time"])) );
                     $status = strval( trim($row["sfqy"]) );
                     closeConn($handle);
                    
                }else die("not exists!");
    
          }else die("mysql_error!".mysql_error());
      }
?>
<DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="images/css.css">
<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="images/all-ie.css" />
<![endif]-->

<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>
<link rel="stylesheet" type="text/css" href="css/jquery.ui.selectmenu.css" /> 
<script language="javascript" src="js/jquery.ui.selectmenu.js" type="text/javascript"></script> 

<script  language="javascript"  >


 $(function() {

                        $.datepicker.regional['zh-CN'] = {
                                closeText : '关闭',
                                prevText : '&#x3c;上月',
                                nextText : '下月&#x3e;',
                                currentText : '今天',
                                monthNames : ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月',
                                                '九月', '十月', '十一月', '十二月'],
                                monthNamesShort : ['一', '二', '三', '四', '五', '六', '七', '八', '九',
                                                '十', '十一', '十二'],
                                dayNames : ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
                                dayNamesShort : ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
                                dayNamesMin : ['日', '一', '二', '三', '四', '五', '六'],
                                dateFormat : 'yy-mm-dd',
                                firstDay : 1,
                                isRTL : false
                        };
                        $.datepicker.setDefaults($.datepicker.regional['zh-CN']);

  $("#ipt_putime").datepicker({
			changeMonth: true,
			changeYear: true,
                       minDate: 0
		});


//  $("table, tr, td").disableSelection(); <- this cause firefox can not work well!
 /* $("#container").draggable({handle: "#dr_title"});*/
  $("#container").resizable();
  $("#btg_confirm_add").button();
  $("#btg_confirm_add").css("fontSize","14px");
	$("#btg_confirm_add").click(
		function()
		{
			if( $.trim( $("#content_styled").val()) =="" )
			{
				alert("请输入消息内容");return false;
			}
		}
	);

	$("#pub_stat").selectmenu();

});

</script>
<style type="text/css">
#container tr
{
  margin-top:8px;
  margin-bottom:8px;
}

#content_styled {
        width: 360px;
       
        border-width: 3px;
        padding: 5px;
        font-family: Tahoma, sans-serif;
/*
        background-image: url(bg.gif);
        background-position: bottom right;
        background-repeat: no-repeat;
*/

}

div.ui-datepicker
{
 font-size:10px;
}

#pub_stat
{
	font-size:10px;
	width:140px;
	font-family:Tahoma;
	padding:2px;
}
.ui-selectmenu-status 
{
	padding:1px;
	padding-left:3px;
}
.ui-selectmenu 
{
	height:22px;
}

.ui-selectmenu-menu li{
	font-size:12px;
	padding:1px;

}
.ui-selectmenu-menu li a, .ui-selectmenu-status 
{
	padding:1px;
	padding-left:3px;
}

</style>
<?php 
  if(isset($_GET["id"]))
  {
?>
<title>修改多播消息</title>
<?php }else{
?>
<title>创建新的多播消息</title>
<?php } ?>
</head>

<body  topmargin="0">

<form method="POST" action="msgSet.php">
 <div align="center"><p>　</p><p>　</p>
<?php if(isset($_GET["id"])) {
?>
<input name="edit_msg_id" type="hidden" value="<?php echo $_GET["id"]; ?>" />
<input name="edit_vip_msg" type="hidden" value="yes" >
<input name="ori_date" type="hidden" value="<?php echo $date; ?>" >
<?php }else { ?>
<input name="add_new_vip_msg" type="hidden" value="add" > <?php } ?>
  <table id="container" border="0" width="468" cellspacing="4" cellpadding="1"  style="border: 1px solid #ccc;border-right:1px solid #999;border-bottom:1px solid #999;  padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
   <tr>
    <td id="dr_title" height="25"  width="358" colspan="2" class="biaoti">
    <?php if(isset($_GET["id"]))
          {
    ?>
      修改多播消息
    <?php } else { ?>
      创建新的多播消息
    <?php }   ?>
</td>
   </tr>

   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center" valign="top">
    <font size="2">消息内容：
   </font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
      <textarea name="txt_msg_body"  id="content_styled"  cols="30" rows="10"><?php echo $content; ?></textarea>

   </td>
   </tr>

   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">发布时间：</font></td>
    <td style="font-size:12px;border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input style="cursor:pointer;" class="g_input"  type="text"   id="ipt_putime"  name="vip_putime"  size="20"  readonly="readonly" value="<?php echo $date; ?>" />  &nbsp;<font size="2" color="#FF0000">默认为当天立即发布</font> </td>
    
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">发布状态：</font></td>
    <td style="font-size:12px;border-left-width: 1px; border-right-width: 1px;" width="200" align="left">
<?php
      if($status != "")
       {
            if($status =="0")
            {
?>
              <select name="pub_stat" id="pub_stat" >
              <option value="0">禁止发布</option>
              <option value="1">允许发布</option>
              </select>
<?php
            }else if ($status == "1")
            {
?>
              <select name="pub_stat" id="pub_stat" >
              <option value="1">允许发布</option>
              <option value="0">禁止发布</option>
              </select>
<?php
            }
      }else
      {
?>
      <select name="pub_stat" id="pub_stat" >
      <option value="0">禁止发布</option>
      <option value="1">允许发布</option>
      </select>
<?php
      }
?>
       &nbsp;<font size="2" color="#FF0000">请确认消息内容是否正确再允许发布</font>
    </td>
   </tr>
	<tr>
		<td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
		<font size="2">发布群体：</font></td>
			<td style="font-size:12px;border-left-width: 1px; border-right-width: 1px;" width="186" align="left">
				<select name="pub_group" >
				<option value="allNOR">所有普通用户</option>
				<option value="allVIP">所有VIP用户</option>
<?php
		$sql = "select id,groupname from usergroup";
		$handle = openConn();
		if($handle == NULL) die("mysql_error!".mysql_error());
		$result = mysql_query($sql,$handle);
		if($result !== false)
		{
			$num = mysql_num_rows($result);
			if($num > 0)
			{
				for($i=0;$i<$num;$i++)
				{
					$row2 = mysql_fetch_array($result,MYSQL_ASSOC);
					echo "<option value=".$row2["id"].">".$row2["groupname"]."</option>";
				}//end for....
						
			}else
			{
				echo "no group";
			}
		}
		closeConn($handle);
?>
				</select>
			</td>
	</tr>	
<tr>
<td style="margin-top:9px;" width="80" >&nbsp;</td>
<td  style="margin-top:9px;">
<?php if(isset($_GET["id"]))
      {
?>
 <input type="hidden" value="edit" name="action">
<input id="btg_confirm_add" type="submit" style=""  value="确定修改" name="U2B"> </td>
<?php }else{
?>
 <input type="hidden" value="save" name="action">
<input id="btg_confirm_add" type="submit" style=""  value="确定创建" name="U2B"> </td>
<?php } ?>
</tr>


  </table>
 
</div>
</form>
</body>
</html>
