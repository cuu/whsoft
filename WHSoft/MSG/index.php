<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk"> 
<script type="text/javascript">document.oncontextmenu=function(e){return false;}</script>

<style type="text/css">
body
{
	background:#000;
	color:yellow;
	-moz-user-select:none;
}
.now
{
	color:red;
}
.nor 
{
	color:yellow;
}
</style>
</head>
<body onselectstart="return false">

<?php
	if(isset($_GET["id"]))
	{
		echo "<img src='top_r2_c1.jpg' /><br /><br /><br />";
		echo $_GET["id"];
		echo "<br /><br /><br /><a href='' onclick='window.history.back();'>back</a>";
		return;
	}
?>


	<ul>

	<li> <a class="now" href="?id=1">实时消息一</a></li>
	<li> <a class="nor" href="?id=2">实时消息2</a></li>

	</ul>
</body>
</html>
