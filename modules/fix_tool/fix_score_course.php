<?php
//$Id:  $
include "config.php";
//認證
sfs_check();

//引入換頁物件(學務系統用法)
// include_once "../../include/chi_page2.php";
//程式使用的Smarty樣本檔
$template_file = dirname (__file__)."/templates/fix_score_course.htm";

//建立物件
$obj= new score_ss($CONN,$smarty);
//初始化
$obj->init();
//處理程序,有時程序內有header指令,故本程序宜於head("score_ss模組");之前
$obj->process();

//秀出網頁布景標頭
head("課表資料檢視與刪除");

//顯示SFS連結選單(欲使用請拿開註解)
echo make_menu($school_menu_p);

//顯示內容
$obj->display($template_file);
//佈景結尾
foot();


//物件class
class score_ss{
	var $CONN;//adodb物件
	var $smarty;//smarty物件
	var $size=10;//每頁筆數
	var $page;//目前頁數
	var $tol;//資料總筆數
   var $IS_JHORES;
	var $year;
   var $seme;
   var $YS='YS';//下拉式選單學期的奱數名稱
   var $year_seme;//下拉式選單班級的奱數值
   var $Sclass='class_id';//下拉式選單班級的奱數名稱
   var $grade_name='Grade';//下拉式選單年級的奱數名稱
   var $Grade;

	//建構函式
	function score_ss($CONN,$smarty){
		$this->CONN=&$CONN;
		$this->smarty=&$smarty;
      global $IS_JHORES;
      $this->IS_JHORES=$IS_JHORES;
      ($_GET[$this->YS]=='') ? $this->year_seme=curr_year()."_".curr_seme():$this->year_seme=$_GET[$this->YS];
      if ($_GET['Tsn']!='') $this->Tsn=(int)$_GET['Tsn'];
      $aa=split("_",$this->year_seme);
      $this->year=$aa[0];
      $this->seme=$aa[1];
	}
	//初始化
	function init() {$this->page=($_GET[page]=='') ? 0:$_GET[page];}
	//程序
	function process() {
		//http://localhost/sfs3/modules/fix_tool/fix_print2.php?year_seme=98_2&Grade=7
		// fix_score_course.php?YS=103_1&Tsn=52
		if($_GET['act']=='del') $this->del();
		if($_GET['act']=='delall') $this->delall();
		$this->all();
	}
	//顯示
	function display($tpl){
		$this->smarty->assign("this",$this);
		$this->smarty->display($tpl);
		//echo "<pre>";print_r($this->Course);
	}
	//擷取資料
	function all(){

		if ($this->year=='') return ;
		if ($this->seme=='') return ;
		if ($this->Tsn=='') return ;
		//所有課程設定
		$SQL="select * from  score_course  where year='{$this->year}' 	and  semester='{$this->seme}' and teacher_sn='{$this->Tsn}' order by class_year,class_name,day,sector 	  ";
		$rs=$this->CONN->Execute($SQL) or die($SQL);
		$arr=$rs->GetArray();
		$this->all=$arr;//return $arr;
		$this->tol=count($arr);
		//return ;
		
		/*取課程中文名稱*/
  		$SQL="select subject_id,subject_name from score_subject ";
		$rs=$this->CONN->Execute($SQL) or die("無法查詢，語法:".$SQL);
		if ($rs->RecordCount()==0) return "尚未設定任何科目資料！";
		$obj=$rs->GetArray();
		foreach($obj as $ary){
			$id=$ary[subject_id];
			$this->Subj[$id]=$ary[subject_name];
		}

		$this->SsidToName=$this->SsidToName();
	}
	function SsidToName(){
		//所有課程設定
		$SQL="select * from score_ss where year='{$this->year}' 	and  semester='{$this->seme}'   ";
		$rs=$this->CONN->Execute($SQL) or die($SQL);
		$arr=$rs->GetArray();
		//$this->all=$arr;//return $arr;		
		foreach ($arr as $ary){
			$id=$ary[ss_id];
			$scope=$ary[scope_id];
			$subject=$ary[subject_id];
			$AA[$id]=$this->Subj[$scope].':'.$this->Subj[$subject];
		}	
	return $AA;
	}

