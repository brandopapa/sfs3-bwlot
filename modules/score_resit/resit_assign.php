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
$C_year_seme=substr($SETUP['now_year_seme'],0,3)."學年度 第 ".substr($SETUP['now_year_seme'],-1)." 學期";
//目前處理的學年學期
$sel_year = substr($SETUP['now_year_seme'],0,3);
$sel_seme = substr($SETUP['now_year_seme'],-1);

//已選定的年級
$Cyear=$_POST['Cyear'];

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


 		if($Cyear>2){
			$ss_link=array("語文"=>"language","數學"=>"math","自然與生活科技"=>"nature","社會"=>"social","健康與體育"=>"health","藝術與人文"=>"art","綜合活動"=>"complex");
			$link_ss=array("language"=>"語文","math"=>"數學","nature"=>"自然與生活科技","social"=>"社會","health"=>"健康與體育","art"=>"藝術與人文","complex"=>"綜合活動");
		} else {
			$ss_link=array("語文"=>"language","數學"=>"math","健康與體育"=>"health","生活"=>"life","綜合活動"=>"complex");
			$link_ss=array("language"=>"語文","math"=>"數學","health"=>"健康與體育","life"=>"生活","complex"=>"綜合活動");
		}
// POST後執行的程式
//設定試卷，ajax,執行後 exit
if ($_POST['act']=='setup_paper') {	
  $scope=$_POST['scope'];
	$sql="select * from resit_paper_setup where seme_year_seme='".$SETUP['now_year_seme']."' and class_year='$Cyear' and scope='$scope'";	
	$res=$CONN->Execute($sql);
  if ($res->recordcount()) {
   $start_time=$res->fields['start_time'];
   $end_time=$res->fields['end_time'];
   $timer_mode=$res->fields['timer_mode'];
   $timer=$res->fields['timer'];
   $relay_answer=$res->fields['relay_answer'];
   $items=$res->fields['items'];
   $double_papers=$res->fields['double_papers'];
  } else {
   $start_time=date('Y-m-d H:i:00');
   $end_time=date('Y-m-d H:i:00');
   $timer_mode=0;
   $timer=45;
   $relay_answer=0;
   $items=0;
   $double_papers=0;
  }	
   $main="
   <input type='hidden' name='scope' value='$scope'>
   <table border='0' cellpadding='3'>
   	<tr>
   	  <td colspan='2' style='color:#800000'><b>".$link_ss[$scope]."領域</b>試卷設定</td>
   	</tr>
   	<tr>
   		<td>考試開始時間</td>
   		<td><input type='text' size='20' name='start_time' value='$start_time'></td>
   	</tr>
   	<tr>
   		<td>考試結束時間</td>
   		<td><input type='text' size='20' name='end_time' value='$end_time'></td>
   	</tr>
   	<tr>
   		<td>計時模式</td>
   		<td>
   		    <input type='radio' name='timer_mode' value='0'".(($timer_mode==0)?" checked":"").">個別計時
   		    <input type='radio' name='timer_mode' value='1'".(($timer_mode==1)?" checked":"").">同時計時
   		</td>
   	</tr>
   	<tr>
   		<td>計時時間</td>
   		<td><input type='text' size='5' name='timer' value='$timer'>分鐘</td>
   	</tr>
   	<tr>
   		<td>出題模式</td>
   		<td>隨機取<input type='text' size='5' name='items' value='$items'>題成卷(輸入0表示全部)</td>
   	</tr>
   	<tr>
   		<td>交卷後立即提供解答</td>
   		<td>
   		    <input type='radio' name='relay_answer' value='0'".(($relay_answer==0)?" checked":"").">否
   		    <input type='radio' name='relay_answer' value='1'".(($relay_answer==1)?" checked":"").">是
   		</td>
   	</tr>
   	<tr>
   		<td>斷線後是否可再領卷</td>
   		<td>
   		    <input type='radio' name='double_papers' value='0'".(($double_papers==0)?" checked":"").">否
   		    <input type='radio' name='double_papers' value='1'".(($double_papers==1)?" checked":"").">是
   		    <br><font size=2>(預設「否」，可避免異地登入同帳號重覆領卷)</font>
   		</td>
   	</tr>

   </table>

   ";

	echo $main;
  exit();
} // end if ($_POST['act']=='setup_paper')

