<?php

// $Id: list.php 5310 2009-01-10 07:57:56Z hami $

include "config.php";
// 不需要 register_globals
if (!ini_get('register_globals')) {
	ini_set("magic_quotes_runtime", 0);
	extract( $_POST );
	extract( $_GET );
	extract( $_SERVER );
}

//取得各類別名稱
	$l_kind="｜<a href=$PHP_SELF>最新榮譽</a>｜";
	$num=count($grada);
	for($i=0;$i<$num;$i++){
		$l_kind.="<a href=$PHP_SELF?gra=$i>$grada[$i]</a>｜";
	}

    $sqlstr =" select count(*) as cc  from cita_kind ";
    if($gra<>"")
	$sqlstr .=" where grada=$gra ";

if ($topage !="")
	$page = $topage; 
$page_count=15;
$result =$CONN->Execute($sqlstr) or user_error("讀取失敗！<br>$sqlstr",256) ; 
 $row = $result->FetchRow() ;          
 $tol_num= $row["cc"];    
if ($tol_num % $page_count > 0 )
	$tolpage = intval($tol_num / $page_count)+1;
else
	$tolpage = intval($tol_num / $page_count);

echo "
<table align=center width='90%'>
<form name='bform' method='post' action=$PHP_SELF>   
<tr><td align=center ><font size=5 color=red face=標楷體>學生競賽榮譽榜</font>　　　　	
<a href='citaList.php' target='_blank'>管理</a>　<a href='search.php'>搜尋</a>　第";
echo " <select name=\"topage\"  onchange=\"document.bform.submit()\">";
	for ($i= 0 ; $i < $tolpage ;$i++)
		if ($page == $i)
			echo sprintf(" <option value=\"%d\" selected>%2d</option>",$i,$i+1);
		else
			echo sprintf(" <option value=\"%d\" >%2d</option>",$i,$i+1);

		echo "</select>頁 /共 $tolpage 頁";


echo "</td></tr><input type='hidden' name='gra' value=$gra></form>
             <tr><td>$l_kind</td></tr></table>";

echo "<table align=center width='90%' border='1' cellspacing='0' cellpadding='4' bgcolor='#CCFFFF' bordercolor='#33CCFF'>
  <tr bgcolor='#66CCFF'> 
    <td >日期</td>
    <td >名稱　　　　($grada[$gra])</td>   
    <td >類別</td>
  </tr>";
    $sqlstr =" select *  from cita_kind ";
         if($gra<>"")
	$sqlstr .=" where grada=$gra ";
$sqlstr .=" order by beg_date DESC  ";
$sqlstr .= " limit ".($page * $page_count).", $page_count";

    $result =$CONN->Execute($sqlstr) or user_error("讀取失敗！<br>$sqlstr",256) ; 
    while ($row = $result->FetchRow() ) {
      $id = $row["id"] ;	
      $beg_date = $row["beg_date"] ;	
      $end_date = $row["end_date"] ;	
      $doc = $row["doc"] ;	
      $helper = $row[helper] ;
  	 $gra = $row[grada] ;


      echo "<tr>\n" ;
      
   
   
     echo " <td >$beg_date</td>" ;
   
      echo "        
    <td><a href='view.php?id=$id'>$doc</a></td>
      <td >" . $grada[$gra] ."</td>
  </tr>" ;
  }
?>  

</table>
