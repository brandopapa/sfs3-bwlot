<?php
//$Id: PHP_tmp.html 5310 2009-01-10 07:57:56Z hami $
include "config.php";
include "../../include/sfs_case_studclass.php";
//認證
sfs_check();
$op = $_REQUEST['op'];
//執行動作判斷
switch($op){
	case "print_this_seme_all_school":
		$seme = $_REQUEST['Y'];
		$sql_select = "select a.student_sn from chc_mend a,stud_base b where a.student_sn=b.student_sn and seme='{$seme}' group by student_sn order by b.curr_class_num";
		$recordSet=$CONN->Execute($sql_select) or user_error($sql_select, 256);
		while(!$recordSet->EOF){
			list($student_sn)=$recordSet->FetchRow();
			$mend_score = one_seme_mend_score($student_sn,$seme);
			$print_area .= mend_table($student_sn,$mend_score)."<p style='page-break-after:always'></p>";
		}
		$print_area = substr($print_area,0,-39);
	break;
	case "print_this_seme_this_grade":
		$seme = $_REQUEST['Y'];
		$students_sn =$_REQUEST['students_sn'];
		foreach($students_sn as $value){
			$mend_score = one_seme_mend_score($value,$seme);
			if(!empty($mend_score)){
				$print_area .= mend_table($value,$mend_score)."<p style='page-break-after:always'></p>";
			}
		}
		$print_area = substr($print_area,0,-39);
	break;
	/*
	case "print_all_seme_this_class":
		$students_sn =$_REQUEST['students_sn'];
		foreach($students_sn as $value1){
			$semes = get_semes($value1);
			foreach($semes as $value2){
				$mend_score .= one_seme_mend_score($value1,$value2);
			}
			$print_area .= mend_table($value1,$mend_score)."<p style='page-break-after:always'></p>";
			//清空記分
			$mend_score="";
		}
		$print_area = substr($print_area,0,-39);
	break;
	*/
	case "print_all_seme_this_stud":
		$student_sn =$_REQUEST['student_sn'];
		$semes = get_mend_semes($student_sn);
		
		foreach($semes as $value){
			$mend_score .= one_seme_mend_score($student_sn,$value);
		}
		$print_area = mend_table($student_sn,$mend_score);
	break;
	case "print_this_seme_this_stud":
		$student_sn =$_REQUEST['student_sn'];
		$seme = $_REQUEST['Y'];
		$mend_score = one_seme_mend_score($student_sn,$seme);
		$print_area = mend_table($student_sn,$mend_score);
	break;
	case "print_this_seme_sel_student":
		$seme = $_REQUEST['Y'];
		$students_sn =$_REQUEST['sel_student_sn'];
		foreach($students_sn as $value){
			$mend_score = one_seme_mend_score($value,$seme);
			if(!empty($mend_score)){
				$print_area .= mend_table($value,$mend_score)."<p style='page-break-after:always'></p>";
			}
		}
		$print_area = substr($print_area,0,-39);
	break;
}

//單學期補考成績
function one_seme_mend_score($student_sn,$seme){
	global $CONN;
	$cht_scope=array(1=>"語文領域","數學領域","自然與生活科技領域","社會領域","健康與體育領域","藝術與人文領域","綜合領域");
	//取此學期有幾科補考
	$num = get_mend_num($student_sn,$seme);
	//取學期中文名
	$cht_ys = get_cht_ys($seme);
	
	$sql_select = "select scope,score_end,seme from chc_mend where student_sn='{$student_sn}' and seme='{$seme}'";
	$recordSet=$CONN->Execute($sql_select) or user_error($sql_select, 256);
	$i = 1;
	while(!$recordSet->EOF){
		list($scope,$score_end)=$recordSet->FetchRow();
			if($i==1){
				$table .= "<tr bgcolor='#FFFFFF'><td rowspan={$num}>{$cht_ys}</td><td>{$cht_scope[$scope]}</td><td>{$score_end}</td></tr>";
			}else{
				$table .= "<tr bgcolor='#FFFFFF'><td>{$cht_scope[$scope]}</td><td>{$score_end}</td></tr>";
			}
			$i++;
	}

	return $table;
}

