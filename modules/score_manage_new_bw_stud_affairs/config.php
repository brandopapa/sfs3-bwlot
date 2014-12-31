<?php
// $Id: config.php 6401 2011-03-30 05:50:15Z infodaes $

/*入學務系統設定檔*/
include "../../include/config.php";
include "../../include/sfs_case_PLlib.php";

//引入函數
include "./my_fun.php";
include "module-upgrade.php";

//選單
$menu_p = array("score_query.php"=>"成績查詢");
//取得模組設定
$m_arr = get_sfs_module_set();
extract($m_arr, EXTR_OVERWRITE);
?>