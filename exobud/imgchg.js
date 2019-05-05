<!--
//
// ■ 進行動態按鈕圖檔的切換動作
//

toggleKey = new Object();
toggleKey[0] = "_off";
toggleKey[1] = "_on";
toggleKey[2] = "_ovr";
toggleKey[3] = "_out";
toggleKey[4] = "_mdn";
toggleKey[5] = "_mup";

function imgChange(id,act){
 if(document.images){ document.images[id].src = eval("img." + id + toggleKey[act] + ".src");}
}

// 當這段程式碼應用到播放器使用時：(exobud.js)
// 以函式 imgChange('按鈕識別名稱',0) 進行的動作即使用 "off" 的圖檔；
// 以函式 imgChange('按鈕識別名稱',1) 進行的動作即使用 "on"  的圖檔。

if(document.images){
 img = new Object();

 // 顯示播放狀態的 Scope 動態圖檔 (靜止／轉動)
 img.scope_off = new Image();
 img.scope_off.src = "./img/scope_off.gif";
 img.scope_on = new Image();
 img.scope_on.src = "./img/scope_on.gif";
}

//-->