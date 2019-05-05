<?php
//引入檔案區（看需要引入甚麼檔，都在此先引入）
include "function_app.php";

//流程控制區（判斷使用者要做的動作，去呼叫相關的函數或物件方法）
switch ($_REQUEST['op']) {

//報名DJ
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

//呈現畫面區（若此動作是需要呈現在畫面上的，那麼統一在此輸出）
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=Big5">
<link rel="stylesheet" type="text/css" media="screen" href="style.css">
<title>愛要大聲說出來</title>
</head>

<body bgcolor = "#235BAF">

<div class="center_block">
  <?php
  echo media();
  echo $main_content;
  ?>
</div>
<div class="copyright">國立交通大學 goto&amp;Play網路電台</div>
</body>
</html>
