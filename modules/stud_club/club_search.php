<?php

// $Id: reward_one.php 6735 2012-04-06 08:14:54Z smallduh $

//取得設定檔
include_once "config.php";

sfs_check();


//秀出網頁
head("社團活動 - 查詢學生參加的社團");

$tool_bar=&make_menu($school_menu_p);

//列出選單
echo $tool_bar;

if ($_SESSION['session_who'] != "教師") {
	echo "很抱歉！本功能模組為教師專用！";
	exit();
}



//取得目前學年度
$curr_year=curr_year();
$curr_seme=curr_seme();

//目前選定學期
$c_curr_seme=sprintf('%03d%1d',$curr_year,$curr_seme);

echo "暫停開放！";


?>