<?php	
header('Content-type: text/html;charset=big5');
// $Id: index.php 5310 2009-01-10 07:57:56Z smallduh $
//取得設定檔
include_once "config.php";
//驗證是否登入
sfs_check(); 
//製作選單 ( $school_menu_p陣列設定於 module-cfg.php )
$tool_bar=&make_menu($school_menu_p);

//讀取補考學期別設定
$sql="select * from resit_seme_setup limit 1";
$res=$CONN->Execute($sql);
$SETUP=$res->fetchrow();

//目前時間
$nowsec=date("U",mktime(date("H"),date("i"),0,date("n"),date("j"),date("Y")));

$seme_year_seme=$SETUP['now_year_seme'];

//取得學生該學期 $SETUP['now_year_seme'] 學籍資料
//學生資料存放於 $STUD array 中, 包括：
// stud_id 學號
// stud_anme 中文姓名
// seme_class 班級 如 701,702
// seme_class_name 班級名稱
// seme_num 座號
$sql="select a.*,b.stud_name from stud_seme a,stud_base b where a.student_sn=b.student_sn and a.student_sn='".$_SESSION['session_tea_sn']."' and a.seme_year_seme='".$curr_year_seme."'";
$res=$CONN->Execute($sql);
$STUD=$res->fetchRow();

$Now_Cyear=substr($STUD['seme_class'],0,1); //九年一貫的年級 , 國中為 7-9

//以本年度學生資料去抓 student_sn , 以免抓不到後來才轉入的學生 student_sn	

$sel_year=substr($SETUP['now_year_seme'],0,3);

$Cyear=$Now_Cyear-($curr_year-$sel_year);

$CLASS_name=$school_kind_name[$Now_Cyear].$STUD['seme_class_name']."班"; //中文，如一年，二年...

//學年度中文名稱
$C_year_seme=substr($SETUP['now_year_seme'],0,3)."學年度第".substr($SETUP['now_year_seme'],-1)."學期";
//九年一貫領域對照
 		if($Cyear>2){
			$ss_link=array("語文"=>"language","數學"=>"math","自然與生活科技"=>"nature","社會"=>"social","健康與體育"=>"health","藝術與人文"=>"art","綜合活動"=>"complex");
			$link_ss=array("language"=>"語文","math"=>"數學","nature"=>"自然與生活科技","social"=>"社會","health"=>"健康與體育","art"=>"藝術與人文","complex"=>"綜合活動");
		} else {
			$ss_link=array("語文"=>"language","數學"=>"math","健康與體育"=>"health","生活"=>"life","綜合活動"=>"complex");
			$link_ss=array("language"=>"語文","math"=>"數學","health"=>"健康與體育","life"=>"生活","complex"=>"綜合活動");
		}
//取得該生該學期的學期成績
	$semes[]=$seme_year_seme;  //目前學期
	$stud_sn[]=$STUD['student_sn'];
	//抓取領域成績
	$sel_year=substr($seme_year_seme,0,3);
	$sel_seme=substr($seme_year_seme,-1);
	$fin_score=cal_fin_score($stud_sn,$semes,"",array($sel_year,$sel_seme,$Cyear),1);

  foreach ($ss_link as $v) {
    ${$v}=$fin_score[$STUD['student_sn']][$v][$seme_year_seme]['score'];
  }
  
  
