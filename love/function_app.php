<?php
//介紹flash
function media(){
  $html=<<<MAIN
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="640" height="200" id="head" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="images/head.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#23408c" /><embed src="images/head.swf" quality="high" bgcolor="#23408c" width="640" height="200" name="head" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
MAIN;
  return $html;
}

//申請表單
function apply_form(){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
  <table class="input_table">
  <tr>
  <td colspan="3" class="col_head_title">愛要大聲說出來</td>
  </tr>
   <tr>
  <td class="col_title">投件人：</td>
  <td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td><small>不限用真實姓名</small></td>
  </tr>
  <tr>
  <td class="col_title">對象：</td>
  <td class="col"><input type="text" name="data[target]" class="txt"></td>
  <td><small>不限用真實姓名；不限個人，可群體</small></td>
  </tr>
  <tr>
  <td class="col_title">電子信箱：</td>
  <td colspan="2"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">內容：<br><small>(100-120字)</small></td>
  <td colspan="2"><textarea name="data[content]" rows=10>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td colspan="3" align="left"><center><small>※若要以聲音傳情，請預錄30秒之內的錄音檔，直接e-mail至 paul900900@gmail.com。<br></small></center></td>
  </tr>
  <tr>
  <td colspan="3" align="center">
  <input type="hidden" name="op" value="app_process">
  <input type="button" value="送出" class="input_btn" onClick="
  if (confirm('請確認所有表格填寫無誤且無遺漏! 按下\'確定\'後會立即送出表單')){
  			this.form.submit();
  		}">
  </td>
  </tr>
  </table>
  </form>
FORM;

  return $main;
}

//送出申請
function app_process($data=array()){
  global $link;
  if(empty($data['name']) or empty($data['target']) or empty($data['content']))die("請輸入完整資料！");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email格式不正確喔！");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $time=date("Y/m/d H:i:s");
  
  //信件內容
	$mail_content = "Hi~ 昌恩：\n
{$data['name']} 同學要投稿空中傳情。
--
內容如下
投件人：{$data['name']}
對象：{$data['target']}
EMAIL：{$data['email']}
內容：
{$data['content']}
投件時間：{$time}
--

(本信為系統自動發出，請不要直接回覆此信)";

	//寄給相關人員
	@mail("paul900900@gmail.com","{$data['name']}的空中傳情稿件",$mail_content) or die("無法寄信給taoc@mail.nctu.edu.tw");
	//@mail("nobel.hsu@gmail.com","{$data['name']}的空中傳情稿件",$mail_content) or die("無法寄信給taoc@mail.nctu.edu.tw");


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>節目開播前將以電子信箱告知稿件錄用者和播出時間，敬請期待！<br>謝謝您的參與！</p></td></tr></table>";
  
  return $html;
}

function app_end(){
  $html=
  "<table><tr class='result'><td height='260'><p>傳情活動已結束，謝謝您的參與！</p></td></tr></table>";
  
  return $html;
}
?>