<?php
//var_dump($_GET);
//echo "<br />";
function match_str($str,$encode='gbk')
{
     $len = mb_strlen ( $str , $encode );
     for($i=0;$i<$len;$i++)
     {
         $tmp = mb_substr ( $str, $i ,1 , $encode );
         if (!preg_match("/^([\x81-\xfe][\x40-\xfe])+$/",$tmp)) {
             if(!ereg("^[0-9a-zA-Z]*$",$tmp)) {
                 return 0;
             }   
         }
     }
     return 1;
}

$s_q = iconv("UTF-8","GBK",$_GET["s_q"]);
$vs = "请输入相关名子手机电话进行快速查找";
echo $s_q." ".mb_strlen($s_q);
if( strstr($s_q, $vs) )
{
  $len = 0;
}
else
{
  $len = mb_strlen($_GET["s_q"]);
}

if( is_numeric ( $s_q ))
  {
     if( strlen($s_q) ==3 && $s_q[0]=="1" && $s_q[1]=="3")
  {
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;      
  }
     if( strlen($s_q) ==3 && $s_q[0]=="1" && $s_q[1]=="5"  )
  {
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;      
  }
     if( strlen($s_q) ==3 && $s_q[0]=="1" && $s_q[1]=="8"  )
  {
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;      
  }
        
    if (strlen( $s_q) >= 10) // maybe the cell phone number
      {
	if( $s_q[0] =="1" && $s_q[1]=="3")
	  {
	    //13xxxxx
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;
	  }
	if( $s_q[0] =="1" && $s_q[1] == "5")
	  {
	    //15xxxxxxxxx
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;
	  }
	if( $s_q[0] =="1" && $s_q[1] == "8")
	  {
	    //18xxxxxxxxx
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;
	  }	
      }
    switch( strlen($s_q))
      {
	//135 1125 8231
      case 11: // cell phone or local phone
	{
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yddh=".$s_q."';"."</script>";
	  return;
	}
	break;
      case 12:
	{
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&gddh=".$s_q."';"."</script>";
	  return;
	// local phone
	}
	break;
      case 7: //local phone
	{
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&gddh=".$s_q."';"."</script>";
	  return;
	}
	break;
      case 8: //local phone
	{
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&gddh=".$s_q."';"."</script>";
	  return;
	}
	break;
      case 4:
	{
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&gddh=".$s_q."';"."</script>";
	  return;
	}
	// area number
	break;

      }
  }

switch( $len)
  {
    case 0:
    {
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search';"."</script>";
	  return;    
    }break;
  case 4:
  {
      //name;
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yhmc=".$s_q."';"."</script>";
	  return;      
  }
  case 2:
  case 3:
  case 5:
    {
      // name if cant not find in name,try address
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yhmc=".$s_q."';"."</script>";
	  return;
    }
    break;
  case 6:
  {
      // name 
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yhmc=".$s_q."';"."</script>";
	  return;
  }break;

  case  12:
    {
    // soft id mac
	  if(match_str($s_q) ==1)
	  {
	    echo "<script  language='javascript'>"." location='softManager.php?action=Search&diskid=".$s_q."';"."</script>";
	  }else
	  {
	    echo "<script  language='javascript'>"." location='softManager.php?action=Search&lxdz=".$s_q."';"."</script>";
	  }
	  return;
    }
    break;
    
  }//end switch

echo "<span style='font-size:20px;' >没有找到您想要的信息</span><br />";
echo "<a href=\"index.php?n=Index\"   >返回</a>";
?>