//開始測驗
if ($_POST['act']=='start_test') {
 //領域別
 $scope=$_POST['scope'];
 $main="<tr><td>補考領域：".$school_kind_name[$Cyear]."級 ".$link_ss[$scope]."</td></tr>";
 //安全性檢查 ==============================
	if (${$scope}>=60) {
 			echo "<br>你不需要補考這個領域! ";
 			exit();
 	}

 //讀取領域設定
  $paper_setup=get_paper_sn($seme_year_seme,$Cyear,$scope);
 	 //考試時段依學期設定或試卷個別設定
 			  if ($SETUP['paper_mode']) {
					$start_time=$SETUP['start_time'];
					$end_time=$SETUP['end_time']; 			  
 			  } else {
					$start_time=$paper_setup['start_time'];
					$end_time=$paper_setup['end_time']; 		  
 			  }
 //檢查是否考試時段內
 				 $StartSec=date("U",mktime(substr($start_time,11,2),substr($start_time,14,2),0,substr($start_time,5,2),substr($start_time,8,2),substr($start_time,0,4)));
				 $EndSec=date("U",mktime(substr($end_time,11,2),substr($end_time,14,2),0,substr($end_time,5,2),substr($end_time,8,2),substr($end_time,0,4)));
  if ($nowsec<$StartSec or $nowsec>$EndSec) {
  	echo "<br>考試時間：$start_time - $end_time <br> 現在不是考試時間！請離開!!!";
    exit();
  }
  
 //檢查是否已領卷，是否允許重覆領卷
 			 $sql="select * from resit_exam_score where student_sn='".$STUD['student_sn']."' and paper_sn='".$paper_setup['sn']."'";
 			 $res=$CONN->Execute($sql);
 			 if ($res->RecordCount()==0) {
 				  echo "<br>你沒有在補考名單裡, 請通知監考老師重新設定！";
 				  exit(); 	
 			 }
 			 $resit_data=$res->fetchRow();  //array
  			if ($resit_data['complete']) {
 				  echo "<br>你已經考過了！";
 				  exit(); 				  
 				}
 				if ($resit_data['entrance'] and $paper_setup['double_papers']==0) {
  				echo "<br>你已經領過卷了！";
 				  exit(); 
 			  } else {
 			  	//允許重覆領或沒領過, 先刪除
 			  	$org_score=$resit_data['org_score'];
 			  	$sql="delete from resit_exam_score where student_sn='".$STUD['student_sn']."' and paper_sn='".$paper_setup['sn']."'";
 			    $res=$CONN->Execute($sql);
 			  	$sql="insert into resit_exam_score (student_sn,paper_sn,org_score) values ('".$STUD['student_sn']."','".$paper_setup['sn']."','$org_score')";
 			    $res=$CONN->Execute($sql) or die ('重建成績資料發生錯誤! SQL='.$sql);
 			 		$sql="select * from resit_exam_score where student_sn='".$STUD['student_sn']."' and paper_sn='".$paper_setup['sn']."'";
 			 		$res=$CONN->Execute($sql);
 			 		$resit_data=$res->fetchRow();  //array
 			  }
 //=安全性檢查結束====================================================================================
 //開始出題, 出題後先建立基本資料, 並讀取 sn ,儲存時比對 sn,paper_sn,student_sn , 
 //取得 score_sn
 $score_sn=$resit_data['sn']; 
 //讀取試題最大值
 $sql="select count(*) as num from `resit_exam_items` where paper_sn='".$paper_setup['sn']."'";
 $res=$CONN->Execute($sql);
 $Max_items=$res->fields['num'];
 if ($Max_items==0) {
   echo "<br>哦喔！老師好像忘了出題！";
   exit();
 }
 
 //依出題設定隨機取題
 $test_count=$paper_setup['items'];
 //檢查出題數設定
 if ($test_count==0 or $test_count>$Max_items) { $test_count=$Max_items; }
 //出題流水號 (利用 test_items array 記錄) ,學生的作答則利用 test_answers array 記錄
 $sql="SELECT * FROM `resit_exam_items` where paper_sn='".$paper_setup['sn']."' ORDER BY RAND() LIMIT 0,$test_count"; 
 $res=$CONN->Execute($sql);
 //開始記錄
 $i=0;
 $item_form='';
 while ($row=$res->fetchRow()) {
	
	$i++;
	$test_items[$i]=$row['sn'];  //記錄試題流水號
	
	$item_style=make_item_style($i,$row);
	$item_form.="
	<tr>
		<td>
			<hr>
		</td>
	</tr>
	<tr>
			<td>$item_style</td>
	</tr>
	";
	
 }
 $item_form.="<tr><td><hr></td></tr>";
 
 $item_form="<table border='0'>".$item_form."</table>";
 
 //記錄領卷時間
 $entrance=1;
 $entrance_time=date("Y-m-d H:i:s");
 $paper_sn=$paper_setup['sn'];
 $student_sn=$STUD['student_sn'];
 //$test_items 命題的題目 , array
 $items=serialize($test_items);

 $sql="update resit_exam_score set items='$items',entrance='1',entrance_time='$entrance_time' where sn='$score_sn'";

 $res=$CONN->Execute($sql) or die('記錄試卷資料失敗! SQL='.$sql);

 $main= "<input type='hidden' name='score_sn' value='$score_sn'>
 <input type='hidden' name='paper_sn' value='$paper_sn'>
 <input type='hidden' name='exam_items' value='$test_count'>".$main.$item_form;
   
 echo $main;
 
 exit(); 

} // end if start_test

