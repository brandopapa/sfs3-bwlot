<?php

//預設的引入檔，不可移除。
include_once "../../include/config.php";
require_once "./module-cfg.php";
require_once "./module-upgrade.php";
include "my_fun.php";
include "../../include/sfs_case_score.php";

//教育會考地區代碼
//$area_code='12';

//學校代碼
$school_id=$SCHOOL_BASE['sch_id'];

//畢業年級
$graduate_year=$IS_JHORES?9:6;

//均衡學習單一領域及格積分得分
$balance_score=2;
$balance_score_max=6;
$balance_semester=array('1011','1012','1021','1022','1031');
$balance_area=array('health','art','complex');


//服務學習積分得分
//五專不限定幹部名稱
//社團社長
$class_leader=1;
$club_leader=1;
$service_minutes=480;  //單位積分計量時間(8*60=480)
$service_score=1;   //每單位積分計量時間所得積分
$leader_score_max=6;
$service_score_max=7;


//日常生活表現評量
//無記過紀錄
$fault_none=1;
//獎勵
$reward_score[1]=2;  //功過相抵後、累積為嘉獎的積分
$reward_score[3]=3; //功過相抵後、累積為小功以上的積分
$reward_score[9]=4; //功過相抵後、累積為大功以上的積分
$reward_score_max=4;

//適性輔導
$adaptive_score_one=1;
$adaptive_score_max=3;

//教育會考
//$exam_subject=array('c'=>'國文','m'=>'數學','e'=>'英語','s'=>'社會','n'=>'自然');
//$exam_score_well=3;
//$exam_score_ok=2;
//$exam_score_no=1;
$exam_score_write=6; //寫作測驗級分
$exam_score_max=15;

$exam_level=array(1=>'A++',2=>'A+',3=>'A',4=>'B++',5=>'B+',6=>'B',7=>'C');
$exam_level_description=array(1=>'精熟',2=>'精熟',3=>'精熟',4=>'基礎',5=>'基礎',6=>'基礎',7=>'待加強');
$exam_level_bonus=array(1=>'3',2=>'3',3=>'3',4=>'2',5=>'2',6=>'2',7=>'1');


//多元學習表現積分總上限
$diversification_score_max=16;

//總分
$max_score=50;

//取得模組參數的類別設定
$m_arr = &get_module_setup("12basic_tech");
extract($m_arr,EXTR_OVERWRITE);

//身分類別代碼
//$stud_kind_arr=array('0'=>'一般生','1'=>'原住民','2'=>'派外人員子女','3'=>'蒙藏生','4'=>'回國僑生','5'=>'港澳生','6'=>'退伍軍人','7'=>'境外優秀科學技術人才子女');
$stud_kind_arr=array(
			0=>'一般生',
			1=>'原住民-未取得原住民文化及語言能力證明',
			2=>'原住民-取得原住民文化及語言能力證明',
			3=>'境外-返國(來臺)就讀未滿一學年',
			4=>'境外-返國(來臺)就讀一學年以上未滿二學年',
			5=>'境外-返國(來臺)就讀二學年以上未滿三學年',
			6=>'派外-返國(來臺)就讀未滿一學年',
			7=>'派外-返國(來臺)就讀一學年以上未滿二學年',
			8=>'派外-返國(來臺)就讀二學年以上未滿三學年',
			9=>'蒙藏生',
			10=>'身障生',
			11=>'僑生',
			12=>'退伍軍人-在營服役期間五年以上，退伍後未滿一年',
			13=>'退伍軍人-在營服役期間五年以上，退伍後一年以上未滿二年',
			14=>'退伍軍人-在營服役期間五年以上，退伍後二年以上未滿三年',
			15=>'退伍軍人-在營服役期間五年以上，退伍後三年以上未滿五年',
			16=>'退伍軍人-在營服役期間四年以上未滿五年，退伍後未滿一年',
			17=>'退伍軍人-在營服役期間四年以上未滿五年，退伍後一年以上未滿二年',
			18=>'退伍軍人-在營服役期間四年以上未滿五年，退伍後二年以上未滿三年',
			19=>'退伍軍人-在營服役期間四年以上未滿五年，退伍後三年以上未滿五年',
			20=>'退伍軍人-在營服役期間三年以上未滿四年，退伍後未滿一年',
			21=>'退伍軍人-在營服役期間三年以上未滿四年，退伍後一年以上未滿二年',
			22=>'退伍軍人-在營服役期間三年以上未滿四年，退伍後二年以上未滿三年',
			23=>'退伍軍人-在營服役期間三年以上未滿四年，退伍後三年以上未滿五年',
			24=>'退伍軍人-在營服役期間未滿三年，已達義務役法定役期，且退伍後未滿三年',
			25=>'退伍軍人-因作戰或因公成殘領有撫卹證明，於免役、除役後未滿',
			26=>'退伍軍人-因病成殘領有撫卹證明，於免役、除役後未滿五年'
);

