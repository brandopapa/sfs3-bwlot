<?php

// $Id: function.php 7726 2013-10-28 08:15:30Z smallduh $

//取得該班課程節數
function get_class_cn($class_id=""){
	global $CONN;
	//取得某班學生陣列
	$c=class_id_2_old($class_id);
	
	//取得該班有幾節課
	$sql_select = "select sections from score_setup where year = '$c[0]' and semester='$c[1]' and class_year='$c[3]'";
	$recordSet=$CONN->Execute($sql_select) or trigger_error("SQL語法錯誤： $sql_select", E_USER_ERROR);
	list($all_sections) = $recordSet->FetchRow();
	return $all_sections;
}

//取得某一學生某月的各種缺曠課累積次數
function getOneMdata($stud_id,$sel_year,$sel_seme,$date,$class_id,$grade_group_id,$mode="",$start_date=""){
	global $CONN;
	$start_str=(empty($start_date))?" and year='$sel_year' and semester='$sel_seme'":"and date >= '$start_date'";

	if ($grade_group_id != "grade_group_1" && $grade_group_id != "grade_group_2"){//全部
	  $sql_select="select section, absent_kind from stud_absent where stud_id='$stud_id' $start_str and date <= '$date'";
	}
	else{
		$course_sql = "";
		if ($grade_group_id == "grade_group_1"){//領域科目缺席記錄
			$course_sql= "select course_id, day, sector from score_course where class_id = '$class_id'
			 and ss_id in(
			     select ss_id from score_ss where
			      concat(year,'_',semester,'_0',class_year) = substring('$class_id',1,8) 
			      and scope_id in
			      (
			        select subject_id from score_subject where subject_name in ( '數學', '語文', '自然與生活科技', '社會', '健康與體育', '藝術與人文', '綜合活動')
			      ) 
			     ) order by day,sector";
		}
		else{//非領域科目缺席記錄
		  $course_sql= "select course_id, day, sector from score_course where class_id = '$class_id'
		   and ss_id in(
		     select ss_id from score_ss where
		      concat(year,'_',semester,'_0',class_year) = substring('$class_id',1,8) 
		      and scope_id in
		      (
		        select subject_id from score_subject where subject_name in ( '基礎主軸', '生命主軸', '生活主軸', '彈性課程')
		      ) 
		     ) order by day,sector";
					
		}
		$rs_course=$CONN->Execute($course_sql) or trigger_error("錯誤訊息： $course_sql", E_USER_ERROR);
		$sql_condition = "and (";
		$tmp = ""; 
		while(!$rs_course->EOF){
			$my_day    = $rs_course->fields['day'];
			$my_sector = $rs_course->fields['sector'];
			if( $tmp != $my_day ){
				$tmp = $my_day; 
				if($sql_condition == "and ("){
					$sql_condition.=" (DATE_FORMAT(date,'%w') = $my_day and section in ($my_sector";
				}
				else{
					$sql_condition.=")) or (DATE_FORMAT(date,'%w') = $my_day and section in ($my_sector";
				}
			}
			else{
				$sql_condition.=",".$my_sector;
			}
			$rs_course->MoveNext();      
		}
		//if($sql_condition != "and ("){
		  $sql_condition.=")))";		  
		//}
		$sql_select="select section, absent_kind from stud_absent where stud_id='$stud_id' $start_str and date <= '$date' ".$sql_condition; 
	}

//	$sql_select="select section, absent_kind from stud_absent where stud_id='$stud_id' $start_str and date <= '$date'";
	//print $sql_select;
	$recordSet=$CONN->Execute($sql_select) or user_error("讀取失敗！<br>$sql_select",256);
	while(list($section,$kind)=$recordSet->FetchRow()){
		if($mode=="種類"){
			$n=($section=="allday")?8:1;
			$m=($section=="allday")?2:1;
			if ($kind=="曠課" && ($section=="uf" || $section=="df")) $theData[f]+=$m;
			if ($section!="uf" && $section!="df") $theData[$kind]+=$n;
		}else{
			$theData[$section]+=1;	
		}
		
	}
	return $theData;
}

//取得某一學生某日的各種缺曠課次數
function getOneDdata($stud_id,$date){
	global $CONN;
	$sql_select="select section, absent_kind from stud_absent where stud_id='$stud_id' and date='$date'";
	$recordSet=$CONN->Execute($sql_select) or user_error("讀取失敗！<br>$sql_select",256);
	while(list($section,$kind)=$recordSet->FetchRow()){
		$theData[$section]+=1;		
	}
	return $theData;
}

