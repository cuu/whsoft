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

<title>��ӹ���Ա</title>
</head>

<body  topmargin="0">

<form method="POST" action="admin_user_db.php">
 <div align="center"><p>��</p><p>��</p>
<input name="add_new_user" type="hidden" value="add" >
  <table border="0" width="368" cellspacing="0" cellpadding="1"  style="border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
   <tr>
    <td height="25"  width="358" colspan="2" class="biaoti">����µĹ���Ա�ʺ�</td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center">
    <font size="2">�ʺ����ƣ�
   </font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input type="text" name="add_username" size="20" value="" >
   </td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">�ʺ����룺</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input type="text" name="add_psw" size="20"></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">�ʺ�ʱЧ��</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input type="text" name="add_pstime" size="20" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="" > &nbsp;<font size="2" color="#FF0000">Ĭ�Ͽհ�ʱЧΪ����</font> </td>
    
   </tr>

   <tr>
  <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center">
 <font size="2">�ʺ����ͣ�</font></td>
<td style="border-left-width: 1px; border-right-width: 1px; " width="286" align="left">
  <select name="u_type" style="width:100px;">
     <option value="2">��ͨ����Ա</option>
     <option value="1">��������Ա </option>
     <option value="3">��ʱ����Ա </option>
  </select>
  
</td>
   </tr>
  </table>
  <p><input type="submit" style="width:55px;"  value="ȷ�����" name="2B">
</div>
</form>
</body>
</html>
