<?php
session_start();
include_once "header.php";
include_once "cscheck.php";
include_once "../../function/conn.php";
include_once "../../function/function.php";

include_once "../../function/xdownpage.php";

?>

<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="images/all-ie.css" />
<![endif]-->

<title>ע���û�����</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>
<script language="javascript">

$(function(){
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
<body style="">
<?php
	$action = getFormValue("action");
	switch($action)
	{
		case "Search":
			Search();
		break;
		case "del":
			del();
		break;
		default:

?>
<br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >ע���û�����</span>
</div>

<form action="softManager.php" method="get" name="formSearch" style="margin:0px">
 <TABLE border="0" cellspacing="0" width="100%" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0" align="left">
  <tr bgcolor="" height="30">
   <td align="left" style="padding-left:10px">
     ע �� �ţ�
	 <input name="diskid" type="text" size="20" maxlength="15" class="logininput">
   </td>
   <td align="left" style="padding-left:10px">
     �û����ƣ�
	 <input name="yhmc" type="text" size="20" maxlength="15" class="logininput" >
   </td>
   <td align="left" style="padding-left:10px">
     �̶��绰��
	 <input name="gddh" type="text" size="20" maxlength="18" class="logininput" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" >
   </td>
  </tr>
  
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     �ƶ��绰��
	 <input name="yddh" type="text" size="20" maxlength="18" class="logininput">
   </td>
   <td align="left" style="padding-left:10px">
     �û����ͣ�
	 <select name="yhlx">
	  <option value="">ȫ���û�</option>
	  <option value="0">��ͨ�û�</option>
	  <option value="1">VIP�û�</option>
	 </select>
   </td>

   <td align="left" style="padding-left:10px">
     ��ǰ״̬��
	 <select name="zt">
	  <option value="">ȫ���û�</option>
	  <option value="1">��ȫʹ��</option>
	  <option value="2">�����˻�</option>
	 </select>
   </td>

  </tr>
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     �Ƿ����ߣ�
	 <select name="sfzx">
	  <option value="">ȫ���û�</option>
	  <option value="1">�����û�</option>
	  <option value="0">�����û�</option>
	 </select>
   </td>

   <td align="left" style="padding-left:10px" colspan="3">
     ע�����ڣ�
	 <input name="zcrq1" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""><b>&nbsp;<=&nbsp;</b>ע������<b>&nbsp;<=&nbsp;</b>
	 <input name="zcrq2" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 �����ֹ���ڣ�
	 <input name="rjjsrq1" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""><b>&nbsp;<=&nbsp;</b>ע������<b>&nbsp;<=&nbsp;</b>
	 <input name="rjjsrq2" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="">
    </td>
   </tr>
   <tr bgcolor="#FFFFFF" height="30">
	<td align="left" style="padding-left:10px;" >
		�����̱��:
		<input name="proxy" type="text" size="10" maxlength="7" class="logininput" onClick="javascript:this.focus()"  "return false;" style="cursor:hand" >
	</td>
    <td align="left" style="padding-left:10px" >
     ����������ڣ�
	 <input name="zhsxrq1" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""><b>&nbsp;<=&nbsp;</b>ע������<b>&nbsp;<=&nbsp;</b>
	 <input name="zhsxrq2" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="">
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  �������
	  <select name="pxgz" class="input1" style="width:150px">
	   <option value="diskid">���ע���</option>
	   <option value="yhmc">�û�����</option>
	   <option value="lxdz">��ϵ��ַ</option>
	   <option value="gddh">�̶��绰</option>
	   <option value="yddh">�ƶ��绰</option>
	   <option value="zcrq" selected="selected">ע������</option>
	   <option value="rjjsrq">�����ֹ����</option>
	   <option value="zhsxrq">�����������</option>
	   <option value="zt">�û�״̬</option>
	   <option value="sfzx">�Ƿ�����</option>
	   <option value="bz">��ע</option>
	  </select>
	  <input type="checkbox" value="yes" name="pxgz_type"> ����
	</td>
   </tr>
   <tr bgcolor="#FFFFFF" height="30">
	<td align="center" colspan="3">
	<input type="hidden" value="Search" name="action"><input id="btg_confirm_search"  type="submit" name="searchButton" value="ȷ������" class="buttonStyle">&nbsp;<input id="btg_confirm_reset" name="reset" type="reset" value="������д" class="buttonStyle">
	</td>
  </tr>
  </TABLE>
</form>
<?php  ;}     //end switch ?>
<?php


function Search()
{
	$diskid = getFormValue("diskid" );    $yhmc           = getFormValue("yhmc");    $gddh  = getFormValue("gddh" );
	$yddh   = getFormValue("yddh"   );    $zcrq1         = getFormValue("zcrq1");    $zcrq2 = getFormValue("zcrq2");
	$rjjsrq1= getFormValue("rjjsrq1");    $rjjsrq2     = getFormValue("rjjsrq2");    $zt    = getFormValue("zt"   );
	$zhsxrq1= getFormValue("zhsxrq1");    $zhsxrq2     = getFormValue("zhsxrq2");    $sfzx  = getFormValue("sfzx" );
	$pxgz   = getFormValue("pxgz"   );    $pxgz_type = getFormValue("pxgz_type");	 $pxgz_type1 = "";
	$pg     = getFormValue("pg");
	$yhlx   = getFormValue("yhlx");
	$proxy  = getFormValue("proxy");
  
	$sql = "";
	$sql .= build_sql_query($diskid  , "diskid", "like", $sql, "", "%");
	$sql .= build_sql_query($yhmc    , "yhmc"  , "like", $sql, "", "%");
	$sql .= build_sql_query($gddh    , "gddh"  , "like", $sql, "", "%");
	$sql .= build_sql_query($yddh    , "yddh"  , "like", $sql, "", "%");
	$sql .= build_sql_query($proxy   , "proxy"  ,"like", $sql, "", "%");

	if($zcrq1 != "")
	{
		if(strpos($sql,"where"))
			$sql .= " and zcrq >='".$zcrq1."'";
		else
			$sql .= " where zcrq >='".$zcrq1."%'";
	}	
        if($zcrq2 != "")
        {
                if(strpos($sql,"where"))
                        $sql .= " and zcrq <='".$zcrq2."'";
                else
                        $sql .= " where zcrq <='".$zcrq2."%'";
        }
	if($rjjsrq1 != "")
	{
		if(strpos($sql,"where"))
			$sql .= " and rjjsrq >='".$rjjsrq1."'";
		else
			$sql .= " where rjjsrq >='".$rjjsrq1."%'";
	}
        if($rjjsrq2 != "")
        {
                if(strpos($sql,"where"))
                        $sql .= " and rjjsrq <='".$rjjsrq2."'";
                else
                        $sql .= " where rjjsrq <='".$rjjsrq2."%'";
        }

	if($zhsxrq1 != "")
	{
		if(strpos($sql,"where"))
			$sql .= " and zhsxrq >='".$zhsxrq1."'";
		else
			$sql .= " where zhsxrq >='".$zhsxrq1."%'";
	}

        if($zhsxrq2 != "")
        {
                if(strpos($sql,"where"))
                        $sql .= " and zhsxrq <='".$zhsxrq2."'";
                else
                        $sql .= " where zhsxrq <='".$zhsxrq2."%'";
        }
 
	if($zt != "")
	{
		if(strpos($sql,"where"))
			$sql .= " and zt =".$zt;
		else
			$sql .= " where zt =".$zt;
	}

	if($yhlx != "")
	{
		if(strpos($sql,"where"))
			$sql .= " and yhlx =".$yhlx;
		else
			$sql .= " where yhlx =".$yhlx;
	}


	if( $sfzx != "")
	{
		if (strpos ($sql, "where"))
		{
			if ($sfzx =="1" )
			{
				$sql .= " and UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq,' '), zhsxsj)) <= 3600";
			}
			else
			{
				$sql .= " and UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq,' '), zhsxsj)) > 3600";
			}
		}
		else
		{
			if ($sfzx == "1")
			{
				$sql .= " where UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq, ' '), zhsxsj))  <= 3600";
			}
			else
			{
				$sql .= " where UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq, ' '), zhsxsj))  > 3600";	
			}
		}
	}

	if(intval($_SESSION["zz"]) ==1)
	{
		
	}
	else if(intval($_SESSION["zz"])!= 1   /* && is_numeric ( $_SESSION["yhgl"] ) */ )
	{
		if( $_SESSION["yhgl"]  != "")
		{
			if(strpos($sql,"where"))
			{
				$sql .=  "  and proxy ='".$_SESSION["yhgl"]."'";
			}else
			{
				$sql .=  "  where proxy ='".$_SESSION["yhgl"]."'";
			}
		}
		else die("session error ");
	}

	$sql = "select id,yhmc,yhlx,lxdz,gddh,yddh,diskid,zcrq,rjjsrq,zt,bz,zhsxrq,zhsxsj ,proxy from softsetup ".$sql;


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
     <TABLE width="1600" style="margin:8px;border:1px solid #bbb;border-bottom:none;" id="out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0"><thead>
      <tr height='30' bgcolor='#000000'>
       <td width="100" class="tdbiaoti">ȷ�ϲ���</td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("yhmc",$pxgz_type1);?>')">�û�����</a>
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
       <td width="150" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("diskid",$pxgz_type1);?>')">���ע���</a>
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
	   <td width="100" class="tdbiaoti">�����˺�</td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("gddh",$pxgz_type1); ?>')">�̶��绰</a>
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
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("yddh",$pxgz_type1);?>')">�ƶ��绰</a>
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
       <td width="200" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("lxdz",$pxgz_type1);?>')">��ϵ��ַ</a>
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
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zcrq",$pxgz_type1);?>')">ע������</a>
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
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("rjjsrq",$pxgz_type1); ?>')">�����ֹ����</a>
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
       <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("yhlx",$pxgz_type1); ?>')">�û�����</a>
	   <?php if ($pxgz =="yhlx" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="yhlx")
		{
			 echo "<img src='images/up.gif'>";
		}
	  //�û�����,��ͨ��vip
	   ?>

	   </td>
       <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zt",$pxgz_type1); ?>')">��ǰ״̬</a>
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
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zhsxrq",$pxgz_type1);  ?>')">�����������</a>
	   <?php if( $pxgz =="zhsxrq" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="zhsxrq") 
		{
		   echo "<img src='images/up.gif'>";
		}
	   ?>
	   </td>
       <td width="100" class="tdbiaoti">�������ʱ��</td>
	   <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("sfzx",$pxgz_type1); ?>')">�Ƿ�����</a>
	   <?php if ($pxgz =="sfzx" &&  $pxgz_type =="yes" )
		{
	       		echo  "<img src='images/down.gif'>";
		}
		else if( $pxgz =="sfzx") 
		{
		   echo "<img src='images/up.gif'>";
		}
	   ?>
	   </td>

<?php if( intval( $_SESSION["zz"])  == 1) { ?>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("proxy",$pxgz_type1);  ?>')">������</a>
	   <?php if ( $pxgz=="proxy" && $pxgz_type =="yes")
		{
	       		echo "<img src='images/down.gif'>";
		}
		else if( $pxgz =="proxy" )
		{
		   echo "<img src='images/up.gif'>";
		}
		 
	   ?>
	   </td>
<?php } ?>
       <td width="190" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("bz",$pxgz_type1);  ?>')">��ע</a>
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
         <a href="" class="del" onClick="JavaScript:openScript('soft_edit.php?id=<?php echo trim($row["id"]);?>','ע���û�<?php echo trim($row["id"]);?>',500,400,'no');return false">�޸�</a>
<?php  
		    if( isset($_SESSION["zz"]) && intval($_SESSION["zz"]) ==1)
		      {
?>
         <a href="?action=del&id=<?php echo trim($row["id"]);?>" class="del" onClick="return confirm('ɾ�����û���ɾ����������ʹ�ù�������˻�,��ȷ������ɾ��������')" target="delframe">ɾ��</a>
<?php
                      }
?>
        </td>
		<td align="center" style="border-bottom:1px  solid #ccc;"><?php echo trim($row["yhmc"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><a href="http://" class="link" onClick="JavaScript:openScript('userManager2.php?diskid=<?php echo trim($row["diskid"]);?>&pxgz=sfzx&pxgz_type=&action=Search','ע���û�<?php echo trim($row["id"]);?>',1000,300,'yes')"><?php echo trim( $row["diskid"]);?></a></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" >
		<?php
		
		  $sql1="select count(id) from userzhb where diskid='".trim($row["diskid"])."'";
		  $result1 = mysql_query($sql1,$handle);
		  $row1 = mysql_fetch_array($result1,MYSQL_NUM);
		  if($row1[0] > 0)
		{ 
			  
		?>
		��<a href="http:///" class="link" onClick="JavaScript:openScript('userManager2.php?diskid=<?php echo trim($row["diskid"]);?>&pxgz=sfzx&pxgz_type=&action=Search','ע���û�<?php echo trim($row["id"]);?>',1000,300,'yes');return false"><?php echo $row1[0]; ?></a>��
		<?php
		
		}
		  else   echo "��<font color=#FF0000>0</font>��";
			
		?>
		</td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["gddh"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;"  ><?php echo trim($row["yddh"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["lxdz"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["zcrq"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["rjjsrq"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;">
		<?php
			 switch( intval($row["yhlx"]) )
                        {
                                case 0: echo "��ͨ�û�"; break;
			        case 1: echo "VIP�û�"; break;
			       default:break;
			}
                 ?>
                </td>												 
		<td align="center" style="border-bottom:1px  solid #ccc;" >
		<?php
			
		  if( intval($row["zt"])==1) 
		    echo "��ȫʹ��";
		  else
		    echo "�ʻ�����";
		 
		?>
		</td>
		<td align="center" style="border-bottom:1px  solid #ccc;"><?php echo trim($row["zhsxrq"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" ><?php echo trim($row["zhsxsj"]);?></td>
		<td align="center" style="border-bottom:1px  solid #ccc;" >
		<?php
		if(DateDiff("n",trim($row["zhsxrq"])." ".trim($row["zhsxsj"]),now())<=60) 
	        	echo "����";
	      	else
	        	echo "����";
	      
		?>
		</td>

		<?php if (intval($_SESSION["zz"] ) == 1) { ?>
		<td align="center" style="border-bottom:1px solid #ccc;" >&nbsp;<?php echo trim($row["proxy"]);?></td>
		<?php  } ?>
		<td align="center" style="border-bottom:1px solid #ccc;" >&nbsp;<?php echo trim($row["bz"]);?></td>
	  </tr>

<?php
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
 		
		}//end for
?>
     </TABLE>
     <table style="margin-left:8px;margin-right:8px;" width="1600" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#ebeff9>
       <tr><td>
<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; ҳ�Σ�".$a->pg."/".$a->page."&nbsp; ��".$a->countall."����¼&nbsp; ".$a->countlist."��/ҳ";
			
?></td></tr>
    </tbody>
     </table>
	 <iframe name="delframe" id="delframe" style="display:none"></iframe>

<?php	
	}
	else
	{
		echo "<center><font size='3pt' color='#ff0000'>û����Ҫ���ҵ���Ϣ!</center></font>";
		
	}
	closeConn($handle);
	return;

} //end Search()

function del()
{
	$id="";
	$id = getFormValue("id");
	$handle = openConn();
	if($handle ==NULL) die();
	$sql = "select diskid from softsetup where id=".intval($id);
	$result = mysql_query($sql,$handle);
	if($result ===false)
	{
		closeConn($handle);
		echo "<script language=javascript>alert('ɾ��ʧ�ܣ��û�������');window.parent.location.reload();</script>";
	}
	else
	{
		$row = mysql_fetch_array($result);
		$sql = "delete from userzhb where diskid='".$row[0]."'";
	}

	//$sql="delete from userzhb where diskid=(select diskid from softsetup where id=".intval($id).")";
	$sql0 = "delete from softsetup where id=".intval($id);
	$result = mysql_query($sql0,$handle);
	if($result === false)
	{
		closeConn($handle);
		echo "<script language=javascript>alert('ɾ��ʧ�ܣ�');window.parent.location.reload();</script>";
		die();
	}else
	{
		mysql_query($sql,$handle);
		closeConn($handle);
		echo "<script language=javascript>alert('ɾ���ɹ���');window.parent.location.reload();</script>";
		die();
	}

}
 
?>
</body>
</html>


