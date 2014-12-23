<?php
//$Id: check.php 5310 2009-01-10 07:57:56Z hami $
include "config.php";

//認證
sfs_check();

if (empty($_POST[year_seme])) {
	$sel_year = curr_year(); //目前學年
	$sel_seme = curr_seme(); //目前學期
	$year_seme=$sel_year."_".$sel_seme;
} else {
	$ys=explode("_",$_POST[year_seme]);
	$sel_year=$ys[0];
	$sel_seme=$ys[1];
}

$section1 = $_POST['section1'];
$section2 = $_POST['section2'];
$section3 = $_POST['section3'];
$section1_checked=($section1)?"checked":"";
$section2_checked=($section2)?"checked":"";
$section3_checked=($section3)?"checked":"";
$year_name = $_POST['year_name'];
//if ($_POST['year_name'] && ($section1 || $section1 || $section1) ) {
if ($year_name && ($section1 || $section1 || $section1) ) {
	$sel_score_period=$_POST['score_period'];
	//add by phoenix 20140221
	$section = '';
	if ($section1 && $section2 && $section3){		
		if ($sel_score_period == '181') {
  	   $score_period = 'score >= 180';
  	}
  	else{
  		$score_period = 'score < 180';
    }
    $section = 'a.test_sort in(1,2,3)';	
  }
	else if ( ($section1 && $section2 ) || ($section1 && $section3 ) || ($section2 && $section3 ) ){
		if ($sel_score_period == '121') {
  	   $score_period = 'score >= 120';
  	}
  	else{
  		$score_period = 'score < 120';
    }
    if ($section1 && $section2){	
      $section = 'a.test_sort in(1,2)';	
    }
    else if ($section2 && $section3){	
      $section = 'a.test_sort in(2,3)';	
    }
    else{
    	$section = 'a.test_sort in(1,3)';	
    }    	
  }
	else{
		if ($sel_score_period == '61') {
  	   $score_period = 'score >= 60';
  	}
  	else{
  		$score_period = 'score < 60';
    }	
    if ($section1){	
      $section = 'a.test_sort = 1';	
    }
    else if ($section2){	
      $section = 'a.test_sort = 2';	
    }
    else if ($section3){	
      $section = 'a.test_sort = 3';	
    }
    else{
    	$section = '';	
    }    	
  }
//  if ($sel_score_period == '100') {
//  	$score_period = 'a.score < 100 and a.score >= 90';
//  }
//  else if ($sel_score_period == '90') {
//  	$score_period = 'a.score < 90 and a.score >= 80';
//  }
//  else if ($sel_score_period == '80') {
//  	$score_period = 'a.score < 80 and a.score >= 70';
//  }
//  else if ($sel_score_period == '70') {
//  	$score_period = 'a.score < 70 and a.score >= 60';
//  }
  
 	//add by phoenix 20140905
//  $sel_section=$_POST['section'];
//  if ($sel_section != '0' && $sel_section != '') {
//     $section = 'and a.test_sort = '.$sel_section;
//  }
//  else{
//  	$section = '';
//  }	
	$seme_year_seme=sprintf("%03d",$sel_year).$sel_seme;
	$score_semester="score_semester_".$sel_year."_".$sel_seme;
	$query="select * from $score_semester where 1=0";
	$res=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
	if ($res) {
		//取學期學生資料
		$all_sn="";
		$query="select * from stud_seme where seme_year_seme='$seme_year_seme' and seme_class like '".$year_name."%'";
		$res=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
		while (!$res->EOF) {
			$all_sn.="'".$res->fields['student_sn']."',";
			$seme_data[$res->fields['student_sn']]['seme_class']=$res->fields['seme_class'];
			$seme_data[$res->fields['student_sn']]['seme_num']=$res->fields['seme_num'];
			$res->MoveNext();
		}
		if ($all_sn) $all_sn=substr($all_sn,0,-1);
		//取出符合成績篩選資料列		
		//$query="select a.class_id,a.student_sn,a.ss_id,a.test_kind,sum(a.score) as score from $score_semester a where $section and a.student_sn in ($all_sn) group by a.class_id,a.student_sn,a.ss_id,a.test_kind having $score_period order by a.class_id,a.student_sn,a.ss_id,a.test_kind";
		$query="select a.class_id,a.student_sn,a.ss_id,a.test_sort,sum(a.score) as score from $score_semester a where $section and a.student_sn in ($all_sn) group by a.class_id,a.student_sn,a.ss_id,a.test_sort having $score_period order by a.class_id,a.student_sn,a.ss_id,a.test_sort";
		$res=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
		$all_sn = '';
		$score_data_key = array();
		while (!$res->EOF) {
			//$all_sn.="'".$res->fields['student_sn']."',";
			$sn = $res->fields['student_sn'];
			$ss_id = $res->fields['ss_id'];
			$test_sort = $res->fields['test_sort'];
		  $query="select a.*,b.stud_name from $score_semester a left join stud_base b on b.student_sn=a.student_sn where a.student_sn = $sn and a.ss_id = $ss_id and a.test_sort = '$test_sort' and b.stud_study_cond in( 0, 15 ) order by a.student_sn,a.ss_id,a.test_sort,a.test_kind";
		  $res1=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
		  if ($res1->rowCount() > 0){
			  $index = $sn.'_'.$ss_id.'_'.$test_sort;		  
			  $score_data_key[] = $index; 
			  $score_data[$index] = $res1->GetRows();
		  }
			$res->MoveNext();
		}
		//if ($all_sn) $all_sn=substr($all_sn,0,-1);
		//$query="select a.*,b.stud_name from $score_semester a left join stud_base b on a.student_sn=b.student_sn where a.$score_period and $section and a.student_sn in ($all_sn) and b.stud_study_cond in( 0, 15 ) order by a.student_sn,a.ss_id,a.test_kind,a.test_sort";
		//$res=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
		//$score_data=$res->GetRows();
		//取科目中文名
		$query="select subject_id,subject_name from score_subject";
		$res=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
		while(!$res->EOF) {
			$subject_data[$res->fields['subject_id']]=$res->fields['subject_name'];
			$res->MoveNext();
		}
		//取課程設定資料
		$query="select ss_id,scope_id,subject_id from score_ss where year='$sel_year' and semester='$sel_seme' and class_year='".$year_name."' and enable=1 and print=1";
		$res=$CONN->Execute($query) or die('SQL執行錯誤:'.$query);
		while(!$res->EOF) {
			$scope_id=$res->fields['scope_id'];
			$subject_id=$res->fields['subject_id'];
			$subject_id=($subject_id=='0')?$scope_id:$subject_id;
			$ss_data[$res->fields['ss_id']]=$subject_id;
			$res->MoveNext();
		}
		$smarty->assign("seme_data",$seme_data);		 
		$smarty->assign("score_data_key",$score_data_key); 
		$smarty->assign("score_data",$score_data); 
		$smarty->assign("subject_data",$subject_data); 
		$smarty->assign("ss_data",$ss_data); 
	} else {
		$smarty->assign("err_msg","該學期無期中成績表！"); 
	}
}