//身心障礙
//$stud_disability_arr=array('0'=>'非身心障礙考生','1'=>'智能障礙','2'=>'視覺障礙','3'=>'聽覺障礙','4'=>'語言障礙','5'=>'肢體障礙','6'=>'身體病弱','7'=>'情緒行為障礙','8'=>'學習障礙','9'=>'多重障礙','A'=>'自閉症','B'=>'其他障礙');
//改為報名費減免身分
$stud_disability_arr=array(
			0=>'無',
			1=>'低收入戶',
			2=>'失業子女',
			3=>'中低收入戶',
);


//競賽成績 ( 搭配 career_race 資料表 )  ( 得要通告學校  有效的名次選項 )
$level_array=array(1=>'國際',2=>'全國、臺灣區',3=>'區域性（跨縣市）',4=>'省、直轄市',5=>'縣市區（鄉鎮）',6=>'校內');
$squad_array=array(1=>'個人賽',2=>'團體賽');
$race_score[1]=array('第一名'=>7,'冠軍'=>7,'金牌'=>7,'特優'=>7,'第二名'=>6,'亞軍'=>6,'銀牌'=>6,'優等'=>6,'第三名'=>5,'季軍'=>5,'銅牌'=>5,'甲等'=>5,'第四名'=>3,'殿軍'=>3,'佳作'=>3,'第五名'=>3,'第六名'=>3);
$race_score[2]=array('第一名'=>6,'冠軍'=>6,'金牌'=>6,'特優'=>6,'第二名'=>5,'亞軍'=>5,'銀牌'=>5,'優等'=>5,'第三名'=>4,'季軍'=>4,'銅牌'=>4,'甲等'=>4,'第四名'=>3,'殿軍'=>3,'佳作'=>3,'第五名'=>3,'入選'=>3,'第六名'=>3);
$race_score[3]=array('第一名'=>3,'冠軍'=>3,'金牌'=>3,'特優'=>3,'第二名'=>2,'亞軍'=>2,'銀牌'=>2,'優等'=>2,'第三名'=>1,'季軍'=>1,'銅牌'=>1,'甲等'=>1);
$race_score[4]=array('第一名'=>3,'冠軍'=>3,'金牌'=>3,'特優'=>3,'第二名'=>2,'亞軍'=>2,'銀牌'=>2,'優等'=>2,'第三名'=>1,'季軍'=>1,'銅牌'=>1,'甲等'=>1);
$race_score_max=7;
$squad_weight=array(1=>1,2=>0.5); //個人與團體權重
//*特殊的競賽項目
$spe_item_arr=array('國際發明展','台北國際發明暨技術交易展');
$spe_bonus_arr=array('第一名'=>4,'冠軍'=>4,'金牌'=>4,'特優'=>4,'第二名'=>3,'亞軍'=>3,'銀牌'=>3,'優等'=>3,'第三名'=>2,'季軍'=>2,'銅牌'=>2);

//體適能   //特殊學生請利用單獨修改功能
$fitness_score_one=2;
//$fitness_score_one_max=4;
//$fitness_addon=array('gold'=>2,'silver'=>1,'copper'=>0.5);
$fitness_semester="'1011','1012','1021','1022','1031','1032'";
$fitness_score_max=6;
//$fitness_medal=array('gold'=>'金','silver'=>'銀','copper'=>'銅','no'=>'--');