//交卷
if ($_POST['act']=='start_test_submit') {
 $score_sn=$_POST['score_sn'];
 $paper_sn=$_POST['paper_sn'];
 $answers=$_POST['answers'];  // array
 $complete=1;
 $complete_time=date("Y-m-d H:i:s");
 
 $correct=0;
 //取出成績記錄(應已有試題陣列)
 $sql="select * from resit_exam_score where sn='$score_sn' and student_sn='".$STUD['student_sn']."' and paper_sn='$paper_sn'";
 $res=$CONN->Execute($sql) or die('讀取作答試題記錄失敗! SQL='.$sql);
 if ($res->recordcount()) {
 	  $row=$res->fetchRow();
    $items=unserialize($row['items']);
    $test_count=count($items);  				//題數
    for($i=1;$i<=$test_count;$i++) {
      $sql_ans="select answer from `resit_exam_items` where sn='".$items[$i]."'";
      $res_ans=$CONN->Execute($sql_ans);
      $answer=$res_ans->fields['answer'];
      if ($answer==$answers[$i]) $correct++;
    } // end for
    
    //分數
    $score=($correct/$test_count)*100;
    $score=round($score,2);
    
    $write_answers=serialize($answers);
    
    $sql="update `resit_exam_score` set score='$score',answers='$write_answers',complete='$complete',complete_time='$complete_time' where sn='$score_sn' and student_sn='".$STUD['student_sn']."' and paper_sn='$paper_sn'";
    $res=$CONN->Execute($sql) or die('寫入作答及成績記錄失敗! SQL='.$sql);
    //echo "得".round($score,2)."分";
    //exit();
    
 } else {
    echo "發生了錯誤! 無法從作答試題記錄比對解答!<br>";
    echo "SQL=".$sql;
    exit();
 }
 

} // end if ($_POST['act']=='start_test_submit')



