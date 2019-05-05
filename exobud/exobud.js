<!--

// 當您修改本程式的原始碼時，請注意執行修改後的程式，可能會導致一些正在執行中的應用程式
// 無法正常運作；另外亦要留意在JavaScript上所使用的變數名稱和設定值，大小寫是有分別的。

var objMmInfo = null;
var intMmCnt = 0;
var intSelMmCnt = 0;
var intActMmCnt = 0;
var cActIdx = 0;
var cActTit = "nAnT";
var strMmInfo = "ExoBUD 媒體檔案資訊";

var blnfpl = false;
var blnEnabled = false;
var blnEOT = false;
var arrSelMm = null;
var arrActMm = null;
var intExobudStat = 0;
var tidTLab = null;
var tidErr = null;
var tidMsg = null;
var intErrCnt = 0;
var blnRept = false;

// 這是「自動連續播放」的設定。一般來說，播放一首音樂完畢後就會自動跳到下一首。
// 但是如果您要播放的媒體是視訊檔案(例如:MV)的話，最好將這個設定值改為 false 。
//    true = 自動連續播放
//   false = 不要自動連續播放，讓使用者自行挑選下一首曲目
var blnAutoProc = true;

// 設定播放面板上所顯示的時間長度，預設是以正常方式(Elapse)抑或倒數方式(Lapse)顯示：
//    true = 以正常方式顯示時間長度，即動態地顯示曲目已播放的時間
//   false = 以倒數方式顯示時間長度，即動態地顯示曲目剩餘的時間
var blnElaps = true;

// 設定播放每首曲目之間的延遲時間(Delay Time)，單位是毫秒(msec)。
// 每100毫秒代表0.1秒，預設值是500毫秒(即0.5秒)，最少也要設為100毫秒。
var intDelay = 500;

// wmpInit() 函式: 使用 wmp-obj v7.x 程式庫建立環境設定
function wmpInit(){
 var wmps = Exobud.settings;
 var wmpc = Exobud.ClosedCaption;

 wmps.autoStart = true;
 wmps.balance = 0;
 wmps.enableErrorDialogs = false;
 wmps.invokeURLs = false;
 wmps.mute = false;
 wmps.playCount = 1;
 wmps.rate = 1;
 wmps.volume = 100;
 if(blnUseSmi){wmpc.captioningID="capText"; capText.style.display="";}
 Exobud.enabled = true;
}

// mkMmPath() 函式: 準備建立 Multi-object 的陣列
function mkMmPath(u,t,f,s){
 this.mmUrl = u;
 this.mmTit = t;
 this.mmDur = 0;
 this.selMm = f;
 this.actMm = f;
 if(blnUseSmi){this.mmSmi=s;}
}

// mkList() 函式: 建立 Multi-object 的陣列
function mkList(u,t,s,f){
 var cu = u;
 var ct = t;
 var cs = s;
 var cf = f;
 var idx = 0;

 if(objMmInfo == null){objMmInfo=new Array(); idx=0;}
 else {idx=objMmInfo.length;}
 if(u=="" || u==null){cu="mms://";}
 if(t=="" || t==null){ct="nAnT";}
 if(f=="f" || f=="F"){cf="f";}
 else {cf="t"; intSelMmCnt++;}

 if(blnUseSmi){
   objMmInfo[idx]=new mkMmPath(cu,ct,cf,cs);
 } else {
   objMmInfo[idx]=new mkMmPath(cu,ct,cf);
 }

 intActMmCnt = intSelMmCnt;
 intMmCnt = objMmInfo.length;
}

// mkSel() 函式: 建立已選取播放項目(Selected Media)的陣列
function mkSel(){
 arrSelMm = null;
 intSelMmCnt = 0;
 var selidx = 0;

 if(intMmCnt<=0){intExobudStat=1; blnEnabled=false; return;} // 沒有任何播放清單項目

 arrSelMm = new Array();
 for(var i=0; i<intMmCnt; i++){
   if(objMmInfo[i].selMm =="t"){arrSelMm[selidx]=i;selidx++;}
 }
 intSelMmCnt=arrSelMm.length;

 if(intSelMmCnt<=0){blnEnabled=false; intExobudStat=2; arrSelMm=null; return;}
 else {blnEnabled=true; mkAct();}
}

