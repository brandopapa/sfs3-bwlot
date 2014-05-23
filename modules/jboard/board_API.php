<?php

// $Id: board_show.php 7779 2013-11-20 16:09:00Z smallduh $

// --系統設定檔
ini_set('memory_limit', '-1');

include	"board_config.php";
include_once "../../include/sfs_case_dataarray.php";

//echo $_GET['api_key'].";".$_GET['act'];;


if ($_GET['api_key']!=$api_key) {
  $row[1]="API Key 錯誤!";
}
//測試
if ($_GET['act']=='test') {
	$row="恭喜! 連線成功!";
  $row=base64_encode(addslashes($row));
  //$row[1]="ok!";
}



if ($_GET['act']=='GetPages') {
  	 //應傳入的條件
	 $bk_id=$_GET['bk_id'];
	 $page_count=$_GET['page_count'];  //每頁幾筆 
	 
	//檢查是否開放 jboard_kind 的 board_is_public=1
	
	//開始組合 sql
   $sql_select = "select b_id from jboard_p  where bk_id='$bk_id' ";
	 


			$sql_select.=" order by b_sort,b_open_date desc ,b_post_time desc ";
			$result = $CONN->Execute($sql_select) or die ($sql_select);
			$tol_num= $result->RecordCount($result);
				
			//計算頁數
			if ($tol_num % $page_count > 0 )
					$tolpage = intval($tol_num / $page_count)+1;
			else
					$tolpage = intval($tol_num / $page_count);
     
     $row=$tolpage;

}

//搜尋頁
if ($_GET['act']=='GetSearch') {
  $search_startday=$_GET['search_startday'];
  $search_endday=$_GET['search_endday'];
  $search_room=$_GET['search_room'];
  $search_teachertitle=$_GET['search_teachertitle'];
  $search_key=$_GET['search_key'];
  $search_limit=$_GET['search_limit'];
  $page_office=$_GET['page_office'];
  
  $sql_select="select * from jboard_p where b_open_date>='$search_startday' and b_open_date<='$search_endday'";
  
	//有限制處室
	if ($search_room!="") {
    $sql_select.=" and b_unit='$search_room'";	
	}
	//有限制職稱
	if ($search_teachertitle!="") {
    $sql_select.=" and b_title='$search_teachertitle'";	
	}		
	//有限制關鍵字
	if ($search_key!="") {
	 	$search_key=iconv("UTF-8","BIG5//IGNORE",$search_key);
	 	$sql_select.=" and b_sub like '%".addslashes($search_key)."%'";
	}
  //有限制搜尋範圍
	if ($search_limit=='1') {
	  $offices=explode(",",$page_office);
	  $sql_select2="";
		  foreach ($offices as $OFFICE) {
		   $sql_select2.="bk_id='".$OFFICE."' or ";
		  }
		  $sql_select2=substr($sql_select2,0,strlen($sql_select2)-4);
		  
		 $sql_select.=" and (".$sql_select2.")"; 
	}
 
 /*
  echo $sql_select;
  exit();
*/
  //完成 sql
  $sql_select.=" order by b_open_date desc limit 100";

  $result = $CONN->Execute($sql_select) or die ($sql_select);
  $ROW=$result->GetRows();
  //轉碼
  $row=array();
  foreach ($ROW as $k=>$v) {
        $row[$k]=array_base64_encode($v);
  }

} // end if GetSearch


//取得所有處室
if ($_GET['act']=='GetRooms') {
	/*處室陣列*/
	$ROOM=room_kind();
	$row=array_base64_encode($ROOM);
}

//取得所有職稱
if ($_GET['act']=='GetTeacherTitle') {
	/*職稱陣列*/
	$TEACHER_TITLE=title_kind();
	$row=array_base64_encode($TEACHER_TITLE);
}