//取得某一筆資料
function getOneDaydata($stud_id,$year,$month,$day){
	global $CONN;
	$sql_select="select section, absent_kind from stud_absent where stud_id='$stud_id' and date='$year-$month-$day'";
	$recordSet=$CONN->Execute($sql_select) or user_error("讀取失敗！<br>$sql_select",256);
	while(list($section,$kind)=$recordSet->FetchRow()){
		$theData[$section]=$kind;
	}
	return $theData;
}

//統計當學期總出缺席紀錄
//請先取得$seme_day[start]=開學日,$seme_day[end]=學期結束日
function sum_abs($sel_year,$sel_seme,$stud_id) {
	global $CONN,$abskind;

	$seme_year_seme=sprintf("%03d",$sel_year).$sel_seme;
	$sql="select * from school_day where year='$sel_year' and seme='$sel_seme'";
	$rs=$CONN->Execute($sql);
	while (!$rs->EOF) {
		$seme_day[$rs->fields['day_kind']]=$rs->fields['day'];
		$rs->MoveNext();
	}
	$sql="select seme_class from stud_seme where seme_year_seme='$seme_year_seme' and stud_id='$stud_id'";
	$rs=$CONN->Execute($sql);
	$seme_class=$rs->fields['seme_class'];
	$class_year=substr($seme_class,0,-2);
	$sql="select sections from score_setup where year='$sel_year' and semester='$sel_seme' and class_year='$class_year'";
	$rs=$CONN->Execute($sql);
	$all_sections=$rs->fields['sections'];
	$sql="select *,DATE_FORMAT(date,'%w') as date_w from stud_absent where year='$sel_year' and semester='$sel_seme' and date>='".$seme_day[st_start]."' and date<='".$seme_day[st_end]."' and stud_id='$stud_id'";
	$rs=$CONN->Execute($sql);
	while (!$rs->EOF) {
		$abs_kind=$rs->fields['absent_kind'];
		$section=$rs->fields['section'];
		$date_w=$rs->fields['date_w'];
		$fg = ($date_w == '0' || $date_w == '6');//週日 或 週六			
		//echo $abs_kind."=".$section."<br>";
		if ($section=='uf' || $section=='df') {
			if ($abs_kind=='曠課') {
			  $all_abs[4]++;
			  if (!$fg) {
			  	$all_abs_5day_7section[4]++;
			  }
			}
		} else {
			$add_day=($section=='allday')?$all_sections:1;			
			if ( ($section=='allday') && (!$fg) ){
				$add_day_5day_7section=7;
			}
			else{
				$add_day_5day_7section=1;
			}
			//$add_day_5day_7section=($section=='allday' && (!$fg))?$7:1;
			if ($abskind[$abs_kind]!=""){
				$all_abs[$abskind[$abs_kind]]+=$add_day;
				if (!$fg) {
					$all_abs_5day_7section[$abskind[$abs_kind]]+=$add_day_5day_7section;
				}
			}	
			else{
				$all_abs[6]+=$add_day;
				if (!$fg) {
					$all_abs_5day_7section[6]+=$add_day_5day_7section;
				}
			}	
		}
		$rs->MoveNext();
	}
	$sql="select * from stud_seme_abs where seme_year_seme='$seme_year_seme' and stud_id='$stud_id'";
	$rs=$CONN->Execute($sql);
	while (!$rs->EOF) {
		$h_abs[$rs->fields['abs_kind']]=$rs->fields['abs_days'];
		$rs->MoveNext();
	}
	for ($i=1;$i<=6;$i++) {
		if ($h_abs[$i] != $all_abs[$i]) {
			if ($h_abs[$i]!="")
				//$sql="update stud_seme_abs set abs_days='$all_abs[$i]' where seme_year_seme='$seme_year_seme' and stud_id='$stud_id' and abs_kind='$i'";
				$sql="update stud_seme_abs set abs_days='$all_abs[$i]',abs_days_5day_7section='$all_abs_5day_7section[$i]' where seme_year_seme='$seme_year_seme' and stud_id='$stud_id' and abs_kind='$i'";
			else 
				//$sql="insert into stud_seme_abs (seme_year_seme,stud_id,abs_kind,abs_days) values ('$seme_year_seme','$stud_id','$i','$all_abs[$i]')";
				$sql="insert into stud_seme_abs (seme_year_seme,stud_id,abs_kind,abs_days,abs_days_5day_7section) values ('$seme_year_seme','$stud_id','$i','$all_abs[$i]','$all_abs_5day_7section[$i]')";
			$CONN->Execute($sql);
			//echo $sql."<br>";
		}
	}
}
//檢查今天有沒有上課
function chk_school_day($day) {
	global $CONN,$seme_year_seme;
	$sql="select day from school_work_day where day='$day'";
	$res=$CONN->Execute($sql);
	if ($res->RecordCount()>0) {
	  return 1;
	} else {
		return 0;
	}
}
?>
