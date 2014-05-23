<?php

// $Id: config.php 6957 2012-10-22 08:32:28Z infodaes $

include "../../include/config.php";
include "../../include/sfs_case_PLlib.php";
include "../../include/sfs_case_studclass.php";
include "../../include/sfs_case_calendar.php";
include "../../include/sfs_case_dataarray.php";
include "../../include/sfs_case_module.php";
include "../../include/sfs_oo_zip2.php";
//取得模組設定

$MSETUP = &get_module_setup('class_things') ;
$is_absent =$MSETUP['is_absent'] ;
$course_input =$MSETUP['course_input'];
$influenza =$MSETUP['influenza'];
$is_sms =$MSETUP['is_sms'];
$is_rewrad =$MSETUP['is_rewrad'];


//---------------------------------------------------
// 這裡請引入您自己的函式庫
//---------------------------------------------------
include "my_fun.php";
include "absent_function.php";
include "module-cfg.php";
?>
