<?php

// $Id: show.php 7751 2013-11-08 08:39:16Z infodaes $

include "config.php";
$stud_id = $_GET['stud_id'];
$der = $_GET['der'];
$cita_year = $_GET['cita_year'];

if($der=="") $der="up_date desc";

    $class_base_p = class_base();
    $sqlstr =" select stud_name  from stud_base where stud_id = '$stud_id' and ($cita_year-stud_study_year<9)" ;
    $result = $CONN->Execute($sqlstr) or user_error("讀取失敗！<br>$sqlstr",256) ; 
    $row = $result->FetchRow() ;          
      $stud_name = $row["stud_name"];     
 
echo " <p align=center><font size=5 color=red>$stud_name 的榮譽榜</font>　　<a href='list.php'>回目錄</a></p>";
      //標題行
    
	echo "<table cellSpacing=0 cellPadding=4 width='100%' align=center border=1 bordercolor='#33CCFF' bgcolor='#CCFFFF'>
          <tr bgcolor='#66CCFF' align=center> 
            <td ><a href=$PHP_SELF?stud_id=$stud_id&der=grada,up_date>▲</a>項目<a href=$PHP_SELF?stud_id=$stud_id&der=grada%20desc,up_date>▼</a></td>
		<td >成績</td><td >年班</td>
		<td ><a href=$PHP_SELF?stud_id=$stud_id&der=up_date>▲</a>日期<a href=$PHP_SELF?stud_id=$stud_id&der=up_date%20desc>▼</a></td></tr>";              

  
    //班上報名資料
	
     $sqlstr ="select * from cita_data a inner join cita_kind b on a.kind=b.id where ( a.stud_id = '$stud_id' and a.order_pos>-1) order by $der  " ;

	//echo $query ;
    $result = $CONN->Execute($sqlstr) or user_error("讀取失敗！<br>$sqlstr",256);
    while ($row = $result->FetchRow() ) {
        $did = $row["id"] ;	
        $item = $row["item"] ;
        $order_pos = $row["order_pos"]+1 ;      
        $data_get = $row["data_get"] ;
        $data_input = $row["data_input"] ;   
	 $up_date = $row["up_date"] ;    
        $class_id = $row["class_id"] ;              
        $doc = $row["doc"] ;  
	 $gra = $row["grada"] ;  

	$class_name=class_id_to_full_class_name($class_id);
    
        echo "<tr> 
  		<td ><a href='view.php?id=$did'>$doc</a><font size=2>---$grada[$gra]</font></td>
            <td >$data_get</td>
            <td >$class_name</td>          
	          <td >$up_date</td>
         </tr>" ;
   
   }           
   echo "</table>" ;  
   
   //統計 -------------------------------------------------------
   //學校、組數統計	
  
   echo  "<br><table cellSpacing=0 cellPadding=4 width='50%' align=center border=1 bordercolor='#33CCFF' bgcolor='#CCFFFF'>
             <tr bgcolor='#66CCFF'><td>項目</td><td>次數</td></tr>\n" ;   
   $sqlstr = " select b.grada , count(*) as cc  from cita_data a,cita_kind b where (a.kind = b.id and a.stud_id='$stud_id'  and a.order_pos>-1) group by b.grada   " ;
   $result =  $CONN->Execute($sqlstr) ;      
   while ($row = $result->FetchRow() ) {
    $data_get  = $row["grada"] ;
       $num = $row["cc"] ;
     echo  "<tr><td>$grada[$data_get]</td><td>$num </td></tr>\n" ;   
     $school_num_g ++ ;
     $group_num_g += $num ;
   } 
	         
   echo "<tr><td>共 $school_num_g 項</td><td>共 $group_num_g 次</td></tr></table>\n<br>" ;  

              
?>
