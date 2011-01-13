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

function strlen (string) {
    // Get string length  
    // 
    // version: 1009.2513
    // discuss at: http://phpjs.org/functions/strlen
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Sakimori
    // +      input by: Kirk Strobeck
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: Onno Marsman
    // +    revised by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: May look like overkill, but in order to be truly faithful to handling all Unicode
    // %        note 1: characters and to this function in PHP which does not count the number of bytes
    // %        note 1: but counts the number of characters, something like this is really necessary.
    // *     example 1: strlen('Kevin van Zonneveld');
    // *     returns 1: 19
    // *     example 2: strlen('A\ud87e\udc04Z');
    // *     returns 2: 3
    var str = string+'';
    var i = 0, chr = '', lgth = 0;
 
    if (!this.php_js || !this.php_js.ini || !this.php_js.ini['unicode.semantics'] ||
            this.php_js.ini['unicode.semantics'].local_value.toLowerCase() !== 'on') {
        return string.length;
    }
 
    var getWholeChar = function (str, i) {
        var code = str.charCodeAt(i);
        var next = '', prev = '';
        if (0xD800 <= code && code <= 0xDBFF) { // High surrogate (could change last hex to 0xDB7F to treat high private surrogates as single characters)
            if (str.length <= (i+1))  {
                throw 'High surrogate without following low surrogate';
            }
            next = str.charCodeAt(i+1);
            if (0xDC00 > next || next > 0xDFFF) {
                throw 'High surrogate without following low surrogate';
            }
            return str.charAt(i)+str.charAt(i+1);
        } else if (0xDC00 <= code && code <= 0xDFFF) { // Low surrogate
            if (i === 0) {
                throw 'Low surrogate without preceding high surrogate';
            }
            prev = str.charCodeAt(i-1);
            if (0xD800 > prev || prev > 0xDBFF) { //(could change last hex to 0xDB7F to treat high private surrogates as single characters)
                throw 'Low surrogate without preceding high surrogate';
            }
            return false; // We can pass over low surrogates now as the second component in a pair which we have already processed
        }
        return str.charAt(i);
    };
 
    for (i=0, lgth=0; i < str.length; i++) {
        if ((chr = getWholeChar(str, i)) === false) {
            continue;
        } // Adapt this line at the top of any loop, passing in the whole string and the current iteration and returning a variable to represent the individual character; purpose is to treat the first part of a surrogate pair as the whole character and then ignore the second part
        lgth++;
    }
    return lgth;
}
function strcmp ( str1, str2 ) {

    return ( ( str1 == str2 ) ? 0 : ( ( str1 > str2 ) ? 1 : -1 ) );
}

 $(function() {
  $("#btg_confirm_add").button();
  $("#btg_confirm_add").css("fontSize","14px");

  $("#btg_confirm_add").click( 
     function()
    {
      var str="";
       str = $("#u_type option:selected").text();
       str = jQuery.trim(str);
      
      if( strcmp( str ,"临时管理员") == 0 && strlen( jQuery.trim( $("#ipt_pstime").val()) ) ==0  )
       {
          alert("添加[临时管理员]必须设定有限时效!");
          return false;
       }else 
      {

       }
    }
  );
});
</script>

<title>添加管理员</title>
</head>

<body  topmargin="0">

<form method="POST" action="admin_user_db.php">
 <div align="center"><p>　</p><p>　</p>
<input name="add_new_user" type="hidden" value="add" >
  <table border="0" width="368" cellspacing="0" cellpadding="1"  style="border: 1px solid #000000; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
   <tr>
    <td height="25"  width="358" colspan="2" class="biaoti">添加新的管理员帐号</td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center">
    <font size="2">帐号名称：
   </font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input type="text" name="add_username" size="20" value="" >
   </td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">帐号密码：</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input type="text" name="add_psw" size="20"></td>
   </tr>
   <tr>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="80" align="center">
    <font size="2">帐号时效：</font></td>
    <td style="border-left-width: 1px; border-right-width: 1px;" width="286" align="left">
    <input type="text" id="ipt_pstime"  name="add_pstime" size="20" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""  value="不限时"> &nbsp;<font size="2" color="#FF0000">默认空白时效为无限</font> </td>
    
   </tr>

   <tr>
  <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center">
 <font size="2">帐号类型：</font></td>
<td style="border-left-width: 1px; border-right-width: 1px; " width="286" align="left">
  <select id="u_type" name="u_type" style="width:100px;">
     <option value="2" selected="selected">普通管理员</option>
     <option value="1">超级管理员</option>
     <option value="1">临时管理员</option>
  </select>
  
</td>
   </tr>
   <tr>
  <td style="border-left-width: 1px; border-right-width: 1px; " width="80" align="center">
 <font size="2">帐号状态：</font></td>
<td style="border-left-width: 1px; border-right-width: 1px; " width="286" align="left">
  <select id="edit_zt" name="edit_zt" style="width:100px;">
     <option value="1">使用</option>
     <option value="0">禁用</option>
  </select>
   &nbsp;<font size="2" color="#FF0000">默认为使用 </font>
</td>
   </tr>



  </table>
  <p><input id="btg_confirm_add" type="submit" style=""  value="确定添加" name="2B">
</div>
</form>
</body>
</html>
