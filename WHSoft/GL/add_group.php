<?php
/*
add_group.php
*/
?>
<?php
session_start();
include_once "header.php";
//include_once "waibu.php";
//include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/function.php";
include_once "../../function/xdownpage.php";

?>
<?php
	$sql = "SELECT LAST_INSERT_ID()";
	$handle = openConn();
	if($handle == NULL) die("mysql_error!".mysql_error());
	$result = mysql_query($sql,$handle);
	if($result !== false)
	{
		$res_count = mysql_fetch_array($result,MYSQL_NUM);
	//	echo "last id: ".$res_count[0];
	}else {	die("mysql_error!".mysql_error());	}

	closeConn($handle);			
	
	$pxgz = getFormValue("pxgz");
	$pxgz_type = getFormValue("pxgz_type");

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
<script language="javascript" src="js/jquery.cookie.js" type="text/javascript"></script>

<script  language="javascript">
	var idx;
	$(function() {
		$(".g_checkbox").click(
			function()
			{
				idx = $(".g_checkbox").index(this);
				if ($(this).attr("checked") == true)
				{
				//	alert( $(".g_checkbox").index(this) );
			//
					$("#check_label"+idx).css("color","red");
					$("#check_label"+idx).text("添加");
					$(this).parent().parent().toggleClass("checked_line");	
				}else
				{
					$(this).parent().parent().toggleClass("checked_line")
					$("#check_label"+idx).text("");
				}

			}
		);
		
		if( $.cookie("gname"))
		{
			$("#group_name").val(  $.cookie("gname") );
		}
		
		$("#group_name").blur(
			function()
			{
				if( $(this).val().length > 0)
				{
					$.cookie("gname",$.trim($(this).val()) );
				}
				//alert("set cookie");
			}
		);

	});
</script>
<style type="text/css">
.checked_line
{
	background:#ffcc99;
}
</style>

</head>
<body>
<form style="margin:8px;"  method="POST" action="groupSet.php">
<div  style="clear:both;">
<span>要创建的群名子:</span><br /> 
<input type="text" size="34" class="g_input"  id="group_name" value="" /> 
<span style="color:#ccc;"> 字数不能超过200个字</span>
</div> 
<br />
<div > <label> 从下面列出的用户中添加成员 (成员数:<span id="meb_count" >0</span>)</label> </div>

