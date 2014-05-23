<?php

// $Id: sfs_case_dataarray.php 7663 2013-10-08 11:38:04Z hami $
// 各種資料陣列
// 取代原 data_array_function.php

//在學情形〈尚未用到〉
function study_cond() {
	 return array("0"=>"在籍","1"=>"轉出","2"=>"轉入","3"=>"中輟復學","4"=>"休學復學","5"=>"畢業","6"=>"休學","7"=>"出國","8"=>"調校","9"=>"升級","10"=>"降級","11"=>"死亡","12"=>"中輟","13"=>"新生入學","14"=>"轉學復學","15"=>"在家自學");
}

//學生身份別
function stud_kind(){
	$res = SFS_TEXT("stud_kind");
	if (count($res))
		return $res;
	else
	return array("0"=>"一般學生","1"=>"本人殘障","2"=>"家長殘障","3"=>"低收入戶","4"=>"大陸來台依親者","5"=>"功勳子女","6"=>"海外僑生","7"=>"港澳生","8"=>"邊疆生","9"=>"原住民","10"=>"外籍生","11"=>"資優生","12"=>"派外人員子女","13"=>"體育績優","14"=>"顏面傷殘","15"=>"教職員子女","16"=>"公教遺族(因公)","17"=>"公教遺族(因病)","18"=>"身心障礙(檢定)","19"=>"其他");
}

//班級性質
function stud_class_kind(){
	return array("0"=>"一般班","1"=>"特殊班");
}

//特殊班類別
function stud_spe_kind() {
	$res = SFS_TEXT("stud_spe_kind");
	if (count($res))
		return $res;
	else
	return array("1"=>"障礙類","2"=>"資優類","3"=>"資源班");
}

//特殊班上課性質
function stud_spe_class_id() {
	$res = SFS_TEXT("stud_spe_class_id");
	if (count($res))
		return $res;
	else
	return array("1"=>"集中式","2"=>"分散式");
}

//特殊班班別
function stud_spe_class_kind() {
	$res = SFS_TEXT("spe_class_kind");
	if (count($res))
		return $res;
	else
	return array("1"=>"啟智","2"=>"啟明","3"=>"啟聰","4"=>"巡迴輔導","5"=>"啟學","6"=>"啟聲","7"=>"啟健","8"=>"啟迪","9"=>"啟仁(肢障)","10"=>"語障","11"=>"身心障礙","12"=>"學習困難","13"=>"在家教育","14"=>"多重障礙","15"=>"一般智賦優異","16"=>"音樂","17"=>"美術","18"=>"舞蹈","19"=>"體育","20"=>"其他");
}

//入學資格
function stud_preschool_status() {
	$res = SFS_TEXT("preschool_status");
	if (count($res))
		return $res;
	else
		return array("0"=>"本學區","1"=>"大學區","2"=>"隨父就讀","3"=>"隨母就讀");
}

//輔導個案編號〈尚未用到〉
function stud_eduh() {
	$res = SFS_TEXT("stud_eduh");
	if (count($res))
		return $res;
	else
		return array("1"=>"長期輔導","2"=>"導師轉介","3"=>"訓導處轉介","4"=>"個案主動咨詢","99"=>"其他");
}

//輔導個案編號〈尚未用到〉
function eh_caes() {
	$res = SFS_TEXT("eh_caes");
	if (count($res))
		return $res;
	else
		return array("1"=>"行為","2"=>"學習困擾","3"=>"情緒","4"=>"人際關係","5"=>"兩性教育","6"=>"家庭適應不良","7"=>"生涯規劃","8"=>"師生關係","9"=>"價值觀","99"=>"其他");
}

