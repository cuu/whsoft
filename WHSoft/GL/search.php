<?php
var_dump($_GET);
echo "<br />";

$len = mb_strlen($_GET["s_q"]);
$s_q = $_GET["s_q"];
if( is_numeric ( $s_q ))
  {
    if (strlen( $s_q) >= 10) // maybe the cell phone number
      {
	if( $s_q[0] =="1" && $s_q[1]=="3")
	  {
	    //13xxxxx
	  }
	if( $s_q[0] =="1" && $s_q[1] == "5")
	  {
	    //15xxxxxxxxx
	  }
	if( $s_q[0] =="1" && $s_q[1] == "8")
	  {
	    //18xxxxxxxxx

	  }	
      }
    switch( strlen($s_q))
      {
	//135 1125 8231
      case 11: // cell phone or local phone
	break;
      case 12:
	// local phone 
	break;
      case 7: //local phone
	break;
      case 8: //local phone
	break;
      case 4:
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
    }
    break;
  case  12:
    // soft id mac
    break;
    
  }
?>