//儲存試卷設定
if ($_POST['act']=='setup_paper_submit') {
		
  $scope=$_POST['scope'];
	$sql="select * from resit_paper_setup where seme_year_seme='".$SETUP['now_year_seme']."' and class_year='$Cyear' and scope='$scope'";	
	$res=$CONN->Execute($sql);
	
	$start_time=$_POST['start_time'];
	$end_time=$_POST['end_time'];
	$timer_mode=$_POST['timer_mode'];
	$timer=$_POST['timer'];
	$items=$_POST['items'];
	$relay_answer=$_POST['relay_answer'];
	$double_papers=$_POST['double_papers'];
	
	if ($res->recordcount()) {
	  $sql="update resit_paper_setup set start_time='$start_time',end_time='$end_time',timer_mode='$timer_mode',timer='$timer',items='$items',relay_answer='$relay_answer',double_papers='$double_papers' where seme_year_seme='".$SETUP['now_year_seme']."' and class_year='$Cyear' and scope='$scope'";
    $res=$CONN->Execute($sql) or die ('Error! Query='.$sql);
	} else {
	  $sql="insert into resit_paper_setup (seme_year_seme,class_year,scope,start_time,end_time,timer_mode,timer,items,relay_answer,double_papers) values ('".$SETUP['now_year_seme']."','$Cyear','$scope','$start_time','$end_time','$timer_mode','$timer','$items','$relay_answer','$double_papers')";
	  $res=$CONN->Execute($sql) or die ('Error! Query='.$sql);
	}
	
	echo $link_ss[$scope]."領域試卷設定儲存完畢!";
	
  exit();
}


//儲存試題
if ($_POST['act']=='edit_paper_submit') {		 
	$opt2=$_POST['opt2'];
	$item_scope=$_POST['item_scope'];
	$paper_setup=get_paper_sn($SETUP['now_year_seme'],$Cyear,$item_scope);
  $item_sn=$_POST['item_sn'];
  
  $question=trim($_POST['question']);
  $cha=trim($_POST['cha']);
  $chb=trim($_POST['chb']);
  $chc=trim($_POST['chc']);
  $chd=trim($_POST['chd']);
  $answer=$_POST['answer'];

	//處理圖片 取得 $fig_q,$fig_a,$fig_b,$fig_c,$fig_d 五個值
	$fig_array=array("q","a","b","c","d");
	foreach ($fig_array as $v) {
		$target_fig="thefig_".$v;
		$target_fig_name="fig_".$v;
		${$target_fig_name}="";
	   if ($_FILES[$target_fig]!="") {	   	
	   	//檢驗副檔名
      $expand_name=explode(".",$_FILES[$target_fig]['name']);
      $nn=count($expand_name)-1;
      $ATTR=strtolower($expand_name[$nn]); //轉小寫副檔名
      if ($ATTR=='jpg' or $ATTR=='png') {
          $img_info = getimagesize($_FILES[$target_fig]['tmp_name']);
    			$xx   = $img_info['0'];
    			$yy   = $img_info['1'];
					$imgtype=$_FILES[$target_fig]['type'];
					
          $sFP=fopen($_FILES[$target_fig]['tmp_name'],"r");				//載入檔案
       		$sFile=addslashes(fread($sFP,filesize($_FILES[$target_fig]['tmp_name'])));
       		$sFile=base64_encode($sFile);
    			
    			$sql="insert into resit_images (filetype,xx,yy,content) values ('$imgtype','$xx','$yy','$sFile')";
					$res=$CONN->Execute($sql);					
		     	list(${$target_fig_name})=mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()"));
      } 
     } //end if	
	} // end foreach

  //echo "'$fig_q','$fig_a','$fig_b','$fig_c','$fig_d'";
  //exit();

  if ($item_sn=='') {
	 //新增試題
	 $sql="insert into resit_exam_items (paper_sn,question,cha,chb,chc,chd,fig_q,fig_a,fig_b,fig_c,fig_d,answer) values ('".$paper_setup['sn']."','$question','$cha','$chb','$chc','$chd','$fig_q','$fig_a','$fig_b','$fig_c','$fig_d','$answer')";
	 $res=$CONN->Execute($sql) or die ("Error! SQL=".$sql);
	 //取得最後的 sn , 以顯示最後編輯的試題	
	 list($Last_item_sn)=mysql_fetch_row(mysql_query("SELECT LAST_INSERT_ID()"));
  } else {
   $item_org=get_item($item_sn);
   //若有附圖,刪除原圖
   	$fig_array=array("q","a","b","c","d");
	  foreach ($fig_array as $v) {
		 $target_fig="fig_".$v;
		 if ($item_org[$target_fig]>0) {
		   if (${$target_fig}>0 or $_POST['del_fig'][$v]) {
		     $CONN->Execute("delete from resit_images where sn='".$item_org[$target_fig]."'");
		   } else {
		     ${$target_fig}=$item_org[$target_fig];
		   }
		 }
		} // end foreach
		
		$sql="update resit_exam_items set question='$question',cha='$cha',chb='$chb',chc='$chc',chd='$chd',fig_q='$fig_q',fig_a='$fig_a',fig_b='$fig_b',fig_c='$fig_c',fig_d='$fig_d',answer='$answer' where sn='$item_sn'";
		$res=$CONN->Execute($sql) or die ("修改試題失敗! SQL=".$sql);
	
   //編輯試題        
   $Last_item_sn=$item_sn; 
  }
  //保持編輯試題狀態
  $_POST['act']=($opt2!='')?$opt2:'edit_paper';  
} // end if edit_paper_submit