//輔導個案編號〈尚未用到〉
function eh_meth() {
	$res = SFS_TEXT("eh_meth");
	if (count($res))
		return $res;
	else
		return array("1"=>"外向性攻擊行為","2"=>"內向性攻擊行為","3"=>"偷竊","4"=>"離家出走","5"=>"孤僻.羞怯","6"=>"衝動行為","7"=>"逃學","8"=>"焦慮行為","9"=>"容易分心","10"=>"說謊","11"=>"性不良適應","12"=>"恐嚇勒索","13"=>"遊蕩","14"=>"參加不良幫派","15"=>"賭博","16"=>"抽煙","17"=>"吸毒","18"=>"喝酒","99"=>"其他");
}

//職稱陣列
function title_kind() {
	global $CONN;
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	// init $arr
	$arr=array();

	$res = $CONN->Execute("select  teach_title_id ,title_name from teacher_title  where enable=1 order by teach_title_id ") or user_error("讀取失敗！",256);
	while (!$res->EOF) {
		$arr[$res->fields[0]] = $res->fields[1];
		$res->MoveNext();	
	}
	return $arr;
}

//職別陣列
function post_kind(){
	$res = SFS_TEXT("post_kind");
	if (count($res))
		return $res;
	else
		return array("1"=>"校長","2"=>"教師兼主任","3"=>"主任","4"=>"教師兼組長","5"=>"組長","6"=>"導師","7"=>"專任教師","8"=>"實習教師","9"=>"試用教師","10"=>"代理/代課教師","11"=>"兼任教師","12"=>"職員","13"=>"護士","14"=>"警衛","15"=>"工友");
}

//官等陣列
function official_level(){
	$res = SFS_TEXT("official_level");
	if (count($res))
		return $res;
	else
		return array("1"=>"簡任","2"=>"薦任","3"=>"委任");
}

//處室類別陣列
function room_kind(){
	global $CONN;
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	// init $arr
	$arr =array();

	$result = $CONN->Execute("select room_id , room_name from school_room where enable=1 order by room_id") or user_error("讀取失敗！",256);
	while (!$result->EOF){
		$arr[$result->fields[0]] = $result->fields[1];
		$result->MoveNext();
	}
	return $arr;
}


//教師現況陣列
function remove() {
	$res = SFS_TEXT("remove");
	if (count($res))
		return $res;
	else
	return array ("0"=>"在職","1"=>"調出","2"=>"退休","3"=>"代課期滿","4"=>"資遣");
}


//教師證照陣列
function tea_check_kind() {
	$res = SFS_TEXT("tea_check_kind");
	if (count($res))
		return $res;
	else
	return array("1"=>"本科或相關科檢定合格","2"=>"實習教師","3"=>"試用教師登記","4"=>"登記中","5"=>"其他");
}

//教師證照狀態陣列
function teach_check_kind() {
	$res = SFS_TEXT("teach_check_kind");
	if (count($res))
		return $res;
	else
	return array("1"=>"檢定","2"=>"登記","3"=>"審查","A"=>"本科或相關科登記","B"=>"非相關科登記","C"=>"技術教師登記","D"=>"試用教師登記","E"=>"實習教師");
}

//教師主要任教領域〈依96年教育類報表設定〉
function teach_category($level=0) {
	$res = SFS_TEXT("teach_category");
	if (count($res))
		return $res;
	else
		if ($level==0)
			return array("11"=>"本國","15"=>"英語","17"=>"鄉土語言","20"=>"健康與體育","30"=>"社會","40"=>"數學","50"=>"藝術與人文","60"=>"自然與生活科技","70"=>"綜合活動","80"=>"其他");
		else
			return array("11"=>"本國","15"=>"英語","91"=>"健康教育","92"=>"體育","93"=>"歷史","94"=>"地理","95"=>"公民","96"=>"美術","97"=>"音樂","98"=>"表演藝術","99"=>"生物","9A"=>"理化","9B"=>"地科","9C"=>"電腦","9D"=>"生活科技","9E"=>"家政","9F"=>"童軍","9G"=>"輔導","80"=>"其他");
}