// mkAct() 函式: 建立已啟用播放項目(Activated Media)的陣列
function mkAct(){
 arrActMm = null;
 intActMmCnt = 0;
 var selidx = 0;
 var actidx = 0;

 if(blnEnabled){
   arrActMm=new Array();
   for(var i=0; i<intSelMmCnt; i++){
     selidx=arrSelMm[i];
     if(objMmInfo[selidx].actMm=="t"){arrActMm[actidx]=selidx; actidx++;}
   }
   intActMmCnt=arrActMm.length;
 }
 else { return;}
 if(intActMmCnt<=0){blnEOT=true;arrActMm=null;}
 else {blnEOT=false;}
}

// chkAllSel() 函式: 全部選取所有的播放清單項目
function chkAllSel(){
 for(var i=0; i<intMmCnt; i++){
   objMmInfo[i].selMm="t";
   objMmInfo[i].actMm="t";
 }
 mkSel();
}

// chkAllDesel() 函式: 不選取所有的播放清單項目
function chkAllDesel(){
 for(var i=0; i<intMmCnt; i++){
   objMmInfo[i].selMm="f";
   objMmInfo[i].actMm="f";
 }
 mkSel();
}

// chkItemSel() 函式: 選取或不選取播放清單項目
function chkItemSel(idx){
 if(objMmInfo[idx].selMm =="t"){
   objMmInfo[idx].selMm="f";objMmInfo[idx].actMm="f";
 } else {
   objMmInfo[idx].selMm="t";objMmInfo[idx].actMm="t";
 }
 mkSel();
}

// chkItemAct() 函式: 將某個已啟用播放項目(Activated Media)凍結
function chkItemAct(idx){
 objMmInfo[idx].actMm="f";
 mkAct();
}

// mkSelAct() 函式: 將已選取播放項目(Selected Media)加入到已啟用播放項目(Activated Media)
function mkSelAct(){
 var idx=0;
 for(var i=0; i<intSelMmCnt; i++){
   idx=arrSelMm[i];
   objMmInfo[idx].actMm="t";
 }
 mkAct();
}

// initExobud() 函式: 初始化 ExoBUD MP(II) 媒體播放程式
function initExobud(){
 wmpInit();
 mkSel();
 blnfpl = false;

 if(!blnShowTitle){ document.exobudform.disp1.style.display = "none";}
 if(!blnShowTime){ document.exobudform.disp2.style.display = "none";}

 if(!blnShowVolCtrl) {
   document.exobudform.vmute.style.display = "none";
   document.exobudform.vdn.style.display = "none";
   document.exobudform.vup.style.display = "none";
 }
 if(!blnShowPlist){ document.exobudform.plist.style.display = "none";}

 if(blnRept){ document.exobudform.rept.style.background=btnOnColor;}
 else { document.exobudform.rept.style.background=btnOffColor;}

 if(blnRndPlay){ document.exobudform.pmode.value="Ｒ"; document.exobudform.pmode.style.background=btnOnColor;}
 else { document.exobudform.pmode.value="Ｓ"; document.exobudform.pmode.style.background=btnOffColor;}
 showTLab();
 document.exobudform.disp1.value = "請按右側<播放>開始收聽電台";
 if(blnStatusBar){ window.status=('請按右側<播放>開始收聽電台');}
 if(blnAutoStart){startExobud();}
}

// startExobud() 函式: 開始播放曲目
function startExobud(){
 var wmps = Exobud.playState;
 if(wmps==2){Exobud.controls.play(); return;}
 if(wmps==3){ return;}

 blnfpl=false;
 if(!blnEnabled){waitMsg();return;}
 if(blnEOT){mkSelAct();}
 if(intErrCnt>0){intErrCnt=0;tidErr=setTimeout('retryPlay(),1000');return;}
 if(blnRndPlay){rndPlay();}
 else {cActIdx=arrActMm[0]; selMmPlay(cActIdx);}
}

