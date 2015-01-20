<?php	
header('Content-type: text/html;charset=big5');
// $Id: index.php 5310 2009-01-10 07:57:56Z smallduh $
//取得設定檔
include_once "config.php";
require_once "../../include/sfs_case_excel.php";

//驗證是否登入
sfs_check(); 
//製作選單 ( $school_menu_p陣列設定於 module-cfg.php )
$tool_bar=&make_menu($school_menu_p);

//讀取補考學期別設定
$sql="select * from resit_seme_setup limit 1";
$res=$CONN->Execute($sql);
$SETUP=$res->fetchrow();
$C_year_seme=substr($SETUP['now_year_seme'],0,3)."學年度 第 ".substr($SETUP['now_year_seme'],-1)." 學期";


//目前處理的學年學期
$sel_year = substr($SETUP['now_year_seme'],0,3);
$sel_seme = substr($SETUP['now_year_seme'],-1);

//已選定的年級
$Cyear=$_POST['Cyear'];
 		if($Cyear>2){
			$ss_link=array("語文"=>"language","數學"=>"math","自然與生活科技"=>"nature","社會"=>"social","健康與體育"=>"health","藝術與人文"=>"art","綜合活動"=>"complex");
			$link_ss=array("language"=>"語文","math"=>"數學","nature"=>"自然與生活科技","social"=>"社會","health"=>"健康與體育","art"=>"藝術與人文","complex"=>"綜合活動");
		} else {
			$ss_link=array("語文"=>"language","數學"=>"math","健康與體育"=>"health","生活"=>"life","綜合活動"=>"complex");
			$link_ss=array("language"=>"語文","math"=>"數學","health"=>"健康與體育","life"=>"生活","complex"=>"綜合活動");
		}

//確認可補考的年級
//例如: 以國中而言, 現今學年 103 , 若啟用 102學年, 只能考該年的一年級和二年級, 因為三年級已畢業
// 國中或國小判定 $IS_JHORES=6 (國中) , $IS_JHORES=0 (國小)
if ($IS_JHORES==6) {
	$SY=$curr_year-3;   //以103為例, 基準點為 100
} else {
	$SY=$curr_year-6;   //以103為例, 基準點為 97
}

//製作年級選單
$sy_circle=$sel_year-$SY;	
$now_cy=3-$sy_circle;

// ajax 檢視已補考名單
if ($_POST['act']=='html_resit_list') {
	$S['go']='補考中';
	$S['ready']='未補考';
	$S['tested']='補考完';
 	//領域別
 	// $Cyesr : 年級
	$scope=$_POST['scope'];
	$opt1=$_POST['opt1'];
	$seme_year_seme=$SETUP['now_year_seme'];
  //抓取班級設定裡的班級名稱
	$class_base= class_base($curr_year_seme);
	
	//讀取已補考名單
	switch ($opt1) {
	  case 'ready':
			$sql="select a.*,c.stud_id,c.stud_name,c.curr_class_num from resit_exam_score a,resit_paper_setup b,stud_base c where a.paper_sn=b.sn and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope' and a.student_sn=c.student_sn and entrance='0' and complete='0' order by curr_class_num";
	  break;
	  case 'go':
			$sql="select a.*,c.stud_id,c.stud_name,c.curr_class_num from resit_exam_score a,resit_paper_setup b,stud_base c where a.paper_sn=b.sn and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope' and a.student_sn=c.student_sn and entrance='1' and complete='0' order by curr_class_num";	  
	  break;
	  case 'tested':
			$sql="select a.*,c.stud_id,c.stud_name,c.curr_class_num from resit_exam_score a,resit_paper_setup b,stud_base c where a.paper_sn=b.sn and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope' and a.student_sn=c.student_sn and complete='1' order by curr_class_num";
	  break;

	}
	$res=$CONN->Execute($sql) or die($sql);
	while ($row=$res->FetchRow()) {
		$student_sn=$row['student_sn'];
		$curr_class_num=$row['curr_class_num'];
		$seme_class=substr($curr_class_num,0,3);
		$seme_num=substr($curr_class_num,-2);
		
		$main.="
			<tr>
	     <td style='font-size:10pt' align='center'>".$class_base[$seme_class]."</td>
	     <td style='font-size:10pt' align='center'>".$seme_num."</td>
	     <td style='font-size:10pt' align='center'>".$row['stud_name']."</td>
	     <td style='font-size:10pt' align='center'>".$row['org_score']."</td>
	     <td style='font-size:10pt' align='center'>".$row['score']."</td>
	     <td style='font-size:9pt'>".$row['entrance_time']."</td>		
	     <td style='font-size:9pt'>".$row['complete_time']."</td>		
			</tr>
		";
		

	}
	  $main="	  
	 <table border=\"0\" width=\"100%\" cellspacing=\"3\" cellpadding=\"2\">
  	<tr>
   	  <td colspan='5' style='color:#800000'><b>".$link_ss[$scope]."領域</b> - [<font color=blue>".$S[$opt1]."</font>]名單</td>
   	</tr>
	   <tr bgcolor=\"#FFCCCC\">
	     <td style='font-size:10pt'>班級</td>
	     <td style='font-size:10pt'>座號</td>
	     <td style='font-size:10pt'>姓名</td>
	     <td style='font-size:10pt'>原成績</td>
	     <td style='font-size:10pt'>補考成績</td>
	     <td style='font-size:10pt'>領卷時間</td>
	     <td style='font-size:10pt'>完成時間</td>
	   </tr>
	 ".$main."
	 </table>"; 
	  
 
  echo $main;
  exit();

}

