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

//�n�J���
function login_form(){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post">
  <table class="input_table">
  <tr>
  <td class="col_title">�b���G</td>
  <td class="col"><input type="text" name="id" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�K�X�G</td>
  <td class="col"><input type="password" name="passwd" class="txt"></td>
  </tr>
  <td colspan="2" align="center">
  <input type="hidden" name="op" value="login">
  <input type="submit" value="�n�J" class="input_btn">
  </td>
  </tr>
  </table>
  </form>
FORM;

  return $main;
}

//���o�Y�H�����
function get_mem_data($the_id="") {
  global $link;
  if(empty($the_id))return;
  $sql="select * from id_passwd where id='{$the_id}'";
  $result=mysql_db_query("ctradio",$sql,$link) or die("�L�k���o{$the_id}����ơI<br>".$sql);
  $data = mysql_fetch_assoc($result);
  return $data;
}

//�����T�{
function check_user($id="",$passwd=""){
  if(empty($id) or empty($passwd))return false ;
  $user=get_mem_data($id);
  if($user['id']==$id and $user['passwd']==$passwd){
    if(empty($_SESSION["id"])){
      $_SESSION["id"]=$id;
      $_SESSION["passwd"]=$passwd;
    }
    return true;
  }
  return false;
}

//�n�X
function logout(){
	$_SESSION = array();
	session_destroy();
}

//�ӽЪ��
function apply_form(){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
  <table class="input_table">
  <tr>
  <td colspan="4" class="col_head_title">���G���˥D��&�y���x��</td>
  </tr>
   <tr>
  <td class="col_title">�m�W�G</td><td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td class="col_title">�t�šG</td><td class="col"><input type="text" name="data[depart]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�ʧO�G</td><td class="col"><input name="data[sex]" type="radio" value="�k" checked>�k
<input name="data[sex]" type="radio" value="�k">�k</td>
  <td class="col_title">�ͤ�G</td><td class="col">����<input type="text" name="data[b_y]" class="txt2" size="3">�~<input type="text" name="data[b_m]" class="txt2" size="3">��<input type="text" name="data[b_d]" class="txt2" size="3">��</td>
  </tr>
  <tr>
  <td class="col_title">����G</td><td class="col"><input type="text" name="data[phone]" class="txt"></td>
  <td class="col_title">�q�l�H�c�G</td><td class="col"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�ӤH²���G</td><td colspan="3"><textarea name="data[introduction]" rows=4>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td class="col_title">�y���g���μ����g��G</td><td colspan="3"><textarea name="data[program]" rows=8>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td></td>
 <!-- <td colspan="3" align="left"><small>���`�إ��e�]�t�w����<strong>�`�ؤ��e�B�Φ�</strong>��<strong>��ť��H</strong>�C<br></small></td> -->
  </tr>
  <tr>
  <td colspan="4" align="center">
  <input type="hidden" name="op" value="app_process">
  <input type="button" value="�e�X" class="input_btn" onClick="
  if (confirm('�нT�{�Ҧ�����g�L�~�B�L��|! ���U\'�T�w\'��|�ߧY�e�X�ӽЪ�')){
  			this.form.submit();
  		}">
  </td>
  </tr>
  </table>
  </form>
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >�޲z�̵n�J</a></div>
FORM;

  return $main;
}

