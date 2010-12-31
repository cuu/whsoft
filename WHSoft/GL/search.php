<?php
//var_dump($_GET);
//echo "<br />";


$len = mb_strlen($_GET["s_q"]);
$s_q = $_GET["s_q"];
if( is_numeric ( $s_q ))
  {
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
  case 2:
  case 3:
  case 4:
  case 5:
    {
      // name if cant not find in name,try address
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&yhmc=".$s_q."';"."</script>";
	  return;
    }
    break;
  case  12:
    {
    // soft id mac
	  echo "<script  language='javascript'>"." location='softManager.php?action=Search&diskid=".$s_q."';"."</script>";
	  return;
    }
    break;
    
  }//end switch

echo "<span style='font-size:20px;' >没有找到您想要的信息</span><br />";
echo "<a href='index.php' target='mainFrame' >返回</a>";
?>