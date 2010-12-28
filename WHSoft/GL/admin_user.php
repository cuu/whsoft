<?php
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
<title>管理员帐号密码</title>
</head>

<body bgcolor="#CAD7F7" topmargin="0">

<form method="POST" action="admin_user_db.php">
 <div align="center"><p>　</p><p>　</p>
  <table border="0" width="368" cellspacing="0" cellpadding="1" bgcolor="#F1F3F5" style="border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
   <tr>
    <td height="25" background="images/admin_bg_1.gif" width="358" colspan="2" class="biaoti">管理员帐号密码</td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="64" align="center">
    <font size="2">原帐号：</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="286" align="left">
    <input type="text" name="yusername" size="20" value=""></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="64" align="center">
    <font size="2">原密码：</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="286" align="left">
    <input type="text" name="ypsw" size="20"></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="64" align="center">
    <font size="2">新帐号：</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="286" align="left">
    <input type="text" name="xusername" size="20" value="">&nbsp;<font size="2" color="#FF0000">若不修改请勿填写</font></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="64" align="center">
    <font size="2">新密码：</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px; border-top: 1px dotted #C0C0C0; border-bottom: 1px dotted #C0C0C0" width="286" align="left">
    <input type="text" name="xpsw" size="20">&nbsp;<font size="2" color="#FF0000">若不修改请勿填写</font></td>
   </tr>
  </table>
  <p><input type="submit" value="修改" name="B1"></div>
</form>
</body>
</html>
