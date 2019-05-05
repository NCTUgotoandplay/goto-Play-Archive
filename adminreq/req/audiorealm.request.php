function request(songID)
{
 var samhost = "<? echo $sam["host"]; ?>";
 var samport = "<? echo $sam["port"]; ?>";
 var path = "http://www.audiorealm.com/req/";

 reqwin = window.open(path+"req.html?songID="+songID+'&samport='+samport+'&samhost='+samhost, "_AR_request", "location=no,status=no,menubar=no,scrollbars=no,resizeable=yes,height=500,width=668");
}