$section1_str="<input type='checkbox' name='section1' $section1_checked onclick='this.form.submit()';>第1階段";
$section2_str="<input type='checkbox' name='section2' $section2_checked onclick='this.form.submit()';>第2階段";
$section3_str="<input type='checkbox' name='section3' $section3_checked onclick='this.form.submit()';>第3階段";

$smarty->assign("SFS_TEMPLATE",$SFS_TEMPLATE); 
$smarty->assign("module_name","成績預警分析"); 
$smarty->assign("SFS_MENU",$menu_p); 
$smarty->assign("year_seme_menu",year_seme_menu($sel_year,$sel_seme)); 
$smarty->assign("class_year_menu",class_year_menu($sel_year,$sel_seme,$_POST[year_name])); 
//$smarty->assign("section_menu",section_menu($_POST[section])); //add by phoenix 20140905
$smarty->assign("score_period_menu",score_period_menu2($_POST[score_period],"",$section1,$section2,$section3)); //add by phoenix 20140321
$smarty->assign("section1",$section1_str); //add by phoenix 20141107
$smarty->assign("section2",$section2_str); //add by phoenix 20141107
$smarty->assign("section3",$section3_str); //add by phoenix 20141107
$smarty->display("score_manage_new_check_score_notify.tpl");
?>