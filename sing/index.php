<?php
//�ޤJ�ɮװϡ]�ݻݭn�ޤJ�ƻ��ɡA���b�����ޤJ�^
include "setup.php";
include "function_app.php";
session_start();

//�y�{����ϡ]�P�_�ϥΪ̭n�����ʧ@�A�h�I�s��������ƩΪ����k�^
switch ($_REQUEST['op']) {

//���WDJ
default:
 $main_content = app_deadline(); //���W�I��
 // $main_content = apply_form();
  break;
case "app_process":
  app_process($_POST['data']);
  header("location: {$_SERVER['PHP_SELF']}?op=app_result");
  break;
case "app_result":
  $main_content = app_result();
  break;
case "app_listall":
  if(check_user($_SESSION["id"],$_SESSION["passwd"])){
  $main_content = app_listall();
  break;}else{
  $main_content = login_form();
  break;
  }
case "app_reply":
  $main_content = app_reply($_GET['sn']);
  break;
case "app_reply_pro":
  app_reply_pro($_POST['data']);
  header("location: {$_SERVER['PHP_SELF']}?op=app_listall&p={$_GET['p']}");
  break;
case "app_del":
  app_del($_GET['sn']);
  header("location: {$_SERVER['PHP_SELF']}?op=app_listall&p={$_GET['p']}");
  break;
//�n�J
case "login_form":
  $main_content = login_form();
  break;
case "login":
  check_user($_POST["id"],$_POST["passwd"]);
  header("location: {$_SERVER['PHP_SELF']}?op=app_listall");
  break;
case "logout":
  logout();
  header("location: {$_SERVER['PHP_SELF']}");
  break;
}

//�e�{�e���ϡ]�Y���ʧ@�O�ݭn�e�{�b�e���W���A����Τ@�b����X�^
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=Big5">
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<title>�ݻŹq�x���W��</title>
</head>

<body background="images/bg.gif">

<div class="center_block">
  <?php
  echo media();
  echo $main_content;
  ?>
</div>
<div class="copyright">��ߥ�q�j�� goto&amp;Play�����q�x</div>
</body>
</html>