//弱勢身分
$stud_free_arr=array(
			0=>'無',
			1=>'低收入戶',
			2=>'失業子女',
			3=>'中低收入戶',
			4=>'特殊境遇家庭'
);
$stud_free_rate=array(0=>0,1=>2,2=>2,3=>2,4=>2);
$disadvantage_score_max=2;

//其他
$others_array['GEPT']['name']='全民英語能力分級檢定測驗(GEPT)';
	//$others_array['GEPT']['items']=array('A1'=>'初級初試','A2'=>'初級複試','B1'=>'中級初試','B2'=>'中級複試','C1'=>'中高級初試','C2'=>'中高級複試','D1'=>'高級初試','D2'=>'高級複試','E0'=>'優級');
	$others_array['GEPT']['items']=array(
		'0'=>'(無)',
		'1'=>'初級 初試及格',
		'2'=>'初級 複試及格',
		'3'=>'中級 初試及格',
		'4'=>'中級 複試及格',
		'5'=>'中高級 初試及格',
		'6'=>'中高級 複試及格',
		'7'=>'高級 初試及格',
		'8'=>'高級 複試及格',
		'9'=>'優級 初試及格',
		'10'=>'優級 複試及格',
	);
$others_array['TOEIC']['name']='多益測驗(TOEIC)';
	//$others_array['TOEIC']['items']=array('A'=>'A級(聽力400以上；閱讀385以上)','B'=>'B級(聽力275以上；閱讀275以上)','C'=>'C級(聽力110以上；閱讀115以上)');
	$others_array['TOEIC']['items']=array(
		'0'=>'(無)',
		'1'=>'聽力110以上 閱讀115以上',
		'2'=>'聽力275以上 閱讀275以上',
		'3'=>'聽力400以上 閱讀385以上'
	);

//屬性欄位順序名稱對照
$kind_field_mirror=array(1=>'clan',2=>'area',3=>'memo',4=>'note');


//報名學校對照
$schools['north']=array(
	'000'=>'(未選填)',
	'111'=>'國立臺北商業大學',
	'112'=>'大華科技大學',
	'113'=>'慈濟技術學院',
	'114'=>'致理技術學院',
	'115'=>'醒吾科技大學',
	'116'=>'臺北城市科技大學',
	'117'=>'蘭陽技術學院',
	'118'=>'德霖技術學院',
	'119'=>'經國管理暨健康學院',
	'120'=>'黎明技術學院',
	'121'=>'長庚科技大學',
	'122'=>'華夏科技大學',
	'123'=>'崇右技術學院',
	'124'=>'台北海洋技術學院',
	'125'=>'台灣觀光學院',
	'126'=>'康寧醫護暨管理專科學校',
	'127'=>'馬偕醫護管理專科學校',
	'128'=>'耕莘健康管理專科學校',
	'129'=>'聖母醫護管理專科學校',
	'130'=>'新生醫護管理專科學校',
	'131'=>'聖約翰科技大學',
	'132'=>'桃園創新技術學院',
	'133'=>'龍華科技大學',
	'134'=>'健行科技大學'	
);

$schools['central']=array(
	'000'=>'(未選填)',
	'211'=>'國立臺中科技大學',
	'212'=>'弘光科技大學',
	'213'=>'南開科技大學',
	'214'=>'仁德醫護管理專科學校'
);

$schools['south']=array(
	'000'=>'(未選填)',
	'311'=>'國立高雄海洋科技大學',
	'312'=>'國立臺南護理專科學校',
	'313'=>'大同技術學院',
	'314'=>'東方設計學院',
	'315'=>'文藻外語大學',
	'316'=>'美和科技大學',
	'317'=>'南榮科技大學',
	'318'=>'輔英科技大學',
	'319'=>'中華醫事科技大學',
	'320'=>'和春技術學院',
	'321'=>'慈惠醫護管理專科學校',
	'322'=>'樹人醫護管理專科學校',
	'323'=>'敏惠醫護管理專科學校',
	'324'=>'高美醫護管理專科學校',
	'325'=>'育英醫護管理專科學校',
	'326'=>'崇仁醫護管理專科學校',
	'327'=>'國立臺東專科學校',
	'328'=>'國立高雄餐旅大學'
);



//禁止直接修改多元學習表現級分(因為需要即時計算)
$diversification_editable=0;


?>
