<?php
$station_name = "電台狀態";

$refresh = "45";  // Page refresh time in seconds. Put 0 for no refresh
$timeout = "1"; // Number of seconds before connecton times out - a higher value will slow the page down if any servers are offline

/* ----------- Server configuration ---------- */

// Note: dont include http://
// Main server: The song title will be taken from this server

$ip[1] = "gotoandplay.nctu.edu.tw";  //安裝shoutcast的主機IP
$port[1] = "8000";  //安裝shoutcast的主機所使用的port，預設是8000

/* ----- No need to edit below this line ----- */
/* ------------------------------------------- */
$servers = count($ip);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>電台狀態</title>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<?php
if ($refresh != "0") 
	{
	print "<meta http-equiv=\"refresh\" content=\"$refresh\">\n";
	}
?>
<style>
.table{	font-size: 12px;
				font-family: Verdana, Arial;
				color: #333333;
				background-color: #EEEEEE;
				border: none;
				width: 100%;
			}
.content{	color: #2A5578;
					background-color: #EEEEEE;
					font-size: 12px;
					font-family: Verdana, Arial;
					text-align: left;
					line-height:200%;
				}

.red{	color: #CC0000;
			font-weight: bold;
		}
</style>
</head>
<body bgcolor="#EEEEEE">

<?php
$i = "1";
while($i<=$servers)
	{
	$fp = @fsockopen($ip[$i],$port[$i],$errno,$errstr,$timeout);
	if (!$fp) 
		{ 
		$listeners[$i] = "0";
		$msg[$i] = "<span class=\"red\">錯誤 [拒絕連線 / 伺服器關閉中]</span>";
		$error[$i] = "1";
		} 
	else
		{ 
		fputs($fp, "GET /7.html HTTP/1.0\r\nUser-Agent: Mozilla\r\n\r\n");
		while (!feof($fp)) 
			{
			$info = fgets($fp);
			}
		$info = str_replace('<HTML><meta http-equiv="Pragma" content="no-cache"></head><body>', "", $info);
		$info = str_replace('</body></html>', "", $info);
		$stats = explode(',', $info);
		if (empty($stats[1]) )
			{
			$listeners[$i] = "0";
			$msg[$i] = "<span class=\"red\">電台休息中</span>";
			$error[$i] = "1";
			}
		else
			{
			if ($stats[1] == "1")
				{
				$song[$i] = $stats[6];
				$bitrate[$i] = $stats[5];
				$peak[$i] = $stats[2];
				
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "<span class=\"red\">";
					}
				$msg[$i] .= "音質：$bitrate[$i] kbps<br>";
				if ($stats[0] == $max[$i]) 
					{ 
					$msg[$i] .= "</span>";
					}
				$msg[$i] .= "\n    目前播放歌曲：<br> $song[1]\n";
				}
			else
				{
				$listeners[$i] = "0";
				$msg[$i] = "    <span class=\"red\">錯誤 [無法從伺服器取得資訊]</span>";
				$error[$i] = "1";
				}
			}
		}
	$i++;
	}

 	
?>

<?php
	}
else
	{
?>

<?php
	}
print "    <p> $msg[$i]\n  </p></td></tr></table>\n";
	$i++;
	}
?>

</body>
</html>