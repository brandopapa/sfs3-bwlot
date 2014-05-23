#!/usr/bin/php
<?php
// 加入時區設定
date_default_timezone_set('Asia/Taipei');

//1.1版
//echo "#開始更新 sfs3......\n";

//sfs3 安裝目錄(請依需要修改)
$SFS_INSTALL_PATH="/home/sfs6/dev3.sfs3.bwsh.kindn.es";

//sfs3 解壓暫存目錄(請依需要修改)
$SFS_TEMP_DIR="/tmp/sfs3_stable";

//sfs3 下載網址(勿修改)
$SFS_TAR_URL="http://sfscvs.tc.edu.tw/";

//記錄自動排程執行時間
$fp=fopen($SFS_INSTALL_PATH."/data/system/cron","w");
fputs($fp,date("Y-m-d H:i:s"));
fclose($fp);

//取得由網頁設定的變數值
$v_arr=array();
$v_arr['SCHEDULE']="";
$v_arr['TEMPORARY']="";
if (file_exists($SFS_INSTALL_PATH."/data/system/update")) {
	$fp=fopen($SFS_INSTALL_PATH."/data/system/update","r");
	while(!feof($fp)) {
		$temp_arr=array();
		$temp_arr=explode("=",fgets($fp,1024));
		if (count($temp_arr)==2) $v_arr[$temp_arr[0]]=sprintf("%02d",intval($temp_arr[1]));
	}
	fclose($fp);
}

//如果沒有設定定期更新時間, 則定期更新時間設定為早上六點
if ($v_arr['SCHEDULE']=="") $v_arr['SCHEDULE']="06";

//取得現在時間的小時別
$hour=date("H");

//若符合更新時間則進行更新
if ($v_arr['SCHEDULE']==$hour || $v_arr['TEMPORARY']==$hour || $argv[1]=="now") {

	echo "#開始更新 sfs3......\n";

	//判斷PHP版本別
	if ( !function_exists('version_compare') || version_compare( phpversion(), '5', '<' ) )
		$SFS_TAR_FILE="sfs_stable.tar.gz";
	else
		$SFS_TAR_FILE="sfs_stable5.tar.gz";

	//判斷暫存目錄是否已存在
	if (is_dir($SFS_TEMP_DIR)) {
		exec("rm -rf ".$SFS_TEMP_DIR);
	}

	//判斷舊有程式碼是否已存在
	if (file_exists("/tmp/".$SFS_TAR_FILE)) {
		exec("rm -f /tmp/".$SFS_TAR_FILE);
	}

	//判斷sfs3是否安裝
	if (!is_dir($SFS_INSTALL_PATH)) {
		echo "Oh! Error! .... Directory *** sfs3 *** not exists!\n";
		echo "Please install sfs3 first!\n";
		exit;
	}

	//下載、解壓與複製主程式
	echo "#下載主程式......\n";
	exec("wget -q ".$SFS_TAR_URL.$SFS_TAR_FILE." --directory-prefix=/tmp");
	echo "#主程式解壓縮......\n";
	exec("tar zxf /tmp/".$SFS_TAR_FILE." -C /tmp");
	echo "#複製主程式......\n";
	exec("cp -a ".$SFS_TEMP_DIR."/* ".$SFS_INSTALL_PATH);

	//顯示更新版本號
	include $SFS_INSTALL_PATH."/sfs-release.php";
	echo "#更新至 ".$SFS_BUILD_DATE."\n";

	//回寫設定檔
	$fp=fopen($SFS_INSTALL_PATH."/data/system/update","w");
	fputs($fp,"SCHEDULE=".$v_arr['SCHEDULE']);
	fclose($fp);

	echo "END...\n";
}
?>