//匯出不及格名單
if ($_POST['act']=='output_resit_name') {
  
	//領域別
	$scope=$_POST['opt1'];
	
  $seme_year_seme=$SETUP['now_year_seme'];
  
 //抓取班級設定裡的班級名稱
	$class_base= class_base($curr_year_seme);
	$stud_sn=array();

  //以本年度學生資料去抓 student_sn , 以免抓不到後來才轉入的學生 student_sn	
	$Now_Cyear=$Cyear+$now_cy;
	$query="select a.student_sn,a.stud_id,a.stud_name,a.curr_class_num,a.stud_addr_2,a.stud_tel_2,a.stud_tel_3,a.addr_zip,c.guardian_name from stud_base a,stud_seme b,stud_domicile c where a.student_sn=b.student_sn and b.student_sn=c.student_sn and b.seme_year_seme='$curr_year_seme' and a.curr_class_num like '".$Now_Cyear."%' and stud_study_cond in ('0','15') order by curr_class_num";
  $res=$CONN->Execute($query) or die ("讀取學生基本資料發生錯誤! SQL=".$query);	
	
	//學生總人數
	$student_all=$res->recordcount(); 
	while(!$res->EOF) {
		$student_sn=$res->fields['student_sn'];
		$stud_sn[]=$student_sn;
		$curr_class_num=$res->fields['curr_class_num'];
		$seme_class=substr($curr_class_num,0,3);
		
		$student_data[$student_sn]['seme_class']=substr($curr_class_num,0,3);
		$student_data[$student_sn]['seme_num']=substr($curr_class_num,-2);
		
		$student_data[$student_sn]['stud_name']=$res->fields['stud_name'];
		$student_data[$student_sn]['stud_id']=$res->fields['stud_id'];
		$student_data[$student_sn]['stud_addr_2']=$res->fields['stud_addr_2'];
		$student_data[$student_sn]['stud_tel_2']=$res->fields['stud_tel_2'];
		$student_data[$student_sn]['stud_tel_3']=$res->fields['stud_tel_3'];
		$student_data[$student_sn]['addr_zip']=$res->fields['addr_zip'];
		$student_data[$student_sn]['guardian_name']=$res->fields['guardian_name'];
		
		$student_data[$student_sn]['class_name']=$class_base[$seme_class];

		$res->MoveNext();
	} // end while
	
	$semes[]=$seme_year_seme;  //目前學期
	//抓取領域成績
	$sel_year=substr($seme_year_seme,0,3);
	$sel_seme=substr($seme_year_seme,-1);

	$fin_score=cal_fin_score($stud_sn,$semes,"",$strs,1);

 //全部領域
 if ($scope=="ALL") {
	$x=new sfs_xls();
	$x->setUTF8();
	$x->filename=substr($seme_year_seme,0,3)."學年度第".substr($seme_year_seme,-1).'學期應補考學生名單.xls';
	$x->setBorderStyle(1);
	$x->addSheet("應補考名單");
	$x->items[0]=array('學號','目前班級','目前座號','姓名','語文','數學','自然','社會','健體','藝文','綜合','應補考領域','已補考領域','家長姓名','市內電話','行動電話','郵遞區號','通訊地址');

	foreach ($stud_sn as $student_sn) {
    //檢查是否有任一科不及格
    $language=$math=$nature=$social=$health=$art=$complex="";
    $resit_scope=$resit_tested="";
	  $put_it=0;
	  foreach ($ss_link as $v=>$S) {
	  	${$S}=$fin_score[$student_sn][$S][$seme_year_seme]['score'];
	   if ($fin_score[$student_sn][$S][$seme_year_seme]['score']<60) {
	     $put_it=1;
	     $resit_scope.="【".$v."】";
	   }
	   	$sql="select a.score from resit_exam_score a,resit_paper_setup b where a.paper_sn=b.sn and a.student_sn='$student_sn' and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$S'";
			$res=$CONN->Execute($sql) or die($sql);
			if ($res->recordcount()) {
	      $resit_tested.="【".$v."】";
		  }
	  }
	  
	  if ($put_it==1) {
    	//是否有補考成績
			//$sql="select a.* from resit_exam_score a,resit_paper_setup b where a.paper_sn=b.sn and a.student_sn='$student_sn' and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope'";
			//$res=$CONN->Execute($sql) or die($sql);
			//if ($res->recordcount()==0) {
			//  $resit_score="";
			//} else {
		  //  $resit_score=$res->fields['score'];
		  //}
			$x->items[]=array($student_data[$student_sn]['stud_id'],$student_data[$student_sn]['class_name'],$student_data[$student_sn]['seme_num'],$student_data[$student_sn]['stud_name'],$language,$math,$nature,$social,$health,$art,$complex,$resit_scope,$resit_tested,$student_data[$student_sn]['guardian_name'],$student_data[$student_sn]['stud_tel_2'],$student_data[$student_sn]['stud_tel_3'],$student_data[$student_sn]['addr_zip'],$student_data[$student_sn]['stud_addr_2']);
  	} // end if
  } // end foreach 
 
 
 //單一領域
 } else {
	$x=new sfs_xls();
	$x->setUTF8();
	$x->filename=$seme_year_seme.$link_ss[$scope].'不及格學生名單.xls';
	$x->setBorderStyle(1);
	$x->addSheet($link_ss[$scope]."不及格");
	$x->items[0]=array('學號','目前班級','目前座號','姓名','學期分數','補考分數','家長姓名','市內電話','行動電話','郵遞區號','通訊地址');

	foreach ($stud_sn as $student_sn) {
		if ($fin_score[$student_sn][$scope][$seme_year_seme]['score']<60) {
			
    	//是否有補考成績
			$sql="select a.* from resit_exam_score a,resit_paper_setup b where a.paper_sn=b.sn and a.student_sn='$student_sn' and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope'";
			$res=$CONN->Execute($sql) or die($sql);
			if ($res->recordcount()==0) {
			  $resit_score="";
			} else {
		    $resit_score=$res->fields['score'];
		  }
			$x->items[]=array($student_data[$student_sn]['stud_id'],$student_data[$student_sn]['class_name'],$student_data[$student_sn]['seme_num'],$student_data[$student_sn]['stud_name'],$fin_score[$student_sn][$scope][$seme_year_seme]['score'],$resit_score,$student_data[$student_sn]['guardian_name'],$student_data[$student_sn]['stud_tel_2'],$student_data[$student_sn]['stud_tel_3'],$student_data[$student_sn]['addr_zip'],$student_data[$student_sn]['stud_addr_2']);
  	} // end if
  } // end foreach
 } // end if $scope=='ALL'
 
		$x->writeSheet();
		$x->process();

  exit();

}  // end if 匯出不及格名單