/**************** 開始秀出網頁 ******************/
//秀出 SFS3 標題
head();
//列出選單
echo $tool_bar;
?>
<style type="text/css">
 .bg_0 { background-color:#FFFFFF  }
 .bg_Click { background-color:#FFCC99  }
 .bg_Over { background-color:#CCFFFF  }
</style>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="act" value="">
	<input type="hidden" name="scope" value="">

<?php
 echo "補考學期別：".$C_year_seme."，";
 echo "當前班級座號：".$CLASS_name." ".$STUD['seme_num']."號 ".$STUD['stud_name']."<br>";

//未進行任何選擇時的畫面

?>
 <table border="0">
  <tr>
  	<!--左畫面 -->
    <td valign="top">
    	<div id="show_top">
 	<table border="1" style="border-collapse:collapse;font-size:10pt" bordercolor="#111111" cellpadding="3">
 		<tr bgcolor="#FFCCFF">
 			<td align='center'>領域別</td>
 			<td align='center'>成績</td>
 			<td align='center'>是否需補考</td>
 			<td align='center'>補考開始時間</td>
 			<td align='center'>補考結束時間</td>
 			<td align='center'>補考成績</td>
 			<td align='center'>補考完成時間</td>
 			<td align='center'>操作</td>
 		</tr>
 		<?php
 		foreach ($ss_link as $k=>$v) {
 			//$v 為領域別
 			//取得本領域補考設定
 			 $paper_setup=get_paper_sn($seme_year_seme,$Cyear,$v);
 			 //考試時段依學期設定或試卷個別設定
 			  if ($SETUP['paper_mode']) {
					$start_time=$SETUP['start_time'];
					$end_time=$SETUP['end_time']; 			  
 			  } else {
					$start_time=$paper_setup['start_time'];
					$end_time=$paper_setup['end_time']; 		  
 			  }

 			// 是否及格, 如果不及格, 應檢查是否參加過補考, 若參加過, 不能再考
 			if (${$v}<60) {
 			 $show1='<font color=red>需要</font>';
     
 			 //取得 resit_exam_score 裡本領域學生成績
 			 $sql="select * from resit_exam_score where student_sn='".$STUD['student_sn']."' and paper_sn='".$paper_setup['sn']."'";
 			 $res=$CONN->Execute($sql);
 			 $resit_data=$res->fetchRow();  //array

 			 //檢查是否考完或領卷完畢考試中
 			 if ($resit_data['complete']) {
 			 //有補考完畢
 			   $show2=$resit_data['score'];  			//補考成績
 			   $show3=$resit_data['update_time'];  //補考完成時間
 			   $show4="-";
 			 } elseif ($resit_data['entrance'] and $paper_setup['double_papers']==0) {
 			 //還沒補考完, 是否領卷
  			 $show2="-";  //補考成績
 			   $show3="-";  //補考完成時間
 			 	 $show4="<font size=''2 color='red'>已領卷，若因當機需重新領卷，請通知監考老師</font>";
 			 } else {
 			 //未領卷, 檢查時間是否到了
				//可考試時段 時分秒月日年 2015-01-11 12:12:12
				 $StartSec=date("U",mktime(substr($start_time,11,2),substr($start_time,14,2),0,substr($start_time,5,2),substr($start_time,8,2),substr($start_time,0,4)));
				 $EndSec=date("U",mktime(substr($end_time,11,2),substr($end_time,14,2),0,substr($end_time,5,2),substr($end_time,8,2),substr($end_time,0,4)));
  			 $show2="-";  //補考成績
 			   $show3="-";  //補考完成時間
				 //檢查時段
				 	if ($nowsec>=$StartSec and $nowsec<=$EndSec) {
				 	  //時段內 
				 	  $show4="<input type='button' value='領卷考試' class='start_test' id='".$v."'>";
				 	} elseif ($nowsec<$StartSec) {
				 		//時間未到
				 	  $show4="<font color=blue>考試時間未到</font>";
				 	} else {
				 		//時間已過
				 	  $show4="<font color=blue>考試時間已過</font>";
				 	}	//end if 時段檢查		 		 
 			 } //end if 檢查是否考完或領卷完畢考試中 			 
 			} else {
 			 $show1='不需要';
 			 $show2='-';
 			 $show3='-';
 			 $show4='-';
 			} // end if 檢查是否及格
 		  ?>
 		  <tr>
 		    <td><?php echo $k;?></td>
 		    <td align="center"><?php echo ${$v};?></td>
 		    <td align="center"><?php echo $show1;?></td>
 		    <td align="center"><?php echo $start_time;?></td>
 		    <td align="center"><?php echo $end_time;?></td>
 		    <td align="center"><?php echo $show2;?></td>
 		    <td align="center"><?php echo $show3;?></td>
 		    <td align="center"><?php echo $show4;?></td>
 		  </tr>
 		  <?php
 		  } // end foreach
 		  ?>
  	</table>
 	</div>
    </td>
  </tr>
 </table>
 <div id="show_buttom">
 </div>
 <table border="0">
   	<tr id='start_test_submit_button' style="display:none">
			<td>
			  <input type="button" id="start_test_submit" value="交卷">
			</td>
   	</tr>
 </table> 	

</form>
<?php
//  --程式檔尾
foot();
?>

<script>

//定義資料列數及滑鼠指標使用的 class id
 var intALL=500;
 var strMouseOver='bg_Over';
 var strMouseClick='bg_Click';
 
 //定義陣列及建立預設值
 var intTR=new Array(intALL); //用以記錄是否已按下
 var strTR=new Array(intALL); //用以記錄原始的 tr 的 class 值 
 
 for (i=1;i<=intALL;i++) {
   intTR[i]=0;
 }


//設定試卷
$(".start_test").click(function(){
	var scope=$(this).attr("id");
	var act='start_test';
	
  $.ajax({
   	type: "post",
    url: 'se_resit.php',
    data: { act:act,scope:scope },
    dataType: "text",
    error: function(xhr) {
      alert('ajax request 發生錯誤!');
    },
    success: function(response) {
    	show_top.style.display='none';    	
    	$('#show_buttom').html(response);
      $('#show_buttom').fadeIn(); 
      if (response.substr(0,4)!='<br>') {
        start_test_submit_button.style.display='block';
		  }
    } // end success
	});   // end $.ajax	
})

//設定試卷
$("#start_test_submit").click(function(){
	
	//檢查是否有未作答
	 var i=0;
   var a=0;
   var ok=0;
   var exam_items=document.myform.exam_items.value;
  
  		while (i < document.myform.elements.length)  {
    		if (document.myform.elements[i].name.substr(0,7)=='answers') {
      		if (document.myform.elements[i].checked) a++;
    		}
    		i++;
  		}
   
  if (a<exam_items) {
   is_confirmed = confirm('您有題目未作答喔!! \n應作答 '+exam_items+' 題, 你只作答 '+a+' 題,\n您確定要交卷了嗎？');
    if (is_confirmed) {
     ok=1;
    }else{
     ok=0;
    }
  } else {
   ok=1;
  }

  if (ok==1) {
  	document.myform.act.value='start_test_submit';
  	document.myform.submit();
  } else {
    return false;
  }

})


//按下滑鼠左鍵時
 function ClickLine(w,c) {
 	if (intTR[c]==0) {
  document.getElementById(w).className = strMouseClick;
	 intTR[c]=1;
 	} else {
   document.getElementById(w).className = strTR[c];
   intTR[c]=0;
  }
 }
 
//滑鼠停在上面時
 function OverLine(w,c) {
   if (intTR[c]==0) {
 	  strTR[c]=document.getElementById(w).className;
   }
   document.getElementById(w).className = strMouseOver;  
 }
 
 //滑鼠移開時
 function OutLine(w,c) {
 	if (intTR[c]==0) {
   document.getElementById(w).className = strTR[c];
 	} else {
   document.getElementById(w).className = strMouseClick;
  }
 } 

</script>