//表格
function mend_table($student_sn,$mend_score){
	global $school_short_name;
	$cht_study_cond=array("在籍","轉出","轉入","中輟復學","休學復學","畢業","休學","出國","調校","升級","降級","死亡","中輟","新生入學","轉學復學","在家自學");
	
	$stud_base = get_stud_base($student_sn,"");
	$cht_class = class_id2big5(substr($stud_base['curr_class_num'],0,3),curr_year(),curr_seme());
	$num = substr($stud_base['curr_class_num'],3,2);
	$cht_class_num = $cht_class.$num."號";
	$today = date("Y-m-d");
	
	$table="
	<table cellPadding='0' border=0 cellSpacing='0' width='90%' align=center style='border-collapse:collapse;font-size:12pt;line-height:16pt'>
	<tr><td colspan=8 align=center><H3>{$school_short_name} 學習領域補考成績證明</H3><BR></td></tr>
	<TR align=right>
	<TD width=10>&nbsp;</TD>
	<TD >學生姓名：</TD>
	<TD align=left><B><U>{$stud_base['stud_name']}</U></B></TD>
	<TD align=left>出生年月日：<U><B>{$stud_base['stud_birthday']}</B></U></TD>
	<TD>&nbsp;</TD>
	<TD align=left>學&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;號：<B><U>{$stud_base['stud_id']}</U></B></TD>
	<TD>&nbsp;</TD>
	</TR>
	<TR align=right>
	<TD width=10>&nbsp;</TD>
	<TD>目前年班：</TD>
	<TD align=left>{$cht_class_num}</TD>
	<TD align=left>就 學 狀 態 ：{$cht_study_cond[$stud_base['stud_study_cond']]}</TD>
	<TD>&nbsp;</TD>
	<TD align=left>開立日期：{$today}</TD>
	<TD>&nbsp;</TD>
	</TR>
	</table>
	<br>
	<div align=center>
	<table  style='text-align: left;border-collapse:collapse' border='1' cellspacing='2' cellpadding='2'>
	<tr bgcolor='#FFFFFF'><td width=200>學期</td><td width=200>領域名稱</td><td width=200>採記成績</td></tr>
	{$mend_score}
	</table>
	</div>
	</br>
	<table cellPadding='0' border=0 cellSpacing='0' width='90%' align=center >
	<tr style='font-size:12pt;line-height:20pt'  align=center >
	<td width=33%>承辦：</td>
	<td width=33%>主任：</td>
	<td width=34%>校長：</td>
	</tr>
	</table>
	";
	return $table;
}
//查某生在哪幾個學期有補考記錄
function get_semes($student_sn){
	global $CONN;
	$sql_select = "select seme from chc_mend where student_sn='{$student_sn}' group by seme";
	$recordSet=$CONN->Execute($sql_select) or user_error($sql_select, 256);
	$i=0;
	while(!$recordSet->EOF){
		list($semes[$i])=$recordSet->FetchRow();
		$i++;
	}
	return $semes;
}

//查某生在某學期有幾個領域要補考$seme為4碼
function get_mend_num($student_sn,$seme){
	global $CONN;
	$seme_select=(!empty($seme))?"and seme='{$seme}'":"";
	$sql_select = "select count(*) from chc_mend where student_sn='{$student_sn}' {$seme_select}";
	$recordSet=$CONN->Execute($sql_select);
	list($num)=$recordSet->FetchRow();
	return $num;
}

//學期轉換成中文
function get_cht_ys($ys){
	$cht_year = explode("_",$ys);
	$cht_seme = ($cht_year[1]==1)?"上":"下";
	$cht_ys = $cht_year[0]."學年".$cht_seme."學期";
	return $cht_ys;
}

function get_mend_semes($student_sn){
	global $CONN;
	$sql_select = "select seme from chc_mend where student_sn='{$student_sn}' group by seme";
	$recordSet=$CONN->Execute($sql_select) or user_error($sql_select, 256);
	while(!$recordSet->EOF){
		list($seme)=$recordSet->FetchRow();
		$semes[]=$seme;
	}
	return $semes;
}
?>

<html>
<title>補考成績證明</title>
<body onload='window.print()'>
<?php
if(empty($print_area)) $print_area = "無任何資料";
echo $print_area;
?>
</body>
</html>