//�e�X�ӽ�
function app_process($data=array()){
  global $link;
  if(empty($data['name']) or empty($data['depart']) or empty($data['b_y']) or empty($data['phone']))die("�п�J����ӤH��ơI");
  if(empty($data['introduction']))die("�ж�g�ӤH²���I");
  //if(empty($data['program']))die("�ж�g�I");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email�榡�����T��I");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $birthday="{$data['b_y']}/{$data['b_m']}/{$data['b_d']}";
  $time=date("Y/m/d H:i:s");
  $sql="insert into apply (name,depart,sex,birthday,phone,email,introduction,program,time) values('{$data['name']} ','{$data['depart']}','{$data['sex']} ','{$birthday}','{$data['phone']} ','{$data['email']}','{$data['introduction']} ','{$data['program']} ','{$time}')";
  mysql_db_query("ctradio",$sql,$link) or die("�L�k�g�J���!<br>".$sql);
  
  //�H�󤺮e
	$mail_content = "Hi~ Goto&Play�q�x�x���G\n
{$data['name']} �P�ǭn�ѻP2010�~�q�xDJ���¿�C
--
�ӽЪ�p�U
�m�W�G{$data['name']}         �t�šG{$data['depart']}
�ʧO�G{$data['sex']}         �ͤ�G{$birthday}
�q�ܡG{$data['phone']}         EMAIL�G{$data['email']}
�ӤH²���G
{$data['introduction']}
�g���G
{$data['program']}
�ӽЮɶ��G{$time}
--

(���H���t�Φ۰ʵo�X�A�Ф��n�����^�Ц��H)";

	//�H�������H��
	//@mail("lyforever62@hotmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ; //or die("�L�k�H�H��taoc@mail.nctu.edu.tw");
        //@mail("amandatsail0108@hotmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
        //@mail("bantu-b@hotmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
        //@mail("j38261833@hotmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
        //@mail("zaida.cts96@g2.nctu.edu.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
//@mail("m75251986@yahoo.com.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
	//@mail("ymmat55.cts93@nctu.edu.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
             //   @mail("nobel.hsu@gmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ; 
               @mail("taoc@mail.nctu.edu.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ; 

	@mail("uselesscat.cts97@nctu.edu.tw","{$data['name']}���ӽЪ�",$mail_content) ; 
	@mail("uselesscat@gmail.com","{$data['name']}���ӽЪ�",$mail_content) ;
                @mail("thejoroba@gmail.com","{$data['name']}�����˥D���ӽЪ�",$mail_content) ;
        @mail("ben78710@hotmail.com","{$data['name']}�����˥D���ӽЪ�",$mail_content) ;


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>�A���ӽФw�e�X�I</p><p></p></td></tr></table>";
  
  return $html;
}

function app_deadline(){
   $html=<<<FORM
  <table><tr class='result'><td height='260'><p>���W�w�I��I</p><p>�N�|�HE-MAIL�q���¿ﵲ�G�C</p></td></tr></table>
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >�޲z�̵n�J</a></div>
FORM;
  
  return $html;
}

//�q�X�Ҧ����e
function app_listall(){
  global $link;

  //�B�z����
  $p=(empty($_GET['p']))?1:$_GET['p'];
  $num=6;
  $total=0;
  $start=($p-1)*$num;
  
  $limit="limit $start,$num";
  $sql="select * from apply order by sn desc $limit";
  $result=mysql_db_query("ctradio",$sql,$link) or die("�L�k���o��ơI<br>".$sql);  
  $bre_list="";
  while($db_data = mysql_fetch_row($result)){

    list($sn,$name,$depart,$sex,$birthday,$phone,$email,$introduction,$program,$time,$ps)=$db_data;

    $introduction=nl2br($introduction);
	$program=nl2br($program);
	
	//�Y�O�޲z�̫h��ܾާ@�\��
	//if(check_user($_SESSION["id"],$_SESSION["passwd"])){
	  $admin_tool="
		  <a href='{$_SERVER['PHP_SEIF']}?op=app_reply&p={$p}&sn={$sn}'>�Ƶ�</a><br>
		  <a href='javascript:delete_data($sn,$p)'>�R��</a>";

//	}else{
	//  $admin_tool="";
//	}
	
	$bre_list.="<tr class='view'>
    <td width='100' style='font-size:16px' nowrap align='center'>$name $number<br>
    <font class='eng'>$depart</font></td>
	<td width='60' style='font-size:16px' nowrap align='center'>$sex<br>
    <font class='eng'>$birthday</font></td>
    <td width='130' align='center' class='eng'>$phone<br>$email</td>
    <td valign='top'>$introduction</td>
	<td width='60'>$time</td>
	<td width='60' class='func'>$admin_tool</td>
    </tr>
	<tr class='view'>
	<td colspan='5' valign='top'>$program</td>
	<td class='func'>$ps</td>
	</tr><tr><td colspan='6'></td></tr>";
  }
  
  //���o�`��Ƽ�
  $sql="select count(*) from apply";
  $result=mysql_db_query("ctradio",$sql,$link) or die("�L�k���o�`��ƼơI<br>".$sql);
  list($total) = mysql_fetch_row($result);
  
  $n=ceil($total/$num);
  $page_list="<select onChange=\"if(this.value!='') location.href = '{$_SERVER['PHP_SELF']}?op=app_listall&p=' + this.value\">";
  for($a=1;$a<=$n;$a++){
    $selected=($p==$a)?"selected":"";
    $page_list.="<option value='{$a}' $selected>�� $a ��</option>";
  }
  $page_list.="</select>";

  $next_page=$p+1;
  $previous_page=$p-1;

  if($p!=1){
    $pre="<a href='{$_SERVER['PHP_SELF']}?op=app_listall&p={$previous_page}'>�W�@��</a>";
	}else{
	$pre="<span class='gray'>�W�@��</span>";
  }
  if($p==$n){
    $next="<span class='gray'>�U�@��</span>";
	}else{
	$next="<a href='{$_SERVER['PHP_SELF']}?op=app_listall&p={$next_page}'>�U�@��</a>";
  }
  
  $nav="
  <div class='nav'>
  �]�����@ $total ���ӽи�ơ^
  $pre
  $page_list
  $next
  </div>";
  
  $main=<<<LIST_ALL
  
  <script language="JavaScript" type="text/JavaScript">
  <!--
  //�T�{�R��
  function delete_data(sn, p){
    var sure = window.confirm(' �T�w�n�R�������? ');
	if (!sure)return;
	location.href="{$_SERVER['PHP_SELF']}?op=app_del&p="+p+"&sn="+sn;
  }
  //-->
  </script>
  
  
  <table class="list">
  <tr class="view"><td colspan='7' align='center'><b class="title">�q�xDJ�ӽФH�C��</b></td></tr>
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr align="center">
  <th>�m�W�t��</th>
  <th>�ʧO�ͤ�</th>
  <th>�p���覡</th>
  <th>�ӤH²��</th>
  <th>�ɶ�</th>
  <th>�Ƶ�</th>
  </tr>
  $bre_list
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr class="view"><td colspan='7' align='center'><a href='{$_SERVER['PHP_SELF']}?op=logout'>�n�X</a></td></tr>
  </table>
LIST_ALL;

  return $main;
}

//�޲z�̦^��
function app_reply($sn=""){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
  <table class="input_table">
  <tr>
  <td colspan="2" class="col_head_title">�޲z�̳Ƶ�</td>
  </tr>
  <tr>
  <td class="col_title">�Ƶ��G</td><td width='180'><textarea name="data[reply]" rows=5>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
  <input type="hidden" name="op" value="app_reply_pro">
  <input type="hidden" name="data[sn]" value="{$sn}">
  <input type="submit" value="�e�X" class="input_btn">
  </td>
  </tr>
  </table>
  </form>
FORM;

  return $main;
}

//�g�J�^��
function app_reply_pro($data=array()){
  global $link;
  if(empty($data['reply']))die("�п�J�^�Ф��e�I");
  
  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $sql="update apply set ps='{$data['reply']}' where sn={$data[sn]}";
  mysql_db_query("ctradio",$sql,$link) or die("�L�k�g�J���!<br>".$sql);  
}

//�R���@�����
function app_del($sn=""){
    global $link;
    $sql="delete from apply where sn={$sn}";
    mysql_db_query("ctradio",$sql,$link) or die("�L�k�g�J���!<br>".$sql); 
}
?>