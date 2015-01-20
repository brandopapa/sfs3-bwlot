<?php
//$Id$
include_once "config.php";

//認證
sfs_check();

//秀出網頁布景標頭
head("校園MSN");
//主選單設定
//$school_menu_p=(empty($school_menu_p))?array():$school_menu_p;

//主要內容
 $smarty->assign("SFS_TEMPLATE",$SFS_TEMPLATE); //sfs路徑 
 $smarty->assign("SFS_PATH_HTML",$SFS_PATH_HTML); //sfs HTML
 $smarty->assign("SFS_MENU",$MODULE_MENU); //選單變數

 $smarty->display('msn_index.tpl'); 


//佈景結尾
foot();
?>
