<?php 
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
<title>����˻�����</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
</head>
<body>
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
 <TABLE border=0 cellPadding=0 cellSpacing=0 width="800" align="center">
  <tr>
   <td background="images/admin_bg_1.gif"  height="25" colspan="5" class="biaoti">����˻�����</td>
  </tr>
 </TABLE>
 <form action="userManager.php" method="get" name="formSearch" style="margin:0px">
 <TABLE border="1" cellspacing="0" width="800" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0" align="center">
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     ע �� �ţ�
	 <input name="diskid" type="text" size="20" maxlength="15" class="logininput">
   </td>
   <td align="left" style="padding-left:10px">
     �ʻ����ƣ�
	 <input name="zhmc" type="text" size="20" maxlength="20" class="logininput" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
   </td>
   <td align="left" style="padding-left:10px">
     �˻��ʺţ�
	 <input name="zh" type="text" size="20" maxlength="20" class="logininput">
   </td>
  </tr>
  
  <tr bgcolor="#FFFFFF" height="30">
   <td align="left" style="padding-left:10px">
     �ʻ����ͣ�
	 <select name="zhlx">
	  <option value="">ȫ���û�</option>
	  <option value="0">ģ���ʻ�</option>
	  <option value="1">��ʵ�ʻ�</option>
	 </select>
   </td>
   <td align="left" style="padding-left:10px">
     ��㹫˾����
	 <input name="zcfsm" type="text" size="20" maxlength="50" class="logininput">
   </td>
   <td align="left" style="padding-left:10px">
     ���������ƣ�
	 <input name="serverame" type="text" size="20" maxlength="50" class="logininput">
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
   <td align="left" style="padding-left:10px" colspan="2">
     �˻���
	 <input value=">=" type="radio" name="zhye_type" checked class="radio">
	 <b>>=</b> <input value="<=" type="radio" name="zhye_type" class="radio">
	 <b><=</b> <input name="zhye" type="text" size="8" maxlength="10" class="logininput" onKeyUp="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')">
   </td>
  </tr>
  
   <tr bgcolor="#FFFFFF" height="30">
    <td align="left" style="padding-left:10px" colspan="3">
     ����������ڣ�
	 <input name="zhsxrq1" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly=""><b>&nbsp;<=&nbsp;</b>ע������<b>&nbsp;<=&nbsp;</b>
	 <input name="zhsxrq2" type="text" size="10" maxlength="10" class="logininput" onClick="javascript:this.focus()" onFocus="fPopCalendar(this,this,PopCal); return false;" style="cursor:hand" readonly="">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  �������
	  <select name="pxgz" class="input1" style="width:150px">
	   <option value="zhmc">�ʻ�����</option>
	   <option value="zh">�ʻ��ʺ�</option>
	   <option value="zhlx">�ʻ�����</option>
	   <option value="zcfsm">��㹫˾��</option>
	   <option value="serverame">����������</option>
	   <option value="zhye">�ʻ����</option>
	   <option value="diskid" selected="selected">���ע���</option>
	   <option value="zhsxrq">�����������</option>
	   <option value="sfzx">�Ƿ�����</option>
	   <option value="rjbb">����汾</option>
	  </select>
	  <input type="checkbox" value="yes" name="pxgz_type"> ����
	</td>
   </tr>
   <tr bgcolor="#FFFFFF" height="30">
	<td align="center" colspan="3"><input type="hidden" value="Search" name="action"><input type="submit" name="searchButton" value="ȷ������" class="buttonStyle">&nbsp;<input name="reset" type="reset" value="������д" class="buttonStyle">
	</td>
  </tr>
   
  </TABLE>
 </form>
<?php 
	;}

?>
<?php
function stf($str,$pxgz,$pxgz_type)
{
	
	  if( $pxgz == $str && $pxgz_type =="yes")
                echo "<img src='images/down.gif'>";
          else if( $pxgz == $str)
             echo "<img src='images/up.gif'>";

}

