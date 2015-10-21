<?php
// $Id: my_fun.php 2015-10-04 22:12:01Z qfon $


//取得模組設定
$m_arr = &get_module_setup("score_sort");
extract($m_arr, EXTR_OVERWRITE);




//由student_sn得到本學期學生的班級座號姓名
function student_sn_to_classinfo2($student_sn,$viewyear){
    global $CONN;
    $rs_sn=$CONN->Execute("select stud_id from stud_base where student_sn='$student_sn'");
    $stud_id=$rs_sn->fields["stud_id"];
    $seme_year_seme=sprintf("%03d",$viewyear).curr_seme();
    $rs_seme=$CONN->Execute("select seme_class,seme_num from stud_seme where stud_id='$stud_id' and seme_year_seme='$seme_year_seme' order by seme_num ");
    $seme_class=$rs_seme->fields["seme_class"];
    $year= substr($seme_class,0,-2);
    $class= substr($seme_class,-2);
    $site=$rs_seme->fields["seme_num"];
    //echo $year.$class.$site;
    $rs1=&$CONN->Execute("select  stud_name,stud_sex,curr_class_num,stud_person_id  from  stud_base where student_sn='$student_sn'");
    $curr_class_num=$rs1->fields['curr_class_num'];
    $stud_sex=$rs1->fields['stud_sex'];
    $stud_name=$rs1->fields['stud_name'];
    //$site= substr($curr_class_num,-2);
    //$class= substr($curr_class_num,-4,2);
    //$year= substr($curr_class_num,0,1);
	$stud_pid=$rs1->fields['stud_person_id'];
	
    settype($site,"integer");
    settype($class,"integer");
    settype($year,"integer");
    settype($stud_sex,"integer");

    $year_class_site_sex=array($year,$class,$site,$stud_sex,$stud_name,$stud_pid);
    return $year_class_site_sex;
}



//找出第二學期相同科目的ss_id
function  same_name_ss_id($sel_year,$ss_id,$ccd){
    global $CONN;
	
	$score_semester2="score_semester_".$sel_year."_2";
	
	
	$sql="select ss_id from $score_semester2 where $ccd Group By ss_id";
	
	//echo $sql;
	$rs=$CONN->Execute($sql);
	if(is_object($rs)){
		while (!$rs->EOF) {
			$test_sort=$rs->fields["ss_id"];
			$show_subject=ss_id_to_subject_name($test_sort);
		    if ($show_subject==ss_id_to_subject_name($ss_id))
			{
			$ss_id=$test_sort;	
			//echo $show_subject;
			break;
			}
			
			
			$rs->MoveNext();
	
			
		}
	}
	
	
    return $ss_id;
		
 
}

//取得領域名稱
function ss_id_scope_name($ss_id,$sel_year,$class_id)
{
	global $CONN;
	
	
	$class_year=substr($class_id,0,1);
	
	if (substr($class_id,0,1)=="c")
	{
	 $class_year=substr($class_id,3,1);
	}
	else
	{
	 if (substr($class_id,3,1)=="_")
	 {
		 $class_year=substr($class_id,7,1);
	 }	
	 else
	 {
		 $class_year=substr($class_id,0,1);
	 }
	}
	

	$sql5="select link_ss,ss_id from score_ss where year='$sel_year' and class_year='$class_year' and ss_id='$ss_id' Limit 1";
		
	//echo $sql5;
    $rs5=$CONN->Execute($sql5);
    if(is_object($rs5))
	{
		//$show_subject=array();
	 while (!$rs5->EOF) 
	{
    
	$show_subject=$rs5->fields["link_ss"]."領域";
	
	 
	 $rs5->MoveNext();
	}		 
	}		
	
	return $show_subject;
}



//由ss_id找出相同領域名稱的ss_id
function  ss_id_to_scope_id($ss_id,$sel_year,$class_id){
    global $CONN;
        $sql3="select link_ss from score_ss where ss_id=$ss_id";
        $rs3=$CONN->Execute($sql3);
        $subject_id = $rs3->fields["link_ss"];
		
		$class_year=substr($class_id,0,1);
		
        $sql4="select ss_id from score_ss where year='$sel_year' and class_year='$class_year' and link_ss='$subject_id'";
		
		//echo $sql4;
        $rs4=$CONN->Execute($sql4);
     	if(is_object($rs4)){
			$h=0;
		while (!$rs4->EOF) {
			$h++;
	        $show_subject[$h]=$rs4->fields["ss_id"];
	
     		$rs4->MoveNext();
	
			
		}
	}

    return $show_subject;
}