//縣市陣列
function birth_state(){
	$res = SFS_TEXT("birth_state");
	if (count($res))
		return $res;
	else
	return array("01"=>"台北市","02"=>"高雄市","03"=>"宜蘭縣","04"=>"基隆市","05"=>"台北縣","06"=>"桃園縣","07"=>"新竹縣","08"=>"新竹市","09"=>"苗栗縣","10"=>"台中縣","11"=>"台中市","12"=>"南投縣","13"=>"彰化縣","14"=>"雲林縣","15"=>"嘉義縣","16"=>"嘉義市","17"=>"台南縣","18"=>"台南市","19"=>"高雄縣","20"=>"屏東縣","21"=>"台東縣","22"=>"花蓮縣","23"=>"澎湖縣","24"=>"金門縣","25"=>"連江縣");
}

//血型
function blood(){
	return array("1"=>"A","2"=>"B","3"=>"O","4"=>"AB");
}


//證號別
function stud_country_kind(){
	return array("0"=>"身分證字號","1"=>"護照號碼","2"=>"居留證號碼");
}

//學歷
function edu_kind(){
	$res = SFS_TEXT("edu_kind");
	if (count($res))
		return $res;
	else
		return array("1"=>"博士","2"=>"碩士","3"=>"大學","4"=>"專科","5"=>"高中","6"=>"國中","7"=>"國小畢業","8"=>"國小肄業","9"=>"識字(未就學)","10"=>"不識字");
}

//人事系統職稱陣列〈尚未用到〉
function tnc_post_kind(){
		return array("1"=>"校長","2"=>"教師兼主任","3"=>"主任","4"=>"教師兼組長","5"=>"組長","6"=>"導師","7"=>"專任教師","8"=>"實習教師","9"=>"試用教師","10"=>"代理/代課教師","11"=>"兼任教師","12"=>"職員","13"=>"護士","14"=>"警衛","15"=>"工友");
}

//事系統官等陣列〈尚未用到〉
function tnc_official_level(){
		return array("1"=>"簡任","2"=>"薦任","3"=>"委任");
}

//學歷別
function tea_edu_kind() {
	return array ("1"=>"研究所畢業(博士)","2"=>"研究所畢業(碩士)","3"=>"研究所四十學分班結業","4"=>"師大及教育學院畢業","5"=>"大學院校一般科系畢業(有修習教育學分)","6"=>"大學院校一般科系畢業(無修習教育學分)","7"=>"師範專科畢業","8"=>"其他專科畢業","9"=>"師範學校畢業","10"=>"軍事學校畢業","11"=>"其他");
}


//職業別〈尚未用到〉
function occu_kind(){
	return array("1"=>"士","2"=>"農","3"=>"工","4"=>"商","5"=>"軍","6"=>"教","7"=>"服務","8"=>"其他","9"=>"公");
}

//與監護人關係〈尚未用到〉
function guar_kind(){
	return array("1"=>"父子","2"=>"母子","3"=>"父女","4"=>"母女","5"=>"其他");
}

//個人病史資料〈尚未用到〉
function per_sick_kind() {
	$res = SFS_TEXT("per_sick_kind");
	if (count($res))
		return $res;
	else
	return array("1"=>"心臟病","2"=>"B型肝炎帶原","3"=>"腮腺炎","4"=>"癲癇","5"=>"肺炎","6"=>"水痘","7"=>"氣喘","8"=>"腎臟病","9"=>"血友病","10"=>"肺結核","11"=>"疝氣","12"=>"特異體質","13"=>"腦炎","14"=>"重傷","15"=>"食品藥物過敏","16"=>"風濕熱","17"=>"德國麻疹","18"=>"小兒麻痺","19"=>"傷寒");
}

//家族病史資料
function fam_sick_kind() {
	$res = SFS_TEXT("fam_sick_kind");
	if (count($res))
		return $res;
	else	
	return array("1"=>"高血壓","2"=>"糖尿病","3"=>"B型肝炎帶原","4"=>"癲癇","5"=>"精神疾病","6"=>"肺結核","7"=>"過敏性疾病","8"=>"心臟血管疾病","9"=>"內分泌疾病","10"=>"腫瘤","11"=>"?銗L");
}


