<html>
<head>
<?php
include "jq_ui.php";
?>


<script>


  $(function() {
       $('.rmv-dft-val').val("请输入相关名子手机电话进行查找");
      $('.rmv-dft-val').css("color","#ccc");
      $('.rmv-dft-val').click(
        function() {
            if (this.value == "请输入相关名子手机电话进行查找")
           {
              this.value = '';
              $(this).css("color","#000");
           }
        });

      $('.rmv-dft-val').blur(
        function() {
              if (this.value == '') {
              $(this).css("color","#ccc");
              this.value ="请输入相关名子手机电话进行查找" ;
            }
        }
      );

    $("#btg_search").button();

  });
</script>


<style type="text/css">
body
  {
  height:100%;
  width:100%;
   

  margin:0px;

  }
</style>
</head>
<body>
<div style="padding:20px;font-size:20px;">
欢印使用 金石智能管理后台系统1.0
 <br />   本系统有如下功能:
<ul>

<li>   查看和管理所有注册用户 </li>
<li>   修改或删除管理员帐号 </li>
<li>  设置激活注册等短信内容 </li>
</ul>
   本系统要求用户使用IE6.0浏览器以上,或谷歌浏览器. 同时打开Javascript功能<br />
   所有操作请慎重,误操作不可撤销!
   感谢您的使用
</div>
<div id="search_panel" style="padding-left:30px;" >
<form action="search.php" method="GET" name="searh_form">
   <input type="text" class="rmv-dft-val"  name="s_q" size="57" value="" style="border-top:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #999;border-bottom:1px solid #999;height:30px; padding:5px 3px 0px 6px;font:18px arial,sans-serif bold;" />
   <input id="btg_search" type="submit" size="10" value="查找" style="height:30px;font:15px arial,sans-serif bold;vertical-align:top;outline:0 none; cursor: pointer;color:#000;" />
</form>

</div>
</body>
</html>