function sortview($sel_year,$sel_seme,$class_id,$ss_id,$test_sort,$test_kind,$test_percent,$pr=0)
{
global $CONN;
//echo ss_id_to_scope_name($ss_id);

//echo "\$class_id:$class_id";

//echo "\$ss_id:$ss_id";

//if (substr($class_id,0,1)=="c")$class_year=substr($class_id,3,1);

	if (substr($class_id,0,1)=="c")
	{
	$class_year=substr($class_id,3,1);
	}
	else
	{
	 if (substr($class_id,3,1)=="_")
	 {
		 $class_year=substr($class_id,7,1);
	 }	
	 else
	 {
		 $class_year=substr($class_id,0,1);
	 }
	}

//echo "\$class_year:".$class_year;


//領域
if (substr($ss_id,0,1)=="s")
{
$ss_id=substr($ss_id,1);

$arry=ss_id_to_scope_id($ss_id,$sel_year,$class_year);
//print_r($arry);

$sss="";
while (list($key, $value) = each($arry)) {
$sss.="or ss_id='".$value."' ";
}

//echo "\$sss:".$sss;

}
//領域
	

	
	if ($test_percent>0)
	{
	
	//echo "\$sel_seme:$sel_seme";
	
	if ($sel_seme>0)
	{
	$score_semester="score_semester_".$sel_year."_".$sel_seme;
	//$ccd1="class_id Like '%$class_id%'";
	}
	else
	{
	$score_semester1="score_semester_".$sel_year."_1";	
	$score_semester2="score_semester_".$sel_year."_2";
    $ccd1="class_id Like '$sel_year%$class_id%'";
	
     if (substr($class_id,0,1)=="c")
	 {
	
	 $kf1=substr($class_id,1);	 
	 $ccd1="class_id Like '$sel_year%$kf1%'";
	 }

	$ss_idv=same_name_ss_id($sel_year,$ss_id,$ccd1);
	
	if (!empty($ss_id))$ssid1="and (ss_id='$ss_id' $sss)";
    if (!empty($ss_idv))$ssid2="and (ss_id='$ss_idv' $sss)";	
	
	

	}	
	
	
	//echo "\$ccd1:$ccd1";
	


	
	$ccd="";
	if (!empty($class_id))
	{
	 $ccd="class_id Like '$class_id%'";	 
	}
	
	
	
 
	
	
	$st="";
    
	if ($test_sort<255 and !empty($test_sort))
	{
		$st="and test_sort='$test_sort'";
	}
	
	$ssid="";
	if (!empty($ss_id))
	{
		$ssid="and (ss_id='$ss_id' $sss)";
	}
	
	
	$kin="";
	if (!empty($test_kind))
	{
	$kin="and test_kind Like '%$test_kind%'";	
	}
	

	if ($sel_seme>0)
	{

      $sql2="select * from $score_semester WHERE ss_id IN (SELECT ss_id FROM score_ss WHERE enable = '1') and $ccd $ssid $st $kin Group By student_sn";
	  //$sql2="select * from $score_semester where $ccd $ssid $st $kin Group By student_sn";
	
	}
	else
	{
		$sql2="select student_sn as sn from $score_semester1 WHERE ss_id IN (SELECT ss_id FROM score_ss WHERE enable = '1') and $ccd1 $ssid1 $st $kin UNION select student_sn as sn from $score_semester2 WHERE ss_id IN (SELECT ss_id FROM score_ss WHERE enable = '1') and $ccd1 $ssid2 $st $kin Group By sn";
		//$sql2="select student_sn as sn from $score_semester1 where $ccd1 $ssid1 $st $kin UNION select student_sn as sn from $score_semester2 where $ccd1 $ssid2 $st $kin Group By sn";

	}
	
	//echo "<br>1:".$sql2;
    $rs2=&$CONN->Execute($sql2);
	
    $count=$rs2->RecordCount();
	
	//echo "\$test_percent:".$test_percent;

  // echo "總人數:".$count;

 	
	
	//echo "\$prr:".$prr."<br>";
	$p="";
	if (!empty($test_percent))
	{
	$prr=round($test_percent*$count*0.01);	
	$p="Limit $prr";
	}
	
	if ($sel_seme>0)
	{
		
		
	$sql="select student_sn as sn,Sum(score) as tt from $score_semester WHERE ss_id IN (SELECT ss_id FROM score_ss WHERE enable = '1') and $ccd $ssid $st $kin Group By sn Order By SUM(score) $p";
	//$sql="select student_sn as sn,Sum(score) as tt from $score_semester where $ccd $ssid $st $kin Group By sn Order By SUM(score) $p";
	
	}
	else
	{
		
	$sql="select *,Sum(tscore) as tt from (select student_sn as sn,SUM(score) as tscore from $score_semester1 WHERE ss_id IN (SELECT ss_id FROM score_ss WHERE enable = '1') and $ccd1 $ssid1 $st $kin Group By sn UNION select student_sn as sn,SUM(score) as tscore from $score_semester2 WHERE ss_id IN (SELECT ss_id FROM score_ss WHERE enable = '1') and $ccd1 $ssid2 $st $kin Group By sn) MyDerivedTable Group By sn Order By tt $p";
	//$sql="select *,Sum(tscore) as tt from (select student_sn as sn,SUM(score) as tscore from $score_semester1 where $ccd1 $ssid1 $st $kin Group By sn UNION select student_sn as sn,SUM(score) as tscore from $score_semester2 where $ccd1 $ssid2 $st $kin Group By sn) MyDerivedTable Group By sn Order By tt $p";
		
	}
	
   //echo "<br>2:".$sql;
   $rs=&$CONN->Execute($sql);
	
	
    $i=0;
    $jx=0;
    if(is_object($rs))
	{
	 
	  while (!$rs->EOF) 
	  {
		    $i++;
            $test_sort=$rs->fields["sn"];		
			$tscore[$i]=$rs->fields["tt"];
			$ttr[$i]=$test_sort;			
            $rs->MoveNext();
			
        }
		
		$main0="";
		$csvmain="";
		$main="";
		
		$pl=$count*150;
		
		$main0.="<head>";
	    $main0.="<link rel='stylesheet' href='TableCSSCode.css' type='text/css'/>";	
	    $main0.="</head>";
		
		$main0.="<center><div class='CSSTableGenerator' style='width:600px;height:$pl px;'>";
		$main0.="<table >";
		$main0.="<tr><td>補救順位</td><td>班級</td><td>座號</td><td>姓名</td><td>成績</td><td>名次</td><td>PR值</td></tr>";
				
			
		$main.= "<center><table width=90% border=1><tr>";
		
		$main.= "<td width=5% align=middle>補救順位</td><td width=12% align=middle>身分證字號</td><td width=8% align=middle>班級</td><td width=8% align=middle>座號</td><td width=10% align=middle>姓名</td><td width=10% align=middle>成績</td><td width=5% align=middle>名次</td><td width=5% align=middle>PR值</td></tr>";
		
		$csvmain.="補救順位,身分證字號,班級,座號,姓名,成績,名次,PR值\r\n";
		
		//echo "xx";
		//$minch=$count+1;
		$m=0;
		
		for($k=$i;$k>=0;$k--)
		{
			if ($tscore[$k] !=$tscore[$k+1])
			{
			$m=$m+$jx+1;
		    $jx=0;					
			}
			else
			{
			
			$jx++;
			
			}	
			
			$ym[$k]=$m;
		}
		
		
		
		for($k=1;$k<=$i;$k++)
		{
    
	        //$minch--;
				
			$pr_value=round((($count-$ym[$k])/$count)*100);
			
			$sa=student_sn_to_classinfo2($ttr[$k],$sel_year);
			
		    
			$main0.= "<tr><td align=middle>".$k."</td><td>".$sa[1]."</td><td>".$sa[2]."</td><td>".$sa[4]."</td><td>".round($tscore[$k],2)."</td><td>".$ym[$k]."</td><td>".$pr_value."</td></tr>";

			
			$main.= "<tr><td align=middle>".$k."</td><td align=middle>".$sa[5]."</td><td align=middle>".$sa[1]."</td><td align=middle>".$sa[2]."</td><td align=middle>".$sa[4]."</td><td align=middle>".round($tscore[$k],2)."</td><td align=middle>".$ym[$k]."</td><td align=middle>".$pr_value."</td></tr>";
		   
			
			$csvmain.=$k.",".$sa[5].",".$sa[1].",".$sa[2].",".$sa[4].",".round($tscore[$k],2).",".$ym[$k].",".$pr_value."\r\n";
 
		}
        
		$main.= "</table></center>";
		 
		 
		$main0.="</table>";
		
		$main0.="</div>";
		 
		
    }
	
	}
	if ($pr==0)return $main0;
	if ($pr==1)return $main;
	if ($pr==2)return $csvmain;
}


