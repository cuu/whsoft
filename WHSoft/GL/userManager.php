<?php
session_start();

include_once "header.php";
include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/xdownpage.php";
?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<title>外汇账户管理</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>
<script  language="javascript" >
  $(function() {
$("#out_list tbody  tr").hover(
    function(){
        $(this).css("background", "#f4f4ff");
    },
    function(){
        $(this).css("background", "transparent");
    }

);

    $("#btg_confirm_search").button();
    $("#btg_confirm_reset").button();
  });
</script>
</head>
<body>
<?php
	$action = getFormValue("action");
	switch($action)
	{
		case "Search": u_Search(); //user_manager search
		break;
		case "del"   : del();
		break;
		default:
?> <br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >外汇账户管理</span>
</div>
 <form action="userManager.php" method="get" name="formSearch" style="margin:0px">
 <TABLE  border="0" cellspacing="0" width="100%" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0" align="left">
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     注 册 号：
	 <input name="diskid" type="text" size="20" maxlength="15" class="logininput">
   </td>
   <td align="left" style="padding-left:10px">
     帐户名称：
	 <input name="zhmc" type="text" size="20" maxlength="20" class="logininput" >
   </td>
   <td align="left" style="padding-left:10px">
     账户帐号：
	 <input name="zh" type="text" size="20" maxlength="20" class="logininput" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" >
   </td>
  </tr>
  
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     帐户类型：
	 <select name="zhlx">
	  <option value="">全部用户</option>
	  <option value="0">模拟帐户</option>
	  <option value="1">真实帐户</option>
	 </select>
   </td>
   <td align="left" style="padding-left:10px">
     外汇公司名：
	 <input name="zcfsm" type="text" size="20" maxlength="50" class="logininput">
   </td>
   <td align="left" style="padding-left:10px">
     服务器名称：
	 <input name="serverame" type="text" size="20" maxlength="50" class="logininput">
   </td>
  </tr>
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     是否在线：
	 <select name="sfzx">
	  <option value="">全部用户</option>
	  <option value="1">在线用户</option>
	  <option value="0">离线用户</option>
	 </select>
   </td>
   <td align="left" style="padding-left:10px" colspan="2">
     账户余额：
	 <input value=">=" type="radio" name="zhye_type" checked class="radio">
	 <b>>=</b> <input value="<=" type="radio" name="zhye_type" class="radio">
	 <b><=</b> <input name="zhye" type="text" size="8" maxlength="10" class="logininput" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
   </td>
  </tr>
  
   <tr bgcolor="#FFFFFF" height="30">
    <td align="left" style="padding-left:10px" colspan="3">
     最后上线日期：
	 <input name="zhsxrq1" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""><b>&nbsp;<=&nbsp;</b>注册日期<b>&nbsp;<=&nbsp;</b>
	 <input name="zhsxrq2" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  排序规则：
	  <select name="pxgz" class="input1" style="width:150px">
	   <option value="a.zhmc">帐户名称</option>
	   <option value="a.zh">帐户帐号</option>
	   <option value="a.zhlx">帐户类型</option>
	   <option value="a.zcfsm">外汇公司名</option>
	   <option value="a.serverame">服务器名称</option>
	   <option value="a.zhye">帐户余额</option>
	   <option value="a.diskid" selected="selected">软件注册号</option>
	   <option value="a.zhsxrq">最后上线日期</option>
	   <option value="a.sfzx">是否在线</option>
	   <option value="a.rjbb">软件版本</option>
	  </select>
	  <input type="checkbox" value="yes" name="pxgz_type"> 倒序
	</td>
   </tr>
   <tr bgcolor="#FFFFFF" height="30">
	<td align="center" colspan="3"><input type="hidden" value="Search" name="action"><input id="btg_confirm_search" type="submit" name="searchButton" value="确定搜索" class="buttonStyle">&nbsp;<input id="btg_confirm_reset"  name="reset" type="reset" value="重新填写" class="buttonStyle">
	</td>
  </tr>
   
  </TABLE>
 </form>
<?php 
	; } //end switch

function stf($str,$pxgz,$pxgz_type)
{
          if( $pxgz == $str && $pxgz_type =="yes")
                echo "<img src='images/down.gif'>";
          else if( $pxgz == $str)
             echo "<img src='images/up.gif'>";

}


