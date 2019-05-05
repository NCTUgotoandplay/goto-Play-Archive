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

//登入表單
function login_form(){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post">
  <table class="input_table">
  <tr>
  <td class="col_title">帳號：</td>
  <td class="col"><input type="text" name="id" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">密碼：</td>
  <td class="col"><input type="password" name="passwd" class="txt"></td>
  </tr>
  <td colspan="2" align="center">
  <input type="hidden" name="op" value="login">
  <input type="submit" value="登入" class="input_btn">
  </td>
  </tr>
  </table>
  </form>
FORM;

  return $main;
}

//取得某人的資料
function get_mem_data($the_id="") {
  global $link;
  if(empty($the_id))return;
  $sql="select * from id_passwd where id='{$the_id}'";
  $result=mysql_db_query("ctradio",$sql,$link) or die("無法取得{$the_id}的資料！<br>".$sql);
  $data = mysql_fetch_assoc($result);
  return $data;
}

//身份確認
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

//登出
function logout(){
	$_SESSION = array();
	session_destroy();
}

//申請表單
function apply_form(){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
  <table class="input_table">
  <tr>
  <td colspan="4" class="col_head_title">庚寅梅竹主播&球評徵選</td>
  </tr>
   <tr>
  <td class="col_title">姓名：</td><td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td class="col_title">系級：</td><td class="col"><input type="text" name="data[depart]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">性別：</td><td class="col"><input name="data[sex]" type="radio" value="男" checked>男
<input name="data[sex]" type="radio" value="女">女</td>
  <td class="col_title">生日：</td><td class="col">民國<input type="text" name="data[b_y]" class="txt2" size="3">年<input type="text" name="data[b_m]" class="txt2" size="3">月<input type="text" name="data[b_d]" class="txt2" size="3">日</td>
  </tr>
  <tr>
  <td class="col_title">手機：</td><td class="col"><input type="text" name="data[phone]" class="txt"></td>
  <td class="col_title">電子信箱：</td><td class="col"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">個人簡介：</td><td colspan="3"><textarea name="data[introduction]" rows=4>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td class="col_title">球隊經歷或播報經驗：</td><td colspan="3"><textarea name="data[program]" rows=8>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td></td>
 <!-- <td colspan="3" align="left"><small>※節目企畫包含預期的<strong>節目內容、形式</strong>及<strong>收聽對象</strong>。<br></small></td> -->
  </tr>
  <tr>
  <td colspan="4" align="center">
  <input type="hidden" name="op" value="app_process">
  <input type="button" value="送出" class="input_btn" onClick="
  if (confirm('請確認所有表格填寫無誤且無遺漏! 按下\'確定\'後會立即送出申請表')){
  			this.form.submit();
  		}">
  </td>
  </tr>
  </table>
  </form>
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >管理者登入</a></div>
FORM;

  return $main;
}

//送出申請
function app_process($data=array()){
  global $link;
  if(empty($data['name']) or empty($data['depart']) or empty($data['b_y']) or empty($data['phone']))die("請輸入完整個人資料！");
  if(empty($data['introduction']))die("請填寫個人簡介！");
  //if(empty($data['program']))die("請填寫！");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email格式不正確喔！");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $birthday="{$data['b_y']}/{$data['b_m']}/{$data['b_d']}";
  $time=date("Y/m/d H:i:s");
  $sql="insert into apply (name,depart,sex,birthday,phone,email,introduction,program,time) values('{$data['name']} ','{$data['depart']}','{$data['sex']} ','{$birthday}','{$data['phone']} ','{$data['email']}','{$data['introduction']} ','{$data['program']} ','{$time}')";
  mysql_db_query("ctradio",$sql,$link) or die("無法寫入資料!<br>".$sql);
  
  //信件內容
	$mail_content = "Hi~ Goto&Play電台台長：\n
{$data['name']} 同學要參與2010年電台DJ的甄選。
--
申請表如下
姓名：{$data['name']}         系級：{$data['depart']}
性別：{$data['sex']}         生日：{$birthday}
電話：{$data['phone']}         EMAIL：{$data['email']}
個人簡介：
{$data['introduction']}
經歷：
{$data['program']}
申請時間：{$time}
--

(本信為系統自動發出，請不要直接回覆此信)";

	//寄給相關人員
	//@mail("lyforever62@hotmail.com","{$data['name']}的電台DJ申請表",$mail_content) ; //or die("無法寄信給taoc@mail.nctu.edu.tw");
        //@mail("amandatsail0108@hotmail.com","{$data['name']}的電台DJ申請表",$mail_content) ;
        //@mail("bantu-b@hotmail.com","{$data['name']}的電台DJ申請表",$mail_content) ;
        //@mail("j38261833@hotmail.com","{$data['name']}的電台DJ申請表",$mail_content) ;
        //@mail("zaida.cts96@g2.nctu.edu.tw","{$data['name']}的電台DJ申請表",$mail_content) ;
//@mail("m75251986@yahoo.com.tw","{$data['name']}的電台DJ申請表",$mail_content) ;
	//@mail("ymmat55.cts93@nctu.edu.tw","{$data['name']}的電台DJ申請表",$mail_content) ;
             //   @mail("nobel.hsu@gmail.com","{$data['name']}的電台DJ申請表",$mail_content) ; 
               @mail("taoc@mail.nctu.edu.tw","{$data['name']}的電台DJ申請表",$mail_content) ; 

	@mail("uselesscat.cts97@nctu.edu.tw","{$data['name']}的申請表",$mail_content) ; 
	@mail("uselesscat@gmail.com","{$data['name']}的申請表",$mail_content) ;
                @mail("thejoroba@gmail.com","{$data['name']}的梅竹主播申請表",$mail_content) ;
        @mail("ben78710@hotmail.com","{$data['name']}的梅竹主播申請表",$mail_content) ;


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>你的申請已送出！</p><p></p></td></tr></table>";
  
  return $html;
}