//科目或領域選單
function subject_menu($sel_year,$sel_seme,$class_id,$ss_id,$test_kind,$test_sort,$id) {
	global $CONN,$score_semester,$choice_kind,$yorn;
	
	$score_semester="score_semester_".$sel_year."_".$sel_seme;
	
	$ccd="";
	if (!empty($class_id))
	{
	$ccd="class_id Like '$class_id%'";
	}
	
	if (empty($sel_seme))
	{
		
	 $sel_seme1=1;
	// $sel_seme2=2;

     $score_semester="score_semester_".intval($sel_year)."_".$sel_seme1;
	// $score_semester2="score_semester_".intval($sel_year)."_".$sel_seme2;
	 
	 $ccd="class_id Like '$sel_year%$class_id%'";

	 
	 if (substr($class_id,0,1)=="c")
	 {
	
	 $kf1=substr($class_id,1);	 
	 $ccd="class_id Like '$sel_year%$kf1%'";
	 }
	}
	


	$sql="select ss_id from $score_semester where $ccd Group By ss_id";

	
	//echo $sql;

	$rs=$CONN->Execute($sql);
	if(is_object($rs)){
		
		$arry=array();
		
		while (!$rs->EOF) {
			$test_sort=$rs->fields["ss_id"];
			$show_subject[$test_sort]=ss_id_to_subject_name($test_sort);
			
			
			$arry=ss_id_to_scope_id($test_sort,$sel_year,$class_id);
			

		   
            $ac[$test_sort]=ss_id_scope_name($test_sort,$sel_year,$class_id);
			
			$rs->MoveNext();
		}
		
		 
		 //刪除陣列重複值
		 $ac=array_unique($ac);
		//print_r($ac);
		
		 //$show_subject=array_merge($show_subject,$ac);
		 
          while (list($key, $value) = each($ac)) {
         // echo "Key: $key; Value: $value<br />\n";
		  $show_subject["s".$key]=$value;
		   
          }
		
	}
	

	$ss = new drop_select();
	$ss->s_name ="subject";
	$ss->top_option = "選擇科目或領域";
	$ss->id = $id;
	$ss->arr = $show_subject;
	$ss->is_submit = true;

	return $ss->get_select();
}