function u_Search()
{
	$diskid    = getFormValue("diskid"   );   $zhmc    = getFormValue("zhmc"   );   $zh        = getFormValue("zh"       );
	$zhlx      = getFormValue("zhlx"     );   $zcfsm   = getFormValue("zcfsm"  );   $serverame = getFormValue("serverame");
	$sfzx      = getFormValue("sfzx"     );   $zhye    = getFormValue("zhye"   );   $zhye_type = getFormValue("zhye_type");
	$zhsxrq1   = getFormValue("zhsxrq1"  );   $zhsxrq2 = getFormValue("zhsxrq2");   $pxgz      = getFormValue("pxgz"     );
	$pxgz_type = getFormValue("pxgz_type");
	$pg        = getFormValue("pg"       );

	$sql ="";
	build_sql_query($diskid     , "a.diskid"    , "like", $sql, "", "%");
	build_sql_query($zhmc       , "a.zhmc"      , "like", $sql, "", "%");
	build_sql_query($zh         , "a.zh"        , "like", $sql, "", "%");
	if($zhlx!="")
	{
		if(strpos($sql,"where"))
			$sql .= " and a.zhlx = ".$zhlx;
		else
			$sql .= " where a.zhlx = ".$zhlx;
	}

	build_sql_query($zcfsm      , "a.zcfsm"     , "like", $sql, "", "%");
	build_sql_query($serverame  , "a.serverame" , "like", $sql, "", "%");
	if($zhsxrq1!="")
	{
		if(strpos($sql,"where"))
			$sql.= " and a.zhsxrq >='".$zhsxrq1."'";
		else
			$sql.= " where a.zhsxrq >='".$zhsxrq1."%'";
	}
	if($zhsxrq2!="")
	{
		if(strpos($sql,"where"))
			$sql .= " and a.zhsxrq <='".$zhsxrq2."'";
		else
			$sql .= " where a.zhsxrq <='".$zhsxrq2."%'";
	}


        if( $sfzx != "")
        {
                if (strpos ($sql, "where"))
                {
                        if ($sfzx =="1" )
                        {
                                $sql .= " and  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(a.zhsxrq,' '), a.zhsxsj))  <= 3600";
                        }
                        else
                        {
                                $sql .= " and  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(a.zhsxrq,' '), a.zhsxsj))  > 3600";
                        }
                }
                else
                {
                        if ($sfzx == "1")
                        {
                                $sql .= " where UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(a.zhsxrq, ' '), a.zhsxsj))  <= 3600";
                        }
                        else
                        {
                                $sql .= " where  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(a.zhsxrq, ' '), a.zhsxsj))  > 3600";
                        }
                }
        }

        if($zhye!="")
        {
                if(strpos($sql,"where"))
                        $sql .= " and zhye ".$zhye_type.$zhye;
                else
                        $sql .= " where zhye ".$zhye_type.$zhye;
        }

		
	if(strpos($sql,"where"))
	{
		$sql .= " and a.diskid=b.diskid"; 
	}else
	{
		$sql .= " where a.diskid=b.diskid";
	}

	if(intval($_SESSION["zz"]) ==1)
	{
		
	}
	else if(intval($_SESSION["zz"])!= 1   /* && is_numeric ( $_SESSION["yhgl"] ) */ )
	{
		if( $_SESSION["yhgl"]  != "")
		{
			$sql .=  "  and  a.proxy ='".$_SESSION["yhgl"]."'";
		}
		else die("session error ");
	}

	$sql = "select a.id,a.rjbb,a.zhmc,a.zh,a.zhlx,a.zcfsm,a.serverame,a.zhye,a.diskid,a.zhsxrq,a.zhsxsj,a.proxy,b.yhmc from userzhb a,softsetup b ".$sql;
	


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
        //$sql_get_count = "select count(*) from userzhb";
        $result = mysql_query($sql2,$handle);
	if ($result !== false)
	  {
	    $all_num = mysql_num_rows($result);
	  }else { die("mysql_error".mysql_error()); }
        
     
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
     <TABLE border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="margin:8px;border:1px solid #bbb;border-bottom:none;border-collapse: collapse" bordercolor="#C0C0C0"  id="out_list" ><thead>
      <tr height='30' bgcolor='#000000'>
       <td width="100" class="tdbiaoti">确认操作</td>
	   <td width="140" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.diskid",$pxgz_type1);?>')">软件注册号</a>
	   <?php
		stf("a.diskid",$pxgz,$pxgz_type); 
	   ?></td>
	   <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("b.yhmc",$pxgz_type1);?>')">用户名称</a>
	   <?php  stf("b.yhmc",$pxgz,$pxgz_type); ?>
	   
	   </td>
	   <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.rjbb",$pxgz_type1);?>')">软件版本</a>
	   <?php stf("a.rjbb",$pxgz,$pxgz_type); ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.zhmc",$pxgz_type1);?>')">帐户名称</a>
	   <?php stf("a.zhmc",$pxgz,$pxgz_type); ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.zh",$pxgz_type1);?>')">帐户帐号</a>
	   	<?php stf("a.zh",$pxgz,$pxgz_type); ?>
	   
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.zhlx",$pxgz_type1);?>')">帐户类型</a>
	   <?php stf("a.zhlx",$pxgz,$pxgz_type); ?>
	   
	   </td>
       <td width="200" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.zcfsm",$pxgz_type1);?>')">外汇公司名</a>
		<?php stf("a.zcfsm",$pxgz,$pxgz_type); ?>
	   
	   </td>
       <td width="200" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.serverame",$pxgz_type1);?>')">服务器名称</a>
	<?php stf("a.serverame",$pxgz,$pxgz_type); ?>	   
	   
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.zhye",$pxgz_type1);?>')">帐户余额</a>
	<?php stf("a.zhye",$pxgz,$pxgz_type); ?>   
	   
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.zhsxrq",$pxgz_type1);?>')">最后上线日期</a>
	<?php stf("a.zhsxrq",$pxgz,$pxgz_type); ?>	   
	   </td>
       <td width="100" class="tdbiaoti">最后上线时间</td>
	   <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("a.sfzx",$pxgz_type1);?>')">是否在线</a>
		<?php stf("a.sfzx",$pxgz,$pxgz_type); ?>	   
	   </td>
	  </tr>
	</thead>
<?php 
	
                $row = mysql_fetch_array($result,MYSQL_ASSOC);
                for( $i = 0; $i < $num; $i++)
                {

?>
	  <tr height='25' style="border-bottom:1px solid #ccc;cursor:hand; background:#ffffff" onDblClick="javascript:if (this.style.background=='#ffffff'){this.style.background='#ccffff'}else{this.style.background='#ffffff'}">
	    <td align="center">
<?php
		      if( isset($_SESSION["zz"]) && intval($_SESSION["zz"]) ==1)
			{
?>
         <a href="?action=del&id=<?php echo trim($row["id"]);?>" class="del" onClick="return confirm('删除该账户将无法恢复,您确定进行删除操作吗？')" target="delframe">删除</a>
<?php
                        }
                        else
                        { echo "<span style='color:#F2F2F2;'>无法操作</span>"; }
?>

        </td>
		<td align="center"><?php echo trim($row["diskid"]);?></td>
		<td align="center"><?php echo trim($row["yhmc"]);?></td>
		<td align="center">
		<?php 
			switch( intval($row["rjbb"]))
			{
				case 1: echo "标准版"; break;
				case 2: echo "速利版"; break;
				case 3: echo "黄金版"; break;
				case 4: echo "增强版"; break;
				case 5: echo "趋势版"; break;
				default:break;
			}

		?>
		</td>
		<td align="center"><?php echo trim($row["zhmc"]);?></td>
		<td align="center"><?php echo trim($row["zh"]);?></td>
		<td align="center">
		<?php
		  if(intval($row["zhlx"])==0) 
		    echo "模拟帐户";
		  else
		    echo "真实帐户";
		 
		?>
		</td>
		<td align="center"><?php echo trim($row["zcfsm"]);?></td>
		<td align="center"><?php echo trim($row["serverame"]);?></td>
		<td align="center"><?php echo trim($row["zhye"]);?></td>
		<td align="center"><?php echo trim($row["zhsxrq"]);?></td>
		<td align="center"><?php echo trim($row["zhsxsj"]);?></td>
		<td align="center">
		<?php
		  if(DateDiff("n",trim($row["zhsxrq"])." ".trim($row["zhsxsj"]),now())<=60) 
	        	echo "在线";
	      else
	        	echo "离线";
	      
		?>
		</td>
	  </tr>


<?php
		
		$row = mysql_fetch_array($result,MYSQL_ASSOC);	
		} // end for
?>
     </TABLE>
     <table  style="margin-left:8px;margin-right:8px;" width="100%" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#F1F3F5>
       <tr><td>
	<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; 页次：".$a->pg."/".$a->page."&nbsp; 共".$a->countall."条记录&nbsp; ".$a->countlist."条/页";

	?>
	</td></tr>
     </table>
	 <iframe name="delframe" id="delframe" style="display:none"></iframe>
<?php
	}
	else
		echo "<center><font size='3pt' color='#ff0000'>没有您要查找的信息!</center></font>";
	
	closeConn($handle);	


} // end u_Search

function del()
{
	$id = getFormValue("id");
	$sql = "delete from userzhb where id=".intval($id);
	$handle = openConn();
	if($handle ==NULL) return;
	$result = mysql_query($sql,$handle);
	if($result === false)
	{
		echo "<script language=javascript>alert('删除失败！');window.parent.location.reload();</script>";
		return;
	}
	else
	{
		echo "<script language=javascript>alert('删除成功！');window.parent.location.reload();</script>";
		return;
	}
} // end del


