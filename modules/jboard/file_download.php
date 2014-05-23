<?php
// 將圖檔從資料庫取出後秀出
// 這段程式的使用方式為 <img src="img_show.php">
// 這樣才能應用在其他網頁上
// 程式開始
include_once('board_config.php');
$name=$_POST['filename'];
$b_id=$_POST['b_id'];
$query="select org_filename,filetype,content from jboard_files where b_id='".$b_id."' and new_filename='".$name."'";
$res=$CONN->Execute($query);
$org_filename=$res->fields['org_filename'];
$filetype=$res->fields['filetype'];
$content=$res->fields['content'];
$content=stripslashes(base64_decode($content));

	header("Content-disposition: attachment; filename=$org_filename");
	header("Content-type: $filetype");
	//header("Pragma: no-cache");
	//配合 SSL連線時，IE 6,7,8下載有問題，進行修改，取消 no-cache
	header("Cache-Control: max-age=0");
	header("Pragma: public");
	header("Expires: 0");

 echo $content;

// 程式結束
?>