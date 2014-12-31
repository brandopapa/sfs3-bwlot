<?php

// $Id: mstudent2.php 7546 2013-09-19 03:35:15Z hami $

// --系統設定檔
include "docword_config.php";
//管理者檢查
if(checkid($PHP_SELF))
	$ischecked = true;
//-----------------------------------
include "header.php";
prog(5); //上方menu (在 docword_config.php 中設定)

//取得目前學年
$curr_year = curr_year();

//取得目前學期
$curr_seme =  curr_seme();

//$newer_only=$_POST['newer_only'];

// 不需要 register_globals
if (!ini_get('register_globals')) {
	ini_set("magic_quotes_runtime", 0);
	extract( $_POST );
	extract( $_GET );
	extract( $_SERVER );
}

//印出檔頭
//head("批次建立公文資料");
//print_menu($menu_p);

if ($do_key=="批次建立資料"){

	//取得郵遞區號代表的縣市鄉鎮 陣列
	//$zip_arr = get_zip_arr();
	$rst=-1;
	//目前月份
	$month = date("m");
	//目前學年
	//$class_year = curr_year();
	//目前學期
	//$curr_seme =curr_seme();
	


	//學年學期
	//$seme_year_seme = sprintf("%04d", curr_year().curr_seme());

	
	//判斷是否隔年
//	if (curr_seme()==1 and $month < $SFS_SEME2)
//		$class_year++;
	//取出 csv 的值
	$temp_file= $temp_path."docword.csv";
	echo $temp_file;
	if ($_FILES['docdata']['size'] >0 && $_FILES['docdata']['name'] != ""){
//		copy($_FILES['docdata']['tmp_name'] , $temp_file);		
		$fd = fopen ($_FILES['docdata']['tmp_name'],"r");
		
		for ($i=0;$i<2;$i++){
		    $tt = sfs_fgetcsv ($fd, 5000, ",");
		    // 只抓取匯入檔的第二列
//		    if ($i==1)
//		      $c_year = $class_year-$tt[3]+1+$IS_JHORES; // 計算年級，$IS_JHORES 使三種學制的年級計算正常
		}
/*
		$query = "select c_sort,c_name  from school_class where year='$class_year' and semester='$curr_seme' and c_year='$c_year' ";
		$res = $CONN->Execute($query)or die ($query) ;
		if ($res->EOF){
		  $con_temp =  "您的匯入檔中 $c_year 年級(入學年: $tt[3])，尚未設定班級數，請注意這個年級在貴校學制中是否有效？若屬有效年級範圍，請至教務處->學期初設定，將班級數設定好之後，再執行本程式。<br>本次執行中斷的查詢指令為： $query";
		}
		else {
			while (!$res->EOF) {
				$temp_class_name[$res->fields[0]] = $res->fields[1];
				$res->MoveNext();
			}
*/									
			
			//進行匯入資料的檢查			
			rewind($fd);
			$j =0;
			$doc1_id_array=array();
			$curr_class_num_array=array();
			while ($ck_tt = sfs_fgetcsv ($fd, 5000, ",")) {
				if ($j++ == 0){ //第一筆為抬頭，不檢查
                    continue ;
                }
				$doc1_id= trim($ck_tt[0]); //收發文號				
//				$doc1_date_sign= trim($ck_tt[1]); //收文時間
//				$doc1_date= trim($ck_tt[2]); //來文日期
//				$doc1_kind= trim($ck_tt[3]); //公文類別
//				$doc1_word= trim($ck_tt[4]).trim($ck_tt[5]); //來文字號
//				$doc1_main= trim($ck_tt[6]); //來文摘要
//				$doc1_unit= trim($ck_tt[7]); //來文單位
//				$doc1_unit_sel= trim($ck_tt[9]); //承辦處室

				//檢查文號是否存在
				if($doc1_id=="") {
					$msg="收文字號 不准空白，於第 ".$j." 筆公文資料";
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;					
					foot();
					exit;
				}
				
				//檢查文號是否重複
				if(in_array($doc1_id,$doc1_id_array))  {
					$msg="您所要匯入的公文資料中收文字號：$doc1_id 重複"; 
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;					
					foot();
					exit;
				}
				
				//沒有重複則加入文號陣列
				$doc1_id_array[$j]=$doc1_id;
				
				
				$doc1_date_sign_s = trim ($ck_tt[1]);				

				//檢查收文日期 收文時間
				if($doc1_date_sign_s=="") {
					$msg="收發文號：$doc1_id 的公文沒有[收文日期 收文時間]";
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;					
					foot();
					exit;
				}
				else{					
					$doc1_date_sign = DateTime::createFromFormat('Y年M月d日 H:i', (substr($doc1_date_sign_s,0,3) + 1911).substr($doc1_date_sign_s,3));
				}
				
				$doc1_date_s = trim($ck_tt[2]);								
				//檢查來文日期			
				if($doc1_date_s=="") {
					$msg="收發文號：$doc1_id 的公文沒有[來文日期]"; 
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;					
					foot();
					exit;
				}
				else{					
					$doc1_date = DateTime::createFromFormat('Y年MM月dd日', (substr($doc1_date_s,0,3) + 1911).substr($doc1_date_s,3));
				}
				
				$doc1_kind = trim($ck_tt[3]);				
				//檢查公文類別
				if($doc1_kind=="") {
					$msg="收發文號：$doc1_id 的公文沒有[公文類別] ";
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;					
					foot();
					exit;
				}
				//檢查來文字號
				$doc1_word=trim($ck_tt[4]).trim($ck_tt[5]);
				if($doc1_word=="") {
					$msg="收發文號：$doc1_id 的公文沒有[來文字號]"; 
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;									
					foot();	
					exit;
					}
								
				$doc1_main = trim ($ck_tt[6]);
				//檢查來文摘要
				if($doc1_main=="") {
					$msg="收發文號：$doc1_id 的公文沒有[來文摘要]"; 
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;									
					foot();	
					exit;
					}
				
				
/*				
				$stud_birthday_array=split("[/.-]",$stud_birthday);
				if($stud_birthday_array[0]<1900 || $stud_birthday_array[0]>2030 || $stud_birthday_array[1]<1 || $stud_birthday_array[1]>12 || $stud_birthday_array[2]<1 || $stud_birthday_array[2]>31) {
					$msg="收發文號：$doc1_id  姓名：$stud_name 的生日（ $stud_birthday ）填寫錯誤"; 
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;				
					foot();
					exit;
				}
*/				
				$doc1_unit = trim ($ck_tt[7]);
				//檢查來文單位
				if($doc1_unit=="") {
					$msg="收發文號：$doc1_id 的公文沒有[來文單位]"; 
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;					
					foot();
					exit;
				}				

				//檢查現存資料庫中是否該文號中已存在
				$sql="select doc1_id from sch_doc1 where doc1_id='$doc1_id'";
				$rs=$CONN->Execute($sql) or trigger_error($sql,256);
				$m=0;
				if(!$rs->EOF){
					$msg="收發文號：$doc1_id 已使用，請查明！";
					$alert="<p></p><table cellspacing=1 cellpadding=6 border=0 bgcolor='#FFF829' width='80%' align='center'><tr><td align='center'><h1><img src='images/caution.png' align='middle' border=0> 警告</h1></font></td></tr><tr><td align='center' bgcolor='#FFFFFF' width='90%'> $msg </td></tr></table>";
					echo $alert;
					foot();
					exit;
				}
				
				//都沒問題了
				$check_pass="ok";
			}
			
			$doc_unit_p = doc_unit();
			while(list($tkey,$tvalue)= each ($doc_unit_p)){
				$doc_unit_p_array[$tvalue] = $tkey;
			}								
      $doc_kind_p = doc_kind();
			while(list($tkey,$tvalue)= each ($doc_kind_p)){
				 $doc1_kind_array[$tvalue] = $tkey;
			}					
						
			//檢查通過才放行，使之開始寫入資料庫
			if($check_pass=="ok"){			
				rewind($fd);
				$i =0;
				while ($tt = sfs_fgetcsv ($fd, 5000, ",")) {
					if ($i++ == 0){ //第一筆為抬頭
						$ok_temp .="<font color='red'>第一筆應為抬頭，若您的公文資料檔的第一筆是公文資料的話，該資料項將無法匯入！</font><br>";
						continue ;
					}
										
					//修改的程式碼
					$doc1_id= trim($tt[0]);
					$doc1_date_sign = (substr(trim($tt[1]),0,3) + 1911).'-'.str_replace('日','',str_replace('月','-',substr(trim($tt[1]),5))).':00';
					//$doc1_date_sign = date('Y-M-d H:i:00',strtotime($doc1_date_sign_s));//收文日期 收文時間
					$doc1_date = (substr(trim($tt[2]),0,3) + 1911).'-'.str_replace('日','',str_replace('月','-',substr(trim($tt[2]),5)));
					//$doc1_date = date('Y-M-d', strtotime($doc1_date_s)); //來文日期
					$doc1_kind = $doc1_kind_array[trim($tt[3])]; //公文類別
					$doc1_word = trim($tt[4]).trim($tt[5]); //來文字號
					$doc1_main = trim($tt[6]); //來文摘要
					$doc1_unit = trim($tt[7]); //來文單位
					$doc1_unit_sel = trim($tt[9]); //承辦處室
					$doc1_year_limit = 1;
					$doc1_unit_num1 = $doc_unit_p_array[$doc1_unit_sel];
					$doc1_unit_num2 = '';
//					$go=true;				
//					if($newer_only and $stud_study_year<>$class_year) $go=false;
//					if($go) {

            $sql_insert = "insert into sch_doc1 (doc1_id,doc1_year_limit,doc1_kind,doc1_date,
            doc1_date_sign,doc1_unit,doc1_word,doc1_main,doc1_unit_num1,doc1_unit_num2,teach_id,doc1_k_id,do_teacher) 
            values ('$doc1_id','$doc1_year_limit','$doc1_kind','$doc1_date','$doc1_date_sign',
            '$doc1_unit','$doc1_word','$doc1_main','$doc1_unit_num1','$doc1_unit_num2','$session_log_id','0','')";						
						$result2 = $CONN->Execute($sql_insert);
						if ($result2) {
							$ok_temp .= "$doc1_id 新增成功!<br>";
						}	else $con_temp .= "$doc1_id 新增資料失敗! <br>";
//					} else $con_temp .="文號: $doc1_id 匯入限定禁止!!<BR>";
				}
			}
		//}
	}
	else
	{
		echo "檔案格式錯誤!";
		exit;
	}
	unlink($temp_file);
	
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="0" >
<tr><td valign=top bgcolor="#CCCCCC">
<table border="1" cellspacing="0" cellpadding="2" bordercolorlight="#333354" bordercolordark="#FFFFFF"  width="100%" class=main_body >

<form action ="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method=post>
<tr><td  nowrap>檔案：<input type=file name=docdata><BR><BR><font color='red'>PS.歷年度已有的公文資料請勿重覆匯入！</font></td>
<td width=65% rowspan="2" valign=top>
<?php
if ($con_temp<>''){
	echo "<b>新增錯誤<b><p>";
	echo "<font size=4>$con_temp</font>";
}
else
	echo '
<p><b><font size="4">公文資料批次建檔說明</font></b></p>
<p>1.本程式只能建立收文公文資料。<br>
2.利用 excel 或其他工具鍵入公文資料，存成 csv 檔，並保留第一列標題檔，如 
<a href=docword.csv target=new>範例檔</a><BR>
3.日期以西元為準。<br>';

?>

</td>
</tr>
<tr><td nowrap><input type=submit name="do_key" value="批次建立資料"></td></tr>
</form>
</table>
</td></tr></table>

<?php
echo $ok_temp;
foot();


?> 