<?php
	
	$sql2="";
	$sql = "select id,yhmc,yhlx,lxdz,gddh,yddh,diskid,zcrq,rjjsrq,zt,bz from softsetup";

	if($pxgz!="")
	{
		if($pxgz == "sfzx")
		{
			$sql .= " order by ( UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq,' '), zhsxsj)))";
		}else
		{
			$sql .= " order by ".$pxgz;
		}
		if($pxgz_type =="yes")
		{
			$sql .= " desc";
			$pxgz_type1 = "";
		}else
		{
			$pxgz_type1 = "yes";
		}
	}

	if( $pg!="")
	{
	  $sql2 = $sql;
	        $sql .= " LIMIT ".((intval($pg)-1)*20).", 20";	
		
	}
	else
	{
	  $sql2 = $sql; 
	  	$sql .= " LIMIT 0 , 20";
	}
	$handle = openConn();
	if($handle ==NULL)  die();
	//$sql_get_count = "select count(*) from softsetup";
	$result = mysql_query($sql2,$handle);
	if( $result !== false)
	  {
	$all = mysql_num_rows($result);
	$all_num = $all;
	  }
	else { die("mysql error".mysql_error()); }

        $result = mysql_query($sql,$handle);
       	if($result ===false)
	{
		echo "Search mysql error()".mysql_error()."<br />";
		closeConn($handle);
		die();	
	}

	$num = mysql_num_rows($result);
	if($num > 0 )
	{
	
?>
     <table style="margin:8px;border:1px solid #bbb;border-bottom:none;" id="out_list" border="0" cellspacing="0" width="1600" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0"><thead>
      <tr height='30' bgcolor='#000000'>
       <td width="100" class="tdbiaoti">确认操作</td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("yhmc",$pxgz_type1);?>')">用户名称</a>
	   <?php 
		if ($pxgz=="yhmc" && $pxgz_type== "yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz=="yhmc" )
		{
			echo "<img src='images/up.gif'>";
		}
	   ?>
	   </td>
       <td width="150" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("diskid",$pxgz_type1);?>')">软件注册号</a>
	   <?php if ($pxgz =="diskid" &&  $pxgz_type=="yes") 
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if($pxgz=="diskid")
		{
		   echo "<img src='images/up.gif'>";
		}
	   ?>
	   </td>

       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("gddh",$pxgz_type1); ?>')">固定电话</a>
	   <?php if ($pxgz =="gddh" && $pxgz_type=="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="gddh" )
		{
		   echo "<img src='images/up.gif'>";
		}
	   ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("yddh",$pxgz_type1);?>')">移动电话</a>
	   <?php if( $pxgz=="yddh" && $pxgz_type=="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz=="yddh" )
		{
		   echo  "<img src='images/up.gif'>";
		}		
	   ?>
	   </td>
       <td width="200" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("lxdz",$pxgz_type1);?>')">联系地址</a>
	   <?php if ($pxgz=="lxdz" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="lxdz" )
		{
		   echo "<img src='images/up.gif'>";
		} 
	   ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zcrq",$pxgz_type1);?>')">注册日期</a>
	   <?php
		if($pxgz =="zcrq" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if(  $pxgz == "zcrq") 
		{
			echo  "<img src='images/up.gif'>";
		}
	   ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("rjjsrq",$pxgz_type1); ?>')">软件截止日期</a>
	   <?php 
		if ($pxgz == "rjjsrq" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="rjjsrq")
		{
		   echo  "<img src='images/up.gif'>";
		}
		 
	   ?>
	   </td>
       <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("yhlx",$pxgz_type1); ?>')">用户类型</a>
	   <?php if ($pxgz =="yhlx" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="yhlx")
		{
			 echo "<img src='images/up.gif'>";
		}
	  //用户类型,普通和vip
	   ?>

	   </td>
       <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zt",$pxgz_type1); ?>')">当前状态</a>
	   <?php if ($pxgz =="zt" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="zt")
		{
			 echo "<img src='images/up.gif'>";
		}
	   ?>

	   </td>


       <td width="190" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("bz",$pxgz_type1);  ?>')">备注</a>
	   <?php if ( $pxgz=="bz" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="bz" )
		{
		   echo "<img src='images/up.gif'>";
		}
		 
	   ?>
	   </td>
	  </tr>
	</thead>
      <tbody>

<?php
		$row = mysql_fetch_array($result,MYSQL_ASSOC); 
		for( $i = 0; $i < $num; $i++)
		{
?>
	  <tr height='25' style="cursor:hand; " onDblClick="javascript:if (this.style.background=='#ffffff'){this.style.background='#ccffff'}else{this.style.background='#ffffff'}">
	    <td align="center" style="border-bottom:1px  solid #ccc;">
	   <label id="check_label<?php echo $i; ?>" ></label>
<?php  
		    if( isset($_SESSION["zz"]) && intval($_SESSION["zz"]) ==1)
		      {
?>
         		<input type="checkbox" name="g_checkbox" class="g_checkbox"  value=""  style="height:18px;width:20px;"  />
			<input type="hidden" name="user_id" id="id_user<?php echo $i;?>" value="<?php echo trim($row["id"]); ?>" />
<?php
                      }
?>
        </td>
		<td align="center" style="border-bottom:1px  solid #ccc;"><?php echo trim($row["yhmc"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><a href="http://" class="link" onClick="JavaScript:openScript('userManager2.php?diskid=<?php echo trim($row["diskid"]);?>&pxgz=sfzx&pxgz_type=&action=Search','注册用户<?php echo trim($row["id"]);?>',1000,300,'yes')"><?php echo trim( $row["diskid"]);?></a></td>

		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["gddh"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;"  ><?php echo trim($row["yddh"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["lxdz"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["zcrq"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["rjjsrq"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;">
		<?php
			 switch( intval($row["yhlx"]) )
                        {
                                case 0: echo "普通用户"; break;
			        case 1: echo "VIP用户"; break;
			       default:break;
			}
                 ?>
                </td>												 
		<td align="center" style="border-bottom:1px  solid #ccc;" >
		<?php
			
		  if( intval($row["zt"])==1) 
		    echo "完全使用";
		  else
		    echo "帐户锁定";
		 
		?>
		</td>

		<td align="center" style="border-bottom:1px solid #ccc;" >&nbsp;<?php echo trim($row["bz"]);?></td>
	  </tr>


<?php
		$row = mysql_fetch_array($result,MYSQL_ASSOC);		
		}// end for( $i = 0; $i < $num; $i++)
?>
     </TABLE>
     <table style="margin-left:8px;margin-right:8px;" width="1600" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#ebeff9>
       <tr><td>
<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; 页次：".$a->pg."/".$a->page."&nbsp; 共".$a->countall."条记录&nbsp; ".$a->countlist."条/页";
			
?></td></tr>
    </tbody>
     </table>
	 <iframe name="delframe" id="delframe" style="display:none"></iframe>

<?php 
	}// end if($num > 0 )
	else
	{
		echo "<center><font size='3pt' color='#ff0000'>没有用户!</center></font>";
		
	}
	closeConn($handle);
	return;
?>
</form>

</body>
</html>