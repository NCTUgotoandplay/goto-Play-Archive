<!--
//
// ■ 播放程式的基本設定
//
// ▽自動播放？
//   設定當播放器載入頁面時，是否自動播放(Auto Start)媒體檔案：
//      true = 自動播放 (一般站長會選擇這個方式)
//     false = 不要自動播放，等待使用者啟動播放器
var blnAutoStart = true;

// ▽隨機播放？
//   設定曲目的預設播放順序，是否隨機(亂數/Random Playing)播放媒體檔案：
//      true = 隨機播放 (一般站長會選擇這個方式)
//     false = 根據我所排列的順序，循序播放
var blnRndPlay = true;

// ▽顯示媒體標題方塊？
//   設定播放面板上是否要顯示媒體標題：
//      true = 顯示媒體標題
//     false = 不要顯示媒體標題，將方塊隱藏起來
var blnShowTitle = true;

// ▽顯示時間長度方塊？
//   設定播放面板上是否要顯示時間長度：
//      true = 顯示時間長度
//     false = 不要顯示時間長度，將方塊隱藏起來
var blnShowTime = true;

// ▽在狀態列顯示文字訊息？
//   設定是否要在瀏覽器的狀態列(Status Bar)，顯示播放器的目前動作：
//      true = 在狀態列顯示播放器文字訊息 (適合在隱藏媒體標題/時間長度方塊的情況下使用)
//     false = 不要在狀態列顯示播放器文字訊息
//   (如果您在設定播放清單項目時，使用了像 &#12345; 的『＆控制碼』，必須將此項設為 false)
var blnStatusBar = false;

// ▽顯示音量控制按鈕？
//   設定播放面板上是否要顯示音量控制(Volume Control-包括靜音模式)的按鈕：
//      true = 顯示音量控制，容許使用者調校音量
//     false = 不顯示音量控制，將按鈕隱藏起來
var blnShowVolCtrl = true;

// ▽顯示播放清單按鈕？
//   設定播放面板上是否要顯示播放清單(Playlist)按鈕：
//      true = 顯示播放清單按鈕，讓使用者檢視播放清單內容及挑選曲目
//     false = 不顯示播放清單按鈕，使用者無法檢視播放清單內容及挑選曲目
var blnShowPlist = false;

// ▽使用字幕功能，開啟字幕框？
//   設定是否使用SMIL字幕功能(Closed Captioning-須配合副檔名為"SMI"的純文字檔案使用)：
//      true = 使用字幕功能，在字幕框中顯示同步歌詞或文字訊息(也可以包含圖片等資訊)
//     false = 關閉字幕功能
var blnUseSmi = false;

// ▽循環播放？
//   設定當所有曲目播放完畢後，是否重新播放所有曲目(循環播放/Loop Tracks)：
//      true = 使用循環播放功能 (一般站長會選擇這個方式)
//     false = 不使用循環播放功能，當所有曲目播放完畢後停止播放
var blnLoopTrk = true;

// ▽彈出視窗顯示媒體檔案資訊？
//   設定在開始播放每一首曲目時，是否彈出視窗顯示媒體檔案資訊(Media Info)：
//      true = 顯示媒體檔案資訊 (請認真考慮清楚，因為瀏覽者可能會感到厭煩的，此功能只適合測試用)
//     false = 不顯示媒體檔案資訊
var blnShowMmInfo = false;

// ▽播放面板上，動態按鈕的顏色設定值
//   以下兩個設定值是用來指定播放面板上，動態按鈕 (例如: 重複曲目,暫停-繼續,靜音...)
//   在不同狀態下 (On/Off) 所顯示的背景顏色。請注意，其中 btnOffColor 的顏色，必須與
//   在播放面板樣式表 (exobud.css) 中所設定，.buttons 樣式 background-color 的一樣。
//   您可以使用16位元顏色碼(#FFFFFF)或文字顏色碼(white)來設定這兩個值。
var btnOffColor = "white"; // 一般、預設狀態或未被選取的按鈕 (Button Off)
var btnOnColor = "silver"; // 已被選取或已變更預設狀態的按鈕 (Button On)

//-->