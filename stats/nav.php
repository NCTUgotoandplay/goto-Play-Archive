<table border="0" cellspacing="0" cellpadding="2" bgcolor="#EEEEEE" width="150">
  <tr>
    <td nowrap colspan="2" bgcolor="#C0C0C0" align="center"><b>�\����</b></td>
  </tr>
<? if($nava){ ?>
  <tr>
    <td nowrap align="center"><img border="0" src="images/speaker.gif"></td>
    <td nowrap><a href="http://61.228.44.45:8000/listen.pls">�I�ڦ�ť</a> </td>
  </tr>
<? } ?>
<? if($navb){ ?>
  <tr>
    <td nowrap align="center"><img border="0" src="images/play.gif"></td>
    <td nowrap><a href="playing.php">���񤤦���</a></td>
  </tr>
<? } ?>
<? if($navc){ ?>
  <tr>
    <td nowrap align="center"><img border="0" src="images/tb-file-list.gif"></td>
    <td nowrap><a href="playlist.php?limit=25">�����I��</a></td>
  </tr>
<? } ?>
<? if($navd){ ?>
  <tr>
    <td nowrap align="center"><img border="0" src="images/tb-file-list.gif"></td>
    <td nowrap><a href="history.php">���v�M��</a></td>
  </tr>
<? } ?>
<? if($navd){ ?>
  <tr>
    <td nowrap align="center"><img border="0" src="images/chat.gif"></td>
    <td nowrap><a href="chat.php">�u�W���</a></td>
  </tr>
<? } ?>
<? if($nave){ ?>
  <tr>
    <td nowrap align="center"><img border="0" src="images/email.gif"></td>
    <td nowrap><a href="mailto:<? echo $email; ?>">E-mail</a></td>
  </tr>
</table><br>
<table width="150">
  <tr>
    <td nowrap colspan="2" bgcolor="#C0C0C0" align="center"><b>�q�x��T</b></td>
  </tr>
  <tr>
    <td nowrap><iframe src="./server-stats.php" name="server-stats" scrolling="no" width="150" height="165"  marginwidth="0" marginheight="0" frameborder="0"></iframe></td>
  </tr>
<? } ?>
</table>