$class_year_list="
  <select size='1' name='Cyear' onchange='this.form.submit()'>
   <option value=''>請選擇年級</option>";
   for ($i=1;$i<=$sy_circle;$i++) {
    $CY=$i+$IS_JHORES;
    $NCY=$CY+$now_cy;
    $class_year_list.="<option value='$CY'".(($CY==$Cyear)?" selected":"").">".$school_kind_name[$CY]."級 (目前就讀".$school_kind_name[$NCY]."級)</option>";
   }    
$class_year_list.="
  </select>
";

// POST後執行的程式


//計算各領域不及格人數
if ($Cyear!="") {
		if ($_POST['act']=='get_all_resit_name') {
		 $all_students=count_scope_fail($Cyear,$SETUP['now_year_seme'],$ss_link,$link_ss);
		 $INFO="該學年學生總數 $all_students 人，已自學期成績資料庫中更新補考名單!";		 
	  } 
	  	$seme_year_seme=$SETUP['now_year_seme'];
	   foreach ($ss_link as $scope) {
	   	//不及格人數
	     $sql="select count(*) as num from resit_exam_score a,resit_paper_setup b where a.paper_sn=b.sn and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope'";
			 $res=$CONN->Execute($sql) or die ("讀取人數發生錯誤！SQL=".$sql);
			 $fail['still'][$scope]=$res->fields['num'];
			//已補考人數 
	     $sql="select count(*) as num from resit_exam_score a,resit_paper_setup b where a.paper_sn=b.sn and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope' and a.complete='1'";
			 $res=$CONN->Execute($sql) or die ("讀取人數發生錯誤！SQL=".$sql);
			 $fail['tested'][$scope]=$res->fields['num'];
			//待補考人數
	     $sql="select count(*) as num from resit_exam_score a,resit_paper_setup b where a.paper_sn=b.sn and b.seme_year_seme='$seme_year_seme' and b.class_year='$Cyear' and b.scope='$scope' and a.complete='0'";
			 $res=$CONN->Execute($sql) or die ("讀取人數發生錯誤！SQL=".$sql);
			 $fail['ready'][$scope]=$res->fields['num'];			 
	   } // end foreach	   
		
} // end if $Cyear!="";