// selMmPlay() 函式: 處理媒體標題
function selMmPlay(idx){
 clearTimeout(tidErr);
 cActIdx=idx;
 var trknum=idx+1;
 var ctit =objMmInfo[idx].mmTit;
 if(ctit=="nAnT"){ctit="(沒有媒體標題)"}
 if(blnUseSmi){Exobud.ClosedCaption.SAMIFileName = objMmInfo[idx].mmSmi;}
 Exobud.URL = objMmInfo[idx].mmUrl;
 cActTit = "T" + trknum + ". " + ctit;
 document.exobudform.disp1.value = cActTit;
 if(blnStatusBar){ window.status=(cActTit);}
 chkItemAct(cActIdx);
}

// wmpPlay() 函式: 使用 wmp-obj v7.x 程式庫播放曲目
function wmpPlay(){Exobud.controls.play();}

// wmpStop() 函式: 停止播放曲目及顯示「就緒」狀態訊息
function wmpStop(){
 intErrCnt=0;
 clearTimeout(tidErr);
 clearInterval(tidTLab);
 document.exobudform.stopt.style.background=btnOnColor;
 document.exobudform.pauzt.style.background=btnOffColor;
 imgChange("scope",0);
 showTLab();
 mkSelAct();
 Exobud.controls.stop();
 Exobud.close();
 document.exobudform.disp1.value = "請按右側<播放>開始收聽電台";
 if(blnStatusBar){ window.status=('請按右側<播放>開始收聽電台');return true;}
}

// wmpPause() 函式: 使用 wmp-obj v7.x 程式庫暫停播放曲目
function wmpPause(){Exobud.controls.pause();}

// wmpPP() 函式: 在暫停播放和繼續播放之間進行切換
function wmpPP(){
 var wmps = Exobud.playState;
 var wmpc = Exobud.controls;
 clearInterval(tidTLab);
 clearTimeout(tidMsg);
 if(wmps==2){wmpc.play();}
 if(wmps==3){wmpc.pause(); document.exobudform.disp2.value="暫停"; tidMsg=setTimeout('rtnTLab()',1500);}
 return;
}

// rndPlay() 函式: 隨機播放(Random Play)的運算方式
function rndPlay(){
 if(!blnEnabled){waitMsg();return;}
 intErrCnt=0;
 var idx=Math.floor(Math.random() * intActMmCnt);
 cActIdx=arrActMm[idx];
 selMmPlay(cActIdx);
}

// playAuto() 函式: 對已啟用播放項目進行「自動連續播放」的處理
// 這是根據上面 blnAutoProc 的設定值而決定的動作。
function playAuto(){
 if(blnRept){selMmPlay(cActIdx);return;}
 if(!blnAutoProc){wmpStop();return;}
 if(blnfpl){wmpStop();return;}
 if(!blnEnabled){wmpStop();return;}
 if(blnEOT){
   if(blnLoopTrk){startExobud();}
   else {wmpStop();}
 } else {
   if(blnRndPlay){rndPlay();}
   else {cActIdx=arrActMm[0]; selMmPlay(cActIdx);}
 }
}

// 播放使用者在播放清單上所點選的單一曲目
function selPlPlay(idx){
 blnfpl=true;
 selMmPlay(idx);
}

// playPrev() 函式: 播放上一首已啟用播放項目
function playPrev(){
 var wmps = Exobud.playState;
 if(wmps==2 || wmps==3){Exobud.controls.stop();}
 blnfpl=false;
 if(!blnEnabled){waitMsg();return;}
 if(blnEOT){mkSelAct();}

 intErrCnt=0;
 if(blnRndPlay){rndPlay();}
 else {
   var idx=cActIdx;
   var blnFind=false;
   for(var i=0;i<intSelMmCnt;i++){ if(cActIdx==arrSelMm[i]){idx=i-1; blnFind=true;}}
   if(!blnFind){startExobud();return;}
   if(idx<0){idx=intSelMmCnt-1;cActIdx=arrSelMm[idx];}
   else {cActIdx=arrSelMm[idx];}
   selMmPlay(cActIdx);
 }
}

