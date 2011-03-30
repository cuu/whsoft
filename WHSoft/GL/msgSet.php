<?php
/// 多播消息设置 for vip or other  用户
/*
具体发布 根据 ingroup(TEXT)
I set
xxx,xxx,xx,xxxx,xxx <-- all numbers for different group
allVIP  <- for all VIP users
allNOR  <- for all NORMAL users
allGRP  <- for all the users

*/
//mysql table layout:
/*
  $mysql_timestamp = date('Y-m-d H:i:s', $php_timestamp);

*/

?>
<?php
session_start();
include_once "header.php";
include_once "cscheck.php"; // for dev
include_once "../../function/conn.php";
include_once "../../function/function.php";

include_once "../../function/xdownpage.php";

?>


<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<link rel="stylesheet" type="text/css" href="images/css.css">
<!--[if IE]>
  <link rel="stylesheet" type="text/css" href="images/all-ie.css" />
<![endif]-->

<title>多播消息设置</title>
<script language="javascript" src="images/time.js" type="text/javascript"></script>
<script language="javascript" src="images/js.js" type="text/javascript"></script>
<?php
include "jq_ui.php";
?>
<link rel="stylesheet" type="text/css" href="images/jquery.bubblepopup.v2.3.1.css" />
<script language="javascript" src="images/jquery.bubblepopup.v2.3.1.min.js" type="text/javascript"></script>

<script language="javascript">
  $(function() {
	 
	$("#page_table_bar").width(  $("#vip_out_list").width());
	/*
	$(".show_in_group").mouseover(
		function()
		{
			$(this).toggleClass("hightlight");
		}
	);
	$(".show_in_group").mouseout(
		function()
		{
			$(this).toggleClass("hightlight");
		}
	);
	*/
	$(".show_in_group").each(
		function()
		{
			$(this).CreateBubblePopup({
			innerHtml: $(this).html(),
			innerHtmlStyle: {
				color:'#333333', 
				'text-align':'center'
			},												
			themeName: 	'orange',
			themePath: 	'images/jquerybubblepopup-theme'								 
			});
		}
	);

});
</script>
<style type="text/css">
#vip_out_list
{
  margin:8px;
  border:1px solid #bbb;
  border-bottom:none;
}
#vip_out_list td
{
  border-bottom:1px solid #ccc;
text-overflow:ellipsis;
overflow:hidden;white-space: nowrap;
}

.popup {
    position: absolute;
    display: none; 
}
.hightlight
{
	background:#b5d5ff;
}

</style>

</head>
<body>

<?php
	$action      = getFormValue("action")     ;
	$noteContent = getFormValue("noteContent");
	$sfqy        = getFormValue("sfqy")       ;
	$id          = getFormValue("id")         ;
        $pg          = getFormValue("pg");
