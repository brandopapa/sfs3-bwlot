<?php
//$Id: PHP_tmp.html 5310 2009-01-10 07:57:56Z hami $
include "config.php";

//認證
sfs_check();
//程式使用的Smarty樣本檔
//建立物件
$obj= new basic_chc($CONN,$smarty);
//初始化
//$obj->init();
//處理程序,有時程序內有header指令,故本程序宜於head("12basic_chc模組");之前
$obj->process();
//秀出網頁布景標頭
head("補考成績管理");
//顯示SFS連結選單(欲使用請拿開註解)
echo make_menu($school_menu_p,$obj->linkstr);//
// print_menu($school_menu_p);//,$obj->linkstr
$obj->display();
//佈景結尾
foot();
//物件class
class basic_chc{
	var $CONN;//adodb物件
	var $smarty;//smarty物件
	var $size=10;//每頁筆數
	var $page;//目前頁數
	var $tol;//資料總筆數
//	var $scope=array(1=>'語文',2=>'數學',3=>'自然與生活科技',4=>'社會',
//	5=>'健康與體育',6=>'藝術與人文',7=>'綜合活動',8=>'全部領域');
//	var $scope=array(7=>'全部領域+CSV下載',8=>'全部領域+網頁顯示');
    var $scope=array(8=>'全部領域');
	var $scope2=array(1=>'語文',2=>'數學',3=>'自然與生活科技',4=>'社會',
	5=>'健康與體育',6=>'藝術與人文',7=>'綜合活動');
	var $scopefailnum=array(1=>'一個領域以上不及格',2=>'二個領域以上不及格',3=>'三個領域以上不及格',4=>'四個領域以上不及格',
	5=>'五個領域以上不及格',6=>'六個領域以上不及格',7=>'七個領域以上不及格',8=>'全部領域不及格'); 
	var $Semesfailnum=array(1=>'一個學期成績列表',2=>'二個學期成績列表',3=>'三個學期成績列表',4=>'四個學期成績列表',5=>'五個學期成績列表',6=>'六個學期成績列表'); 
	var $all_seme_array_smarty=array();
	var $linkstr;
	//建構函式
	function basic_chc($CONN,$smarty){
		$this->CONN=&$CONN;
		$this->smarty=&$smarty;
	}
	//初始化
	function init() {
		//過濾字串及決定GET或POST變數
		$Y=gVar('Y');$G=gVar('G');$S=gVar('S');$Sfailnum=gVar('Sfailnum');$Semesnum=gVar('Semesnum');		
		//學年度格式 92_2,或102_1
		if (preg_match("/^[0-9]{2,3}_[1-2]$/",$Y)) $this->Y=$Y;		
		//年級格式..1-6小學,7-9國中
		if (preg_match("/^[1-9]$/",$G)) $this->G=$G;		
		//領域代碼1-7,8表示全部領域
		if (preg_match("/^[1-8]$/",$S)) $this->S=$S;
		//不及格領域數代碼1-7,8表示全部領域
		if (preg_match("/^[1-8]$/",$Sfailnum)) $this->Sfailnum=$Sfailnum;		
		//不及格學期數代碼1-6
		if (preg_match("/^[1-6]$/",$Semesnum)) $this->Semesnum=$Semesnum;		
		//學年度選單
		$this->sel_year=sel_year('Y',$this->Y);
		//年級選單
		$this->sel_grade=sel_grade('G',$this->G,$_SERVER['PHP_SELF'].'?Y='.$this->Y.'&G=');
		//頁數
		//$this->page=($_GET[page]=='') ? 0:$_GET[page];
		//其他分頁連結參數
		$this->linkstr="Y={$this->Y}&G={$this->G}&S={$this->S}&Sfailnum={$this->Sfailnum}&Semesnum={$this->Semesnum}";
		//$this->linkstr="Y={$this->Y}&G={$this->G}";
	}
		//程序
	function process() {
		//if ($_GET['act']=='update') $this->updateDate();
		$this->init();
		$this->all();
	}
	//顯示
	function display(){
//		$temp1 = dirname (__file__)."/templates/ungraduate_stu_print.htm";
		$temp2 = dirname (__file__)."/templates/ungraduate_stu.htm";
//		($this->S == "8") ? $tpl=$temp2 : $tpl = $temp1;
        ($this->S == "8") ? $tpl = $temp2 : $tpl = $temp2;
		$this->smarty->assign("this",$this);
		$this->smarty->display($tpl);
	}
	//擷取資料
	function all(){
//		var $scope2=array(1=>'語文',2=>'數學',3=>'自然與生活科技',4=>'社會',5=>'健康與體育',6=>'藝術與人文',7=>'綜合活動');
         $student_sex = array("1"=>"男","2"=>"女");
		 $cal_fin_score_ss = array("language"=>"1","math"=>"2","nature"=>"3","social"=>"4","health"=>"5","art"=>"6","complex"=>"7");
		if ($this->Y=='') return;
		if ($this->G=='') return;
		if ($this->S=='') return;
		if ($this->Sfailnum=='') return;
		if ($this->Semesnum=='') return;
		//學年學期選單取出的值ex 103_1
		$ys=explode("_",$this->Y);
		$sel_year=$ys[0];
		$sel_seme=$ys[1];
		$seme_year_seme=sprintf("%03d",$sel_year).$sel_seme;
        //要列出成績的學期數Semesnum ex:Semesnum=5 列出1011 1012 1021 1022 1031 用於mysql為$all_seme_mysql 用於smarty為$all_seme_array_smarty
        //97行~182行為上述程式碼
        if ($sel_seme==1) {
		 switch ($this->Semesnum) {
		  case 1:
		  	$all_seme_mysql = "("."'".$ys[0]."_".$ys[1]."'".")";
		  	$all_seme_array[1] = $ys[0].$ys[1];
			break;
		  case 2:	 
		    $all_seme_mysql = "("."'".($ys[0]-1)."_".($ys[1]+1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1] = ($ys[0]-1).($ys[1]+1);
		    $all_seme_array[2] = $ys[0].$ys[1];
			break;	
		  case 3:	
		    $all_seme_mysql = "("."'".($ys[0]-1)."_".$ys[1]."'".","."'".($ys[0]-1)."_".($ys[1]+1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-1).$ys[1];
            $all_seme_array[2]= ($ys[0]-1).($ys[1]+1);
            $all_seme_array[3]= $ys[0].$ys[1];
			break;
		  case 4:	
		    $all_seme_mysql = "("."'".($ys[0]-2)."_".($ys[1]+1)."'".","."'".($ys[0]-1)."_".$ys[1]."'".","."'".($ys[0]-1)."_".($ys[1]+1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-2).($ys[1]+1);
            $all_seme_array[2]= ($ys[0]-1).$ys[1];
            $all_seme_array[3]= ($ys[0]-1).($ys[1]+1);
            $all_seme_array[4]= $ys[0].$ys[1];
			break;		
		  case 5:	
		    $all_seme_mysql = "("."'".($ys[0]-2)."_".($ys[1])."'".","."'".($ys[0]-2)."_".($ys[1]+1)."'".","."'".($ys[0]-1)."_".$ys[1]."'".","."'".($ys[0]-1)."_".($ys[1]+1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-2).($ys[1]);
            $all_seme_array[2]= ($ys[0]-2).($ys[1]+1);
            $all_seme_array[3]= ($ys[0]-1).$ys[1];
            $all_seme_array[4]= ($ys[0]-1).($ys[1]+1);
            $all_seme_array[5]= $ys[0].$ys[1];
			break;
		  case 6:	
		    $all_seme_mysql = "("."'".($ys[0]-3)."_".($ys[1]+1)."'".","."'".($ys[0]-2)."_".($ys[1])."'".","."'".($ys[0]-2)."_".($ys[1]+1)."'".","."'".($ys[0]-1)."_".$ys[1]."'".","."'".($ys[0]-1)."_".($ys[1]+1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-3).($ys[1]+1);
            $all_seme_array[2]= ($ys[0]-2).($ys[1]);
            $all_seme_array[3]= ($ys[0]-2).($ys[1]+1);
            $all_seme_array[4]= ($ys[0]-1).$ys[1];
            $all_seme_array[5]= ($ys[0]-1).($ys[1]+1);
            $all_seme_array[6]= $ys[0].$ys[1];
			break;		
	      }
		} else {
		  switch ($this->Semesnum) {
		  case 1:
		  	$all_seme_mysql = "("."'".$ys[0]."_".$ys[1]."'".")";
		  	$all_seme_array[1] = $ys[0].$ys[1];
			break;
		  case 2:	
		    $all_seme_mysql = "("."'".($ys[0])."_".($ys[1]-1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1] = ($ys[0]).($ys[1]-1);
		    $all_seme_array[2] = $ys[0].$ys[1];
			break;	
		  case 3:	
		    $all_seme_mysql = "("."'".($ys[0]-1)."_".$ys[1]."'".","."'".($ys[0])."_".($ys[1]-1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-1).$ys[1];
            $all_seme_array[2]= ($ys[0]).($ys[1]-1);
            $all_seme_array[3]= $ys[0].$ys[1]; 
			break;
		  case 4:	
		    $all_seme_mysql = "("."'".($ys[0]-1)."_".($ys[1]-1)."'".","."'".($ys[0]-1)."_".$ys[1]."'".","."'".($ys[0])."_".($ys[1]-1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-1).($ys[1]-1);
            $all_seme_array[2]= ($ys[0]-1).$ys[1];
            $all_seme_array[3]= ($ys[0]).($ys[1]-1);
            $all_seme_array[4]= $ys[0].$ys[1];
			break;		
		  case 5:	
		    $all_seme_mysql = "("."'".($ys[0]-2)."_".($ys[1])."'".","."'".($ys[0]-1)."_".($ys[1]-1)."'".","."'".($ys[0]-1)."_".($ys[1])."'".","."'".($ys[0])."_".($ys[1]-1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-2).($ys[1]);
            $all_seme_array[2]= ($ys[0]-1).($ys[1]-1);
            $all_seme_array[3]= ($ys[0]-1).($ys[1]);
            $all_seme_array[4]= ($ys[0]).($ys[1]-1);
            $all_seme_array[5]= $ys[0].$ys[1];
			break;
		  case 6:	
		    $all_seme_mysql = "("."'".($ys[0]-2)."_".($ys[1]-1)."'".","."'".($ys[0]-2)."_".($ys[1])."'".","."'".($ys[0]-1)."_".($ys[1]-1)."'".","."'".($ys[0]-1)."_".($ys[1])."'".","."'".($ys[0])."_".($ys[1]-1)."'".","."'".$ys[0]."_".$ys[1]."'".")";
		    $all_seme_array[1]= ($ys[0]-2).($ys[1]-1);
            $all_seme_array[2]= ($ys[0]-2).($ys[1]);
            $all_seme_array[3]= ($ys[0]-1).($ys[1]-1);
            $all_seme_array[4]= ($ys[0]-1).($ys[1]);
            $all_seme_array[5]= ($ys[0]).($ys[1]-1);
            $all_seme_array[6]= $ys[0].$ys[1];
			break;		
	      }
		}
		$this->all_seme_array_smarty=$all_seme_array;		
		$seme_class=$this->G."%";
		$Scope=$this->S;
		$SQL2="and c.scope='$Scope'";
		($Scope!="8") ? $ADD_SQL=$SQL2:$ADD_SQL='';
//		($Scope!="8") ? $ADD_SQL=$SQL2:$ADD_SQL=$SQL2;	
/*
		$query="SELECT a.stud_id, a.stud_name, a.stud_sex, b.seme_class, b.seme_num, b.seme_year_seme, c.* 
        FROM stud_base a, stud_seme b, chc_mend c
        WHERE a.student_sn = c.student_sn 
        AND c.student_sn = b.student_sn 
        AND a.stud_study_cond='0'
        AND c.seme IN $all_seme_mysql
        AND b.seme_year_seme = REPLACE (c.`seme` ,'_','')
        $ADD_SQL
        ORDER BY b.seme_class,b.seme_num,b.seme_year_seme,c.student_sn,c.scope
        ";		
*/
		//增加判斷是否為今年再籍學生，如此轉學生也會顯現
		//本學年度-選擇學年+選擇年級=目前年級，才被選取。
		//但是如此一來又會產生畢業生無法被選取的另一個問題
		$curr_y=curr_year();
		$sel_y=substr($this->Y,0,-2);
		$sel_g=$this->G;
		$opsql=$curr_y-$sel_y+$sel_g."%";
		//echo $opsql;
		$query="select a.stud_id,a.stud_name,a.stud_sex,
		b.seme_class,b.seme_num,b.seme_year_seme,c.* 
		from stud_base a,chc_mend c left join stud_seme b 
		on (c.student_sn=b.student_sn  
		and b.seme_year_seme='$seme_year_seme'  
		and b.seme_class like '$seme_class' )
		where a.student_sn=c.student_sn 
		and c.seme='$this->Y' 
		and a.stud_study_cond=0
		and a.curr_class_num LIKE '$opsql' 
		$ADD_SQL
		order by b.seme_class,b.seme_num ";
		//echo $query;        
		$res=$this->CONN->Execute($query);
		$ALL=$res->GetArray();
		$i=0;
		if ($Scope=="8") {
			$New=array();
			foreach ($ALL as $ary){
				$sn=$ary['student_sn'];//學號
				$snlist[]=$ary['student_sn'];//學號列表
				$ss=$ary['scope'];//領域
				$semes=$ary['seme_year_seme'];//學期
				$score_end = $ary['score_end'];//補考完成績
	            //$New['A']為學生基本資料
				$New['A'][$sn]=$ary;
				//$New['B']為全領域成績
				$New['B'][$sn][$semes][$ss]=$ary;
				//$New['C']、$New['D']為補考後成績
				$New['C'][$sn][$semes][$ss]=$score_end;
				$New['D'][$sn][$semes][$ss]=$score_end;
//				$New['G'][$sn][$semes][$ss]=$score_end;
				//$New['C']要計算補考通過領域數total_ss_pass、$New['D']要計算補考領域數total_ss]
				$New['D'][$sn][$semes][total_ss]= (count($New['D'][$sn][$semes])==1)?(count($New['D'][$sn][$semes])):(count($New['D'][$sn][$semes])-1);
				$New['C'][$sn][$semes][total_ss_pass]= 0;		
                foreach ($New['C'][$sn][$semes] as $ss => $v) {
				     if ($v ==60) {
						 $New['C'][$sn][$semes][total_ss_pass]++;
					  } 
				 }
				 //$New['E']要計算補考未通過領域數total_ss_Nopass
				 $New['E'][$sn][$semes][total_ss]=$New['D'][$sn][$semes][total_ss];
				 $New['E'][$sn][$semes][total_ss_pass]=$New['C'][$sn][$semes][total_ss_pass];
				 $New['E'][$sn][$semes][total_ss_Nopass]=$New['E'][$sn][$semes][total_ss]-$New['E'][$sn][$semes][total_ss_pass];
                 $New['A'][$sn][total_ss_Nopass]=$New['E'][$sn][$semes][total_ss_Nopass];
			}			 			
			//取得其他領域成績
			$snlist_uniqe_csv = array_unique($snlist);						
			$snlist_uniqe = array_unique($snlist);						
		    sort($snlist_uniqe);								
			$cal_fin_score_array = $this->cal_fin_score($snlist_uniqe,$all_seme_array,"","",2);												
			foreach ($snlist_uniqe as $value_sn){
			   foreach ($all_seme_array as $value_seme){
				  foreach ($cal_fin_score_ss as $index_ss => $value_ss){
					  //$New['F']要取得未補考領域成績
					  $New['F'][$value_sn][$value_seme][$value_ss] = $cal_fin_score_array[$value_sn][$index_ss][$value_seme];
					  $New['I'][$value_sn][$value_ss][$value_seme] = $New['F'][$value_sn][$value_seme][$value_ss];
					  //$New['G']要計算各領域各學期平均成績
					  $New['G'][$value_sn][$value_ss][$value_seme] =$New['B'][$value_sn][$value_seme][$value_ss][score_end];
				  } 
			   } 				
			}
			foreach ($snlist_uniqe as $value_sn){
			   foreach ($all_seme_array as $value_seme){
				  foreach ($cal_fin_score_ss as $index_ss => $value_ss){
					 if ($New['G'][$value_sn][$value_ss][$value_seme] =="") {
					  $New['G'][$value_sn][$value_ss][$value_seme] = $New['I'][$value_sn][$value_ss][$value_seme][score];
					 }
	              } 
			   } 				
			}
			foreach ($snlist_uniqe as $value_sn){			   
			   foreach ($cal_fin_score_ss as $index_ss => $value_ss){				
				  foreach ($all_seme_array as $value_seme){ 
					  $New['G'][$value_sn][$value_ss][total_score]=$New['G'][$value_sn][$value_ss][total_score]+$New['G'][$value_sn][$value_ss][$value_seme];
					  if ($New['G'][$value_sn][$value_ss][$value_seme]!="") $New['G'][$value_sn][$value_ss][rate_score]++;
					  $New['G'][$value_sn][$value_ss][avg_score]=$New['G'][$value_sn][$value_ss][total_score]/$New['G'][$value_sn][$value_ss][rate_score];
			          //$New['H']要計算各領域(1,2,3,4,5,6)學期平均成績來計算通過領域數
					  $New['H'][$value_sn][$value_ss]=$New['G'][$value_sn][$value_ss][avg_score];					  
				  } 				 
				  if ($New['H'][$value_sn][$value_ss]>=60) $New['H'][$value_sn][total_ss_pass]++;
				  $New['H'][$value_sn][total_ss_Nopass]=7-$New['H'][$value_sn][total_ss_pass];
			   } 				
			}
		$this->stu_data=$New;
//		echo "<pre>";
//		print_r($snlist_uniqe);
//		echo "</pre>";
		
	    if ($_REQUEST['op']=="CSV") {
	    $this->stu_data=$New;	 
		//$CSV_data 為CSV輸出檔案
		$CSV_data = "學號,班級,座號,姓名,性別,語文平均,數學平均,自然與生活科技平均,社會平均,健康與體育平均,藝術與人文平均,綜合活動平均,通過領域數,未通過領域數\r\n";
		foreach ($snlist_uniqe_csv as $value_sn) {	
		  if ($this->stu_data['H'][$value_sn]['total_ss_Nopass'] >= $this->Sfailnum) {		   
		    if (substr($this->stu_data['A'][$value_sn][seme_class],0,1) == $this->G) {
		      $CSV_data .="{$this->stu_data['A'][$value_sn]['stud_id']},{$this->stu_data['A'][$value_sn]['seme_class']},{$this->stu_data['A'][$value_sn]['seme_num']},{$this->stu_data['A'][$value_sn]['stud_name']},{$student_sex[$this->stu_data['A'][$value_sn]['stud_sex']]},{$this->stu_data['H'][$value_sn][1]},{$this->stu_data['H'][$value_sn][2]},{$this->stu_data['H'][$value_sn][3]},{$this->stu_data['H'][$value_sn][4]},{$this->stu_data['H'][$value_sn][5]},{$this->stu_data['H'][$value_sn][6]},{$this->stu_data['H'][$value_sn][7]},{$this->stu_data['H'][$value_sn]['total_ss_pass']},{$this->stu_data['H'][$value_sn]['total_ss_Nopass']}\r\n";
		    }  
		  }  
		}	      	
		$CSV_filename = $ys[0]."學年".$ys[1]."學期".$this->G."年級".$this->Semesnum."學期".$this->Sfailnum."個領域以上不及格.csv";
		header("Content-disposition: attachment;filename=$CSV_filename");
		header("Content-type: text/x-csv ; Charset=Big5");
		header("Progma: no-cache");
		header("Expires: 0");
		echo $CSV_data;
		die();
	     }
		}			
	}	
	