//修改試題
if ($_POST['act']=='edit_paper_update') {		 
  $item_sn=$_POST['item_sn'];
	$item_scope=$_POST['item_scope'];
	$item=get_item($item_sn);
	//修改完要返回的動作
  $opt2=$_POST['opt2'];
  //保持編輯試題狀態
  $_POST['act']='edit_paper';  
}

//刪除試題
if ($_POST['act']=='edit_paper_delete') {		 
  $item_sn=$_POST['item_sn'];
	$item_scope=$_POST['item_scope']; 
	$item_org=get_item($item_sn);	
   //若有附圖,刪除原圖
   	$fig_array=array("q","a","b","c","d");
	  foreach ($fig_array as $v) {
		 $target_fig="fig_".$v;
		 if ($item_org[$target_fig]>0) {
		     $CONN->Execute("delete from resit_images where sn='".$item_org[$target_fig]."'");
		 }
		} // end foreach
		//刪除試題
 	  $CONN->Execute("delete from resit_exam_items where sn='".$item_org['sn']."'");
	//刪除完要返回的動作
  $_POST['act']=$_POST['opt2'];  
} // end if $_POST['act']=='edit_paper_delete'

//儲存匯入的試題
if ($_POST['act']=='upload_paper_save') {		 
	$item_scope=$_POST['item_scope'];
	$paper_setup=get_paper_sn($SETUP['now_year_seme'],$Cyear,$item_scope);
  
  foreach ($_POST[field] as $I=>$P) {
    $save=0;
   if ($_POST['save_it'][$I]==1) {
    foreach ($P as $k=>$v) {
      if ($_POST['to_field'][$k]!='none') {
       $save=1;
       $f=$_POST['to_field'][$k];
       ${$f}=$v;       
      } // end if    
    } // end foreach ($P as $k=>$v)
  	
  	if ($question=='' and $cha=='' and $chb=='' and $chc=='' and $chd=='' and $answer=='') continue;
  	
  	$sql="insert into resit_exam_items (paper_sn,question,cha,chb,chc,chd,answer) values ('".$paper_setup['sn']."','$question','$cha','$chb','$chc','$chd','$answer')";
    $res=$CONN->Execute($sql) or die("儲存發生錯誤了! SQL=".$sql);
   } // end if ($_POST['save_it'][$I]==1)
  } // end foreach ($_POST[field] as $I=>$P)
  //切換為列出試題
  $_POST['act']='list_paper';  
} // end if edit_paper_submit

//調整解答 - 儲存
//儲存匯入的試題
if ($_POST['act']=='list_paper_answer_save') {		 
	$item_scope=$_POST['item_scope'];
  
  foreach ($_POST['answer'] as $sn=>$v) {
    $sql="update resit_exam_items set answer='$v' where sn='$sn'";
    $res=$CONN->Execute($sql) or die('儲存解答失敗！SQL='.$sql);
  } // end foreach ($_POST[field] as $I=>$P)
  //切換為列出試題
  $_POST['act']='list_paper';  
} // end if list_paper_answer_save


//**************** 開始秀出網頁 ******************/
//秀出 SFS3 標題

head();
//列出選單
echo $tool_bar;
?>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
	<input type="hidden" name="act" value="">
	<input type="hidden" name="opt1" value="">
	<input type="hidden" name="opt2" value="<?php echo $opt2;?>">
