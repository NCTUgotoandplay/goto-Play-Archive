<?php
//引入檔案區（看需要引入甚麼檔，都在此先引入）
include "setup.php";
include "function_app.php";
session_start();

//流程控制區（判斷使用者要做的動作，去呼叫相關的函數或物件方法）
switch ($_REQUEST['op']) {

//報名DJ
default:
 $main_content = app_deadline(); //報名截止
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
//登入
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

//呈現畫面區（若此動作是需要呈現在畫面上的，那麼統一在此輸出）
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=Big5">
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<title>殘酷電台報名表</title>
</head>

<body background="images/bg.gif">

<div class="center_block">
  <?php
  echo media();
  echo $main_content;
  ?>
</div>
<div class="copyright">國立交通大學 goto&amp;Play網路電台</div>
</body>
</html>
