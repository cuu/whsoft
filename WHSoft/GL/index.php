<html>
<head>
<?php
include "jq_ui.php";
?>


<script>


  $(function() {
       $('.rmv-dft-val').val("��������������ֻ��绰���в���");
      $('.rmv-dft-val').css("color","#ccc");
      $('.rmv-dft-val').click(
        function() {
            if (this.value == "��������������ֻ��绰���в���")
           {
              this.value = '';
              $(this).css("color","#000");
           }
        });

      $('.rmv-dft-val').blur(
        function() {
              if (this.value == '') {
              $(this).css("color","#ccc");
              this.value ="��������������ֻ��绰���в���" ;
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
��ӡʹ�� ��ʯ���ܹ����̨ϵͳ1.0
 <br />   ��ϵͳ�����¹���:
<ul>

<li>   �鿴�͹�������ע���û� </li>
<li>   �޸Ļ�ɾ������Ա�ʺ� </li>
<li>  ���ü���ע��ȶ������� </li>
</ul>
   ��ϵͳҪ���û�ʹ��IE6.0���������,��ȸ������. ͬʱ��Javascript����<br />
   ���в���������,��������ɳ���!
   ��л����ʹ��
</div>
<div id="search_panel" style="padding-left:30px;" >
<form action="search.php" method="GET" name="searh_form">
   <input type="text" class="rmv-dft-val"  name="s_q" size="57" value="" style="border-top:1px solid #ccc;border-left:1px solid #ccc;border-right:1px solid #999;border-bottom:1px solid #999;height:30px; padding:5px 3px 0px 6px;font:18px arial,sans-serif bold;" />
   <input id="btg_search" type="submit" size="10" value="����" style="height:30px;font:15px arial,sans-serif bold;vertical-align:top;outline:0 none; cursor: pointer;color:#000;" />
</form>

</div>
</body>
</html>