// playNext() 函式: 播放下一首已啟用播放項目
function playNext(){
 var wmps = Exobud.playState;
 if(wmps==2 || wmps==3){Exobud.controls.stop();}
 blnfpl=false;
 if(!blnEnabled){waitMsg();return;}
 if(blnEOT){mkSelAct();}

 intErrCnt=0;
 if(blnRndPlay){rndPlay();}
 else {
   var idx=cActIdx;
   var blnFind=false;
   for(var i=0;i<intSelMmCnt;i++){ if(cActIdx==arrSelMm[i]){idx=i+1; blnFind=true;}}
   if(!blnFind){startExobud();return;}
   if(idx>=intSelMmCnt){idx=0;cActIdx=arrSelMm[idx];}
   else {cActIdx=arrSelMm[idx];}
   selMmPlay(cActIdx);
 }
}

// retryPlay() 函式: 再次嘗試連線到媒體檔案
function retryPlay(){
 selMmPlay(cActIdx);
}

// chkRept() 函式: 切換是否重複播放目前的曲目(已啟用播放項目)
function chkRept(){
 var wmps = Exobud.playState;
 if(wmps==3){clearInterval(tidTLab);}
 if(blnRept){
   blnRept=false; document.exobudform.rept.style.background=btnOffColor; document.exobudform.disp2.value="不重複播放";
 } else {
   blnRept=true; document.exobudform.rept.style.background=btnOnColor; document.exobudform.disp2.value="重複播放";
 }
 tidMsg=setTimeout('rtnTLab()',1000);
}

// chgPMode() 函式: 切換以循序(Sequential)抑或隨機(Random)的方式來播放媒體項目
function chgPMode(){
 var wmps = Exobud.playState;
 if(wmps==3){clearInterval(tidTLab);}
 if(blnRndPlay){
   blnRndPlay=false;
   document.exobudform.pmode.value="Ｓ";
   document.exobudform.pmode.style.background=btnOffColor;
   document.exobudform.disp2.value="循序播放";
 } else {
   blnRndPlay=true;
   document.exobudform.pmode.value="Ｒ";
   document.exobudform.pmode.style.background=btnOnColor;
   document.exobudform.disp2.value="隨機播放";
 }
 tidMsg=setTimeout('rtnTLab()',1000);
}

// evtOSChg() 函式: 以彈出視窗方式顯示媒體檔案資訊
function evtOSChg(f){
//   以下是狀態值 (f) 的說明:
//    0(未定義)       8(轉換媒體中)   9(尋找媒體中)  10(連線媒體中)  11(載入媒體中)
//   12(開啟媒體中)  13(媒體已開啟)  20(等待播放中)  21(正在開啟不明的連結)

 if(f==8){capText.innerHTML="ExoBUD MP(II) 字幕顯示系統(SMI)";}
 if(f==13){
   var strTitle = Exobud.currentMedia.getItemInfo("Title");
   if(strTitle.length <= 0){strTitle = "(未命名的標題)"}
   var strAuthor = Exobud.currentMedia.getItemInfo("Author");
   if(strAuthor.length <= 0){strAuthor = "(未命名的演出者)"}
   var strCopy = Exobud.currentMedia.getItemInfo("Copyright");
   if(strCopy.length <= 0){strCopy = "(沒有著作權資訊)"}
   var strType = Exobud.currentMedia.getItemInfo("MediaType");
   var strDur = Exobud.currentMedia.durationString;
   var strUrl = Exobud.URL;
   var trknum = cActIdx+1;
   var ctit = objMmInfo[cActIdx].mmTit;
   if(ctit=="nAnT"){
     objMmInfo[cActIdx].mmTit = strAuthor + " - " + strTitle;
     ctit = strAuthor + " - " + strTitle;
     cActTit = "T" + trknum + ". " + ctit;
     document.exobudform.disp1.value = cActTit;
   }

   strMmInfo  = "　　標題： " + strTitle + " (形式: " + strType +")" + "\n\n";
   strMmInfo += "　演出者： " + strAuthor + "\n\n";
   strMmInfo += "檔案位置： " + strUrl + "\n\n";
   strMmInfo += "　著作權： " + strCopy + "\n\n";
   strMmInfo += "時間長度： " + strDur + "\n\n\n";
   strMmInfo += "　　 Brought to you by ExoBUD MP(II).\n";
   strMmInfo += "　　 Copyright(C) 1999-2003 Jinwoong Yu.\n";
   strMmInfo += "　　 ALL RIGHTS RESERVED.\n";
   if(blnShowMmInfo){alert(strMmInfo);}
 }
}