if ($_GET['act']=='GetMarquee') {
		 $bk_id=$_GET['bk_id'];

		//跑馬燈 $html_link
        $query = "select b_id,b_sub,b_is_intranet,b_title from jboard_p where bk_id='$bk_id' and";        
        $query.=" b_is_marquee = '1' and ((to_days(b_open_date)+b_days > to_days(current_date())) or b_days=0);";
        $result = $CONN->Execute($query) or die($query);
        
        $ROW=$result->GetRows();
        
        //轉碼
       $row=array();
       foreach ($ROW as $k=>$v) {
          $row[$k]=array_base64_encode($v);
        }

}

if ($_GET['act']=='GetList' ) {
	
	 //應傳入的條件
	 $bk_id=$_GET['bk_id'];
	 $post_page=$_GET['post_page'];    //第幾頁
	 $page_count=$_GET['page_count'];  //每頁幾筆 
	 $search_key=$_GET['search_key'];  //有沒有索引條件
	 
	//檢查是否開放 jboard_kind 的 board_is_public=1
	
	//開始組合 sql
   $sql_select = "select a.*,b.board_name from jboard_p a,jboard_kind b where a.bk_id=b.bk_id and a.bk_id='$bk_id' ";
	 
	//有輸入條件	
			if ($search_key!="") {
			 $search_key=iconv("UTF-8","BIG5//IGNORE",$search_key);
			 $sql_select.=" and b_sub like '%".addslashes($search_key)."%'";
			}
			
			$sql_select.=" order by b_sort,b_open_date desc ,b_post_time desc ";

		//取出資料
			$sql_select .= " limit ".($post_page * $page_count).", $page_count";
			$result = $CONN->Execute($sql_select) or die ($sql_select);
            
      $ROW=$result->GetRows();
      //轉碼
      $row=array();
        foreach ($ROW as $k=>$v) {
          $row[$k]=array_base64_encode($v);
        }
            
}



//讀取一篇文章
if ($_GET['act']=='GetOne' and $_GET['b_id']!='') {
	$b_id= $_GET['b_id'];
	$query="update jboard_p set b_hints = b_hints+1 where b_id='$b_id' ";
	$res=$CONN->Execute($query);
	$query = "select  a.*,b.board_name from jboard_p a,jboard_kind b where a.bk_id=b.bk_id  and a.b_id='$b_id'";
	$result = $CONN->Execute($query);
	$row= $result->fetchRow();
	$row=array_base64_encode($row);
}

//讀取一篇文章中的附件列表
if ($_GET['act']=='GetFileNameList' and $_GET['b_id']!='') {
	$b_id= $_GET['b_id'];
	$query = "select new_filename,org_filename from jboard_files where b_id='$b_id'";
	$result = $CONN->Execute($query);
	$ROW= $result->GetRows();
      //轉碼
      $row=array();
        foreach ($ROW as $k=>$v) {
          $row[$k]=array_base64_encode($v);
        }
}


//讀取圖 , 不用轉碼
if ($_GET['act']=='GetImage' and $_GET['b_id']!="" and $_GET['name']!="") {	
	$name=$_GET['name'];
	$b_id=$_GET['b_id'];
	$query="select filetype,content from jboard_images where b_id='".$b_id."' and filename='".$name."'";
	$res=$CONN->Execute($query) or die($query);
	$row= $res->fetchRow();	
}

//讀取檔案 , 不用轉碼
if ($_GET['act']=='GetFile' and $_GET['b_id']!="" and $_GET['name']!="") {	
	$name=$_GET['name'];
	$b_id=$_GET['b_id'];
	$query="select org_filename,filetype,content from jboard_files where b_id='".$b_id."' and new_filename='".$name."'";
	$res=$CONN->Execute($query) or die($query);
	$row= $res->fetchRow();	
	$row['org_filename']=base64_encode(addslashes($row['org_filename']));
}


//送出
exit(json_encode($row));

//將陣列編碼
function array_base64_encode($arr) {
  $B_arr=array();
  foreach ($arr as $k=>$v) {
    $B_arr[$k]=base64_encode(addslashes($v));
  }
  	return $B_arr;
}