	function cal_fin_score($student_sn=array(),$seme=array(),$succ="",$strs="",$precision=1)   //$succ:需合格領域數 $strs:等第評斷代換字串
   {

	//取出學期初設定中  畢業成績計算方式  0:算數平均   1:加權平均(學分概念加權)

	global $CONN;
	if (count($seme)==0) return;
	$SQL="select * from pro_module where pm_name='every_year_setup' AND pm_item='FIN_SCORE_RATE_MODE'";
        $RES=$CONN->Execute($SQL);
        $FIN_SCORE_RATE_MODE=INTVAL($RES->fields['pm_value']);

	$sslk=array("語文-本國語文"=>"chinese","語文-鄉土語文"=>"local","語文-英語"=>"english","健康與體育"=>"health","生活"=>"life","社會"=>"social","藝術與人文"=>"art","自然與生活科技"=>"nature","數學"=>"math","綜合活動"=>"complex");
	if (count($student_sn)>0 && count($seme)>0) {
		$all_sn="'".implode("','",$student_sn)."'";
		$all_seme="'".implode("','",$seme)."'";
		//取得科目成績
		$query="select a.*,b.link_ss,b.rate from stud_seme_score a left join score_ss b on a.ss_id=b.ss_id where a.student_sn in ($all_sn) and a.seme_year_seme in ($all_seme) and b.enable='1' and b.need_exam='1'";
		// 若彰化縣..則修正資料庫語法,加入針對SS_ID的年級作檢查,是否與學生所在年級相符
/*		$sch=get_school_base();
		if($sch[sch_sheng]=='彰化縣'){
			$query="select a.*,b.link_ss,b.rate,b.class_year ,b.year as chc_year,b.semester as chc_semester,c.seme_class as chc_seme_class from stud_seme_score a left join score_ss b on a.ss_id=b.ss_id left join stud_seme as c on (a.seme_year_seme=c.seme_year_seme and a.student_sn =c.student_sn) where a.student_sn in ($all_sn) and a.seme_year_seme in ($all_seme) and b.enable='1' and b.need_exam='1' and (b.class_year=left(c.seme_class,1))";
		}
*/		
		$res=$CONN->Execute($query);
		//取得各學期領域學科成績.加權數並加總
		while(!$res->EOF) {
			//取得領域加權總分
			$subj_score[$res->fields[student_sn]][$res->fields[link_ss]][$res->fields[seme_year_seme]]+=$res->fields[ss_score]*$res->fields[rate];
			//領域總加權數
			$rate[$res->fields[student_sn]][$res->fields[link_ss]][$res->fields[seme_year_seme]]+=$res->fields[rate];
			$res->MoveNext();
		}

		//處理各學期領域平均
		$IS5=false;
		$IS7=false;
		while(list($sn,$v)=each($subj_score)) {
			$sys=array();
			reset($v);
			while(list($link_ss,$vv)=each($v)) {
				reset($vv);
				$ls=$sslk[$link_ss];
				if($ls){  //學期成績計算排除九年一貫對應為"非預設領域科目"與"彈性課程"(非五大或七大領域) 的成績 
					if($ls=="life") $IS5=true;
					if($ls=="art") $IS7=true;
					//計算各領域學期成績
					while(list($seme_year_seme,$s)=each($vv)) {
						$fin_score[$sn][$ls][$seme_year_seme][score]=number_format($s/$rate[$sn][$link_ss][$seme_year_seme],$precision);
						$fin_score[$sn][$ls][$seme_year_seme][rate]=$rate[$sn][$link_ss][$seme_year_seme];

						//$FIN_SCORE_RATE_MODE=1為加權平均  0為算數平均   假設畢業總平均加權數來自原始科目加權數   須注意各學期加權是否合理  比如  前一學期以100 200  500 設定   但次一學期以節數 2  3 6  設定  如此會造成單一學期的該領域成績比重失衡問題
						if($FIN_SCORE_RATE_MODE=='1') {
							//領域畢業總成績
							$fin_score[$sn][$ls][total][score]+=$fin_score[$sn][$ls][$seme_year_seme][score]*$rate[$sn][$link_ss][$seme_year_seme];
							//領域畢業總平均
							$fin_score[$sn][$ls][total][rate]+=$rate[$sn][$link_ss][$seme_year_seme];
						} else {
							$fin_score[$sn][$ls][total][score]+=$fin_score[$sn][$ls][$seme_year_seme][score];
							$fin_score[$sn][$ls][total][rate]+=1;
						}

						//當學期學期總平均處理
						if ($ls=="chinese" || $ls=="local" || $ls=="english") {
							//語文領域特別處理部份
							if ($sys[$seme_year_seme]!=1) $sys[$seme_year_seme]=1;
							$fin_score[$sn][language][$seme_year_seme][score]+=$fin_score[$sn][$ls][$seme_year_seme][score]*$fin_score[$sn][$ls][$seme_year_seme][rate];
							$fin_score[$sn][language][$seme_year_seme][rate]+=$fin_score[$sn][$ls][$seme_year_seme][rate];
						} else {

							if($FIN_SCORE_RATE_MODE=='1') {
								$fin_score[$sn][$seme_year_seme][total][score]+=$fin_score[$sn][$ls][$seme_year_seme][score]*$rate[$sn][$link_ss][$seme_year_seme];
								$fin_score[$sn][$seme_year_seme][total][rate]+=$rate[$sn][$link_ss][$seme_year_seme];
							} else {
								$fin_score[$sn][$seme_year_seme][total][score]+=$fin_score[$sn][$ls][$seme_year_seme][score];
								$fin_score[$sn][$seme_year_seme][total][rate]+=1;
							}
						}
					}
				}
				$fin_score[$sn][$ls][avg][score]=number_format($fin_score[$sn][$ls][total][score]/$fin_score[$sn][$ls][total][rate],$precision);

				//除 本國語文  鄉土語言  英語  和 彈性課程 外   將其他領域平均成績加入"畢業"總成績
				if ($ls!="chinese" && $ls!="local" && $ls!="english" && $ls!="") {
					if($FIN_SCORE_RATE_MODE=='1') {
						$fin_score[$sn][total][score]+=$fin_score[$sn][$ls][total][score];
						$fin_score[$sn][total][rate]+=$fin_score[$sn][$ls][total][rate];
					} else {
						$fin_score[$sn][total][score]+=$fin_score[$sn][$ls][avg][score];
						$fin_score[$sn][total][rate]+=1;
//echo $sn."---".$fin_score[$sn][total][score]." --- ".$fin_score[$sn][$ls][avg][score]."---".$fin_score[$sn][total][rate]."<BR>";
					}
					//判斷及格領域數
					if ($fin_score[$sn][$ls][avg][score] >= 60) $fin_score[$sn][succ]++;
				}
			}


			//生活領域成績特別處理
			if($IS5 && $IS7) {
				$fin_score[$sn][art][total][score]+=$fin_score[$sn][life][avg][score]*$fin_score[$sn][life][total][rate]/3;
				$fin_score[$sn][nature][total][score]+=$fin_score[$sn][life][avg][score]*$fin_score[$sn][life][total][rate]/3;
				$fin_score[$sn][social][total][score]+=$fin_score[$sn][life][avg][score]*$fin_score[$sn][life][total][rate]/3;

				$fin_score[$sn][art][total][rate]+=$fin_score[$sn][life][total][rate]/3;
				$fin_score[$sn][nature][total][rate]+=$fin_score[$sn][life][total][rate]/3;
				$fin_score[$sn][social][total][rate]+=$fin_score[$sn][life][total][rate]/3;

				$fin_score[$sn][art][avg][score]=number_format($fin_score[$sn][art][total][score]/$fin_score[$sn][art][total][rate],$precision);
				$fin_score[$sn][nature][avg][score]=number_format($fin_score[$sn][nature][total][score]/$fin_score[$sn][nature][total][rate],$precision);
				$fin_score[$sn][social][avg][score]=number_format($fin_score[$sn][social][total][score]/$fin_score[$sn][social][total][rate],$precision);
			}


			//語文領域成績特別獨立計算
			if (count($sys)>0) {
				$r=0;
				while(list($seme_year_seme,$s)=each($sys)) {
					$fin_score[$sn][language][$seme_year_seme][score]=number_format($fin_score[$sn][language][$seme_year_seme][score]/$fin_score[$sn][language][$seme_year_seme][rate],$precision);


					if($FIN_SCORE_RATE_MODE=='1')	{
						$fin_score[$sn][language][avg][score]+=$fin_score[$sn][language][$seme_year_seme][score]*$fin_score[$sn][language][$seme_year_seme][rate];
						$fin_score[$sn][language][total][score]+=$fin_score[$sn][language][$seme_year_seme][score]*$fin_score[$sn][language][$seme_year_seme][rate];
						$fin_score[$sn][language][total][rate]+=$fin_score[$sn][language][$seme_year_seme][rate];



						$fin_score[$sn][$seme_year_seme][total][score]+=$fin_score[$sn][language][$seme_year_seme][score]*$fin_score[$sn][language][$seme_year_seme][rate];
						$r+=$fin_score[$sn][language][$seme_year_seme][rate];
		//echo $sn."---".$r."---".$fin_score[$sn][language][$seme_year_seme][rate]."---".$fin_score[$sn][language][avg][score]."<BR>";
						$fin_score[$sn][$seme_year_seme][total][rate]+=$fin_score[$sn][language][$seme_year_seme][rate];
					} else {
						$fin_score[$sn][language][avg][score]+=$fin_score[$sn][language][$seme_year_seme][score];
						$fin_score[$sn][$seme_year_seme][total][score]+=$fin_score[$sn][language][$seme_year_seme][score];
						$r+=1;
						$fin_score[$sn][$seme_year_seme][total][rate]+=1;
					}
					$fin_score[$sn][$seme_year_seme][avg][score]=number_format($fin_score[$sn][$seme_year_seme][total][score]/$fin_score[$sn][$seme_year_seme][total][rate],$precision);
				}

				$fin_score[$sn][language][avg][score]=number_format($fin_score[$sn][language][avg][score]/$r,$precision);
				if($FIN_SCORE_RATE_MODE=='1')	{
					$fin_score[$sn][total][score]+=$fin_score[$sn][language][avg][score]*$r;
					$fin_score[$sn][total][rate]+=$r;
				} else {
					$fin_score[$sn][total][score]+=$fin_score[$sn][language][avg][score];
					$fin_score[$sn][total][rate]+=1;
				}

				$fin_score[$sn][avg][score]=number_format($fin_score[$sn][total][score]/$fin_score[$sn][total][rate],$precision);
				//複製到排名陣列
				$rank_score[$sn]=$fin_score[$sn]['total']['score'];


				if ($fin_score[$sn][language][avg][score] >= 60) $fin_score[$sn][succ]++;
			}

			if ($succ) {
				if ($fin_score[$sn][succ] < $succ) $show_score[$sn]=$fin_score[$sn];
			}
      
      //針對最後結果做排序
			arsort($rank_score);
			//計算名次
			$rank=0;
			foreach($rank_score as $key=>$value) {
				$rank+=1;
				$fin_score[$key]['total']['rank']=$rank;
			}

		}


		if ($succ)
			return $show_score;
		else
			return $fin_score;
	} elseif (count($student_sn)==0) {
		return "沒有傳入學生流水號";
	} else {
		return "沒有傳入學期";
	}
   }

}
