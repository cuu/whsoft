<?php if (!defined('PmWiki')) exit();
$DefaultPasswords['admin'] = 'twTneNCtNHgl6';
$DefaultPasswords['edit'] = $DefaultPasswords['admin'];
$EnableGUIButtons = 1;
putenv("TZ=Asia/Shanghai");
include_once($FarmD.'/scripts/xlpage-utf-8.php');
include_once("$FarmD/scripts/authuser.php");
include_once("$FarmD/cookbook/enablehtml.php");



EnableHtml('table|tr|td|tbody|thead|img');
EnableHtml('div|input|span|form|b|i|u|sup|sub|a|iframe|small');
  



?>
