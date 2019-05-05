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
  <td colspan="4" class="col_head_title">殘酷電台報名表</td>
  </tr>
   <tr>
  <td class="col_title">本名：</td><td class="col"><input type="text" name="data[name]" class="txt"></td>
  <td class="col_title">綽號：</td><td class="col"><input type="text" name="data[nickname]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">系級：</td><td class="col"><input type="text" name="data[depart]" class="txt"></td>
  <td class="col_title">性別：</td><td class="col"><input name="data[sex]" type="radio" value="男" checked>男
<input name="data[sex]" type="radio" value="女">女</td>
  </tr>
  <tr>
  <td class="col_title">Skype ID：</td><td class="col"><input type="text" name="data[skype]" class="txt"></td>
  <td class="col_title">電子信箱：</td><td class="col"><input type="text" name="data[email]" class="txt"></td>
  </tr>
  <tr>
  <td class="col_title">演唱歌曲：</td><td class="col"><input type="text" name="data[song]" class="txt"></td>
  <td class="col_title">伴唱音樂：</td><td class="col"><input name="data[kala]" type="radio" value="有" checked>自備
<input name="data[kala]" type="radio" value="無">請幫我準備</td>
  </tr>
  <tr>
  <td colspan="4" align="left"><center><small>※節目採Skype Call-out方式進行，若無Skype請<a href="http://skype.pchome.com.tw/download.jsp" target="_blank">點此下載</a>，並申請帳號。<br></small></center></td>
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
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >DJ登入</a></div>
FORM;

  return $main;
}

//送出申請
function app_process($data=array()){
  global $link;
  if(empty($data['name']) or empty($data['depart']) or empty($data['nickname']) or empty($data['skype']))die("請輸入完整個人資料！");
  if(empty($data['song']))die("請填演唱歌曲！");
  if(!eregi("[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$data['email']))die("Email格式不正確喔！");

  if(!get_magic_quotes_gpc()){
    foreach($user as $col=>$val){
      $val=addslashes($val);
      $data[$col]=$val;
    }
  }

  $time=date("Y/m/d H:i:s");
  $sql="insert into sing (name,nickname,depart,sex,skype,email,song,kala,time) values('{$data['name']} ','{$data['nickname']} ','{$data['depart']}','{$data['sex']} ','{$data['skype']} ','{$data['email']}','{$data['song']} ','{$data['kala']} ','{$time}')";
  mysql_db_query("ctradio",$sql,$link) or die("無法寫入資料!<br>".$sql);
  
  //信件內容
	$mail_content = "Hi~ 冠金：\n
{$data['name']} 同學要參加殘酷電台歌唱大賽。
--
申請表如下
本名：{$data['name']}         綽號：{$data['nickname']}      
系級：{$data['depart']}         性別：{$data['sex']}
Skype ID：{$data['skype']}         EMAIL：{$data['email']}
演唱歌曲：{$data['song']}
是否自備伴唱帶：{$data['kala']}
報名時間：{$time}
--

(本信為系統自動發出，請不要直接回覆此信)";

	//寄給相關人員
	@mail("ymmat55.cts93@nctu.edu.tw","{$data['name']}的殘酷電台報名表",$mail_content) or die("無法寄信給taoc@mail.nctu.edu.tw");


}

function app_result(){
  $html=
  "<table><tr class='result'><td height='260'><p>恭喜完成報名！</p></td></tr></table>";
  
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
  $sql="select * from sing order by sn desc $limit";
  $result=mysql_db_query("ctradio",$sql,$link) or die("無法取得資料！<br>".$sql);  
  $bre_list="";
  while($db_data = mysql_fetch_row($result)){

    list($sn,$name,$nickname,$depart,$sex,$skype,$email,$song,$kala,$time,$ps)=$db_data;

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
    <td width='80' style='font-size:16px' nowrap align='center'>$name<br>
    $nickname</font></td>
	<td width='100' style='font-size:16px' nowrap align='center'>$sex<br>
    $depart</font></td>
    <td width='130' align='center' class='eng'>$skype<br>$email</td>
    <td style='font-size:16px' nowrap align='center'>$song<br>
	<font class='eng'>($kala 自備伴唱帶)</font></td>
	<td width='60'>$time</td>
	<td width='40'>$ps</td>
	<td width='40' class='func'>$admin_tool</td>
    </tr>
	";
  }
  
  //取得總資料數
  $sql="select count(*) from sing";
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
  <tr class="view"><td colspan='7' align='center'><b class="title">殘酷電台參賽者列表</b></td></tr>
  <tr class="view"><td colspan='7' align='center'>$nav</td></tr>
  <tr align="center">
  <th>姓名綽號</th>
  <th>性別系級</th>
  <th>聯絡方式</th>
  <th>演唱歌曲</th>
  <th>時間</th>
  <th>備註</th>
  <th></th>
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

  $sql="update sing set ps='{$data['reply']}' where sn={$data[sn]}";
  mysql_db_query("ctradio",$sql,$link) or die("無法寫入資料!<br>".$sql);  
}

//刪除一筆資料
function app_del($sn=""){
    global $link;
    $sql="delete from sing where sn={$sn}";
    mysql_db_query("ctradio",$sql,$link) or die("無法寫入資料!<br>".$sql); 
}
function app_deadline(){
   $html=<<<FORM
  <center><table><tr class='result'><td height='260'><p>節目籌備中</p><p>請各位聽眾耐心等候。</p><p><a href='/radio/signup/'index.php>歡迎報名徵選DJ</a></p></td></tr></table></center>
  <div align="right"><a href='{$_SERVER['PHP_SELF']}?op=login_form' class="littlefont" >管理者登入</a></div>
FORM;
  
  return $html;
}
?>