<?php
 echo "<font color=red>補考學期別：".$C_year_seme."</font><br>";
 echo "請選擇命題的年級：".$class_year_list;
 
 if ($Cyear!="") { 
 	?>
 <table border="0" width="100%">
  <tr>
  	<!--左畫面 -->
    <td width="480" valign="top" rowspan="2">
    	
 		<table border="1"  style="border-collapse:collapse;font-size:10pt" bordercolor="#111111" cellpadding="3" width="100%">
 		 <tr bgcolor="#FFCCFF" width="100%">
 			<td align="center">領域別</td>
 			<td align="center">題數 </td>
 			<td align="center">操作</td>
 		 </tr>
 		<?php
 		foreach ($ss_link as $k=>$v) {
 			
 			if ($_POST['opt1']!="") {
 			  $display=($_POST['opt1']==$v)?"bloak":"none";
 			  //目前操作領域別改由 $item_scope 記錄
 			  $item_scope=$_POST['opt1'];
 			} else { 				
 			  $display="table-row"; 			  
 			}
 			
 			//計算本領域題數
 			$sql="select a.* from resit_exam_items a, resit_paper_setup b where a.paper_sn=b.sn and b.seme_year_seme='".$SETUP['now_year_seme']."' and b.class_year='$Cyear' and b.scope='$v'";
 			$res=$CONN->Execute($sql);
 			$num=$res->RecordCount();
			//確認已啟用，否則後三顆鈕 disable 					
 			$sql="select * from resit_paper_setup where seme_year_seme='".$SETUP['now_year_seme']."' and class_year='$Cyear' and scope='$v'";	
			$res=$CONN->Execute($sql);
			$disabled=($res->recordcount()==0)?"disabled":"";
 		  ?>
 		  <tr width="100%" class="scope_table" id="<?php echo $v;?>" style="background-Color:#FFFFFF;display:<?php echo $display;?>">
 		    <td><?php echo $k;?></td>
 		    <td align="center"><?php echo $num;?></td>
 				<td align="center">
 					<input type="button" value="設定試卷" class="setup_paper" id="btn-<?php echo $v;?>-setup">
 					<input type="button" value="匯入試題" class="upload_paper" id="btn-<?php echo $v;?>-upload" <?php echo $disabled;?>>
 					<input type="button" value="線上命題" class="edit_paper" id="btn-<?php echo $v;?>-edit" <?php echo $disabled;?>>
 					<input type="button" value="檢視試題" class="list_paper" id="btn-<?php echo $v;?>-list" <?php echo $disabled;?>>
 				</td>
 		  </tr>
 		  <?php
 		} 		
 		?>
 	  </table>
    </td>
  	<!--右畫面 -->
    <td valign="top">
		<span id="show_right"></span>
    </td>
  </tr>
  <tr>
  	<td>
  		<div id="setup_paper_readme" style="display:none">
  			<input type="button" id="setup_paper_submit" value="儲存設定"><br>
  		<font size='2' color='#0000cc'>
      <img src='./images/filefind.png'>說明:<br>
   1.當採用「個別計時」模式時，學生皆可獲得相同的計時時間作答。<br>
   2.當採用「同時計時」模式時，學生於相同的時間結束考試。<br>
   3.若「學期補考設定」的領卷模式設定為「依下列設定時段內開放<br>所有試卷」，則此處考試時間相關設定無任何作用。<br>
      </font>
      </div>
  	</td>
  </tr> 
 </table>
 <?php
 if ($_POST['act']=='edit_paper') {
 ?>
 <input type="hidden" name="item_scope" value="<?php echo $item_scope;?>">
 <input type="hidden" name="item_sn" value="<?php echo $item['sn'];?>">
 <table border="0">
 	<tr>
 	  <td>
 	  <span id="show_buttom">
 	  	<?php form_item($item);?>
 	  </span>
 	  </td>
 	</tr>
 	<tr>
  	<td>
  		<div id="edit_paper_readme" style="display:block">
  			<input type="button" id="edit_paper_submit" value="儲存試題">
  			<input type="button" id="edit_paper_end" value="結束命題">
  			<br>
  			<?php
  			 if ($Last_item_sn) {
  			?>
  				<table border='1' bordercolor='#FFFFFF' cellspacing='0' bordercolordark='#FFFFFF' bordercolorlight='#800000'>
   					<tr bgcolor='#FFCC66'>
    				 	<td style='font-size:10pt;color:#0000cc'><img src='.\images\filefind.png'>檢視先前試題</td>
   					</tr>
   					<tr>
     					<td><?php echo show_item($Last_item_sn);?></td>
   					</tr>
  				</table>
  			<?php
  		  } //end if ($Last_item_sn)
  			?>
  		<font size='2' color='#0000cc'>
      <img src='./images/filefind.png'>編輯試題說明:<br>
      1.試題可上傳附圖，系統並未限制附圖大小，但為了版面美觀及閱讀的舒適度，請適度調整試題的附圖大小。<br>
      2.題幹的附圖，寬度盡可能不超過 400px；選目的寬度盡可能不超過200px。<br>
      3.選項若含圖片，建議先利用繪圖軟體調整四個選項的圖片大小(寬及高)相近。
      </font>
      </div>
      
  		<div id="edit_paper_readme" style="display:block">
  		<font size='2' color='#0000cc'>
      <img src='./images/filefind.png'>線上命題說明:<br>
      1.如果該領域已有學生參加補考，試題資料庫切勿更動，。
      </font>
      </div>
  	</td>
  </tr> 
 </table> 	
 	<?php
  
  } // end if $_POST['act']=='edit_paper'

 //匯入試題
 if ($_POST['act']=='upload_paper') {
 ?>
 <input type="hidden" name="item_scope" value="<?php echo $item_scope;?>">
 <input type="hidden" name="item_sn" value="<?php echo $item['sn'];?>">
 <table border="0">
 	<tr>
 	  <td>
 	  <span id="show_buttom">
 	  <textarea name="items" cols="78" rows="5"></textarea><br>
 	  請確認斷行分析字文符號：<br>
 	  第1斷行符號<input type='text' name='cut[]' value='.' size='10'><br>
 	  第2斷行符號<input type='text' name='cut[]' value='(A)' size='10'><br>
 	  第3斷行符號<input type='text' name='cut[]' value='(B)' size='10'><br>
 	  第4斷行符號<input type='text' name='cut[]' value='(C)' size='10'><br>
 	  第5斷行符號<input type='text' name='cut[]' value='(D)' size='10'><br>
 	  第6斷行符號<input type='text' name='cut[]' value='' size='10'><br>
 	  第7斷行符號<input type='text' name='cut[]' value='' size='10'><br>
 	  第8斷行符號<input type='text' name='cut[]' value='' size='10'><br>
 	  第9斷行符號<input type='text' name='cut[]' value='' size='10'>
	  </span>
 	  </td>
 	</tr>
 	<tr>
  	<td>
  		<div id="edit_paper_readme" style="display:block">
  			<input type="button" id="upload_paper_submit" value="分析試題">
  			<input type="button" id="edit_paper_end" value="離開">
  			<br>
  		<font size='2' color='#0000cc'>
      <img src='./images/filefind.png'>匯入試題說明:<br>
      1.採用匯入方式，可同時建立多題文字型試題。<br>
      2.關於附圖部分，必須匯入完畢後再採修改試題方式逐題上傳。<br>
      3.貼上的文字必須為一題一行的格式。
      
      </font>
      </div>
  	</td>
  </tr> 
 </table> 	
 	<?php
  
  } // end if $_POST['act']=='edit_paper'


//匯入試題上傳文字
if ($_POST['act']=='upload_paper_submit') {	
	
	$items=stripslashes($_POST['items']);
	$buffer = explode("\n",$items);  //以換行符號, 把資料切割
  //開始
  $i=0;
  foreach ($buffer as $P )  {
  	$i++;
  	//以斷行符號作為資料切格依據, 最多10個
  	$j=0;
  	$j_max=0;
 		$P=trim($P); //去除前後空白
		foreach ($_POST['cut'] as $C) {
  	  if ($C!="") {  	
    		$NewP=explode($C,$P,2);
  	    $j++;
  	    $P_item[$i][$j]=trim($NewP[0]);
  	    $P=trim($NewP[1]);
  	  }  	
  	} // end foreach
    $j++;
    $P_item[$i][$j]=$P; //剩餘文字
    if ($j>$j_max) { $j_max=$j; }
  } // end foreach
	
	//開始組合成 from
	$content="";
	for ($I=1;$I<=$i;$I++) {
	 //欄位
	 $content_tr=$content_td="";
	 for ($J=1;$J<=$j_max;$J++) {
	  $content_td.="<td><input type='text' size='12' name='field[$I][$J]' value='".$P_item[$I][$J]."'></td>";
	 }
	 //列
	 $content_tr="
	  <tr class='upload_table'>
	   <td align='center'><input type='checkbox' name='save_it[$I]' value='1' checked></td>
	   $content_td
	  </tr>
	 ";
	 $content.=$content_tr;
	}
	
	//標題欄
	 for ($J=1;$J<=$j_max;$J++) {
	  $content_title.="
	  <td>
  		<select size='1' name='to_field[$J]'>
    		<option value='none'>不儲存</option>
    		<option value='question'>題幹</option>
    		<option value='cha'>選目A</option>
    		<option value='chb'>選目B</option>
    		<option value='chc'>選目C</option>
    		<option value='chd'>選目D</option>
    		<option value='answer'>解答</option>
  		</select>	  
	  </td>";
	 }
	$content_title="<tr bgcolor='#FFCC66'><td>儲存</td>$content_title</tr>";
	$main="
	  <table border='0'>
	  $content_title
	  $content
	  </table>
	";
	//開始呈現
	?>
	<input type="hidden" name="item_scope" value="<?php echo $item_scope;?>">
	<?php
  echo $main;
  ?>
  <input type="button" id="upload_paper_save" value="儲存試題">
	<input type="button" id="edit_paper_end" value="離開">
	<br>
 		<font size='2' color='#0000cc'>
      <img src='./images/filefind.png'>操作說明:<br>
      1.請選定每一欄位要對應的試題項目。<br>
      2.如果該欄位資料要捨棄，請選擇「不儲存」。<br>
      3.注意，對應的欄位資料請勿重覆，以免資料庫出錯!      
      </font>

  <?php
}



 //檢視試題 
 if ($_POST['act']=='list_paper') {
 ?>
 <input type="hidden" name="item_scope" value="<?php echo $item_scope;?>">
 <input type="hidden" name="item_sn" value="<?php echo $item['sn'];?>">
 <table border="0">
 	<tr>
 	  <td>
 	  <span id="show_buttom">
 	  	<input type="button" id="list_paper_end" value="結束檢視">
 	  	<input type="button" id="list_paper_answer" value="調整解答">
 	  	<table border='0'>
 	  	
		<?php
 			$sql="select a.sn from resit_exam_items a, resit_paper_setup b where a.paper_sn=b.sn and b.seme_year_seme='".$SETUP['now_year_seme']."' and b.class_year='$Cyear' and b.scope='$item_scope'";
 			$res=$CONN->Execute($sql);
 			$row=$res->GetRows();
 			foreach ($row as $K) {
 			  $sn=$K['sn'];
				?>
				<tr><td><hr></td></tr>
				<tr>
					<td><?php echo show_item($sn);?></td>
				</tr>
				<?php 			  
 			}
		?>
		</table>
 	  </span>
 	  </td>
 	</tr>
 </table> 	
 	<?php
  
  } // end if $_POST['act']=='list_paper'
 
 //檢視試題 
 if ($_POST['act']=='list_paper_answer') {
 ?>
 <input type="hidden" name="item_scope" value="<?php echo $item_scope;?>">
 <input type="hidden" name="item_sn" value="<?php echo $item['sn'];?>">
 <table border="0">
 	<tr>
 	  <td>
 	  <span id="show_buttom">
 	  	<input type="button" id="list_paper_end" value="結束檢視">
 	  	<input type="button" style="color:#FF0000" id="list_paper_answer_save" value="儲存解答">
 	  	<table border='0'> 	  	
		<?php
 			$sql="select a.sn from resit_exam_items a, resit_paper_setup b where a.paper_sn=b.sn and b.seme_year_seme='".$SETUP['now_year_seme']."' and b.class_year='$Cyear' and b.scope='$item_scope'";
 			$res=$CONN->Execute($sql);
 			$row=$res->GetRows();
 			foreach ($row as $K) {
 			  $sn=$K['sn'];
				?>
				<tr><td><hr></td></tr>
				<tr>
					<td><?php echo show_item($sn,1);?></td>
				</tr>
				<?php 			  
 			}
		?>
		</table>
 	  </span>
 	  </td>
 	</tr>
 </table> 	
 	<?php  
  } // end if $_POST['act']=='list_paper_answer_save' 
 
 } //end if $Cyear 