	function delall(){
		if ($this->Tsn=='' || $this->Tsn==0) return ;
		//所有課程設定
		$SQL="delete  from  score_course  where year='{$this->year}' and  semester='{$this->seme}'  and   	teacher_sn='{$this->Tsn}' ";
		$rs=$this->CONN->Execute($SQL) or die($SQL);
//echo $SQL;
		$URL=$_SERVER[PHP_SELF]."?YS=".$this->year_seme;
		Header("Location:$URL");
	}
	function del(){
		$id=(int)$_GET['id'];
		if ($id==0 || $id=='') die("無法查詢，語法:".$SQL);
		//所有課程設定
		$SQL="delete  from  score_course  where year='{$this->year}' and  semester='{$this->seme}'  and course_id='$id' ";
		$rs=$this->CONN->Execute($SQL) or die($SQL);
//echo $SQL;
		$URL=$_SERVER[PHP_SELF]."?YS=".$this->year_seme."&Tsn=".$_GET['Tsn'];
		Header("Location:$URL");
	}



##################  學期下拉式選單函式  ##########################
function select($show_class=1) {
    $SQL = "select * from school_day where  day<=now() and day_kind='start' order by day desc ";
    $rs=&$this->CONN->Execute($SQL) or die("無法查詢，語法:".$SQL);
    while(!$rs->EOF){
        $ro = $rs->FetchNextObject(false);
        // 亦可$ro=$rs->FetchNextObj()，但是僅用於新版本的adodb，目前的SFS3不適用
        $year_seme=$ro->year."_".$ro->seme;
        $obj_stu[$year_seme]=$ro->year."學年度第".$ro->seme."學期";
    }
    $str="<select name='".$this->YS."' onChange=\"location.href='".$_SERVER[PHP_SELF]."?".$this->YS."='+this.options[this.selectedIndex].value;\">\n";
        //$str.="<option value=''>-未選擇-</option>\n";
    foreach($obj_stu as $key=>$val) {
        ($key==$this->year_seme) ? $bb=' selected':$bb='';
        $str.= "<option value='$key' $bb>$val</option>\n";
        }
    $str.="</select>";
    return $str;
}

##################  學期下拉式選單函式  ##########################
function select_tea($select) {
		if ($this->year=='') return ;
		if ($this->seme=='') return ;

	$SQL="select  a.teacher_sn,a.name,a.sex ,count(b.course_id) as tol from teacher_base a, score_course b where a.teacher_sn=b.teacher_sn and b.year='{$this->year}'  and b.semester='{$this->seme}' group by b.teacher_sn order by  hex(left(a.name,2)),a.sex desc  ";
		$rs=$this->CONN->Execute($SQL) or die($SQL);
		$this->tea=$rs->GetArray();
		//echo "<pre>";print_r($arr);
		$str="<select name='".$this->YS."' onChange=\"location.href='".$_SERVER[PHP_SELF]."?YS=".$this->year_seme."&Tsn='+this.options[this.selectedIndex].value;\">\n";
		$str.="<option value=''>-未選擇-</option>\n";

	foreach($this->tea as $ary) {
		$key=$ary['teacher_sn'];
		if ($ary['sex']=='1') {$SS=' class=blue ';$val=$ary['name']."(".$ary['tol'].")";}
		if ($ary['sex']=='2') {$SS=' class=red ';$val=$ary['name']."(".$ary['tol'].")";}
		($ary['teacher_sn']==$this->Tsn) ? $bb=' selected':$bb='';
		$str.= "<option value='$key' $bb $SS>$val</option>\n";
        }
    $str.="</select>";
    return $str;
}






} 
