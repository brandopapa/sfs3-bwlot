<?php
//$Id:  $
//載入設定檔
require ("config.php");

// 認證檢查
sfs_check();

$UP_YEAR=($IS_JHORES==0)?6:$UP_YEAR=9;//判斷國中小

$postBtn = "臺中市就學管控系統轉出csv檔";
$class_name = class_base();
if ($_POST[do_key]==$postBtn){
	$curr_year =curr_year();
	$new_school_str=($_POST[curr_grade_school])?"and g.new_school= '$_POST[curr_grade_school]'":"";
	$str ="畢業學年度,年級,班級名稱,國籍,身分證字號,學生姓名,性別,出生年,出生月,出生日,入學年,畢業字號,監護人,聯絡電話,戶籍地址,升入國中,附記說明\r\n";
	//先抓取畢業生資料表
	$sql = "SELECT a.*,b.curr_class_num,b.stud_country,b.stud_person_id,b.stud_name,b.stud_sex,b.stud_birthday,b.stud_study_year,b.stud_addr_1,b.stud_tel_1,c.guardian_name FROM grad_stud a INNER JOIN stud_base b ON a.student_sn=b.student_sn INNER JOIN stud_domicile c ON a.student_sn=c.student_sn WHERE stud_grad_year='$curr_year' ORDER BY grad_num";
	$result =$CONN->Execute($sql) or user_error("讀取失敗！<br>$sql",256) ;    	
	
	while(!$result->EOF){
		//班級
		$c_name = $class_name[substr($result->fields[curr_class_num],0,-2)];
		$str.="\"".$curr_year."\",";
		$str.="\"".$result->fields['class_year']."\",";
		$str.="\"".$c_name."\",";
		$str.="\"".$result->fields['stud_country']."\",";
		$str.="\"".$result->fields['stud_person_id']."\",";
		$str.="\"".$result->fields['stud_name']."\",";
		$str.="\"".($result->fields['stud_sex']=='1'?'男':'女')."\",";
		$str.="\"".date('Y',$result->fields['stud_birthday'])."\",";
		$str.="\"".date('m',$result->fields['stud_birthday'])."\",";
		$str.="\"".date('d',$result->fields['stud_birthday'])."\",";
		$str.="\"".$result->fields['stud_study_year']."\",";
		$str.="\"".$result->fields['grad_word'].'第'.$result->fields['grad_num']."號\",";
		$str.="\"".$result->fields['guardian_name']."\",";
		$str.="\"".$result->fields['stud_tel_1']."\",";
		$str.="\"".$result->fields['stud_addr_1']."\",";
		$str.="\"".$result->fields['new_school']."\",";		
		$str.="\"\"\r\n";
		


		$result->MoveNext();
	}
	
	header("Content-disposition: attachment; filename=".$SCHOOL_BASE[sch_cname_ss].curr_year()."學年度畢業生資料轉出-臺中市就學管控系統.csv");
	header("Content-type: text/x-csv");
	//header("Pragma: no-cache");
				//配合 SSL連線時，IE 6,7,8下載有問題，進行修改 
				header("Cache-Control: max-age=0");
				header("Pragma: public");
	header("Expires: 0");

	echo $str;	
	exit;
}

head();
print_menu($menu_p);


?>


  <form name ="myform" action="<?php echo $PHP_SELF ?>" method="post" >
   
  <BR><input type="submit" name="do_key" value="<?php echo $postBtn ?>">
</form>

<?php
foot();
?>
