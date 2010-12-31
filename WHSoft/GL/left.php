<?php
session_start();

?>
<HTML>
<HEAD>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<META http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="images/left.css" rel="stylesheet" type="text/css" />
<SCRIPT language="javascript" src="images/left.js"></SCRIPT>
<SCRIPT language="javascript" src="images/jquery-1.4.4.min.js"></SCRIPT>


</HEAD>
<BODY >
 
 <TABLE cellSpacing="0" cellPadding="0" width="100%" align="left" >
  <TBODY>
   <TR>
    <TD vAlign="top">
	 <TABLE cellSpacing="0" cellPadding="0" width="150" align="center">
      <TBODY>
       <TR><TD height="4"></TD></TR>
       <TR>
        <TD height="38" vAlign="bottom" >
		 <table width="100%"  border="0" cellspacing="5" cellpadding="0">
          <tr>
           <td width="19%">&nbsp;</td>
           <td width="81%"><span class=""><a href="index.php"   target="mainFrame"  >后台管理菜单</a></span></td>
          </tr>
         </table>
		</TD>
       </TR>
      </TBODY>
	 </TABLE>
	 
	 <TABLE cellSpacing="1" cellPadding="0" width="207" align="center">
      <TBODY>
       <TR style="border-bottom:1p solid #bbccff;">
        <TD  height="25" onselectstart="return false">&nbsp;<SPAN>
	<?php
	if( intval($_SESSION["zz"]) == 1)
	  {
	    ?>
        <a href="admin_user2.php" target="mainFrame"><B>帐户修改</B></A>
	      <?php }else
	  {
	    ?>
	    <a href="admin_user.php" target="mainFrame"><B>帐户修改</B></A>
	    <?php   }
	    ?>
 | <a href="logout.php" target=_top><B>退出</B></A></SPAN>&nbsp;&nbsp;&nbsp;&nbsp;</TD>
       </TR>
	  </TBODY>
	 </TABLE>

	 <div style="height:1px;border-top:1px solid #bbccff;"></div>

	 <TABLE id="l_menu" cellSpacing=0 cellPadding=0 width=207 align=center>
      <TBODY>
       <TR>
        <TD class=menu_title id=menuTitle0 onMouseOver="this.className='menu_title2';" onclick=showsubmenu(0) onMouseOut="this.className='menu_title';" onselectstart="return false"  height=2><SPAN></SPAN></TD>
       </TR>
       <TR>
        <TD id=submenu0>
	  <DIV class=sec_menu style="WIDTH: 207px;text-align:center;">
          <TABLE cellSpacing=0 cellPadding=0 width=207 align=center>
           <TBODY>
            <TR><TD height=5></TD><td></td></TR>
            <TR>
	     <td id="kkk1" >&nbsp;</td>
<TD style="" id="kk1" height=25 ><a id="menu_item1" href="softManager.php" target=mainFrame>注册用户管理</a></TD>
            </TR>
            <TR>
		  <td id="kkk2" >&nbsp;</td>
            <TD style="" id="kk2" height=25 bgcolor=""><a id="menu_item2" href="userManager.php" target=mainFrame>外汇账户管理</A></TD>
            </TR>
	     <TR>
									      <td id="kkk3" >&nbsp;</td>
             <TD style="" id="kk3" height=25 bgcolor=""><a id="menu_item3" href="noteSet.php" target=mainFrame>短信内容设置</A></TD>
            </TR>
			<TR><TD height=5></TD></TR>
           
	 
		 </TABLE>
        </DIV>
	   </TD>
	  </TR>
	 </TBODY>
	</TABLE>
	 
	 
	</TD>
   </TR>
  </TBODY>
<script>
      	      $("#menu_item1").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		  $("#kk2").css('background-color','white');
		  $("#kk3").css('background-color',"white");
		  $("#kkk1").css('background-color', '#bbccff');
		  $("#kkk2").css('background-color', '#fff');
		  $("#kkk3").css('background-color', '#fff');
		});
      	      $("#menu_item2").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		  $("#kk1").css('background-color','white');
		  $("#kk3").css('background-color',"white");
		  $("#kkk2").css('background-color', '#bbccff');
		  $("#kkk1").css('background-color', '#fff');
		  $("#kkk3").css('background-color', '#fff');
		});
      	      $("#menu_item3").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		  $("#kk2").css('background-color','white');
		  $("#kk1").css('background-color',"white");
		  $("#kkk3").css('background-color', '#bbccff');
		  $("#kkk1").css('background-color', '#fff');
		  $("#kkk2").css('background-color', '#fff');
		});
</script>
</body>
</html>
