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
  <td colspan="4" class="col_head_title">goto&Play�I�q�t��</td>
  </tr>
   <tr>
  <td class="col_title">�I�q�̡G</td><td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td class="col_title">�I�q��H�G</td><td class="col"><input type="text" name="data[target]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�I���q���G</td><td class="col"><input type="text" name="data[song]" class="txt"></td>
  <td class="col_title">�q��m�W�G</td><td class="col"><input type="text" name="data[artist]" class="txt"></td>
  </tr>
  <td class="col_title">���X�ɬq�G</td><td colspan="3"><select name="data[hours]">
  <option>�п�ܼ��X�ɬq</option>
  <option>�g�T 21:30~22:30 ���ֹs�t��</option>
  <option>�g�| 22:30~23:30 goto�I�I�I</option></select></td>
  </tr>
  <tr>
  <td class="col_title">�q�l�H�c�G</td><td class="col" colspan="3"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">�d�����e�G</td><td colspan="3"><textarea name="data[message]" rows=4>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td colspan="4" align="center">
  <input type="hidden" name="op" value="app_process">
  <input type="button" value="�e�X�I��" class="input_btn" onClick="
  if (confirm('�нT�{�Ҧ�����g�L�~�B�L��|! ���U\'�T�w\'��|�ߧY�e�X�I��')){
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
  if(empty($data['name']) or empty($data['target']) or empty($data['song']) or empty($data['artist']))die("�п�J�����ơI");
  //if(empty($data['introduction']))die("�ж�g�d���I");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email�榡�����T��I");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $time=date("Y/m/d H:i:s");
  $ps='�����X';
  $sql="insert into request (name,target,song,artist,hours,email,message,time,ps) values('{$data['name']} ','{$data['target']}','{$data['song']} ','{$data['artist']}','{$data['hours']} ','{$data['email']}','{$data['message']} ','{$time}','{$ps}')";
  mysql_db_query("ctradio",$sql,$link) or die("�L�k�g�J���!<br>".$sql);
  
  //�H�󤺮e
	$mail_content = "Hi~ �q�x�T�j�f�G\n
{$data['name']} �P�ǭn�ѻP2007�~�q�xDJ���¿�C
--
�ӽЪ�p�U
�m�W�G{$data['name']}         �t�šG{$data['depart']}
�ʧO�G{$data['sex']}         �ͤ�G{$birthday}
�q�ܡG{$data['phone']}         EMAIL�G{$data['email']}
�ӤH²���G
{$data['introduction']}
�`�إ����G
{$data['program']}
�ӽЮɶ��G{$time}
--

(���H���t�Φ۰ʵo�X�A�Ф��n�����^�Ц��H)";

	//�H�������H��
	//@mail("littlehorse09281@hotmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ; //or die("�L�k�H�H��taoc@mail.nctu.edu.tw");
	//@mail("m75251986@yahoo.com.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
	//@mail("ymmat55.cts93@nctu.edu.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ;
    //@mail("nobel.hsu@gmail.com","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ; 
    //@mail("taoc@mail.nctu.edu.tw","{$data['name']}���q�xDJ�ӽЪ�",$mail_content) ; 


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>�A���I�q�w�e�X�I</p><p>DJ�|�b�A���w�ɬq��'goto�I�I�I'���X�q���C</p></td></tr></table>";
  
  return $html;
}


//�q�X�Ҧ����e
function app_listall(){
  global $link;

  //�B�z����
  $p=(empty($_GET['p']))?1:$_GET['p'];
  $num=10;
  $total=0;
  $start=($p-1)*$num;
  
  $limit="limit $start,$num";
  $sql="select * from request order by sn desc $limit";
  $result=mysql_db_query("ctradio",$sql,$link) or die("�L�k���o��ơI<br>".$sql);  
  $bre_list="";
  while($db_data = mysql_fetch_row($result)){

    list($sn,$name,$target,$song,$artist,$hours,$email,$message,$time,$ps)=$db_data;

    $message=nl2br($message);
	
	//�Y�O�޲z�̫h��ܾާ@�\��
	if(check_user($_SESSION["id"],$_SESSION["passwd"])){
	  $admin_tool="
		  <a href='{$_SERVER['PHP_SEIF']}?op=app_reply&p={$p}&sn={$sn}'>�Ƶ�</a><br>
		  <a href='javascript:delete_data($sn,$p)'>�R��</a>";

	}else{
	  $admin_tool="";
	}
	
	$bre_list.="<tr class='view'>
    <td width='70' nowrap align='center'>$name</td>
	<td width='70' nowrap align='center'>$target</td>
    <td width='100' align='center'>$artist<br>$song</td>
    <td valign='top'>$message<br>(���w$hours)</td>
	<td width='60'>$time</td>
	<td width='80' align='center'>$ps</td>
	<td width='40' class='func'>$admin_tool</td>
    </tr>";
  }
  
  //���o�`��Ƽ�
  $sql="select count(*) from request";
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
  �]�����@ $total ����ơ^
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
  <tr class="view"><td colspan='7' align='center'><b class="title">goto&Play�I�q�d����</b></td></tr>
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr align="center">
  <th>�I�q��</th>
  <th>�I�q��H</th>
  <th>�I���q��</th>
  <th>�d�����e</th>
  <th>�ɶ�</th>
  <th>�Ƶ�</th>
  <th></th>
  </tr>
  $bre_list
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr class="view"><td colspan='7' align='center'><a href='{$_SERVER['PHP_SELF']}?op=login_form'>DJ�n�J</a> <a href='{$_SERVER['PHP_SELF']}?op=logout'>�n�X</a></td></tr>
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
  <td colspan="2" class="col_head_title">DJ�Ƶ�</td>
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

  $sql="update request set ps='{$data['reply']}' where sn={$data[sn]}";
  mysql_db_query("ctradio",$sql,$link) or die("�L�k�g�J���!<br>".$sql);  
}

//�R���@�����
function app_del($sn=""){
    global $link;
    $sql="delete from request where sn={$sn}";
    mysql_db_query("ctradio",$sql,$link) or die("�L�k�g�J���!<br>".$sql); 
}

function app_deadline(){
   $html=<<<FORM
  <center><table><tr class='result'><td height='260'><p>�`���w�Ƥ�</p><p>�ЦU��ť���@�ߵ��ԡC</p><p><a href='/radio/signup/'index.php>�w����W�x��DJ</a></p></td></tr></table></center>
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >�޲z�̵n�J</a></div>
FORM;
  
  return $html;
}
?>