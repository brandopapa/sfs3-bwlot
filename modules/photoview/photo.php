<?php
// $Id: photo.php 6344 2011-02-23 06:20:39Z infodaes $
  require "config.php";
  
  $showpage = $_GET['showpage'] ;
  $query = $_GET['query'] ;

  function geticon($dir){
     //取得第一張小圖

     global   $htmpath ,$savepath ,$big ;
     $picdir=$htmpath  . $dir ;
     $updir=$savepath  . $dir ;

   if ( is_dir($updir) ) {
 
     $dirs = dir($updir) ;
     $dirs ->rewind() ;
     while ( ($filelist = $dirs->read()) and !$stop_m) {
     	 if (($filelist!=".") && ($filelist!="..")){

     	   //windows      
     	   if (WIN_PHP_OS() ) {
     	       if (eregi("(.jpg|.jpeg|.png|.gif|.bmp)$", $filelist)) 
         	 $filelist_arr[] =$picdir."/" .$filelist ;
     	   }else {	
     	      //其他   	
         	if (strstr($filelist,'!!!_'))   	//縮小圖	
         	  $filelist_arr[] =$picdir."/" .$filelist ;
           }
         }
     }
     $dirs->close() ;  	
     sort ($filelist_arr) ;
     return $filelist_arr[0] ;
     
   }
  }
  
  if ($query) $do = "search" ;
  
  //讀取資料庫
  $sqlstr = "SELECT * FROM $tbname  " ;
  
  if ($do == "search") 
     $sqlstr =$sqlstr .  " where act_info like '%$query%' " ;
  $sqlstr .= " order by act_ID  DESC " ;  
  
  if ($debug ) echo $sqlstr ;

  //計算頁數
  $result = $CONN->Execute( $sqlstr) ;
  if ($result) {
    $totalnum =  $result->RecordCount() ;
    $totalpage = ceil( $totalnum / $pagesites) ;
    
    if (!$showpage)  $showpage =1 ;  

    $result = $CONN->PageExecute("$sqlstr", $pagesites , $showpage );
 
  }  
  if (!$totalpage) $totalpage= 1 ;
  
  for ($i = 1; $i <= $totalpage ; $i++) 
       $paper_list[$i]=$i ;
 
 	if($result) 
  		while ($nb=$result->FetchRow() ) {     
			$nb['pic'] = geticon( $nb["act_dir"] ) ;
			$data_list[] = $nb ;
		}	
			
	if ( $show_col<2)  $show_col =2 ;
//==============================================			
$template_dir = $SFS_PATH."/".get_store_path()."/templates";
$smarty->template_dir = $template_dir;
$smarty->assign("SFS_TEMPLATE",$SFS_TEMPLATE);
$smarty->assign("module_name","相片展管理");

 

$smarty->assign("data_list",$data_list);

$smarty->assign("PHP_SELF",basename($PHP_SELF));
 
$smarty->assign("session_tea_name",$session_tea_name);
$smarty->assign("login_id",$_SESSION['session_log_id']);
$smarty->assign("session_id",$_REQUEST["PHPSESSID"]);

$smarty->assign("totalpage",$totalpage);
$smarty->assign("showpage",$showpage);
$smarty->assign("paper_list",$paper_list);
$smarty->assign("show_col",$show_col);
$smarty->assign("prev_page",$showpage -1);
$smarty->assign("next_page",$showpage +1);

$smarty->assign("stand_alone",$stand_alone);
$smarty->assign("memo_pos",$memo_pos);

$view_title=$view_title?$view_title:'相片展';
$smarty->assign("view_title",$view_title);
$font_size=$font_size?$font_size:'12px';
$smarty->assign("font_size",$font_size);
$font_color=$font_color?$font_color:'$000000';
$smarty->assign("font_color",$font_color);
$smarty->assign("show_title",$show_title);
$smarty->assign("show_date",$show_date);
$smarty->assign("show_intro",$show_intro);
$smarty->assign("show_auth",$show_auth);
$smarty->assign("show_op",$show_op);


$smarty->display("photo.htm");
  
?>  