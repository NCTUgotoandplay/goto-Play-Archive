<?php
//����flash
function media(){
  $html=<<<MAIN
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="640" height="200" id="head" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="images/head.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#FFCCCC" /><embed src="images/head.swf" quality="high" bgcolor="#23408c" width="640" height="200" name="head" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
MAIN;
  return $html;
}

//�ӽЪ��
function apply_form(){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
  <table class="input_table">
  <tr>
  <td colspan="4" class="col_head_title">goto&Play�G�⥫���Xgoto&Sale!</td>
  </tr>
   <tr>
  <td class="col_title">��a�G</td>
  <td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td class="col_title">�t�šG</td>
  <td class="col"><input type="text" name="data[dep]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�q�ܡG</td>
  <td class="col"><input type="text" name="data[phone]" class="txt"></td>
  <td class="col_title">EMAIL�G</td>
  <td class="col"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�\�u�ɬq�G</td><td class="col"><select name="data[time]" size="1"><option value="12:30~13:30">12:30~13:30 <option value="13:30~14:30">13:30~14:30 <option value="12:30~14:30">12:30~14:30 </select></td>
  <td class="col_title">���a�j�p�G</td><td class="col"><select name="data[space]" size="1"><option value="�b�i">1.5M*0.5M��l�b�i <option value="�@�i">1.5M*0.5M��l�@�i <option value="��i">1.5M*0.5M��l��i </select></td>
  </tr>
  <tr>
  <td class="col_title">�ӫ~�y�z�G<br><small><center>(100�r��)</center></small></td>
  <td colspan="3"><textarea name="data[goods]" rows=8>{$val['note']}</textarea></td>
  </tr>

  <tr>
  <td colspan="4" align="center">
  <input type="hidden" name="op" value="app_process">
  <input type="button" value="�e�X" class="input_btn" onClick="
  if (confirm('�нT�{�Ҧ�����g�L�~�B�L��|! ���U\'�T�w\'��|�ߧY�e�X���')){
  			this.form.submit();
  		}">
  </td>
  </tr>
  </table>
  </form>
FORM;

  return $main;
}

//�e�X�ӽ�
function app_process($data=array()){
  global $link;
  if(empty($data['name']) or empty($data['dep']) or empty($data['goods']))die("�п�J�����ơI");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email�榡�����T��I");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $time=date("Y/m/d H:i:s");
  
  //�H�󤺮e
	$mail_content = "Hi~ �a���G\n
{$data['name']} �P�ǭn�ѥ[�G�⥫���C
--
���e�p�U
��a�G{$data['name']}    �t�šG{$data['dep']}
�q�ܡG{$data['phone']}    EMAIL�G{$data['email']}
�\�u�ɬq�G{$data['time']}    ���a�j�p�G��l{$data['space']}
�ӫ~�y�z�G
{$data['goods']}
���ɶ��G{$time}
--

(���H���t�Φ۰ʵo�X�A�Ф��n�����^�Ц��H)";

	//�H�������H��
	@mail("ymmat55.cts93@nctu.edu.tw","{$data['name']}���G�⥫�����W",$mail_content) or die("�L�k�H�H��ymmat55.cts93@nctu.edu.tw");
	//@mail("nobel.hsu@gmail.com","{$data['name']}���G�⥫�����W",$mail_content) or die("�L�k�H�H��nobel.hsu@gmail.com");


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>�w�������W�A�q�x�H�����|�P�z�p���I<br>���±z���ѻP�I</p></td></tr></table>";
  
  return $html;
}
?>