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
<div id="main_menubar">
<div id="main_menubar_left" style="float:left;padding:5px;font-size:15px;">
      <span class=""><a href="index.php"   target="mainFrame"  >后台管理菜单</a></span>
	<?php
	if( intval($_SESSION["zz"]) == 1)
	  {
	    ?>
       <span id="kk6"> <a id="menu_item5" href="admin_user2.php" target="mainFrame"><u>帐户修改</u></A> </span>
	      <?php }else
	  {
	    ?>
       <span id="kk6" > <a  id="menu_item5" href="admin_user.php" target="mainFrame"><u>帐户修改</u></A> </span>
	    <?php   }
	    ?>
<span id="kk1"> <a id="menu_item1" href="softManager.php" target=mainFrame>注册用户管理</a> </span>
<span id="kk2"><a id="menu_item2" href="userManager.php" target=mainFrame>外汇账户管理</A> </span>
<span id="kk3"> <a id="menu_item3" href="noteSet.php" target=mainFrame>短信内容设置</A> </span>
</div>
<div id="main_menubar_right" style="float:right;padding:5px;"

<span id="kk5" ><b><?php echo $_SESSION["yhgl"]; ?></b></span>
<span id="kk4">  <a id="menu_item4" href="logout.php" target=mainFrame>登出</A> </span>
</div>

<div style="clear:both;"></div>

</div> <!-- end main_menubar --> 
<script>
      	      $("#menu_item1").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		
		  $("#kk2").css('background-color','white');
		  $("#kk3").css('background-color',"white");
		  $("#kk6").css('background-color',"white");
		 // $("#kkk1").css('background-color', '#bbccff');
		 // $("#kkk2").css('background-color', '#fff');
		 // $("#kkk3").css('background-color', '#fff');
		});
      	      $("#menu_item2").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		  
		  $("#kk1").css('background-color','white');
		  $("#kk3").css('background-color',"white");
		  $("#kk6").css('background-color',"white");
		 // $("#kkk2").css('background-color', '#bbccff');
		 // $("#kkk1").css('background-color', '#fff');
		 // $("#kkk3").css('background-color', '#fff');
		});
      	      $("#menu_item3").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		  $("#kk2").css('background-color','white');
		  $("#kk1").css('background-color',"white");
		  $("#kk6").css('background-color',"white");
		  //$("#kkk3").css('background-color', '#bbccff');
		  //$("#kkk1").css('background-color', '#fff');
		  //$("#kkk2").css('background-color', '#fff');
		});
      	      $("#menu_item5").click(function () {
		  $(this).parent().css('background-color', '#bbccff');
		  $("#kk2").css('background-color','white');
		  $("#kk1").css('background-color',"white");
		  $("#kk3").css('background-color',"white");
		 
		  //$("#kkk3").css('background-color', '#bbccff');
		  //$("#kkk1").css('background-color', '#fff');
		  //$("#kkk2").css('background-color', '#fff');
		});
</script>
</body>
</html>
