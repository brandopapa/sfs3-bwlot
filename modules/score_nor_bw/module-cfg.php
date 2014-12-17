<?php

// $Id: module-cfg.php 7485 2013-09-05 14:31:04Z smallduh $

//---------------------------------------------------
//
// 1.這裡定義：模組資料表名稱 (供 "模組權限設定" 程式使用)
//   這區的 "變數名稱" 請勿改變!!!
//-----------------------------------------------
//
// 若有一個以上，請接續此  陣列來定義
//
// 也可以用以下這種設法：
//
// $MODULE_TABLE_NAME=array(0=>"lunchtb", 1=>"xxxx");
// 
// $MODULE_TABLE_NAME[0] = "lunchtb";
// $MODULE_TABLE_NAME[1]="xxxx";
//
// 請注意要和 module.sql 中的 table 名稱一致!!!
//---------------------------------------------------

// 資料表名稱定義

$MODULE_TABLE_NAME[0] = "";

//---------------------------------------------------
//
// 2.這裡定義：模組中文名稱，請精簡命名 (供 "模組權限設定" 程式使用)
//
// 它會顯示給使用者
//-----------------------------------------------


$MODULE_PRO_KIND_NAME = "平常成績管理_福智內部";


//---------------------------------------------------
//
// 3. 這裡定義：模組版本相關資訊 (供 "自動更新程式" 取用)
//    這區的 "變數名稱" 請勿改變!!!
//
//---------------------------------------------------

// 模組最後更新版本
$MODULE_UPDATE_VER="2.0";

// 模組最後更新日期
$MODULE_UPDATE="2009/03/13";

//重要模組，免被勿刪
if ($IS_JHORES==6)
	$SYS_MODULE=1;
else
	$SYS_MODULE=0;
//---------------------------------------------------
//
// 4. 這裡請定義：您這支程式需要用到的：變數或常數
//---------------------------------^^^^^^^^^^
//
// (不想被 "模組參數管理" 控管者，請置放於此)
//
// 建議：請儘量用英文大寫來定義，最好要能由字面看出其代表的意義。
//
// 這區的 "變數名稱" 可以自由改變!!!
//
//---------------------------------------------------

if ($IS_JHORES==6)
$menu_p = array(
"input.php"=>"平常成績輸入",
"nor.php"=>"平常成績總表",
"check.php"=>"不及格名單",
"report.php"=>"綜合表現記錄表",
"cal.php"=>"統計平常成績",
"chk.php"=>"設定檢核表",
"prt.php"=>"列印平常成績總表",
"five.php"=>"畢業成績表",
"disgrad.php"=>"修業建議名單",
"disgrad2.php"=>"修業建議名單(新)",
"award.php"=>"全勤獎名單",
"club_serv.php"=>"列印學期通知單");
else
$menu_p=array(
"cal.php"=>"統計平常成績",
"chk.php"=>"設定檢核表",
"award.php"=>"全勤獎名單"
);

$item_arr['default_jh']=array(
array("愛整潔","能保持衣著儀容整潔","能保持座位及抽屜的整潔"),
array("有禮貌","言談舉止端莊不踰矩","對師長態度謙恭有禮","看到師長、同學或來賓會問好"),
array("守秩序","上課能按時進教室","能履行班規或生活公約","能依規定攜帶學用品","能遵守教師課堂規範之行為","能遵守考試規則"),
array("責任心","擔任班級工作能克盡職責","上課能專心聽講","能按時繳交作業"),
array("公德心","能節約用水用電，做好資源回收","能愛惜及善用公物"),
array("友愛關懷","能友愛同學","熱心參與公共服務"),
array("團隊合作","能與同學互助合作","積極參與團體活動")
);