// evtPSChg() 函式: 切換播放程式的動作
function evtPSChg(f){
//   以下是狀態值 (f) 的說明:
//    0(未定義)       1(已停止播放)   2(已暫停播放)   3(正在播放中)   4(向前搜索)     5(向後搜索)
//    6(緩衝處理中)   7(等待中)       8(已播放完畢)   9(轉換曲目中)  10(就緒狀態)

 switch(f){
   case 1:
     evtStop();
     break;
   case 2:
     evtPause();
     break;
   case 3:
     evtPlay();
     break;
   case 8:
     setTimeout('playAuto()', intDelay);
     break;
 }
}

// evtWmpBuff() 函式: 對媒體檔案進行緩衝處理(Buffering)的動作
function evtWmpBuff(f){
 if(f){
   document.exobudform.disp2.value = "緩衝處理中";
   var msg = "(緩衝處理中) " + cActTit;
   document.exobudform.disp1.value = msg;
   if(blnStatusBar){ window.status=(msg);}
 } else {
   document.exobudform.disp1.value = cActTit; showTLab();
 }
}

// evtWmpError() 函式: 當無法連線到媒體檔案時，顯示錯誤訊息
function evtWmpError(){
 intErrCnt++;
 Exobud.Error.clearErrorQueue();
 if(intErrCnt<=3){
   document.exobudform.disp2.value = "嘗試連線 (" + intErrCnt + ")";
   var msg = "(嘗試第 " + intErrCnt + " 次連線) " + cActTit;
   document.exobudform.disp1.value = "<無法播放> " + cActTit;
   if(blnStatusBar){ window.status=(msg);}
   tidErr=setTimeout('retryPlay()',1000);
 } else {
   clearTimeout(tidErr);
   intErrCnt=0;showTLab();
   var msg = "已放棄嘗試再連線。現在將會播放下一首曲目。";
   if(blnStatusBar){ window.status=(msg);}
   setTimeout('playAuto()',1000);}
}

// evtStop() 函式: 停止播放
function evtStop(){
 clearTimeout(tidErr);
 clearInterval(tidTLab);
 showTLab();
 intErrCnt=0;
 document.exobudform.pauzt.style.background=btnOffColor;
 document.exobudform.playt.style.background=btnOffColor;
 imgChange("scope",0);
 document.exobudform.disp1.value = "ExoBUD MP(II) v4.1tc+ [等待播放下一首曲目]";
 if(blnStatusBar){ window.status=('ExoBUD MP(II) v4.1tc+ [等待播放下一首曲目]');return true;}
}

// evtPause() 函式: 暫停播放
function evtPause(){
 document.exobudform.pauzt.style.background=btnOnColor;
 document.exobudform.playt.style.background=btnOffColor;
 document.exobudform.stopt.style.background=btnOffColor;
 imgChange("scope",0);
 clearInterval(tidTLab);
 showTLab();
}

