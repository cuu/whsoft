<?php
session_start();
include_once "header.php";
include_once "waibu.php";
include_once "cscheck.php";
?>

<DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Language" content="zh-cn">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="images/css.css">
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>
<script  language="javascript"  >

jQuery(function($) {
                        $.datepicker.regional['zh-CN'] = {
                                closeText : '�ر�',
                                prevText : '&#x3c;����',
                                nextText : '����&#x3e;',
                                currentText : '����',
                                monthNames : ['һ��', '����', '����', '����', '����', '����', '����', '����',
                                                '����', 'ʮ��', 'ʮһ��', 'ʮ����'],
                                monthNamesShort : ['һ', '��', '��', '��', '��', '��', '��', '��', '��',
                                                'ʮ', 'ʮһ', 'ʮ��'],
                                dayNames : ['������', '����һ', '���ڶ�', '������', '������', '������', '������'],
                                dayNamesShort : ['����', '��һ', '�ܶ�', '����', '����', '����', '����'],
                                dayNamesMin : ['��', 'һ', '��', '��', '��', '��', '��'],
                                dateFormat : 'yy-mm-dd',
                                firstDay : 1,
                                isRTL : false
                        };
                        $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
                });


 $(function() {
  $("table, tr, td").disableSelection();
 /* $("#container").draggable({handle: "#dr_title"});*/
  $("#container").resizable();
  $("#btg_confirm_add").button();
  $("#btg_confirm_add").css("fontSize","14px");
 
  $("#ipt_putime").datepicker({
			changeMonth: true,
			changeYear: true,
                       minDate: 0
		});

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



</style>
<title>�����µĶಥ��Ϣ</title>
</head>

<body  topmargin="0">

<form method="POST" action="add_vip_msg.php">
 <div align="center"><p>��</p><p>��</p>
<input name="add_new_vip_msg" type="hidden" value="add" >
  <table id="container" border="0" width="468" cellspacing="4" cellpadding="1"  style="border: 1px solid #ccc;border-right:1px solid #999;border-bottom:1px solid #999;  padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
   <tr>
    <td id="dr_title" height="25"  width="358" colspan="2" class="biaoti">�����µĶಥ��Ϣ</td>
   </tr>

   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center" valign="top">
    <font size="2">��Ϣ���ݣ�
   </font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
      <textarea id="content_styled"  cols="30" rows="5"></textarea>

   </td>
   </tr>

   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">����ʱ�䣺</font></td>
    <td style="font-size:12px;border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input class="g_input"  type="text" id="ipt_putime"  name="vip_putime" size="20"  style="cursor:pointer;font-size:12px;" readonly=""  value=""> &nbsp;<font size="2" color="#FF0000">Ĭ��Ϊ������������</font> </td>
    
   </tr>

<tr>
<td style="margin-top:9px;" width="80" >&nbsp;</td>
<td  style="margin-top:9px;"> <input id="btg_confirm_add" type="submit" style=""  value="ȷ������" name="U2B"> </td>
</tr>


  </table>
 
</div>
</form>
</body>
</html>
