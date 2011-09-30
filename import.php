<?php
include_once "function/conn.php";

function build_softin($cmd)
{
	$arr= explode(" ",$cmd);
	
	
	return "action=softin&DiskId=".$arr[4]."&D_rjbb=1&D_yhmc=".$arr[0]."&D_lxdz=".$arr[1]."&D_zh";
	
}


function softin($cmd)
{
	// curl.exe -s -d "action=softin.... " -k server
		
}

function import_softsetup()
{
	$file = "softsetup.txt";
	$data = "";
	$handle = fopen($file,"r");
	if (!$handle) echo "open ".$file." failed\n";
	$count = 0;
	$co = 0;
	$sql = "";
	$cmd = "";
	$sql_handle = openConn(); if(!$sql_handle)  die("sql error");	
	while(!feof($handle))
	{
	
		$sql = "insert into softsetup(yhmc,lxdz,gddh,yddh,diskid,zcrq,rjjsrq,zt,zhsxrq,zhsxsj,bz) values(";
		$data = fgets ($handle);
		if(strlen($data) < 10) break;
		$tok = strtok($data," ");
		$co = 0;
		while($tok !== false)
		{
			//echo "word=".trim($tok)." ".strlen(trim($tok))."\n";
			$tok = strtok(" ");
			if($co == 10)
			{
				$sql .= "'".trim($tok)."')";
				$cmd .= trim($tok);
				break;
			}
			else if($co == 7)
			{
				$sql .= trim($tok).",";
			}
			else
			{
				$sql.="'".trim($tok)."',";
				$cmd .= trim($tok)." ";
			}	

			$co ++;
		}
		echo $sql."\n\n";
		//echo $cmd."\n\n";
		mysql_query($sql,$sql_handle);
		$sql ="";
		if(feof($handle)) break;
		//$count++;   if($count ==2) break;
	}
	closeConn($sql_handle);
}

function import_zhb()
{
	$file = "userzhb.txt";
	$data = "";
	$handle = fopen($file,"r");
	if (!$handle) echo "open ".$file." failed\n";
	$count = 0;
	$co = 0;
	$sql = "";
	$sql_handle = openConn(); if(!$sql_handle)  die("sql error");

        while(!feof($handle))
        {
		$sql = "insert into userzhb(rjbb,zhmc,zh,zhlx,zcfsm,serverame,zhye,diskid,zhsxrq,zhsxsj) values(";
                $data = fgets ($handle);
		if(strlen($data) < 10) break;
                $tok = strtok($data," ");
                $co = 0;
		
                while($tok !== false)
                {
                        //echo "word=".trim($tok)." ".strlen(trim($tok))."\n";
                        $tok = strtok(" ");
                        if($co == 9)
                        {
                                $sql .= "'".trim($tok)."')";
                                break;
                        }
                        else if($co ==  0 || $co ==3 || $co == 6)
                        {
                                $sql .= trim($tok).",";
                        }
                        else
                        {
                                $sql.="'".trim($tok)."',";
                        }

                        $co ++;
                }
                echo $sql."\n\n";
		mysql_query($sql,$sql_handle);
		if(feof($handle)) break;
        }
	closeConn($sql_handle);


}

if( isset($argv[1])  )
{
	switch($argv[1])
	{
		case "1": import_softsetup(); break;
		case "2": import_zhb();	      break;
		case "3":
		{	
			echo exec("ls -l")."\n";
		}break;
		default:break;
	}	
}

if(isset($_GET["fn"]))
{
	switch($_GET["fn"])
	{
		case "1": import_softsetup(); break;
		case "2": import_zhb();       break;
		default:break;	
	}
}

?>
