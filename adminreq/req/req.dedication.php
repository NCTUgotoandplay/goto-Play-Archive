<?
session_start();
if(!isset($_SESSION["id"])) header("location: index.php");
 $data = Array();
 $data["msg"] = "$rmessage";
 $data["name"] = "$rname";
 
 $db->update("requestlist",$data,"(ID = $requestID)");
 
 $db->open("SELECT * FROM songlist WHERE (ID = $songID)");
 $song = $db->row();
 $song["requestID"] = $requestID;
 PrepareSong($song);
 $dedicated = true;
 
 require("req.success.html");
?>