// 後面%選單

function percent_menu($id) {

      for ($i=0;$i<=100;$i++)
	  {
		  $test_sort=$i;
		  $show_percent[$test_sort]=$i."%";
	
		  
	  }
	  
	if (empty($id))$id=100;  

	$ss = new drop_select();
	$ss->s_name ="percent";
	$ss->top_option = "選擇後面%";
	$ss->id = $id;
	$ss->arr = $show_percent;
	$ss->is_submit = true;
	return $ss->get_select();
}


//由ss_id找出科目名稱的函數
function  ss_id_to_subject_name($ss_id){
    global $CONN;
    $sql1="select subject_id from score_ss where ss_id=$ss_id";
    $rs1=$CONN->Execute($sql1);
    $subject_id = $rs1->fields["subject_id"];
    if($subject_id!=0){
        $sql2="select subject_name from score_subject where subject_id=$subject_id";
        $rs2=$CONN->Execute($sql2);
        $subject_name = $rs2->fields["subject_name"];
    }
    else{
        $sql3="select scope_id from score_ss where ss_id=$ss_id";
        $rs3=$CONN->Execute($sql3);
        $scope_id = $rs3->fields["scope_id"];
        $sql4="select subject_name from score_subject where subject_id=$scope_id";
        $rs4=$CONN->Execute($sql4);
        $subject_name = $rs4->fields["subject_name"];
    }
    return $subject_name;
}



//由ss_id找出領域名稱的函數
function  ss_id_to_scope_name($ss_id){
    global $CONN;
        $sql3="select scope_id from score_ss where ss_id=$ss_id";
        $rs3=$CONN->Execute($sql3);
        $scope_id = $rs3->fields["scope_id"];
        $sql4="select subject_name from score_subject where subject_id=$scope_id";
        $rs4=$CONN->Execute($sql4);
        $scope_name = $rs4->fields["subject_name"];

    return $scope_name;
}