// evtPlay() 函式: 開始播放
function evtPlay(){
 document.exobudform.pauzt.style.background=btnOffColor;
 document.exobudform.playt.style.background=btnOnColor;
 document.exobudform.stopt.style.background=btnOffColor;
 imgChange("scope",1);
 tidTLab=setInterval('showTLab()',1000);
}

// showTLab() 函式: 顯示時間長度
function showTLab(){
 var ps = Exobud.playState;
 if(ps==2 || ps==3){
   var cp=Exobud.controls.currentPosition;
   var cps=Exobud.controls.currentPositionString;
   var dur=Exobud.currentMedia.duration;
   var durs=Exobud.currentMedia.durationString;
   if(blnElaps){
     document.exobudform.disp2.value = cps + " | " + durs;
     var msg = cActTit + " (" + cps + " | " + durs + ")";
     if(ps==2){msg = "(暫停) " + msg;}
     if(blnStatusBar){ window.status=(msg);return true;}
   } else {
     var laps = dur-cp;
     var strLaps = wmpTime(laps);
     document.exobudform.disp2.value = strLaps + " | " + durs;
     var msg = cActTit + " (" + strLaps + " | " + durs + ")";
     if(ps==2){msg = "(暫停) " + msg;}
     if(blnStatusBar){ window.status=(msg);return true;}
   }

 } else {
   document.exobudform.disp2.value = "00:00 | 00:00";
 }
}

// chgTimeFmt() 函式: 變更時間長度的顯示方式
function chgTimeFmt(){
 var wmps = Exobud.playState;
 if(wmps==3){clearInterval(tidTLab);}
 if(blnElaps){
   blnElaps=false; document.exobudform.disp2.value="倒數方式";
 } else {
   blnElaps=true; document.exobudform.disp2.value="正常方式";
 }
 tidMsg=setTimeout('rtnTLab()',1000);
}

// rtnTLab() 函式: 傳回時間長度
function rtnTLab(){
 clearTimeout(tidMsg);
 var wmps = Exobud.playState;
 if(wmps==3){tidTLab=setInterval('showTLab()',1000);}
 else {showTLab();}
}

// wmpTime() 函式: 計算時間長度
function wmpTime(dur){
 var hh, min, sec, timeLabel;
 hh=Math.floor(dur/3600);
 min=Math.floor(dur/60)%60;
 sec=Math.floor(dur%60);
 if(isNaN(min)){ return "00:00";}
 if(isNaN(hh) || hh==0){timeLabel="";}
 else {
   if(hh>9){timeLabel = hh.toString() + ":";}
   else {timeLabel = "0" + hh.toString() + ":";}
 }
 if(min>9){timeLabel = timeLabel + min.toString() + ":";}
 else {timeLabel = timeLabel + "0" + min.toString() + ":";}
 if(sec>9){timeLabel = timeLabel + sec.toString();}
 else {timeLabel = timeLabel + "0" + sec.toString();}
 return timeLabel;
}

// wmpVolUp(), wmpVolDn(), wmpMute() 這幾個都是用來調校音量的函式。(單位：％)
// vmax 代表最大音量(100), vmin 代表最小音量(0), vdep 代表調校音量的間隔(建議設為5至20之間)
// 您只可以在 vmin, vmax, vdep 設為0至100之間的整數數值，vmin 和 vdep 數值不可以大過 vmax。

var vmax = 100;
var vmin = 0;
var vdep = 10;

// wmpVolUp() 函式: 增加音量(Volume Up)
function wmpVolUp(){
 var wmps = Exobud.playState;
 if(wmps==3){clearInterval(tidTLab);}
 var ps = Exobud.settings;
 if(ps.mute){
   ps.mute=false;
   document.exobudform.disp2.value="音量恢復";
   document.exobudform.vmute.style.background=btnOffColor;
 } else {
   if(ps.volume >= (vmax-vdep)){ps.volume = vmax;}
   else {ps.volume = ps.volume + vdep;}
   document.exobudform.disp2.value = "音量: " + ps.volume + "%";
 }
 tidMsg=setTimeout('rtnTLab()',1000);
}