?>
</form>
<?php
//  --程式檔尾
foot();
?>

<Script> 
 <?php
 foreach ($ss_link as $v) {
  $JavaArray.="\"".$v."\",";
 }
 $JavaArray=substr($JavaArray,0,strlen($JavaArray)-1);
 ?>
 //定義所有領域
 var AllScope=[<?php echo $JavaArray;?>]; 

//滑鼠移出移入
$(".scope_table").hover(function(){
	 $(this).css("background-color","#FFFFAA");
	},function(){
	 $(this).css("background-color","#FFFFFF");	
})

//滑鼠移出移入
$(".upload_table").hover(function(){
	 $(this).css("background-color","#AAFFAA");
	},function(){
	 $(this).css("background-color","#FFFFFF");	
})

//滑鼠移出移入
$(".items_table").hover(function(){
	 $(this).css("background-color","#AAAAFF");
	},function(){
	 $(this).css("background-color","#FFFFFF");	
})

//設定試卷
$(".setup_paper").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var scope=NewArray[1];
	var act='setup_paper';
	var Cyear='<?php echo $Cyear;?>';
	   
  $.ajax({
   	type: "post",
    url: 'resit_assign.php',
    data: { act:act,scope:scope,Cyear:Cyear },
    dataType: "text",
    error: function(xhr) {
      alert('ajax request 發生錯誤!');
    },
    success: function(response) {
    	$('#show_right').html(response);
      $('#show_right').fadeIn(); 
      setup_paper_readme.style.display='block';
      setup_paper_readme.style.display='block';
      //for (index = 0; index < AllScope.length; ++index) {
      //  var ss=AllScope[index];        
      //	document.getElementById(ss).style.display = 'block';         
			//}
			
    } // end success
	});   // end $.ajax
})

