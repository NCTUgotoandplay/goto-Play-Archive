<?php
//����flash
function media(){
  $html=<<<MAIN
  <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="640" height="200" id="head" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="images/head.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#23408c" /><embed src="images/head.swf" quality="high" bgcolor="#23408c" width="640" height="200" name="head" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
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
  <td colspan="3" class="col_head_title">�R�n�j�n���X��</td>
  </tr>
   <tr>
  <td class="col_title">���H�G</td>
  <td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td><small>�����ίu��m�W</small></td>
  </tr>
  <tr>
  <td class="col_title">��H�G</td>
  <td class="col"><input type="text" name="data[target]" class="txt"></td>
  <td><small>�����ίu��m�W�F�����ӤH�A�i�s��</small></td>
  </tr>
  <tr>
  <td class="col_title">�q�l�H�c�G</td>
  <td colspan="2"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">���e�G<br><small>(100-120�r)</small></td>
  <td colspan="2"><textarea name="data[content]" rows=10>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td colspan="3" align="left"><center><small>���Y�n�H�n���Ǳ��A�йw��30�����������ɡA����e-mail�� paul900900@gmail.com�C<br></small></center></td>
  </tr>
  <tr>
  <td colspan="3" align="center">
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
  if(empty($data['name']) or empty($data['target']) or empty($data['content']))die("�п�J�����ơI");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email�榡�����T��I");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $time=date("Y/m/d H:i:s");
  
  //�H�󤺮e
	$mail_content = "Hi~ �����G\n
{$data['name']} �P�ǭn��Z�Ť��Ǳ��C
--
���e�p�U
���H�G{$data['name']}
��H�G{$data['target']}
EMAIL�G{$data['email']}
���e�G
{$data['content']}
���ɶ��G{$time}
--

(���H���t�Φ۰ʵo�X�A�Ф��n�����^�Ц��H)";

	//�H�������H��
	@mail("paul900900@gmail.com","{$data['name']}���Ť��Ǳ��Z��",$mail_content) or die("�L�k�H�H��taoc@mail.nctu.edu.tw");
	//@mail("nobel.hsu@gmail.com","{$data['name']}���Ť��Ǳ��Z��",$mail_content) or die("�L�k�H�H��taoc@mail.nctu.edu.tw");


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>�`�ض}���e�N�H�q�l�H�c�i���Z����Ϊ̩M���X�ɶ��A�q�д��ݡI<br>���±z���ѻP�I</p></td></tr></table>";
  
  return $html;
}

function app_end(){
  $html=
  "<table><tr class='result'><td height='260'><p>�Ǳ����ʤw�����A���±z���ѻP�I</p></td></tr></table>";
  
  return $html;
}
?>