if($id == "" ) $id=0;
if($action == "save") vip_save();
else if($action == "del")  vip_del();
else if($action == "edit") vip_edit();
else
{

		  //list all managers
		  /*
		    [delete]---[motify]----content----time--sendornot---  

		   */
		$sql = "select * from vipmsg";
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
		if($handle ==NULL) die( "mysql error". mysql_error() );
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
		if($num > 0)
		{
	    

?>
<br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >多播消息设置</span>
</div>
<div>
<a style="width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_vip_msg.php" >创建新的多播消息</a>
</div>

<table  id ="vip_out_list" border="0" cellspacing="0"  cellpadding="1" bordercolorlight="#fff" bordercolordark="#fff" style="border-collapse: collapse; table-layout:fixed;width:600px;WORD-BREAK: break-all;" bordercolor="#fff"  >
      <tr height='30' bgcolor='#000000'  >
       <td width="150" class="tdbiaoti">确认操作</td>
       <td width="240" height="30"  class="tdbiaoti">消息内容</td>
       <td width="120" class="tdbiaoti">消息最后发布时间</td>
       <td width="120" class="tdbiaoti">此消息目前状态</td>
	<td width="120" class="tdbiaoti">此消息的群</td>
	<td width="120" class="tdbiaoti">是否过期</td>
      <!--  <td width="120" class="tdbiaoti">使用设置</td> -->

       <td></td>
     </tr>
<?php
                  $row = mysql_fetch_array($result,MYSQL_ASSOC); 
                  for( $i = 0; $i < $num; $i++)
                  {   
?>    
                      <tr height='25' style="overflow:hidden;border-bottom:1px solid #ccc;">
                       <td align="center"  >
                         <a  class="del" href="add_vip_msg.php?action=edit&id=<?php echo trim($row["id"]);?>" >修改</a>
		    &nbsp;&nbsp;
                         <a href="?action=del&id=<?php echo trim($row["id"]);?>" class="del" onClick="return confirm('删除该管理帐号,您确定进行删除操作吗？')" target="delframe">删除</a>
                       </td>
                       <td nowrap  style=" overflow:hidden;width:240px;height:25px;" align="center">
                            <?php echo trim($row["content"]); ?>
                       </td>
                       <td align="center">
                            <?php echo trim( date("Y年n月j日",strtotime($row["time"])) ); ?>
                       </td>
		      <td align="center">
		      <?php
			      if( intval( $row["sfqy"] ) ==1)
				  echo "允许发布";
			      else
				  echo "禁止发布"; 
		      ?>
		      </td>
			<td align="center" >
				<?php
					$show_in_group = array();
					if( strstr($row["ingroup"],",") == FALSE)
					{
						if( strstr($row["ingroup"], "allVIP"))
							array_push($show_in_group, "所有VIP用户");
						else if ( strstr($row["ingroup"], "allNOR"))
							array_push($show_in_group,  "所有普通用户");
						else if ( strstr($row["ingroup"], "allGRP"))
							array_push($show_in_group,  "所有用户");
						else if (is_numeric($row["ingroup"]))
						{
							$sql12 = "select groupname from usergroup where id=".$row["ingroup"];
							$handle12 = openConn();
							if($handle12 ==NULL) die( "openConn error".mysql_error());
							$result12 = mysql_query($sql12, $handle12);
							if($result12)
							{
								$row12 = mysql_fetch_array($result12, MYSQL_NUM);
								array_push($show_in_group,  $row12[0]);
								closeConn($handle12);
							}
							else
							{
								die(" mysql_query error ".mysql_error());
							}
							
						}
					}
					else if( trim($row["ingroup"])!="" && strstr($row["ingroup"],",") )
					{
						$n_array2 = explode(",",$row["ingroup"]);
						for($nj = 0; $nj < count($n_array2); $nj++)
						{
							if( strstr($n_array2[$nj], "allGRP"))
								array_push($show_in_group, "所有用户");
							if( strstr($n_array2[$nj], "allVIP"))
								array_push($show_in_group, "所有VIP用户");
							else if( strstr($n_array2[$nj], "allNOR"))
								array_push($show_in_group,  "所有普通用户");
						}
						$handle11 = openConn();
						if($handle11 ==NULL) die( "openConn error".mysql_error());
						for($nj = 0;$nj < count($n_array2); $nj++)
						{
							if( is_numeric($n_array2[$nj]))
							{
								$sql = "select groupname from usergroup where id=".$n_array2[$nj];
								$result44 = mysql_query($sql,$handle11);
								if($result44 !==false)
								{
									$row55 = mysql_fetch_array($result44,MYSQL_NUM);
									if(trim($row55[0]) == "")
									{
										array_push($show_in_group, "群不存在");
									}else
										array_push($show_in_group, $row55[0]);
								}else
								{
									die(" mysql_query error ".mysql_error());
								}
							}
						}
						closeConn($handle11);
						/*
						$sql = "select id,groupname from usergroup";
						$handle11 = openConn();
						if($handle11 ==NULL) die( "openConn error".mysql_error());
						$result44 = mysql_query($sql,$handle11);
						if($result44 !==false)
						{
							$numx = mysql_num_rows($result44);
							if($numx > 0)
							{
								for($zx = 0; $zx < $numx; $zx++)
								{
									$row55 = mysql_fetch_array($result44,MYSQL_ASSOC);
									for($nj = 0; $nj < count($n_array2); $nj++)
									{
										if( $n_array2[$nj] == $row55["id"])
										{
											array_push($show_in_group, $row55["groupname"]);
											break;
										}
									}
								}
							}
							else
							{
								closeConn($handle11);
								array_push($show_in_group, "没有群");
							}
						}else
						{
							die(" mysql_query error ".mysql_error());
						}
						*/
						
					}
					if ( trim($row["ingroup"]) == "" || trim($row["ingroup"]) =="None" )
					{				
						array_push($show_in_group, "没有群");
					}
					echo '<div style="cursor:pointer;" class="show_in_group" >'.implode(",",$show_in_group).'</div>';
				?>
			</td>
			<td align="center">
			<?php
				$now_date = date("Y-m-d H:i:s");
				
				if(DateDiff("d",$row["time"],$now_date ) > 0)
					echo "已过期,可以删除";
				else if(DateDiff("d",$row["time"],$now_date ) == 0)
					echo "今日发布";
				else 
					echo "未过期,准备发布";
			?>
			</td>
                       <td align="center"></td>
                      </tr>

<?php
                      $row = mysql_fetch_array($result,MYSQL_ASSOC); 
                  }
?>
</table>
<table id="page_table_bar"  style="margin-left:10px;" width="600" border="0" align="left" cellpadding="0" cellspacing="8" bgcolor=#ebeff9>
       <tr><td>
<?php
	$a = new Pager($all_num,20);
	echo $a->thestr."&nbsp;".$a->backstr."&nbsp;".$a->nextstr."&nbsp;&nbsp; 页次：".$a->pg."/".$a->page."&nbsp; 共".$a->countall."条记录&nbsp; ".$a->countlist."条/页";
			
?></td></tr>
    </tbody>
     </table>
	 <iframe name="delframe" id="delframe" style="display:none"></iframe>

<?php
               }else
               {
?>

<br />
<div style="background:;font-weight:bold; padding-bottom:2px;padding-left:10px;margin-bottom:14px;" >
		    <span style="font-size:20px;" class="biaoti_guu" >目前没有多播消息,请您添加</span>
</div>
<div>
<a style="font-family:simhei;width:auto;height:auto;margin-left:10px;font-size:12px;color:blue;text-decoration:underline;" href="add_vip_msg.php" >创建新的多播消息</a>
</div>

<?php
            
               }
}
?>

