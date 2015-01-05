<?php
//$Id: query.php 6804 2012-06-22 07:57:21Z smallduh $
include "config.php";

$smarty->assign("book_status_arr",array("0"=>"架上","1"=>"出借"));
if ($_POST[next])
	$_POST[start_num]+=$_POST[num];

if ($_GET[act]=="display") {
	$query="select * from book where book_id='$_GET[book_id]'";
	$res=$CONN->Execute($query);
	$book_name=$res->fields[book_name];
	$smarty->assign("data_arr",$res->GetRows());
	$query="select * from book where TRIM(book_name)='$book_name'";
	$res=$CONN->Execute($query);
	$smarty->assign("oth_data_arr",$res->GetRows());
	$smarty->display("book_query_display.tpl");
} elseif ($_POST[query]) {
//判斷按鈕狀況
	switch($_POST[query]) {
		case "pre_est":
			$_POST[start_num]=0;
			break;
		case "pre":
			if ($_POST[start_num]-$_POST[num] >= 0) $_POST[start_num]-=$_POST[num];
			break;
		case "next":
			$_POST[start_num]+=$_POST[num];
			break;
		case "query":
			$_SESSION["str"]="";
			break;
	}
//判斷session中是否存在查詢str
	$str=$_SESSION["str"];
	if ($str=="") {
		//session_register("str");
		if (trim($_POST[content_1])!="" && $_POST[sel_1]!="")
			$str="and INSTR(".$_POST[sel_1].",'".$_POST[content_1]."')>0 ";
		if (trim($_POST[content_2])!="" && $_POST[sel_2]!="")
			$str.=sprintf("% 3s",$_POST[logic_1])." INSTR(".$_POST[sel_2].",'".$_POST[content_2]."')>0 ";
		if (trim($_POST[content_3])!="" && $_POST[sel_3]!="")
			$str.=sprintf("% 3s",$_POST[logic_2])." INSTR(".$_POST[sel_3].",'".$_POST[content_3]."')>0";
		$in_str=1;
	}
//執行查詢
	if (strlen($str)>0) {
		if ($in_str) {
			$str=substr($str,3);
			$_SESSION["str"]=$str;
		}
		$query="select * from book where $str";
		$res=$CONN->Execute($query);
		$d=$res->GetRows();
		$smarty->assign("data_arr",$d);
		$smarty->assign("data_num",ceil(($_POST[start_num]-1)/$_POST[num])+1);
		$smarty->assign("data_nums",ceil(count($d)/$_POST[num]));
		$smarty->display("book_query_list.tpl");
	}
} else {
	$smarty->assign("sel_arr",array("book_name"=>"書名","book_author"=>"作者","book_maker"=>"出版者","book_id"=>"分類號","book_isbn"=>"ISBN","book_myear"=>"確認出版年月"));
	$smarty->assign("logic_arr",array("and"=>"AND","or"=>"OR","not"=>"NOT"));
	$smarty->assign("num_arr",array("10"=>"10","20"=>"20","30"=>"30","50"=>"50","100"=>"100"));
	$smarty->display("book_query_query.tpl");
}
?>