// wmpVolDn() 函式: 減少音量(Volume Down)
function wmpVolDn(){
 var wmps = Exobud.playState;
 if(wmps==3){clearInterval(tidTLab);}
 var ps = Exobud.settings;
 if(ps.mute){
   ps.mute=false;
   document.exobudform.disp2.value="音量恢復";
   document.exobudform.vmute.style.background=btnOffColor;
 } else {
   if(ps.volume <= vdep){ps.volume = vmin;}
   else {ps.volume = ps.volume - vdep;}
   document.exobudform.disp2.value = "音量: " + ps.volume + "%";
 }
 tidMsg=setTimeout('rtnTLab()',1000);
}

// wmpMute() 函式: 靜音模式(Mute)
function wmpMute(){
 var wmps = Exobud.playState;
 if(wmps==3){clearInterval(tidTLab);}
 var ps = Exobud.settings;
 if(!ps.mute){
   ps.mute=true;
   document.exobudform.disp2.value="開啟靜音模式";
   document.exobudform.vmute.style.background=btnOnColor;
 } else {
   ps.mute=false;
   document.exobudform.disp2.value="關閉靜音模式";
   document.exobudform.vmute.style.background=btnOffColor;
 }
 tidMsg=setTimeout('rtnTLab()',1000);
}

// waitMsg() 函式: 顯示因播放清單空白而無法播放的訊息
function waitMsg(){
 capText.innerHTML="ExoBUD MP(II) 字幕顯示系統(SMI)";
 if(intExobudStat==1){ document.exobudform.disp1.value = "無法播放 － 播放清單上沒有設定任何曲目。";}
 if(intExobudStat==2){ document.exobudform.disp1.value = "無法播放 － 您沒有選取播放清單上任何一首曲目。";}
 if(blnStatusBar){
   if(intExobudStat==1){ window.status=('無法播放 － 播放清單上沒有設定任何曲目。'); return true;}
   if(intExobudStat==2){ window.status=('無法播放 － 您沒有選取播放清單上任何一首曲目。'); return true;}
 }
}

// openPlist() 函式: 以彈出視窗顯示播放清單內容
function openPlist(){
 window.open("exobudpl.htm","mplist","width=400,height=400,scrollbars=no,resizable=yes,copyhistory=no");
}

// chkWmpState() 函式: 當播放程式動作變更時，傳回 playState 的狀態值
function chkWmpState(){
//   以下是狀態值的說明:
//    0(未定義)       1(已停止播放)   2(已暫停播放)   3(正在播放中)   4(向前搜索)     5(向後搜索)
//    6(緩衝處理中)   7(等待中)       8(已播放完畢)   9(轉換曲目中)  10(就緒狀態)
 return Exobud.playState;
}

// chkWmpOState() 函式: 當播放程式開啟媒體檔案準備播放時，傳回 openState 的狀態值
function chkWmpOState(){
//   以下是狀態值的說明:
//    0(未定義)       8(轉換媒體中)   9(尋找媒體中)  10(連線媒體中)  11(載入媒體中)
//   12(開啟媒體中)  13(媒體已開啟)  20(等待播放中)  21(正在開啟不明的連結)
 return Exobud.openState;
}

// chkOnline() 函式: 檢查使用者的連線狀態 (不一定每款面板都會使用)
function chkOnline(){
// 傳回值: true(已連線到網際網路) false(沒有連線到網際網路)
 return Exobud.isOnline;
}

// vizExobud() 函式: 點選連到 ExoBUD MP 播放器原作者的官方網站[韓文] (不一定每款面板都會使用)
function vizExobud(){
// 使用範例: <span onClick="vizExobud()" style="cursor:hand" title="到訪 ExoBUD MP 原作者 Jinwoong Yu 的網站 [韓文]">
 window.open("http://exobud.nayana.org","vizExobud");
}

//-->