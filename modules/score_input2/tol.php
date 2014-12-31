<?php
// $Id: $
/*引入設定檔*/
include "config.php";

//使用者認證
sfs_check();

//取得教師所上年級、班級
$session_tea_sn = $_SESSION['session_tea_sn'] ;
$query =" select class_num  from teacher_post  where teacher_sn  ='$session_tea_sn'";
$result =  $CONN->Execute($query) or user_error("讀取失敗！<br>$query",256) ;
$row = $result->FetchRow() ;
$class_id=$row["class_num"];
$not_allowed="<CENTER><BR><BR><H2>您並非班級導師<BR>或者<BR>系統管理員尚未開放導師操作此功能!</H2></CENTER>";

if($class_id){
	$year_name=substr($class_id,0,-2);
	$me=substr($class_id,-2);
	
	$percision=$_POST['percision']?$_POST['percision']:1;

	//秀出網頁
	head("班級成績總表");

	//列出橫向的連結選單模組
	print_menu($menu_p);

	//設定主網頁顯示區的背景顏色
	$sel_year = curr_year(); //目前學年
	$sel_seme = curr_seme(); //目前學期

	$teacher_id=$_SESSION['session_log_id'];//取得登入老師的id

	$percision_radio="<font size=2 color='red'> ◎成績顯示的精度：";
	$percision_array=array('1'=>'整數','2'=>'小數1位','3'=>'小數2位');
	foreach($percision_array as $key=>$value){
		if($percision==$key) $checked='checked'; else $checked='';
		$percision_radio.="<input type='radio' value='$key' name='percision' $checked onclick='this.form.submit();'>$value";	
	}

	$menu="<form name=\"myform\" method=\"post\" action=\"$_SERVER[PHP_SELF]\">
		<table>
		<tr>
		<td>$percision_radio</td>
		</tr>
		</table></form>";
		
	echo $menu;

	//以上為選單bar

	/******************************************************************************************/
	$percision--;
	//取得學校資料
	$s=get_school_base();

	$sql="select subject_id,subject_name from score_subject where enable='1'";
	$rs=$CONN->Execute($sql);
	while(!$rs->EOF) {
		$subject_name[$rs->fields['subject_id']]=addslashes($rs->fields['subject_name']);
		$rs->MoveNext();
	}

	//092_2_01_01
	//$sql="select * from score_ss where class_id='".sprintf("%03s_%s_%02s_%02s",$sel_year,$sel_seme,$year_name,$me)."' and enable='1'";
	$sql="select s1.*, s2.subject_name from score_ss s1 left join score_subject s2 on s2.subject_id = s1.scope_id where s1.class_id='".sprintf("%03s_%s_%02s_%02s",$sel_year,$sel_seme,$year_name,$me)."' and s1.enable='1'";
	$rs=$CONN->Execute($sql) or trigger_error("sql 錯誤 $sql",E_USER_ERROR);
	if ($rs->RecordCount() ==0){
		//$sql="select * from score_ss where year='$sel_year' and semester='$sel_seme' and class_year='$year_name' and enable='1' and need_exam='1' and class_id='' order by sort,sub_sort";
		$sql="select s1.*, s2.subject_name from score_ss s1 left join score_subject s2 on s2.subject_id = s1.scope_id where s1.year='$sel_year' and s1.semester='$sel_seme' and s1.class_year='$year_name' and s1.enable='1' and s1.need_exam='1' and s1.class_id='' order by sort,sub_sort";
		$rs=$CONN->Execute($sql) or trigger_error("sql 錯誤 $sql",E_USER_ERROR);
	}

	$subject_list="";
	while (!$rs->EOF) {
		$id=$rs->fields['ss_id'];
		$scope_id=$rs->fields['scope_id'];
		$subject_id=$rs->fields['subject_id'];
		$ss_id[$id]=($subject_id==0)?$scope_id:$subject_id;
		$all_rate += $rs->fields['rate'];
		$s_rate[$id] = $rs->fields['rate'];
		
		if ($rs->fields['subject_name'] == "生活主軸") {
	  	$life_rate += $rs->fields['rate'];
	  	$s_life_rate[$id] = $rs->fields['rate'];
	  }	
	  else if ($rs->fields['subject_name'] == "生命主軸") {
	  	$live_rate += $rs->fields['rate'];
	  	$s_live_rate[$id] = $rs->fields['rate'];
	  }
	  else if ($rs->fields['subject_name'] == "基礎主軸") {
	    $basic_rate += $rs->fields['rate'];
	    $s_basic_rate[$id] = $rs->fields['rate'];
	  }
	  else{
	  	$main_rate += $rs->fields['rate'];
	  	$s_main_rate[$id] = $rs->fields['rate'];
	  }
	  		
		$subject_list.="<td width='60' align='center'><small>".stripslashes($subject_name[$ss_id[$id]])."</small></td>";
		$rs->MoveNext();
	}

	$seme_year_seme=sprintf("%03d%d",$sel_year,$sel_seme);
	//$sql="select student_sn,stud_id,seme_class_name,seme_num from stud_seme where seme_year_seme='$seme_year_seme' and seme_class='$class_id' order by seme_num,student_sn";
	$sql="select b.student_sn,b.stud_id,b.seme_class_name,b.seme_num from stud_base a,stud_seme b where a.stud_study_cond = 0 and a.student_sn=b.student_sn and b.seme_year_seme='$seme_year_seme' and b.seme_class='$class_id' order by curr_class_num";
	$rs=$CONN->Execute($sql);
	$all_sn="";
	$all_id="";
	$st_sn_all = array();
	while (!$rs->EOF) {
		$student_sn=$rs->fields['student_sn'];
		$st_sn_all[] = $student_sn;
		$stud_id[$student_sn]=$rs->fields['stud_id'];
		$stud_num[$student_sn]=$rs->fields['seme_num'];
		$stud_name[$student_sn]=$rs->fields['stud_name'];
		$seme_class_name[$student_sn]=$rs->fields['seme_class_name'];
		$all_sn.="'".$student_sn."',";
		$all_id.="'".$stud_id[$student_sn]."',";
		$rs->MoveNext();
	}

	$all_sn=substr($all_sn,0,-1);
	$all_id=substr($all_id,0,-1);
	$sql="select student_sn,stud_name,stud_study_cond from stud_base where student_sn in ($all_sn)";
	$rs=$CONN->Execute($sql);
	while (!$rs->EOF) {
		$stud_name[$rs->fields['student_sn']]=$rs->fields['stud_name'];
		$stud_cond[$rs->fields['student_sn']]=$rs->fields['stud_study_cond'];
		$rs->MoveNext();
	}
//	$sql="select student_sn,ss_id,ss_score from stud_seme_score where seme_year_seme='$seme_year_seme' and student_sn in($all_sn)";

    $sql="select s.student_sn,s.ss_id,s.ss_score,s2.subject_name from stud_seme_score s left join score_ss s1 on s1.ss_id = s.ss_id left join score_subject s2 on s2.subject_id = s1.scope_id where seme_year_seme='$seme_year_seme' and student_sn in($all_sn)";
	$rs=$CONN->Execute($sql);
	while (!$rs->EOF) {
		$stud_score[$rs->fields['student_sn']][$rs->fields['ss_id']]=$rs->fields['ss_score'];
		$stud_avg[$rs->fields['student_sn']] += ($rs->fields['ss_score']*$s_rate[$rs->fields['ss_id']]/$all_rate);
		
		if ($rs->fields['subject_name'] == "生活主軸" && $life_rate > 0) {
			$stud_avg_life[$rs->fields['student_sn']] += ($rs->fields['ss_score']*$s_life_rate[$rs->fields['ss_id']]/$life_rate);
		}
		else if ($rs->fields['subject_name'] == "生命主軸" && $live_rate > 0) {
			$stud_avg_live[$rs->fields['student_sn']] += ($rs->fields['ss_score']*$s_live_rate[$rs->fields['ss_id']]/$live_rate);
		}
		else if ($rs->fields['subject_name'] == "基礎主軸" && $basic_rate > 0) {
			$stud_avg_basic[$rs->fields['student_sn']] += ($rs->fields['ss_score']*$s_basic_rate[$rs->fields['ss_id']]/$basic_rate);
	  }
	  else {
	  	if  ($main_rate > 0){
				$stud_avg_main[$rs->fields['student_sn']] += ($rs->fields['ss_score']*$s_main_rate[$rs->fields['ss_id']]/$main_rate);
		  }
	  }		
		$rs->MoveNext();
	}

	$student_and_score_list="";
	for($k=0;$k<count($st_sn_all);$k++){
		reset($ss_id);
		if($stud_cond[$st_sn_all[$k]]) $bgcolor='#aaaaaa'; else $bgcolor='#c4d9ff';
		$student_and_score_list.="<tr bgcolor='$bgcolor' align='center'><td bgcolor='$bgcolor'>".$stud_num[$st_sn_all[$k]]."</td><td bgcolor='$bgcolor'>".$stud_name[$st_sn_all[$k]]."</td>";
		while(list($id,$subject_id)=each($ss_id)) {
			if($stud_score[$st_sn_all[$k]][$id]<60) $bgcolor='#ffcccc'; else if($stud_score[$st_sn_all[$k]][$id]<70) $bgcolor='#ddffff'; else if($stud_score[$st_sn_all[$k]][$id]<80) $bgcolor='#ffffcc'; else $bgcolor='#ffffff';
			$student_and_score_list.="<td bgcolor='$bgcolor'>".number_format($stud_score[$st_sn_all[$k]][$id],$percision)."</td>";
		}
		if($stud_avg[$st_sn_all[$k]]<60) $bgcolor='#ffcccc'; else if($stud_avg[$st_sn_all[$k]]<70) $bgcolor='#ddffff'; else if($stud_avg[$st_sn_all[$k]]<80) $bgcolor='#ffffcc'; else $bgcolor='#ffffff';
		$student_and_score_list.="<td bgcolor='#FFFF99'><p>".number_format($stud_avg[$st_sn_all[$k]],$percision)."</p></td>";
		
		if($stud_avg_main[$st_sn_all[$k]]<60) $bgcolor1='#ffcccc'; else if($stud_avg_main[$st_sn_all[$k]]<70) $bgcolor1='#ddffff'; else if($stud_avg_main[$st_sn_all[$k]]<80) $bgcolor1='#ffffcc'; else $bgcolor1='#ffffff';
		$student_and_score_list.="<td bgcolor='$bgcolor1'><p>".number_format($stud_avg_main[$st_sn_all[$k]],$percision)."</p></td>";
		
		if($stud_avg_life[$st_sn_all[$k]]<60) $bgcolor2='#ffcccc'; else if($stud_avg_life[$st_sn_all[$k]]<70) $bgcolor2='#ddffff'; else if($stud_avg_life[$st_sn_all[$k]]<80) $bgcolor2='#ffffcc'; else $bgcolor2='#ffffff';
		$student_and_score_list.="<td bgcolor='$bgcolor2'><p>".number_format($stud_avg_life[$st_sn_all[$k]],$percision)."</p></td>";
		
		if($stud_avg_live[$st_sn_all[$k]]<60) $bgcolor3='#ffcccc'; else if($stud_avg_live[$st_sn_all[$k]]<70) $bgcolor3='#ddffff'; else if($stud_avg_live[$st_sn_all[$k]]<80) $bgcolor3='#ffffcc'; else $bgcolor3='#ffffff';
		$student_and_score_list.="<td bgcolor='$bgcolor3'><p>".number_format($stud_avg_live[$st_sn_all[$k]],$percision)."</p></td>";
		
		if($stud_avg_basic[$st_sn_all[$k]]<60) $bgcolor4='#ffcccc'; else if($stud_avg_basic[$st_sn_all[$k]]<70) $bgcolor4='#ddffff'; else if($stud_avg_basic[$st_sn_all[$k]]<80) $bgcolor4='#ffffcc'; else $bgcolor4='#ffffff';
		$student_and_score_list.="<td bgcolor='$bgcolor4'><p>".number_format($stud_avg_basic[$st_sn_all[$k]],$percision)."</p></td>";
		
		$student_and_score_list.="</tr>\n";

		$main=" $print_msg
			<table border='2' cellpadding='3' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%'>
			<tr bgcolor='#c4d9ff' align='center'>
			<td width='30' align='center'><small>座號</small></td>
			<td width='90' align='center'><small>姓名</small></td>
			$subject_list
			<td bgcolor='#FFFF99'><span style='font-size:10pt;'>加權平均</span></td>
			<td bgcolor='#FFFF99'><span style='font-size:10pt;'>領域平均</span></td>
			<td bgcolor='#FFFF99'><span style='font-size:10pt;'>生活主軸平均</span></td>
			<td bgcolor='#FFFF99'><span style='font-size:10pt;'>生命主軸平均</span></td>
			<td bgcolor='#FFFF99'><span style='font-size:10pt;'>基礎主軸平均</span></td>
			</tr>
			$student_and_score_list
			</table>";
	}
	echo $main;
	//程式檔尾
	} else { echo $not_allowed; }
foot();

?>