function  Search()
{
	$diskid    = getFormValue("diskid"   );   $zhmc      = getFormValue("zhmc"     );   $zhlx      = getFormValue("zhlx"     );
	$zcfsm     = getFormValue("zcfsm"    );   $serverame = getFormValue("serverame");   $sfzx      = getFormValue("sfzx"     );
	$zhye_type = getFormValue("zhye_type");   $zhye      = getFormValue("zhye"     );   $zhsxrq1   = getFormValue("zhsxrq1"  );
	$zhsxrq2   = getFormValue("zhsxrq2"  );   $pxgz	     = getFormValue("pxgz"     );   $pxgz_type = getFormValue("pxgz_type");
	$zh        = getFormValue("zh"       );   $pxgz_type1="";
	$pg        = getFormValue("pg"       );
	$sql ="";
	$sql .= build_sql_query($diskid    , "diskid"   , "like", $sql, "", "%");
	$sql .= build_sql_query($zhmc      , "zhmc"     , "like", $sql, "", "%");
	$sql .= build_sql_query($zh        , "zh"       , "like", $sql, "", "%");
	if($zhlx !="")
	{
		if(strpos($sql,"where"))
			$sql .= " and zhlx =".$zhlx;
		else
			$sql .= " where zhlx =".$zhlx;
	}

	$sql .= build_sql_query($zcfsm     , "zcfsm"    , "like", $sql, "","%");
	$sql .= build_sql_query($serverame , "serverame", "like", $sql, "","%");

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
                                $sql .= " and  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq,' '), zhsxsj))  <= 3600";
                        }
                        else
                        {
                                $sql .= " and  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq,' '), zhsxsj))  > 3600";
                        }
                }
                else
                {
                        if ($sfzx == "1")
                        {
                                $sql .= " where  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq, ' '), zhsxsj))  <= 3600";
                        }
                        else
                        {
                                $sql .= " where  UNIX_TIMESTAMP() - UNIX_TIMESTAMP(concat( concat(zhsxrq, ' '), zhsxsj))  > 3600";
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

	$sql = "select id,rjbb,zhmc,zh,zhlx,zcfsm,serverame,zhye,diskid,zhsxrq,zhsxsj from userzhb ".$sql;	

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
                $sql .= " LIMIT ".((intval($pg)-1)*20).", 20";
        }
        else
        {
                $sql .= " LIMIT 0 , 20";
        }

        $handle = openConn();
        if($handle ==NULL)  die();
        $sql_get_count = "select count(*) from userzhb";
        $result = mysql_query($sql_get_count,$handle);
        $all = mysql_fetch_array($result,MYSQL_NUM);
        $all_num = $all[0];

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
     <TABLE border="1" cellspacing="0" width="1460" cellpadding="1" bordercolorlight="#C0C0C0" bordercolordark="#C0C0C0" style="border-collapse: collapse" bordercolor="#C0C0C0">
      <tr height='30' bgcolor='#F1F3F5'>
       <td width="100" class="tdbiaoti">ȷ�ϲ���</td>
	   <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("diskid",$pxgz_type1);?>')">���ע���</a>
	   <?php if( $pxgz =="diskid" && $pxgz_type =="yes")
	       	echo "<img src='images/down.gif'>";
		 else if( $pxgz =="diskid") 
		 	echo "<img src='images/up.gif'>"
		
	   ?></td>
	   <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("rjbb",$pxgz_type1);?>')">����汾</a>
	   <?php if( $pxgz =="rjbb" && $pxgz_type =="yes") 
	       echo "<img src='images/down.gif'>";
		 else if( $pxgz =="rjbb") 
		   echo "<img src='images/up.gif'>";
		
	   ?></td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zhmc",$pxgz_type1);?>')">�ʻ�����</a>
	   <?php if ($pxgz == "zhmc"  && $pxgz_type =="yes") 
	       echo "<img src='images/down.gif'>";
		 else if( $pxgz =="zhmc" )
		   echo "<img src='images/up.gif'>";
		 
	   ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zh",$pxgz_type1);?>')">�ʻ��ʺ�</a>
	   <?php if( $pxgz=="zh"  && $pxgz_type=="yes")
	       echo "<img src='images/down.gif'>";
		 else if( $pxgz =="zh") 
		   echo "<img src='images/up.gif'>"
		 
	   ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zhlx",$pxgz_type1);?>')">�ʻ�����</a>
	<?php
		stf("zhlx",$pxgz,$pxgz_type);
	?>
	   </td>
       <td width="200" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zcfsm",$pxgz_type1);?>')">��㹫˾��</a>
	<?php
		stf("zcfsm",$pxgz,$pxgz_type);
	?>
	   </td>
       <td width="200" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("serverame",$pxgz_type1);?>')">����������</a>
	<?php stf("serverame",$pxgz,$pxgz_type); ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zhye",$pxgz_type1);?>')">�ʻ����</a>
	<?php stf("zhye",$pxgz,$pxgz_type); ?>
	   </td>
       <td width="100" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("zhsxrq",$pxgz_type1);?>')">�����������</a>
	<?php  stf("zhsxrq",$pxgz,$pxgz_type); ?>

	  </td>
       <td width="100" class="tdbiaoti">�������ʱ��</td>
	   <td width="80" class="tdbiaoti"><a href="#" class="tdbiaoti" onClick="changeUrl('<?php echo GetURLSort("sfzx",$pxgz_type1);?>')">�Ƿ�����</a>
	<?php stf("sfzx",$pxgz,$pxgz_type); ?>
	   </td>
	  </tr>
<?php
                $row = mysql_fetch_array($result,MYSQL_ASSOC);
                for( $i = 0; $i < $num; $i++)
                {

?>

	  <tr height='25' style="cursor:hand; background:#ffffff" onDblClick="javascript:if (this.style.background=='#ffffff'){this.style.background='#ccffff'}else{this.style.background='#ffffff'}">
	    <td align="center">
         <a href="?action=del&id=<?php echo trim($row["id"]);?>" class="del" onClick="return confirm('ɾ�����˻����޷��ָ�,��ȷ������ɾ��������')" target="delframe">ɾ��</a>
        </td>
		<td align="center"><?php echo trim($row["diskid"]);?></td>
		<td align="center">
		<?php
		  if ($row["rjbb"] ==1 )
		    echo "��׼��";
		  else if( $row["rjbb"] ==2 )
		    echo "������";
		  else if( $row["rjbb"] ==3 ) 
		    echo "�ƽ��";
                  else if( $row["rjbb"] ==4 )
		    echo "��ǿ��";
                  else if( $row["rjbb"] ==5 )
		    echo "���ư�";
		  
		?>
		</td>
		<td align="center"><?php echo trim($row["zhmc"]);?></td>
		<td align="center"><?php echo trim($row["zh"]);  ?></td>
		<td align="center">
		<?php
		  if( intval($row["zhlx"])==0) 
		    echo "ģ���ʻ�";
		  else
		    echo "��ʵ�ʻ�";
		  
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
	        	echo "����";
	      	  else
	        	echo "����";
	      
		?>
		</td>
	  </tr>

<?php
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		}//end for

?>
     </TABLE>
     <table width="1460" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#F1F3F5>
       <tr><td>
<?php
     $a = new Pager($all_num,20);
     echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; ҳ�Σ�".$a->pg."/".$a->page."&nbsp; ��".$a->countall."����¼&nbsp; ".$a->countlist."��/ҳ";

?>
	</td></tr>
     </table>
	 <iframe name="delframe" id="delframe" style="display:none"></iframe>


<?php
	}
	else
	{
		echo "<center><font size='3pt' color='#ff0000'>û����Ҫ���ҵ���Ϣ!</center></font>";
	}
	closeConn($handle);

}	


function del()
{

	$id = getFormValue("id");

        $sql = "delete from userzhb where id=".intval($id);
        $result = mysql_query($sql,$handle);
        if($result === false)
        {
                closeConn($handle);
                echo "<script language=javascript>alert('ɾ��ʧ�ܣ�');window.parent.location.reload();</script>";
                return;
        }else
        {
                closeConn($handle);
                echo "<script language=javascript>alert('ɾ���ɹ���');window.parent.location.reload();</script>";
                return;
        }


}