//學習領域設定
function course9() {
	$res = SFS_TEXT("course9");
	if (count($res))
		return $res;
	else	
	return array("1"=>"語文","2"=>"健康與體育","3"=>"社會","4"=>"藝術與人文","5"=>"自然與科技","6"=>"生活","7"=>"數學","8"=>"綜合活動","9"=>"彈性學習"); 
}

//五育類別設定〈尚未用到〉
function course5() {
	$res = SFS_TEXT("course5");
	if (count($res))
		return $res;
	else	
	return array("1"=>"德","2"=>"智","3"=>"體","4"=>"群","5"=>"美"); 
}


//科目設定
function subject_kind() {	
	$res = SFS_TEXT("subject_kind");
	if (count($res))
		return $res;
	else	
	return array("1"=>"國語","2"=>"數學","3"=>"社會","4"=>"自然","5"=>"道德與健康","6"=>"生活與倫理","7"=>"體育","8"=>"書法","9"=>"美勞","10"=>"音樂","11"=>"美語","12"=>"電腦","13"=>"鄉土教學","14"=>"生活教育","15"=>"休閒教育","16"=>"社會適應","17"=>"實用數學","18"=>"實用英文","19"=>"彈性應用","20"=>"輔導活動","21"=>"社團活動","22"=>"職業生活");

}


// 原住民別〈尚未用到〉
function native_kind(){ 
	return array("1"=>"泰雅","2"=>"賽夏","3"=>"布農","4"=>"鄒","5"=>"魯凱","6"=>"排灣","7"=>"卑南","8"=>"阿美","9"=>"雅美");
}

// 父母關係〈尚未用到〉
function fath_moth_kind(){ 
	return array("1"=>"同住","2"=>"分住","3"=>"分居","4"=>"離婚","5"=>"其他");
}

// 家庭氣氛〈尚未用到〉
function family_amb_kind(){ 
	return array("1"=>"很和諧","2"=>"和諧","3"=>"普通","4"=>"不和諧","5"=>"很不和諧");
}

// 父母管教方式〈尚未用到〉
function leading_style_kind(){ 
	return array("1"=>"民主式","2"=>"權威式","3"=>"放任式","4"=>"其他");
}


// 經濟狀況〈尚未用到〉
function economics_kind(){ 
	return array("1"=>"富裕","2"=>"小康","3"=>"普通","4"=>"清寒","5"=>"貧困");
}

// 居住環境〈尚未用到〉
function live_atmosphere_kind(){ 
	return array("1"=>"住宅區","2"=>"商業區","3"=>"混合(住、商、工)","4"=>"軍眷區","5"=>"農村","6"=>"漁村","7"=>"工礦區","8"=>"山地","9"=>"其他");
}

// 居住環境〈尚未用到〉
function live_school_kind(){ 
	return array("1"=>"住在家裡(學區內)","2"=>"住在家裡(學區外)","3"=>"寄居親友家","4"=>"其他");
}


 // 稱謂
function bs_calling_kind(){
	return array("1"=>"兄","2"=>"弟","3"=>"姊","4"=>"妹");
}

// 是否〈尚未用到〉
function yes_no(){ 
	return array("1"=>"是","2"=>"否");
}

// 存歿
function is_live(){ 
	return array("1"=>"存","2"=>"歿 ");
}

// 與父關係
function fath_relation(){ 
	return array("1"=>"生父","2"=>"養父","3"=>"繼父");
}


// 與母關係
function moth_relation(){ 
	return array("1"=>"生母","2"=>"養母","3"=>"繼母");
}

// 與監護人關係
function guardian_relation(){ 
	return array("1"=>"父子","2"=>"父女","3"=>"母子","4"=>"母女","5"=>"祖孫","6"=>"兄弟","7"=>"兄妹","8"=>"姐弟","9"=>"姊妹","10"=>"伯叔姑姪甥","11"=>"其他");
}

