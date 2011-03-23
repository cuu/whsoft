<?php
session_start();
include_once "header.php";
include_once "waibu.php";
include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/function.php";
include_once "../../function/xdownpage.php";

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

<!-- <link rel="stylesheet" type="text/css" href="css/ui.dropdownchecklist.standalone.css" /> -->
<link rel="stylesheet" type="text/css" href="css/ui.dropdownchecklist.themeroller.css" />
<script language="javascript" src="js/ui.dropdownchecklist-1.1-min.js" type="text/javascript"></script> 
<script  language="javascript"  >

$(function() {

});

</script>
<style type="text/css">
#btg_confirm_add
{
	background:#fff;
	border:1px solid #ccc;
	width:100px;
	height:24px;
}
#pub_group
{
	z-index:200;
}
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

.checked_line
{
	background:#ffcc99;
}
.unchecked_line
{
	background:#fff;
}

</style>
<title>添加新的代理商</title>
</head>

<body  topmargin="0">
<form id="target_group"  name="group_form"  style="margin:8px;"  method="POST" action="dailiSet.php">
	<input type="hidden" value="save" name="action">

<table id="container" border="0" width="468" cellspacing="4" cellpadding="1"  style="border: 1px solid #ccc;border-right:1px solid #999;border-bottom:1px solid #999;  padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
	<tr>
		<td id="dr_title" height="25"  width="358" colspan="2" class="biaoti">
			添加代理商
		</td>
	</tr>
    <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center" valign="top">
    <font size="2">代理商编号:</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
      <input name="txt_body"  class="g_input"  size="20" />
    </td>
    </tr>

    <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">使用状态：</font></td>
    <td style="font-size:12px;border-left-width: 1px; border-right-width: 1px;" width="200" align="left">
