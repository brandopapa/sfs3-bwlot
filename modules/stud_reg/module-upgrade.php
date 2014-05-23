<?php
// $Id: module-upgrade.php 6877 2012-09-07 02:45:37Z hsiao $
if(!$CONN){
        echo "go away !!";
        exit;
}

// 檢查更新否
// 更新記錄檔路徑
$upgrade_path = "upgrade/".get_store_path($path);
$upgrade_str = set_upload_path("$upgrade_path");

$up_file_name =$upgrade_str."2004-11-29.txt";
if (!is_file($up_file_name)){
	$query="select * from stud_ext_data_menu where 1=0";
	if ($CONN->Execute($query))
		$temp_str = "學生補充資料選項表已存在, 無需升級。";
	else {
			
		$query=" CREATE TABLE if not exists  `stud_ext_data_menu` (
			`id` INT NOT NULL AUTO_INCREMENT ,
			`ext_data_name` VARCHAR( 50 ) NOT NULL ,
			`doc` TEXT,
			PRIMARY KEY ( `id` )
			) COMMENT = '學生補充資料選項' " ;	
		if ($CONN->Execute($query))
			$temp_str = "$query\n 學生補充資料表建立成功 ! \n";
		else
			$temp_str = "$query\n 學生補充資料表建立失敗 ! \n";

	}
	$query="select * from stud_ext_data where 1=0";
	if ($CONN->Execute($query))
		$temp_str = "學生補充資料選項表已存在, 無需升級。";
	else {
		
		$query=" CREATE TABLE if not exists stud_ext_data (
  			stud_id varchar(8) NOT NULL default '',
  			mid int(11) NOT NULL default '0',
  			ext_data text NOT NULL,
  			teach_id varchar(10) NOT NULL default '',
  			ed_date date NOT NULL default '0000-00-00',
  			update_time timestamp(14) NOT NULL,
  			PRIMARY KEY  (stud_id,mid)
			);" ;	
			
		if ($CONN->Execute($query))
			$temp_str = "$query\n 學生補充個人資料表建立成功 ! \n";
		else
			$temp_str = "$query\n 學生補充個人資料表建立失敗 ! \n";

	}
	$temp_query = "學生補充資料表格建立 -- by prolin (2004-9-25)\n\n$temp_str";
	$fp = fopen ($up_file_name, "w");
	fwrite($fp,$temp_query);
	fclose ($fp);
}

$up_file_name =$upgrade_str."2009-02-02.txt";
if (!is_file($up_file_name)){
	$query ="ALTER TABLE `stud_domicile` ADD `fath_grad_kind` TINYINT( 4 ) DEFAULT '1' AFTER `fath_education` , ADD `moth_grad_kind` TINYINT( 4 ) DEFAULT '1' AFTER `moth_education` ;";
	if ($CONN->Execute($query))
		$str="新增畢修業別欄位成功\";
	else
		$str="新增畢修業別欄位失敗";
	$temp_query = "於 stud_domicile 資料表新增父母畢修業別欄位以符合XML 3.0".$str." -- by infodaes 2009-02-02 \n$query";
	$fp = fopen ($up_file_name, "w");
	fwrite($fp,$temp_query);
	fclose ($fp);
}


$up_file_name =$upgrade_str."2009-07-28.txt";
if (!is_file($up_file_name)){
	$query ="ALTER TABLE stud_base ADD enroll_school VARCHAR(30);";
	if ($CONN->Execute($query))
	{
		$str="增加入學時學校欄位 SUCESSED";
	} else {
		$str="增加入學時學校欄位 FAILED";
	}
	$temp_query = "於 stud_base 資料表新增入學時學校欄位 以符合95格式學籍表  ".$str." -- by infodaes 2009-07-28 \n$query";
	$fp = fopen ($up_file_name, "w");
	fwrite($fp,$temp_query);
	fclose ($fp);
}


$up_file_name =$upgrade_str."2009-07-29.txt";
if (!is_file($up_file_name)){
	$query="SELECT student_sn,move_kind,school FROM stud_move WHERE move_kind=13";
	if($res=$CONN->Execute($query))
	{
		$str="將有入學紀錄的學生設為入學學校設定的學校 SUCESSED";
		while(!$res->EOF) {
			$student_sn=$res->fields['student_sn'];
			$enroll_school=$res->fields['school'];
			$CONN->Execute("UPDATE stud_base SET enroll_school='$enroll_school' WHERE student_sn=$student_sn");
			$res->MoveNext();
		}		
	} else {
		$str="將有入學紀錄的學生設為入學學校設定的學校 FAILED";
	}
	$temp_query = "將有入學紀錄的學生設為入學學校設定的學校 stud_base ==> enroll_school_XX ".$str." -- by infodaes 2009-07-29 \n$query";
	$fp = fopen ($up_file_name, "w");
	fwrite($fp,$temp_query);
	fclose ($fp);
}


$up_file_name =$upgrade_str."2012-09-07.txt";
if (!is_file($up_file_name)){
	$query="ALTER table stud_domicile MODIFY fath_email varchar(60), MODIFY moth_email varchar(60), MODIFY guardian_email varchar(60)";
	if($res=$CONN->Execute($query))
	{
		$str="修正父母電子郵件資料長度到varchar(60) SUCESSED";
		while(!$res->EOF) {
			$student_sn=$res->fields['student_sn'];
			$enroll_school=$res->fields['school'];
			$CONN->Execute("UPDATE stud_base SET enroll_school='$enroll_school' WHERE student_sn=$student_sn");
			$res->MoveNext();
		}		
	} else {
		$str="修正父母電子郵件資料長度到varchar(60) FAILED";
	}
	$temp_query = "修正父母電子郵件資料長度到varchar(60) SUCESSED stud_domicile ==> fath_email, moth_email, guardian_email Shengche Hsiao 2012-09-07 \n$query";
	$fp = fopen ($up_file_name, "w");
	fwrite($fp,$temp_query);
	fclose ($fp);
}


?>
