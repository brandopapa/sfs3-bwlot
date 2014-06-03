<?php

// $Id: config.php 6616 2011-11-08 08:09:01Z infodaes $
include "../../include2/config.php";
include "../../include2/sfs_oo_overlib.php";
include "../../include2/sfs_case_PLlib.php";
include "../../include2/sfs_case_subjectscore.php";
include "module-upgrade.php";

//取得模組設定
$m_arr = get_sfs_module_set();
extract($m_arr, EXTR_OVERWRITE);

if(!$is_new_nor) $is_new_nor='y';
if(!$is_mod_nor) $is_mod_nor='y';

//列出橫向的連結選單模組
$year_name = get_teach_class();
$menu_p = array("stud_list.php"=>"名冊列印","group_stud_list.php"=>"分組班名冊列印","normal.php"=>"平時成績", "manage2.php"=>"管理學期成績","write_memo.php"=>"努力程度文字編修","tol.php"=>"班級學期成績","make.php"=>"套用自訂成績單","upload.php"=>"上傳成績單","stick.php"=>"成績貼條");

if ($is_print=="y" or $year_name) $menu_p["print_tol.php"]="顯示階段成績";
if ($year_name != '') $menu_p["../academic_record2/"]="製作成績單 ^";
$menu_p["test.php"]="使用說明";

function stud_class_err() {
	echo "<center><h2>本項作業須具導師資格</h2>";
	echo "<h3>若有疑問請洽 系統管理員</h3></center>";
}

//在學學生編碼 0:在籍, 15:在家自學
$in_study="'0'";

//建立學科能力分組資料表
function creat_elective(){
global $CONN;
$creat1="
CREATE TABLE `elective_tea` (
  `group_id` int(11) NOT NULL auto_increment,
  `group_name` varchar(40) NOT NULL default '',
  `ss_id` int(11) NOT NULL default '0',
  `teacher_sn` int(11) NOT NULL default '0',
  `member` tinyint(3) unsigned NOT NULL default '0',
  `open` set('是','否') NOT NULL default '否',
  PRIMARY KEY  (`group_id`),
  UNIQUE KEY `group_name` (`group_name`,`ss_id`,`teacher_sn`)
)  AUTO_INCREMENT=1 ;";

$creat2="
CREATE TABLE `elective_stu` (
  `elective_stu_sn` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `student_sn` int(11) NOT NULL default '0',
  PRIMARY KEY  (`elective_stu_sn`),
  UNIQUE KEY `ss_id` (`group_id`,`student_sn`)
)  AUTO_INCREMENT=1 ;";

$s1="select * from elective_tea where 1=0";
$r1=$CONN->Execute($s1);
if(!$r1) $CONN->Execute($creat1) or trigger_error("無法自動建立elective_tea資料表\n<br>請將以下語法以手動建立\n<br>$creat1",256);

$s2="select * from elective_stu where 1=0";
$r2=$CONN->Execute($s2);
if(!$r2) $CONN->Execute($creat2) or trigger_error("無法自動建立elective_stu資料表\n<br>請將以下語法以手動建立\n<br>$creat2",256);


return 0;
}
?>
