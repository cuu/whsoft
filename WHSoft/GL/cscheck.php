<?php
// check session and cookie
//ERROR_REPORTING(0);
//session_start();

if( !isset($_SESSION["yhgl"]) ||$_SESSION["yhgl"]=="" )
{
        echo "<script language=javascript>alert('ÇëÏÈµÇÂ½!');window.parent.location.href='login.php';</script>";
        die();
}
else
{
        if( strcmp( strval($_COOKIE["yhgl"]) , "jcok")!=0 )
        {
 /*|| $_COOKIE["login"]!="jcok"*/ 
                echo "<script language=javascript>alert('ÇëÏÈµÇÂ½!');window.parent.location.href='login.php';</script>";
		//echo"cookie error";
                die();
        }
}

?>
