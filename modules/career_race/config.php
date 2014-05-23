<?php

// $Id: config.php 5310 2009-01-10 07:57:56Z smallduh $

	//系統設定檔
	include_once "./module-cfg.php";
	include_once "../../include/config.php";


	//模組更新程式
	require_once "./module-upgrade.php";
	  

//載入本模組的專用函式庫
include_once ('my_functions.php');

$level_array=array(1=>'國際',2=>'全國、臺灣區',3=>'區域性（跨縣市）',4=>'省、直轄市、縣',5=>'縣市區（鄉鎮）',6=>'校內');
$squad_array=array(1=>'個人賽',2=>'團體賽');

//取得模組變數, 並將陣列的 key 作為變數的名稱
//已設定 $rank_select 獲獎項目 
$m_arr = &get_module_setup("career_race");
extract($m_arr,EXTR_OVERWRITE);

if ($rank_select=='') $rank_select="第一名,冠軍,金牌,特優,第二名,亞軍,銀牌,優等,第三名,季軍,銅牌,甲等,第四名,殿軍,佳作,第五名,入選,第六名"; 
if ($nature_select=='') $nature_select='體育類,科學類,語文類,音樂類,美術類,舞蹈類,技藝類';
	
?>