$item_arr['default_es']=array(
array("敬愛人","友愛同學，幫助他人，替別人著想","接納和尊重不同文化的人","感謝生活中為我們服務的人","讚美他人，並學習他人的長處","誠實面對錯誤，勇於改正"),
array("愛整潔","飯前便後洗手、飯後潔牙漱口","書包、抽屜和教室保持整潔，物歸定位","保持儀容整潔，定期修剪指甲","垃圾不落地，維護校園整潔","正確使用廁所，保持整潔"),
array("守秩序","遵守排隊及行進秩序","不喧嘩、奔跑、做危險動作及拿東西丟人","遵守用餐秩序，不邊走邊吃","按時到校，上課不遲到","遵守公物使用規定，不隨意破壞"),
array("有禮貌","會向師長、來賓及同學問好","常說「請、謝謝、對不起」","仔細聆聽別人發言","言行有禮，輕聲細語、不說粗暴的話","虛心接受師長的指導"),
array("做環保","做好垃圾分類、垃圾減量","懂得資源回收再利用","學校午餐\、戶外活動時，自備餐\具用餐\","節約能源，隨手關燈、關水龍頭","愛物惜物，不浪費資源")
);

$item_sel=array("default_jh"=>"預設_國中","default_es"=>"預設_國小");

//---------------------------------------------------
//
// 5. 這裡定義：預設值要由 "模組參數管理" 程式來控管者，
//    若不想，可不必設定。
//
// 格式： var 代表變數名稱
//       msg 代表顯示訊息
//       value 代表變數設定值
//
// 若您決定將這些變數交由 "模組參數管理" 來控管，那麼您的模組程式
// 就要對這些變數有感知，也就是說：若這些變數值在模組參數管理中改變，
// 您的模組就要針對這些變數有不同的動作反映。
//
// 例如：某留言板模組，提供每頁顯示筆數的控制，如下：
// $SFS_MODULE_SETUP[1] =
// array('var'=>"PAGENUM", 'msg'=>"每頁顯示筆數", 'value'=>10);
//
// 上述的意思是說：您定義了一個變數 PAGENUM，這個變數的預設值為 10
// PAGENUM 的中文名稱為 "每頁顯示筆數"，這個變數在安裝模組時會寫入
// pro_module 這個 table 中
//
// 我們有提供一個函式 get_module_setup
// 供您取用目前這個變數的最新狀況值，
//
// 詳情請參考 include/sfs_core_module.php 中的說明。
//
// 這區的 "變數名稱 $SFS_MODULE_SETUP" 請勿改變!!!
//---------------------------------------------------

$ABOO_ARR= array("0"=>"全學期全勤加五分","1"=>"一個月全勤加一分");
$DBOO_ARR= array("1"=>"最低0分","0"=>"無下限");
$UBOO_ARR= array("1"=>"最高100分","0"=>"無上限");
$SCORE_ARR= array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9");
$SFS_MODULE_SETUP[0] =
	array('var'=>"f_w", 'msg'=>"第一次警告扣幾分", 'value'=>array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[1] =
	array('var'=>"s_w", 'msg'=>"第二次警告扣幾分", 'value'=>array("1"=>"1","0"=>"0","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[2] =
	array('var'=>"t_w", 'msg'=>"第三次以上警告扣幾分", 'value'=>array("1"=>"1","0"=>"0","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[3] =
	array('var'=>"f_sw", 'msg'=>"第一次小過扣幾分", 'value'=>array("2"=>"2","0"=>"0","1"=>"1","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[4] =
	array('var'=>"s_sw", 'msg'=>"第二次小過扣幾分", 'value'=>array("2"=>"2","0"=>"0","1"=>"1","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[5] =
	array('var'=>"t_sw", 'msg'=>"第三次以上小過扣幾分", 'value'=>array("3"=>"3","0"=>"0","1"=>"1","2"=>"2","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[6] =
	array('var'=>"f_bw", 'msg'=>"第一次大過扣幾分", 'value'=>array("7"=>"7","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[7] =
	array('var'=>"s_bw", 'msg'=>"第二次大過扣幾分", 'value'=>array("7"=>"7","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[8] =
	array('var'=>"t_bw", 'msg'=>"第三次以上大過扣幾分", 'value'=>array("7"=>"7","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[9] =
	array('var'=>"f_a", 'msg'=>"第一次嘉獎加幾分", 'value'=>array("1"=>"1","0"=>"0","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[10] =
	array('var'=>"s_a", 'msg'=>"第二次嘉獎加幾分", 'value'=>array("1"=>"1","0"=>"0","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[11] =
	array('var'=>"t_a", 'msg'=>"第三次以上嘉獎加幾分", 'value'=>array("1"=>"1","0"=>"0","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[12] =
	array('var'=>"f_sa", 'msg'=>"第一次小功加幾分", 'value'=>array("3"=>"3","0"=>"0","1"=>"1","2"=>"2","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[13] =
	array('var'=>"s_sa", 'msg'=>"第二次小功加幾分", 'value'=>array("3"=>"3","0"=>"0","1"=>"1","2"=>"2","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[14] =
	array('var'=>"t_sa", 'msg'=>"第三次以上小功加幾分", 'value'=>array("3"=>"3","0"=>"0","1"=>"1","2"=>"2","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"));
$SFS_MODULE_SETUP[15] =
	array('var'=>"f_ba", 'msg'=>"第一次大功加幾分", 'value'=>array("9"=>"9","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8"));
$SFS_MODULE_SETUP[16] =
	array('var'=>"s_ba", 'msg'=>"第二次大功加幾分", 'value'=>array("9"=>"9","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8"));
$SFS_MODULE_SETUP[17] =
	array('var'=>"t_ba", 'msg'=>"第三次以上大功加幾分", 'value'=>array("9"=>"9","0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8"));
$SFS_MODULE_SETUP[18] =
	array('var'=>"cl_days", 'msg'=>"請多少節事假扣一分", 'value'=>"30");
$SFS_MODULE_SETUP[19] =
	array('var'=>"sl_days", 'msg'=>"請多少節病假扣一分", 'value'=>"80");
$SFS_MODULE_SETUP[20] =
	array('var'=>"u_score", 'msg'=>"平常表現成績有無上限", 'value'=>$UBOO_ARR);
$SFS_MODULE_SETUP[21] =
	array('var'=>"d_score", 'msg'=>"平常表現成績有無下限", 'value'=>$DBOO_ARR);
$SFS_MODULE_SETUP[22] =
	array('var'=>"a_score", 'msg'=>"全勤計算模式", 'value'=>$ABOO_ARR);
$SFS_MODULE_SETUP[23] =
	array('var'=>"sday", 'msg'=>"修業計算曠課節數", 'value'=>"40");
$SFS_MODULE_SETUP[24] =
	array('var'=>"sday2", 'msg'=>"修業計算缺席節數", 'value'=>"234");
	
//$SFS_MODULE_SETUP[25] =
//	array('var'=>"ncount", 'msg'=>"開放統計平常成績", 'value'=>array("0"=>"否","1"=>"是"));

$SFS_MODULE_SETUP[26] =	array('var'=>"print_title", 'msg'=>"學期通知單印列標題", 'value'=>"學生學習行為表現學期通知單");
$SFS_MODULE_SETUP[27] =	array('var'=>"stud_chk_data", 'msg'=>"列印時預設勾選平常生活表現", 'value'=>array('0'=>'否','1'=>'是'));
$SFS_MODULE_SETUP[28] =	array('var'=>"stud_absent", 'msg'=>"列印時預設勾選出缺席", 'value'=>array('0'=>'否','1'=>'是'));
$SFS_MODULE_SETUP[29] =	array('var'=>"stud_leader", 'msg'=>"列印時預設勾選幹部資料", 'value'=>array('0'=>'否','1'=>'是'));
$SFS_MODULE_SETUP[30] =	array('var'=>"stud_reward", 'msg'=>"列印時預設勾選獎懲記錄", 'value'=>array('0'=>'否','1'=>'是'));
$SFS_MODULE_SETUP[31] =	array('var'=>"stud_club", 'msg'=>"列印時預設勾選社團活動", 'value'=>array('0'=>'否','1'=>'是'));
$SFS_MODULE_SETUP[32] =	array('var'=>"stud_service", 'msg'=>"列印時預設勾選服務學習", 'value'=>array('0'=>'否','1'=>'是'));
$SFS_MODULE_SETUP[33] =	array('var'=>"stud_race", 'msg'=>"列印時預設勾選競賽記錄", 'value'=>array('0'=>'否','1'=>'是'));

?>