//儲存試卷設定
$("#setup_paper_submit").click(function(){
	var act='setup_paper_submit';
	var scope=document.myform.scope.value;
	var start_time=document.myform.start_time.value;
	var end_time=document.myform.end_time.value;
	var Cyear='<?php echo $Cyear;?>';
	var timer=document.myform.timer.value;
	var items=document.myform.items.value;
	//取得 timer_mode , 由於是利用 ajax 動態產生的畫面，這邊無法使用 jQuery 取值
	for (var i=0; i<myform.timer_mode.length; i++) {
   if (myform.timer_mode[i].checked)
   {
      var timer_mode = myform.timer_mode[i].value;
   }
  }
  //取得 relay_answer
	for (var i=0; i<myform.relay_answer.length; i++) {
   if (myform.relay_answer[i].checked)
   {
      var relay_answer = myform.relay_answer[i].value;
   }
  }
  
  //取得 double_papers
	for (var i=0; i<myform.double_papers.length; i++) {
   if (myform.double_papers[i].checked)
   {
      var double_papers = myform.double_papers[i].value;
   }
  }	

   	//考試時間比較
   	starttime=start_time.replace(/-/g, "/"); 
   	starttime=(Date.parse(starttime)).valueOf() ; // 直接轉換成Date型別所代表的值
   	endtime=end_time.replace(/-/g, "/"); 
   	endtime=(Date.parse(endtime)).valueOf() ; // 直接轉換成Date型別所代表的值
    if (starttime>=endtime) {
     alert ("考試結束時間不得早於或等於開始時間!");
     return false;
    }	
	
  $.ajax({
   	type: "post",
    url: 'resit_assign.php',
    data: { act:act,scope:scope,Cyear:Cyear,start_time:start_time,end_time:end_time,timer:timer,items:items,timer_mode:timer_mode,relay_answer:relay_answer,double_papers:double_papers },
    dataType: "text",
    error: function(xhr) {
      alert('ajax request 發生錯誤!');
    },
    success: function(response) {
    	$('#show_right').html(response);
      $('#show_right').fadeIn(); 
      setup_paper_readme.style.display='none';
      for (index = 0; index < AllScope.length; ++index) {
        var ss=AllScope[index];        
        //document.getElementById(ss).style.display = 'block';
        //把按鈕的 disabled 取消
        if (ss==scope) {
          var btnID="btn-"+ss+"-upload";
          document.getElementById(btnID).disabled = false;         
          var btnID="btn-"+ss+"-edit";
          document.getElementById(btnID).disabled = false;         
          var btnID="btn-"+ss+"-list";
          document.getElementById(btnID).disabled = false;         
        }         
			}
    }
	});   // end $.ajax 
 
})