// 獎懲〈2003-6-18 by hami〉
function stud_rep_kind() { 
	return array("1"=>"大功\","2"=>"小功\","3"=>"嘉獎","4"=>"大過","5"=>"小過","6"=>"警告");	
}

//學生假別 〈2003-6-18 by hami〉
function stud_abs_kind(){
	return array("1"=>"事假","2"=>"病假","3"=>"曠課","4"=>"集會","5"=>"公假","6"=>"其他");
}

//教師假別 〈2004-9-29 by brucelyc   2011-2-14 extend by infodaes〉
function tea_abs_kind(){
	return array("11"=>"事假","12"=>"家庭照顧假","21"=>"病假","22"=>"生理假","31"=>"公差","41"=>"婚假","42"=>"產前假","43"=>"娩假","44"=>"流產假","45"=>"陪產假","46"=>"喪假","47"=>"公假","52"=>"公差假","53"=>"公出","61"=>"其他","23"=>"延長病假","81"=>"休假","82"=>"補休假","91"=>"骨髓捐贈假","92"=>"器官捐贈假","93"=>"災防假");
}

//修畢業別 〈2005-1-7 by brucelyc〉
function grad_kind(){
	return array("1"=>"畢業","2"=>"修業");
}

//檢核表選項
function chk_kind(){
	return array("1"=>"完全符合","2"=>"大部份符合","3"=>"部份符合","4"=>"待改進");
}

//日常生活表現文字項目
function nor_text(){
	return array(0=>"日常行為",1=>"團體活動",2=>"公共服務_校內",3=>"公共服務_校外",4=>"特殊表現_校內",5=>"特殊表現_校外");
}

//萬豐健康系統內定值
//身高體重
function hWHDiag(){
	return array("1"=>"家族性矮小","2"=>"體質性遲緩","3"=>"特發性矮小","4"=>"生長激素缺乏","5"=>"透特納氏症","A"=>"無確定診斷","B"=>"診治正常","N"=>"其它診斷","Z"=>"未就診");
}

function hWHID(){
	return array("-2"=>"體型瘦弱","-1"=>"體重過輕","0"=>"體重正常","1"=>"體重過重","2"=>"體型肥胖");
}

//視力
function hSightDiag(){
	return array("1"=>"近視","2"=>"遠視","3"=>"散光","4"=>"近視&散光","5"=>"遠視&散光","7"=>"弱視","N"=>"其它");
}

function hSightManage(){
	return array("1"=>"視力保建","2"=>"點藥治療","3"=>"配鏡矯治","4"=>"家長未處理","5"=>"更換鏡片","6"=>"定期檢查","7"=>"遮眼治療","8"=>"另類治療","9"=>"配戴隱型眼鏡","N"=>"其它");
}

function hSolidDiag(){
	return array("1"=>"斜視","2"=>"弱視","3"=>"斜、弱視","4"=>"曲光不正","A"=>"無確定診斷","B"=>"診治正常","N"=>"其他眼疾","Z"=>"未就醫");
}

function hSquintKind(){
	return array("1"=>"內斜視","2"=>"外斜視","3"=>"上斜視","4"=>"下斜視","5"=>"外旋斜視","6"=>"內旋斜視","7"=>"麻痺性斜視","9"=>"其他");
}


//健康檢查
function hCheckKind(){
	return array("1"=>"心臟病篩檢","2"=>"胸部Ｘ光","3"=>"肝功能","4"=>"腎功能","5"=>"蛔蟲","6"=>"蟯蟲","7"=>"Ａ型肝炎","8"=>"Ｂ型肝炎","9"=>"結核菌素測驗");
}

function hCheckDep(){
	return array("1"=>"牙科","2"=>"眼科","3"=>"心臟科","4"=>"泌尿科","5"=>"耳鼻喉科","6"=>"外科","7"=>"內科","8"=>"語言治療科","9"=>"胸腔科","10"=>"皮膚科","11"=>"過敏科","12"=>"新陳代謝科","13"=>"整形外科","14"=>"神經外科","15"=>"精神科","16"=>"婦科","17"=>"內分泌科");
}

//特殊疾病
function hDiseaseKind(){
	return array("02"=>"肺結核","03"=>"心臟病","04"=>"肝炎","05"=>"氣喘","06"=>"腎臟病","07"=>"癲癇","08"=>"紅斑性狼瘡","09"=>"血友病","10"=>"蠶豆症","11"=>"關節炎","12"=>"糖尿病","13"=>"心理或精神性疾病","14"=>"癌症","15"=>"海洋性貧血","16"=>"重大手術","17"=>"過敏物質","99"=>"其他");
}

//重大疾病
function hSeriousDiseaseKind(){
	return array("01"=>"須積極或長期治療之癌症","02"=>"先天性凝血因子異常","03"=>"嚴重溶血性及再生不良性貧血","04"=>"慢性腎衰竭(尿毒症)，必須接受定期透析治療者","05"=>"需終身治療之全身性自體免疫症候群","06"=>"慢性精神病","07"=>"先天性新陳代謝異常疾病","08"=>"心、肺、胃腸、腎臟、神經、骨骼系統等之先天性畸型及染色體異常","09"=>"燒燙傷面積達全身百分之二十以上；或顏面燒燙傷合併五官功能障礙者","10"=>"接受腎臟、心臟、肺臟、肝臟及骨髓移植後之追蹤治療","11"=>"小兒麻痺、腦性麻痺、早產兒所引起之神經、肌肉、骨骼、肺臟等之併發症者","12"=>"重大創傷且其嚴重程度到達創傷嚴重程度分數十六分以上者","13"=>"因呼吸衰竭長期使用呼吸器者","14"=>"因腸道大量切除或失去功能，或其它慢性疾病引起嚴重營養不良者","15"=>"因潛水、或減壓不當引起之嚴重型減壓病或空氣栓塞症，伴有呼吸、循環或神經系統之併發症且需長期治療者","16"=>"重症肌無力症","17"=>"先天性免疫不全症","18"=>"脊髓損傷或病變所引起之神經、肌肉、皮膚、骨骼、心肺、泌尿及腸胃等之併發症者","19"=>"職業病","20"=>"急性腦血管疾病","21"=>"多發性硬化症","22"=>"先天性肌肉萎縮症","23"=>"外皮之先天畸型","24"=>"痲瘋病","25"=>"肝硬化症","26"=>"早產兒所引起之神經、肌肉、骨骼、心臟、肺臟等之併發症","27"=>"砷及其化合物之毒性作用（烏腳病）","28"=>"後天免疫缺乏症候群","29"=>"運動神經元疾病其身心障礙者","30"=>"庫賈氏病","99"=>"罕見疾病");
}

//身心障礙
function hBodyMindKind(){
	return array("01"=>"視覺障礙者","02"=>"聽覺機能障礙者","03"=>"平衡機能障礙者","04"=>"聲音機能或語言機能障礙者","05"=>"肢體障礙者","06"=>"智能障礙者","07"=>"重要器官失去功能者","08"=>"顏面損傷者","09"=>"植物人","10"=>"失智症者","11"=>"自閉症者","12"=>"慢性精神病患者","13"=>"多重障礙者","14"=>"頑性（難治型）癲癇症者","15"=>"經中央衛生主管機關認定，因罕見疾病而致身心功能障礙","99"=>"其他經中央衛生主管機關認定之障礙者");
}

function hBodyMindLevel(){
	return array("1"=>"輕度","2"=>"中度","3"=>"重度","4"=>"極重度");
}

function hInfLegal(){
	return array("000"=>"其他","001"=>"霍亂","002"=>"傷寒","002a"=>"副傷寒","003"=>"沙門氏菌","004"=>"桿菌性痢疾","005"=>"食物中毒（無原因）","0050"=>"金黃色葡萄球菌","0051"=>"肉毒桿菌中毒","0054"=>"腸炎弧菌","0058"=>"其他細菌性食物中毒","006"=>"阿米巴性痢疾","0080"=>"腸道出血性大腸桿菌感染症","0092"=>"腹瀉","010a"=>"開放性肺結核","011"=>"結核病(除開放性肺結核外)","012"=>"其他結核病","0130"=>"結核性腦膜炎","020"=>"鼠疫","022"=>"炭疽病","025"=>"類鼻疽","026"=>"貓抓病","030"=>"癩病","032"=>"白喉","033"=>"百日咳","0341"=>"猩紅熱","0360"=>"流行性腦脊髓膜炎","037"=>"破傷風","042"=>"後天免疫缺乏症候群","044"=>"ＨＩＶ感染","045"=>"小兒麻痺症","045a"=>"急性無力肢體麻痺","0461"=>"庫賈氏症","052"=>"水痘","055"=>"麻疹","056"=>"德國麻疹","060"=>"黃熱病","061"=>"登革熱","0620"=>"日本腦炎","0654"=>"登革出血熱╱登革休克症候群","0701"=>"急性病毒性肝炎Ａ型","0703"=>"急性病毒性肝炎Ｂ型","0705"=>"急性病毒性肝炎Ｃ型","070d"=>"急性病毒性肝炎Ｄ型","070e"=>"急性病毒性肝炎Ｅ型","070x"=>"急性病毒性肝炎未定型","071"=>"狂犬病","072"=>"腮腺炎","074"=>"克沙奇","0743"=>"手足口症","0749"=>"腸病毒感染併發重症","078"=>"腸胃炎","0786"=>"漢他病毒出血熱","0788"=>"伊波拉病毒出血熱","080"=>"流行性斑疹傷寒","0812"=>"恙蟲病","0820"=>"地方性斑疹傷寒","0820"=>"地方性斑疹傷寒","0830"=>"Ｑ熱","084"=>"瘧疾","087"=>"回歸熱","090"=>"梅毒","098"=>"淋病","100"=>"鉤端螺旋體病","1048"=>"萊姆病","125"=>"血絲蟲病","3200"=>"侵襲性ｂ型嗜血桿菌感染症","321"=>"病毒性腦膜炎","390"=>"風濕熱","4461"=>"川崎氏症","4808"=>"漢他病毒肺症候群","4828"=>"退伍軍人病","487"=>"流行性感冒","487a"=>"流行性感冒重症","7710"=>"先天性德國麻疹症候群","7713"=>"新生兒破傷風","SARS"=>"嚴重急性呼吸道症候群");
}

function hInfManage(){
	return array("A"=>"生病仍上課","B"=>"生病在家休息","C"=>"生病住院");
}

function get_folk_kind(){
	return array("父親","母親","祖父","祖母","兄","弟","姐","妹");
}

//教師選單
function teacher_array() {
	global $CONN;
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);

	// init $teacher_array
	$teacher_array=array();
	$sql_select = "select name,teacher_sn from teacher_base where teach_condition='0' order by name";
	$recordSet=$CONN->Execute($sql_select) or user_error("讀取失敗！<br>$sql_select",256);
	while (list($name,$teacher_sn) = $recordSet->FetchRow()) {
		$teacher_array[$teacher_sn]=$name;
	}
	return $teacher_array;
}

//學期班級導師姓名參照函式
function class_teacher() {
	global $CONN;
	// 確定連線成立
	if (!$CONN) user_error("資料庫連線不存在！請檢查相關設定！",256);
	// init $teacher_array
	$class_teacher_array=array();
	$sql_select = "select class_id,teacher_1 from school_class where enable=1 order by class_id";
	$recordSet=$CONN->Execute($sql_select) or user_error("讀取失敗！<br>$sql_select",256);
	while (list($class_id,$teacher_1) = $recordSet->FetchRow()) {
		$class_teacher_array[$class_id]=$teacher_1;
	}
	return $class_teacher_array;


}
?>