function stage_menu2($sel_year,$sel_seme,$sel_class,$sel_num,$id,$all="",$other_script="") {
	global $CONN,$score_semester,$choice_kind,$yorn;
	
	//echo "\$sel_seme:$sel_seme";
	
	if (empty($sel_seme))
	{
		
	$sel_seme=2;

    $score_semester="score_semester_".intval($sel_year)."_".$sel_seme;
	}
	
	if (empty($sel_class) && !empty($sel_num))
	{
		$sel_class=substr($sel_num,1); 
		$tt="";
	}
	
	if (!empty($sel_class) && !empty($sel_num))
	{
		$tt="and c_sort='$sel_num'";
	}
	

	$sql="select class_id from school_class where year='$sel_year' and semester='$sel_seme' and c_year='$sel_class' $tt";
	
	
	//echo $sql;
	$rs=$CONN->Execute($sql);
	$class_id=$rs->fields["class_id"];
	if ($all) {
		$class_id=substr($class_id,0,strlen($class_id)-2)."%";
		$sql="select distinct test_sort from $score_semester where class_id like '$class_id' and test_sort < '200' order by test_sort";
	} else {
		$sql="select distinct test_sort from $score_semester where class_id Like '$class_id%' order by test_sort";
	}
	
	//echo $sql;
	
	$rs=$CONN->Execute($sql);
	if(is_object($rs)){
		while (!$rs->EOF) {
			$test_sort=$rs->fields["test_sort"];
			if($test_sort<200)	$show_stage[$test_sort]="第".$test_sort."階段";
			$rs->MoveNext();
		}
	}
	
	//array_push($show_stage, "全學期");
    $show_stage["255"]="全學期";
	
	if ($yorn=="n") $show_stage["254"]="平時成績";
	$rs=$CONN->Execute("select distinct print from score_ss where class_year='$sel_class' and enable='1' and need_exam='1' and print!='1'");
	if ($rs->recordcount()>0) $show_stage["255"]="全學期";
	$ss = new drop_select();
	$ss->s_name ="stage";
	$ss->top_option = "選擇階段";
	$ss->id = $id;
	$ss->arr = $show_stage;
	$ss->is_submit = true;
	$ss->other_script = $other_script;
	return $ss->get_select();
}




function kind_menu2($sel_year,$sel_seme,$sel_class,$sel_num,$stage,$id) {
	global $CONN;
	$show_kind=array("1"=>"定期評量","2"=>"平時成績","3"=>"定期+平時");

	$sk = new drop_select();
	$sk->s_name ="kind";
	$sk->top_option = "選擇種類";
	$sk->id = $id;
	$sk->arr = $show_kind;
	$sk->is_submit = true;
	return $sk->get_select();
}




function score_head2($sel_year,$sel_seme,$year_name,$me,$stage,$chart_kind,$subject_name){
    global $CONN,$school_kind_name;
	
    $yn=substr($me,1);
	if ($yn>6)$yn=$yn-6;
	
    $rs1=&$CONN->Execute("select * from school_base");
    $sch_sheng=$rs1->fields['sch_sheng'];
    $sch_cname=$rs1->fields['sch_cname'];
    if(strlen($sel_year)==2) $sel_year="0".$sel_year;
    if(strlen($year_name)==1) $year_name="0".$year_name;
    if(strlen($me)==1) $me="0".$me;
    $class_id=$sel_year."_".$sel_seme."_".$year_name."_".$me;
    $rs2=&$CONN->Execute("select * from school_class where class_id='$class_id'");
    $c_year=$rs2->fields['c_year'];
    $c_name=$rs2->fields['c_name'];
    settype($sel_year,"integer");
    $stage_name=array(1=>"第一階段",2=>"第二階段",3=>"第三階段",4=>"第四階段","255"=>"全學期");
	
	$subject_name=ss_id_to_subject_name($subject_name);
	
	if (!empty($c_name))
	{
		$c_name=$c_name."班";
	}
	else
	{
		$c_name=$yn."年級";
	}
	
	if($sel_seme==0){
		$sel_seme="上下";
		if (intval($year_name)>6)$year_name=intval($year_name)-6;
		$c_name=$year_name."年".$me."班";
		if (substr($me,0,1)=="c")
		{
			$c_name=(substr($me,1)-6)."年級";
		}
		
		
	}
	//echo $c_name;
	
    return $sch_cname.$sel_year."學年度第".$sel_seme."學期".$school_kind_name[$c_year].$c_name.$stage_name[$stage].$subject_name.$chart_kind."成績表";
}



?>