//線上命題
$(".edit_paper").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var scope=NewArray[1];
	  
  document.myform.act.value='edit_paper';
  document.myform.opt1.value=scope;
  
  document.myform.submit();

})

//儲存試題 , 打開本年級領域列表
$("#edit_paper_submit").click(function(){
	//設定 opt1 為某領域, 以便列表僅顯示該領域
	document.myform.opt1.value=document.myform.item_scope.value;
  document.myform.act.value='edit_paper_submit';

  chk_submit=check_form_item();

  if (chk_submit) {
	 document.myform.submit();
	}

})

//修改試題
$(".edit_paper_update").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var item_sn=NewArray[1];

	//設定 opt1 為某領域, 以便列表僅顯示該領域
	document.myform.opt1.value=document.myform.item_scope.value;
	document.myform.opt2.value='<?php echo $_POST['act'];?>';
  document.myform.act.value='edit_paper_update';
  document.myform.item_sn.value=item_sn;
	document.myform.submit();
})

//刪除試題
$(".edit_paper_delete").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var item_sn=NewArray[1];
  
  confirm_delete=confirm("您確定要刪除試題？\n流水號："+item_sn);
  
  if (confirm_delete) {
		//設定 opt1 為某領域, 以便列表僅顯示該領域
		document.myform.opt1.value=document.myform.item_scope.value;
		document.myform.opt2.value='<?php echo $_POST['act'];?>';
  	document.myform.act.value='edit_paper_delete';
  	document.myform.item_sn.value=item_sn;
		document.myform.submit();
  }
})