</body>
</html>

<?php

function js_show_error($str)
{
	echo "<script language=javascript>alert('".$str."！');window.parent.location.reload();</script>";
}
function vip_save()
{

  check_root();
  $txt_msg_body = trim($_POST["txt_msg_body"]);

	if( strlen($txt_msg_body)> 2048)
	{
		js_show_error("多播消息内容长度太长,请重新填写!");
		die();
	}

  $status       = trim($_POST["pub_stat"]);
	if(count($_POST["pub_group"]) > 0)
	{
		$group = implode(",", $_POST["pub_group"] );
	}else
		$group = "None";
	if(strstr($group,"allGRP"))  $group ="allGRP";

  if( strlen($txt_msg_body) < 3)
  {
      js_show_error("多播消息内容长度不正确,请重新填写!");
      die();

  }
  $vip_putime = trim($_POST["vip_putime"]);
  $sql="";
  $title = "vipmsg";
  if( strlen($vip_putime)== 0) 
  {
       $vip_putime = 0;
       $sql = "insert into vipmsg(time,title,content,sfqy,ingroup) values(NOW(),'".$title."','".$txt_msg_body."',".$status.",'".$group."')";
  }
  else
  {
    $vip_putime = date('Y-m-d H:i:s', strtotime($vip_putime." ".date("H:i:s")));
    $sql = "insert into vipmsg(time,title,content,sfqy,ingroup) values('".$vip_putime."','".$title."','".$txt_msg_body."',".$status.",'".$group."')";
  }
  
  $handle = openConn();
  if($handle ==NULL) die( "mysql error". mysql_error() );
  $result = mysql_query($sql,$handle);
  if($result ===false)
  {
      echo "Save mysql error()".mysql_error()."<br />";
      closeConn($handle);
      die();	
  }
  else
  {
      	echo "创建消息成功!";
        closeConn($handle);
  }
   
  return;

}

function vip_del()
{
    check_root();
    $id = trim($_GET["id"]);
    $sql = "delete from  vipmsg  where id=".$id;
  
    $handle = openConn();
    if($handle ==NULL) die( "mysql error". mysql_error() );
    $result = mysql_query($sql,$handle);
    if($result ===false)
    {
        echo "Edit mysql error()".mysql_error()."<br />";
        closeConn($handle);
        die();	
    }
    else
    {
      	echo "<script language=javascript>alert('删除成功！');window.parent.location.reload();</script>";
        closeConn($handle);
    }

  return;     
}

function vip_edit()
{
    check_root();
  
   
    $id       = trim($_POST["edit_msg_id"] );
    $content  = trim($_POST["txt_msg_body"]);
    $status   = trim($_POST["pub_stat"]    );
    $ori_date = trim($_POST["ori_date"]    );
	if(count($_POST["pub_group"]) > 0)
	{
		$group = implode(",", $_POST["pub_group"] );
	}else
		$group = "None";
	if(strstr($group, "allGRP"))  $group = "allGRP";

    if( strlen($content) < 3)
    {
       js_show_error("多播消息内容长度不正确,请重新填写!");
        die();
    }

  
    $time = trim($_POST["vip_putime"]);
    
    $vip_putime = date('Y-m-d H:i:s', strtotime($time." ".date("H:i:s")));
    if( strcmp($time, trim($ori_date)) == 0)
    {
        $sql = "update vipmsg set content='".$content."',sfqy=".$status." ,ingroup='".$group."'  where id=".$id;
    }else
    {
        $sql = "update vipmsg set content='".$content."',time='".$vip_putime."',sfqy=".$status.",ingroup='".$group."'   where id=".$id;
    }
    $handle = openConn();
    if($handle ==NULL) die( "mysql error". mysql_error() );
    $result = mysql_query($sql,$handle);
    if($result ===false)
    {
        echo "Edit mysql error()".mysql_error()."<br />";
        closeConn($handle);
        die();	
    }
    else
    {
      	echo "修改消息成功！";
        closeConn($handle);
    }

  return;   
}
?>