/**************** 開始秀出網頁 ******************/
//秀出 SFS3 標題
head();
//列出選單
echo $tool_bar;
?>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="act" value="">
	<input type="hidden" name="opt1" value="">
	<input type="hidden" name="opt2" value="">
<?php
 echo "<font color=red>補考學期別：".$C_year_seme."</font><br>";
 echo "請選擇要檢視的年級：".$class_year_list;
 
 if ($Cyear!="") { 
 	?>
 <table border="0">
  <tr>
  	<!--左畫面 -->
    <td valign="top">
 	  <table border="1" style="border-collapse:collapse;font-size:10pt" bordercolor="#111111" cellpadding="3">
 		<tr bgcolor="#FFCCFF">
 			<td>領域別</td>
 			<td>不及格</td>
 			<td>已補考</td>
 			<td>待補考</td>
 			<td>檢視操作</td>
 		</tr>
 		<?php
 		foreach ($ss_link as $k=>$v) {
 		  ?>
 		  <tr>
 		    <td><?php echo $k;?></td>
 		    <td><?php echo $fail['still'][$v];?></td>
 		    <td><?php echo $fail['tested'][$v];?></td>
 				<td><?php echo $fail['ready'][$v];?></td>
 				<td>
 					<input type="button" value="未補考" class="html_resit_list" id="btn_<?php echo $v;?>_ready">
					<input type="button" value="補考中" class="html_resit_list" id="btn_<?php echo $v;?>_go">
 					<input type="button" value="補考完" class="html_resit_list" id="btn_<?php echo $v;?>_tested">
 					<input type="button" value="匯出名單" class="output_resit_name" id="<?php echo $v;?>">
 				</td>
 		  </tr>
 		  <?php
 		} 		
 		?>
 		<tr>
 				<td colspan="5" align="center">
 					<input type="button" value="匯出所有領域名單" id="output_resit_name_all">
 				</td>
 		</tr>
 	  </table>
 		<font size='2' color='#0000cc'>
      <img src='./images/filefind.png'>說明:<br>
   1.匯出資料皆採用 Excel 格式，以供套印各類通知單。<br>
   2.本統計表若有錯誤，請按<input type="button" value="更新補考名單" id="get_all_resit_name">重新計算並取得補考名單。
   </font>
   <?php echo "<br><br><font color=red>$INFO</font>";?>
   	 <div id="waiting" style="display:none">
   	 	<br>資料處理中，請稍候......
     </div>
    </td>
  	<!--右畫面 -->
    <td valign="top">
		<span id="show_right"></span>
    </td>
 </table> 	
 	<?php
 } //end if $Cyear 
?>
</form>
<?php
//  --程式檔尾
foot();
?>

<Script>

//匯出不及格名單
$(".output_resit_name").click(function(){
	var scope=$(this).attr("id");
	document.myform.act.value="output_resit_name";
	document.myform.opt1.value=scope;
	document.myform.submit();
	document.myform.act.value="";
})

//匯出不及格名單
$("#output_resit_name_all").click(function(){
	var scope=$(this).attr("id");
	document.myform.act.value="output_resit_name";
	document.myform.opt1.value="ALL";
	document.myform.submit();
	document.myform.act.value="";
})

//重新計算取得補考名單
$("#get_all_resit_name").click(function(){
	document.myform.act.value="get_all_resit_name";
	waiting.style.display="block";
	document.myform.submit();
	document.myform.act.value="";
})

//檢視已補考名單
$(".html_resit_list").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array(3);
　var NewArray = btnID.split("_");
  var scope=NewArray[1];
  var opt1=NewArray[2];
	var act='html_resit_list';
	var Cyear='<?php echo $_POST['Cyear'];?>';
  
    $.ajax({
   	type: "post",
    url: 'resit_score.php',
    data: { act:act,scope:scope,opt1:opt1,Cyear:Cyear },
    dataType: "text",
    error: function(xhr) {
      alert('ajax request 發生錯誤!');
    },
    success: function(response) {
    	$('#show_right').html(response);
      $('#show_right').fadeIn(); 
			
    } // end success
	});   // end $.ajax


})


</Script>