//結束命題 , 打開本年級領域列表
$("#edit_paper_end").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var scope=NewArray[1];
	 
	for (index = 0; index < AllScope.length; ++index) {
    var ss=AllScope[index];        
  	document.getElementById(ss).style.display = 'block';         
  }
  //清除命題區html , 避免誤送
	$('#show_buttom').html("");
	edit_paper_readme.style.display='none'; 	

})

//結束檢視 , 打開本年級領域列表
$("#list_paper_end").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var scope=NewArray[1];
	 
	for (index = 0; index < AllScope.length; ++index) {
    var ss=AllScope[index];        
  	document.getElementById(ss).style.display = 'block';         
  }
  //清除命題區html , 避免誤送
	$('#show_buttom').html("");

})

//檢視試題
$(".list_paper").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var scope=NewArray[1];
	  
  document.myform.act.value='list_paper';
  document.myform.opt1.value=scope;
  
  document.myform.submit();

})

//檢視試題 - 調整解答
$("#list_paper_answer").click(function(){
  
  document.myform.act.value='list_paper_answer';
  document.myform.opt1.value=document.myform.item_scope.value;
  
  document.myform.submit();

})

//檢視試題 - 調整解答儲存
$("#list_paper_answer_save").click(function(){
  
  document.myform.act.value='list_paper_answer_save';
  document.myform.opt1.value=document.myform.item_scope.value;
  
  document.myform.submit();

})


//匯入試題
$(".upload_paper").click(function(){
	var btnID=$(this).attr("id");
	var NewArray = new Array();
　var NewArray = btnID.split("-");
  var scope=NewArray[1];
		
  document.myform.act.value='upload_paper';
  document.myform.opt1.value=scope;
  
  document.myform.submit();

})

//分析試題
$("#upload_paper_submit").click(function(){
	//設定 opt1 為某領域, 以便列表僅顯示該領域
	document.myform.opt1.value=document.myform.item_scope.value;
  document.myform.act.value='upload_paper_submit';

  if (document.myform.items.value=='') {
   alert('未貼入任何文字!');
   return false;
  }  
	document.myform.submit();
})

//儲存試題
$("#upload_paper_save").click(function(){
	//設定 opt1 為某領域, 以便列表僅顯示該領域
	document.myform.opt1.value=document.myform.item_scope.value;
  document.myform.act.value='upload_paper_save';
	document.myform.submit();
})

//檢驗試題表單
function check_form_item() {
 if (document.myform.question.value=='') {
   alert('題幹未輸入!');
   return false;
 }
 if (document.myform.cha.value=='' && document.myform.thefig_a.value=='' && ($("#del_fig_a").length == 0 || $("#del_fig_a").attr('checked'))) {
   alert('選目(A)未輸入!');
   return false; 
 }

 if (document.myform.chb.value=='' && document.myform.thefig_b.value=='' && ($("#del_fig_b").length == 0 || $("#del_fig_b").attr('checked'))) {
   alert('選目(B)未輸入!');
   return false; 
 }

 if (document.myform.chc.value=='' && document.myform.thefig_c.value=='' && ($("#del_fig_c").length == 0 || $("#del_fig_c").attr('checked'))) {
   alert('選目(C)未輸入!');
   return false; 
 }

 if (document.myform.chd.value=='' && document.myform.thefig_d.value=='' && ($("#del_fig_d").length == 0 || $("#del_fig_d").attr('checked'))) {
   alert('選目(D)未輸入!');
   return false; 
 }
 //檢查解答有沒有點選
 var method =$("input[name='answer']:checked").val(); //radio 取值，注意寫法
 if( typeof(method) == "undefined"){ // 注意檢查完全沒有選取的寫法，這行是精華
   alert( "請選取解答！");
  return false;
 }

 return true;
 
}

</Script>