<?php
// $Id: module-upgrade.php 5310 2009-01-10 07:57:56Z hami $

if(!$CONN){
	echo "go away !!";
	exit;
}

// 更新記錄檔路徑
$upgrade_path = "upgrade/".get_store_path($path);
$upgrade_str = set_upload_path("$upgrade_path");
$up_file_name =$upgrade_str."2008-11-02.txt";
if (!is_file($up_file_name)){
	$query ="CREATE TABLE `stud_kind_group` (
  `sn` int(11) NOT NULL auto_increment,
  `description` varchar(40) NOT NULL,
  `kind_list` varchar(100) NOT NULL,
  PRIMARY KEY  (`sn`),
  KEY `description` (`description`)
);";
	if ($CONN->Execute($query))
		$str="成功\";
	else
		$str="失敗";
	$temp_query = "增加stud_kind_group資料表".$str." -- by infodaes (2008-11-02)\n$query";
	$fp = fopen ($up_file_name, "w");
	fwrite($fp,$temp_query);
	fclose ($fp);
}
?>
