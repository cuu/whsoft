<?php
session_start();
include_once "header.php";
//include_once "waibu.php";
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

<title>管理员帐号密码修改</title>
</head>

<body bgcolor="#ffffff" topmargin="0">

<form method="POST" action="admin_user_db.php">
<?php
  if( isset($_GET["edit"]) && $_GET["edit"] =="1" )
    {
?>
    <input type="hidden" name="sedit" value="1" />
<?php
    }
?>
 <div align="center"><p>　</p><p>　</p>
  <table border="0" width="368" cellspacing="0" cellpadding="1"  style="border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
   <tr>
    <td height="25"  width="358" colspan="2" class="biaoti">管理员帐号密码</td>
   </tr>
   <tr>
    <td style="border-left-width: 1px;"  width="80" align="left">
    <font size="2">原帐号：
   </font></td>
    <td style="border-left-width: 1px;" width="286" align="left">
    <input type="text" name="yusername" size="20" value="
<?php
  if( isset($_GET['name']))
    echo $_GET["name"];
?>
" >
   </td>
   </tr>
   <tr>
    <td style="border-left-width: 1px;" width="80" align="left">
    <font size="2">原密码：</font></td>
    <td style="border-left-width: 1px;" width="286" align="left">
    <input type="text" name="ypsw" size="20"></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px;" width="80" align="left">
    <font size="2">新帐号：</font></td>
    <td style="border-left-width: 1px;" width="286" align="left">
    <input type="text" name="xusername" size="20" value="">&nbsp;<font size="2" color="#FF0000">若不修改请勿填写</font></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; " width="80" align="left">
    <font size="2">新密码：</font></td>
    <td style="border-left-width: 1px; " width="286" align="left">
    <input type="text" name="xpsw" size="20">&nbsp;<font size="2" color="#FF0000">若不修改请勿填写</font></td>
   </tr>
<?php
  if(isset($_GET["jzrq"]) && ( intval($_SESSION["zz"] )==1 || intval($_SESSION["zz"] )==3) )
    {
      ?>
   <tr>
    <td style="border-left-width: 1px; " width="80" align="left">
    <font size="2">新时效：</font></td>
    <td style="border-left-width: 1px; " width="286" align="left">
      <input type="text" name="xpstime" size="20" value="<?php if(intval($_GET["jzrq"])!=0){ echo $_GET["jzrq"];}else {echo "不限时";} ?>" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="" >&nbsp;<font  color="#FF0000"  size="2" >若不修改请留空白</font></td>
   </tr>
<?php      
    }
?>
  <?php if (isset($_SESSION["zz"]) && intval($_SESSION["zz"] )==1)
{ 
?>
   <tr>
    <td style="border-left-width: 1px; " width="80" align="left">
    <font size="2">帐号类型：</font></td>
    <td style="border-left-width: 1px;" width="286" align="left">
    <select name="edit_type" >
    <?php
switch($_GET["type"])
  {
case "1":
  {
    ?>
      <option value="1" >Super user </option>
     <option value="2" > Normal user </option>
     <option value="3" > temp </option>   

    <?php
  }break;
case "2":
{
?>
      <option value="2" >Normal user </option>
     <option value="1" > super user </option>
     <option value="3" > temp </option>
    
<?php
      }break;
case "3":
{
?>
  <option value="3" > temp </option>
      <option value="2" >Normal user </option>
     <option value="1" > super user </option>
<?php
    }break;
default:break;
  }
?>
    </select>
    </td>
   </tr>
<tr>
  <td style="border-left-width: 1px; " width="80" align="left">
    <font size="2">帐号状态：</font></td>
    <td style="border-left-width: 1px;" width="286" align="left">
    <select name="edit_zt">
<?php
switch($_GET["zt"])
  {
  case "1":
  {
?>
   <option value="1"> 使用中 </option>
<option value="0"> 被禁用 </option>
<?php
  }break;
  case "0":
  {
?>
    <option value="0"> 被禁用 </option>
     <option value="1"> 使用中 </option>
<?php
      }break;
  default:break;

  }
?>

    </select>
</td>
</tr>

<?php 
} // end if
?>
  </table>
  <p><input type="submit" value="修改" name="B1"></div>
</form>
</body>
</html>