function app_deadline(){
   $html=<<<FORM
  <table><tr class='result'><td height='260'><p>報名已截止！</p><p>將會寄E-MAIL通知甄選結果。</p></td></tr></table>
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >管理者登入</a></div>
FORM;
  
  return $html;
}

//秀出所有內容
function app_listall(){
  global $link;

  //處理分頁
  $p=(empty($_GET['p']))?1:$_GET['p'];
  $num=6;
  $total=0;
  $start=($p-1)*$num;
  
  $limit="limit $start,$num";
  $sql="select * from apply order by sn desc $limit";
  $result=mysql_db_query("ctradio",$sql,$link) or die("無法取得資料！<br>".$sql);  
  $bre_list="";
  while($db_data = mysql_fetch_row($result)){

    list($sn,$name,$depart,$sex,$birthday,$phone,$email,$introduction,$program,$time,$ps)=$db_data;

    $introduction=nl2br($introduction);
	$program=nl2br($program);
	
	//若是管理者則顯示操作功能
	//if(check_user($_SESSION["id"],$_SESSION["passwd"])){
	  $admin_tool="
		  <a href='{$_SERVER['PHP_SEIF']}?op=app_reply&p={$p}&sn={$sn}'>備註</a><br>
		  <a href='javascript:delete_data($sn,$p)'>刪除</a>";

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
  
  //取得總資料數
  $sql="select count(*) from apply";
  $result=mysql_db_query("ctradio",$sql,$link) or die("無法取得總資料數！<br>".$sql);
  list($total) = mysql_fetch_row($result);
  
  $n=ceil($total/$num);
  $page_list="<select onChange=\"if(this.value!='') location.href = '{$_SERVER['PHP_SELF']}?op=app_listall&p=' + this.value\">";
  for($a=1;$a<=$n;$a++){
    $selected=($p==$a)?"selected":"";
    $page_list.="<option value='{$a}' $selected>第 $a 頁</option>";
  }
  $page_list.="</select>";

  $next_page=$p+1;
  $previous_page=$p-1;

  if($p!=1){
    $pre="<a href='{$_SERVER['PHP_SELF']}?op=app_listall&p={$previous_page}'>上一頁</a>";
	}else{
	$pre="<span class='gray'>上一頁</span>";
  }
  if($p==$n){
    $next="<span class='gray'>下一頁</span>";
	}else{
	$next="<a href='{$_SERVER['PHP_SELF']}?op=app_listall&p={$next_page}'>下一頁</a>";
  }
  
  $nav="
  <div class='nav'>
  （全部共 $total 筆申請資料）
  $pre
  $page_list
  $next
  </div>";
  
  $main=<<<LIST_ALL
  
  <script language="JavaScript" type="text/JavaScript">
  <!--
  //確認刪除
  function delete_data(sn, p){
    var sure = window.confirm(' 確定要刪除此資料? ');
	if (!sure)return;
	location.href="{$_SERVER['PHP_SELF']}?op=app_del&p="+p+"&sn="+sn;
  }
  //-->
  </script>
  
  
  <table class="list">
  <tr class="view"><td colspan='7' align='center'><b class="title">電台DJ申請人列表</b></td></tr>
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr align="center">
  <th>姓名系級</th>
  <th>性別生日</th>
  <th>聯絡方式</th>
  <th>個人簡介</th>
  <th>時間</th>
  <th>備註</th>
  </tr>
  $bre_list
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr class="view"><td colspan='7' align='center'><a href='{$_SERVER['PHP_SELF']}?op=logout'>登出</a></td></tr>
  </table>
LIST_ALL;

  return $main;
}

//管理者回覆
function app_reply($sn=""){
  $main=<<<FORM
  <form action="{$_SERVER['PHP_SELF']}" method="post" enctype="multipart/form-data">
  <table class="input_table">
  <tr>
  <td colspan="2" class="col_head_title">管理者備註</td>
  </tr>
  <tr>
  <td class="col_title">備註：</td><td width='180'><textarea name="data[reply]" rows=5>{$val['note']}</textarea></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
  <input type="hidden" name="op" value="app_reply_pro">
  <input type="hidden" name="data[sn]" value="{$sn}">
  <input type="submit" value="送出" class="input_btn">
  </td>
  </tr>
  </table>
  </form>
FORM;

  return $main;
}

//寫入回覆
function app_reply_pro($data=array()){
  global $link;
  if(empty($data['reply']))die("請輸入回覆內容！");
  
  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $sql="update apply set ps='{$data['reply']}' where sn={$data[sn]}";
  mysql_db_query("ctradio",$sql,$link) or die("無法寫入資料!<br>".$sql);  
}

//刪除一筆資料
function app_del($sn=""){
    global $link;
    $sql="delete from apply where sn={$sn}";
    mysql_db_query("ctradio",$sql,$link) or die("無法寫入資料!<br>".$sql); 
}
?>