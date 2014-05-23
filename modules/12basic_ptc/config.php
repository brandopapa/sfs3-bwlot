<?php

//預設的引入檔，不可移除。
include_once "../../include/config.php";
require_once "./module-cfg.php";
require_once "./module-upgrade.php";
include "my_fun.php";
include "../../include/sfs_case_score.php";

//教育會考地區代碼
$area_code='12';

//學校代碼
$school_id=$SCHOOL_BASE['sch_id'];

//畢業年級
$graduate_year=$IS_JHORES?9:6;

//畢業資格級分  0.修業 1.畢業
$graduate_score=array(0=>0,1=>2,2=>0);

//志願序級分
$rank_score='0,7,5,3,2,1';
$rank_score_array=explode(',',$rank_score);

//國中三年就讀偏遠地區學校者級分加分
$remote_level=array(0=>'不符偏遠地區加分條件',1=>'國中三年就讀偏遠地區學校');
	
//低收入、中低收入戶級分加分
$disadvantage_level=array(0=>'無 ',2=>'低收入戶',1=>'中低收入戶');

//均衡學習單一領域及格級分得分
$balance_score=3;
$balance_score_max=9;
$balance_semester=array('1001','1002','1011','1012','1021');
$balance_area=array('health','art','complex');


//服務表現級分得分
//班級幹部 & 特殊服務表現
$leader_allowed=array(1=>'班長',2=>'副班長',3=>'學藝股長',4=>'風紀股長',5=>'衛生股長',6=>'服務股長',7=>'總務股長',8=>'事務股長',9=>'康樂股長',10=>'體育股長',11=>'輔導股長',12=>'特殊服務表現');
$class_leader=1;
$leader_semester=array('7-1','7-2','8-1','8-2','9-1');

//社團社長
$association_leader=0.5;
$association_semester="'1001','1002','1011','1012','1021'";

$service_score_max=5;

//無記過紀錄
$fault_none=8;
$fault_warning=6;
$fault_peccadillo=4;
$fault_score_max=8;

//獎勵紀錄  屏東縣未使用
$reward_score[1]=0.5;
$reward_score[3]=1;
$reward_score[9]=3;
$reward_score_max=4;

//適性發展
$my_aspiration=2;
$domicile_suggestion=2;
$guidance_suggestion=2;

//教育會考
$exam_subject=array('c'=>'國文','m'=>'數學','e'=>'英語','s'=>'社會','n'=>'自然');
$exam_score_well=5;
$exam_score_ok=3;
$exam_score_no=1;
$exam_score_max=25;

//總分
$max_score=79;

//取得模組參數的類別設定
$m_arr = &get_module_setup("12basic_ptc");
extract($m_arr,EXTR_OVERWRITE);

//身分類別代碼
$stud_kind_arr=array('0'=>'一般生','1'=>'原住民','2'=>'派外人員子女','3'=>'蒙藏生','4'=>'回國僑生','5'=>'港澳生','6'=>'退伍軍人','7'=>'境外優秀科學技術人才子女');

//身心障礙
//$stud_disability_arr=array('0'=>'非身心障礙考生','1'=>'智能障礙','2'=>'視覺障礙','3'=>'聽覺障礙','4'=>'語言障礙','5'=>'肢體障礙','6'=>'身體病弱','7'=>'情緒行為障礙','8'=>'學習障礙','9'=>'多重障礙','A'=>'自閉症','B'=>'其他障礙');
$stud_disability_arr=array('0'=>'非身心障礙考生','1'=>'智能障礙','2'=>'視覺障礙','3'=>'聽覺障礙','4'=>'語言障礙','5'=>'肢體障礙','6'=>'腦性麻痺','7'=>'身體病弱','8'=>'情緒行為障礙','9'=>'學習障礙','A'=>'多重障礙','B'=>'自閉症','C'=>'發展遲緩','D'=>'其他障礙');

//身分類別低收失業對照
$stud_free_arr=array('0'=>'一般生','1'=>'低收入戶','2'=>'中低收入戶','3'=>'失業勞工');
$stud_free_rate=array(0=>0,1=>2,2=>1,3=>0.5);

//競賽成績 ( 搭配 career_race 資料表 )  ( 得要通告學校  有效的名次選項 )
$level_array=array(1=>'國際',2=>'全國、臺灣區',3=>'區域性（跨縣市）',4=>'省、直轄市',5=>'縣市區（鄉鎮）',6=>'校內'); 
//$squad_array=array(1=>'個人賽',2=>'團體賽');
//$squad_team=array('0.5'=>'4人','0.25'=>'20人');
$race_score[1]=array('第一名'=>9,'冠軍'=>9,'金牌'=>9,'特優'=>9,'第二名'=>8,'亞軍'=>8,'銀牌'=>8,'優等'=>8,'第三名'=>7,'季軍'=>7,'銅牌'=>7,'甲等'=>7,'第四名'=>6,'殿軍'=>6,'佳作'=>6,'第五名'=>5,'第六名'=>4);
$race_score[2]=array('第一名'=>6,'冠軍'=>6,'金牌'=>6,'特優'=>6,'第二名'=>5,'亞軍'=>5,'銀牌'=>5,'優等'=>5,'第三名'=>4,'季軍'=>4,'銅牌'=>4,'甲等'=>4,'第四名'=>3,'殿軍'=>3,'佳作'=>3,'第五名'=>2,'入選'=>2,'第六名'=>1);
$race_score[4]=array('第一名'=>3,'冠軍'=>3,'金牌'=>3,'特優'=>3,'第二名'=>2,'亞軍'=>2,'銀牌'=>2,'優等'=>2,'第三名'=>1,'季軍'=>1,'銅牌'=>1,'甲等'=>1);
$race_score_max=9;


//體適能
$fitness_score_one=1;
$fitness_score_one_max=4;
$fitness_addon=array('gold'=>2,'silver'=>1,'copper'=>0.5);
$fitness_semester="'1001','1002','1011','1012','1021','1022'";
$fitness_score_disability=4;
$fitness_score_max=6;
$fitness_medal=array('gold'=>'金','silver'=>'銀','copper'=>'銅','no'=>'--');


//屬性欄位順序名稱對照
$kind_field_mirror=array(1=>'clan',2=>'area',3=>'memo',4=>'note');

//因為這三個模組變數為後面產生  怕學校已安裝未取回  所以設個預設值
$native_id=intval($native_id)?$native_id:9;
$native_language_sort=intval($native_language_sort)?$native_language_sort:3;
$native_language_text=$native_language_text?$native_language_text:'是';




?>
