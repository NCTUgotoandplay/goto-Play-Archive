<?php
//�ޤJ�ɮװϡ]�ݻݭn�ޤJ�ƻ��ɡA���b�����ޤJ�^
include "function_app.php";

//�y�{����ϡ]�P�_�ϥΪ̭n�����ʧ@�A�h�I�s��������ƩΪ����k�^
switch ($_REQUEST['op']) {

//���WDJ
default:
  $main_content = app_end();
  break;
case "app_process":
  app_process($_POST['data']);
  header("location: {$_SERVER['PHP_SELF']}?op=app_result");
  break;
case "app_result":
  $main_content = app_result();
  break;
}

//�e�{�e���ϡ]�Y���ʧ@�O�ݭn�e�{�b�e���W���A����Τ@�b����X�^
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=Big5">
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<title>�R�n�j�n���X��</title>
</head>

<body bgcolor = "#235BAF">

<div class="center_block">
  <?php
  echo media();
  echo $main_content;
  ?>
</div>
<div class="copyright">��ߥ�q�j�� goto&amp;Play�����q�x</div>
</body>
</html>
