<?php
//介紹flash
function media(){
  $html=<<<MAIN
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="640" height="200" id="head" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="images/head.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#FFCCCC" /><embed src="images/head.swf" quality="high" bgcolor="#23408c" width="640" height="200" name="head" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
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
  <td colspan="4" class="col_head_title">goto&Play二手市集—goto&Sale!</td>
  </tr>
   <tr>
  <td class="col_title">賣家：</td>
  <td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td class="col_title">系級：</td>
  <td class="col"><input type="text" name="data[dep]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">電話：</td>
  <td class="col"><input type="text" name="data[phone]" class="txt"></td>
  <td class="col_title">EMAIL：</td>
  <td class="col"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">擺攤時段：</td><td class="col"><select name="data[time]" size="1"><option value="12:30~13:30">12:30~13:30 <option value="13:30~14:30">13:30~14:30 <option value="12:30~14:30">12:30~14:30 </select></td>
  <td class="col_title">場地大小：</td><td class="col"><select name="data[space]" size="1"><option value="半張">1.5M*0.5M桌子半張 <option value="一張">1.5M*0.5M桌子一張 <option value="兩張">1.5M*0.5M桌子兩張 </select></td>
  </tr>
  <tr>
  <td class="col_title">商品描述：<br><small><center>(100字內)</center></small></td>
  <td colspan="3"><textarea name="data[goods]" rows=8>{$val['note']}</textarea></td>
  </tr>

  <tr>
  <td colspan="4" align="center">
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
  if(empty($data['name']) or empty($data['dep']) or empty($data['goods']))die("請輸入完整資料！");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email格式不正確喔！");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $time=date("Y/m/d H:i:s");
  
  //信件內容
	$mail_content = "Hi~ 冠金：\n
{$data['name']} 同學要參加二手市集。
--
內容如下
賣家：{$data['name']}    系級：{$data['dep']}
電話：{$data['phone']}    EMAIL：{$data['email']}
擺攤時段：{$data['time']}    場地大小：桌子{$data['space']}
商品描述：
{$data['goods']}
投件時間：{$time}
--

(本信為系統自動發出，請不要直接回覆此信)";

	//寄給相關人員
	@mail("ymmat55.cts93@nctu.edu.tw","{$data['name']}的二手市集報名",$mail_content) or die("無法寄信給ymmat55.cts93@nctu.edu.tw");
	//@mail("nobel.hsu@gmail.com","{$data['name']}的二手市集報名",$mail_content) or die("無法寄信給nobel.hsu@gmail.com");


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>已完成報名，電台人員近日會與您聯絡！<br>謝謝您的參與！</p></td></tr></table>";
  
  return $html;
}
?>