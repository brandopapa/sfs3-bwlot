<?php
header('Content-type: text/html; charset=utf-8');
include_once ('config.php');
include_once ('my_functions.php');

ini_set('max_execution_time', 0); //?´æ?¹å?·è?????


if (!isset($_SESSION['MSN_LOGIN_ID'])) {
  echo "<Script language=\"JavaScript\">window.close();</Script>";
	exit();
}
//??å¾???å¸«ç?ç´???å§?????email
mysql_query("set names 'latin1';");
$query="select a.teacher_sn,a.name,b.email,b.email2,b.email3 from teacher_base a,teacher_connect b where a.teacher_sn=b.teacher_sn and a.teach_id='".$_SESSION['MSN_LOGIN_ID']."'";
$result=mysql_query($query);
list($teacher_sn,$MYNAME,$email,$email2,$email3)=mysql_fetch_row($result);
$MYEMAIL=($email=="")?$email2:$email;
if ($MYEMAIL=="") $MYEMAIL=$email3;

$MYNAME=iconv("big5","utf-8",$MYNAME);


//?¼é???°è??? *******************************************************
if ($_GET['act']=='post') {


$m_to=(isset($_GET['set']))?$_GET['set']:"";


?>
<html>
<head>
<title>?¼é??è¨???</title>
<style>
A:link {font-size:9pt;color:#ff0000; text-decoration: none}
A:visited {font-size:9pt;color: #ff0000; text-decoration: none;}
A:hover {font-size:9pt;color: #ffff00; text-decoration: underline}
td {font-size:10pt}
</style>

<script src="./include/jquery.js" type="text/javascript"></script>
<script src='./include/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<script type="text/javascript" src="../../javascripts/JSCal2-1.9/src/js/jscal2.js"></script>
<script type="text/javascript" src="../../javascripts/JSCal2-1.9/src/js/lang/b5.js"></script>
<link type="text/css" rel="stylesheet" href="../../javascripts/JSCal2-1.9/src/css/jscal2.css">

<script language="javascript">
function choice()
{
	if (document.form1.m_to.value=='') {
    document.form1.m_to.value=document.form1.set.value;
  }else{
    document.form1.m_to.value=document.form1.m_to.value+";"+document.form1.set.value;
  }
}

function b_submit() {
	
	var save=1;
	 
	if (document.form1.msg.value=='') {
	  alert('?¨å???è¼¸å?¥å?§å®¹!!');
	  document.form1.msg.focus();
	  save=0;
    return false;
	}
	 
	 
	//ç§?äººè???
	if (document.form1.option1.value==1) {
	  if (document.form1.m_to.value=='') {
  		alert('æ²???è¼¸å?¥è??¯æ?¥æ?¶å?è±¡ç??å¸³è??ï¼?');
  		document.form1.m_to.focus();
    	save=0;
    	return false;	    
	  }	
	}


	//?»å???
	if (document.form1.option1.value==3) {
	  if (document.form1.stdate.value=='') {
  		alert('æ²???è¼¸å?¥èµ·å§??¥æ??ï¼?');
    	save=0;
    	return false;	    
	  }	
	  if (document.form1.enddate.value=='') {
  		alert('æ²???è¼¸å?¥ç????¥æ??ï¼?');
    	save=0;
    	return false;	    
	  }	
	  if (document.form1.delay_sec.value=='' || document.form1.delay_sec.value>300 || document.form1.delay_sec<5) {
  		alert('è«?è¼¸å?¥å?ç¤ºç??¸ï? ( 5ç§?~300ç§?ä¹??? )');
    	save=0;
    	return false;	    
	  }	
	  if (document.form1.pic_file.value=='') {
  		alert('æ²????¸æ??æª?æ¡?ï¼?');
    	save=0;
    	return false;	    
	  }	

	}
 //ç¢ºè?è³????½æ??è¼¸å??
 if (save) {	
	wait_post.style.display="none";
  wait.style.display="block";
  document.form1.act.value='save';
  document.form1.submit();
 }
    
} // end function

</script>
</head>
<body bgcolor="#FFFFFF">

<div align="center">
  <center>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data">
	<input type="hidden" name="act" value="">
	<input type="hidden" name="option1" value="0">
	<input type="hidden" name="email" value="0">

  <font color="#FF0000">?¼é??è¨???</font>

  <table border="1" cellpadding="3" cellspacing="0" width="100%" bordercolorlight="#FFFFFF" bordercolordark="#FFFFFF" bordercolor="#800000">
		<tr>
			<td bgColor="#ffffcc" width="60" style="font-size: 10pt">è¨??¯é???</td>
			<td bgColor="#CCFFCC" style="font-size: 10pt">
			<input value="0" type="radio" name="data_kind" onclick="chkpublic();">?¬é?? 
			<input value="1" CHECKED type="radio" name="data_kind" onclick="chkprivate();document.form1.email.value='0';">ç§?äº? 
			<?php
			 if ($_SESSION['is_email'] and $SMPTHost!="") {
			 ?>
    		<input value="4" type="radio" name="data_kind" onclick="chkemail();document.form1.email.value='1';">E-mail
		 	 <?php
		   } // end if
			 if ($_SESSION['is_upload']) {
				?>
				<input value="2" type="radio" name="data_kind" onclick="chkfileshare();">æª?æ¡???äº?
				<?php
		   } // end if
			 if ($_SESSION['is_showpic']) {
				?>
			 <input value="3" type="radio" name="data_kind" onclick="chkpic();" ><font color=#FF0000>?»å?????(??)</font> 	
				<?php
		   } // end if
			 ?>
			</td>
		</tr>
		<tr id="Myfolder" style="display:none">
			<td bgColor="#ffffcc" width="60" style="font-size: 10pt">æª?æ¡?é¡???</td>
			<td bgColor="#CCFFCC" style="font-size: 10pt">
				<select size="1" name="folder">
				<?php
				mysql_query("set names 'utf8';");
				$query="select * from sc_msn_folder where idnumber!='private' order by foldername";
				$result=mysql_query($query);
				while ($row=mysql_fetch_array($result)) {
	       ?>
	       <option value="<?php echo $row['idnumber'];?>"><?php echo $row['foldername'];?></option>
	       <?php			 
				}
				?>
			</select>
			</td>
		</tr>

		<tr id="Myprivate" style="display: table-row">
			<td bgColor="#ffffcc" width="60" style="font-size: 10pt">?¥æ?¶å?è±?</td>
			<td bgColor="#CCFFCC" style="font-size: 10pt">
			<input tabIndex="1" type="text" name="m_to" value="<?php echo $m_to;?>">
			
			<input type="button" style="font-size:10px" value="?¨æ?¡å¸³??" onclick="OpenTeacherID()" title="???¸å¸³??">
			??
			<img style="cursor:pointer" border="1" width="16" height="16" src="./images/online.jpg" onclick="window.location='main_online.php'" title="?±ä?ç·???è¡¨é?¸å??">
			</td>
		</tr>
		<tr id="Mypublic" style="display: table-row">
			<td bgColor="#ffffcc" width="60" style="font-size: 10pt">
			å±?ç¤ºæ????</td>
			<td bgColor="#CCFFCC" style="font-size: 10pt">
			<input value="3" type="radio" name="lasttime">3å¤? 
			<input value="5" type="radio" name="lasttime">5å¤? 
			<input value="7" type="radio" name="lasttime" CHECKED>7å¤?
			<input value="10" type="radio" name="lasttime">10å¤? 
			<input value="14" type="radio" name="lasttime">14å¤? 
			<input value="30" type="radio" name="lasttime">30å¤?</td> 
		</tr>
		<tr id="email_subject" style="display:none">
			<td bgColor="#ffffcc" width="60" style="font-size: 10pt">
			ä¿¡ä»¶æ¨?é¡?</td>
			<td bgColor="#ccffcc" style="font-size: 10pt">
			<input type="text" name="email_subject" size="30"> 
			</td>			
		</tr>

		<tr>
			<td bgColor="#ffffcc" width="60" style="font-size: 10pt">
			è¨??¯å?§å®¹</td>
			<td bgColor="#ccffcc" style="font-size: 10pt">
			<textarea tabIndex="2" rows="6" cols="36" name="msg"></textarea> 
			</td>
			
		</tr>

		<tr id="Myfile" style="display: table-row">
			<td style="font-size: 10pt" bgColor="#ffffcc">
			????æª?æ¡?</td>
		
			<td bgColor="#ccffcc" style="font-size: 10pt">
				<table border="0" width="100%">
					<tr>
						<td><input type="file" class="multi" name="thefile[]"></td>
						<td align="left"><input type="button" value="???¥æ­¤æª?" name="B1"></td>
					</tr>
				</table>		
			</td>
		</tr>
  	<tr id="M_public" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">èªªæ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#FF0000">
				1.æ­¤å?¬é??è¨??¯æ???¨ã???¡å??MSN???????¡å?§è??¯äº¤æµ???æ¬?ä½?ä¸­ï?ä»¥æ?²å???«é?¢ç???¹å????¾ã??<br>
				2.?¡æ?¯æ?¡å?§IP???»è?¦é?½è?½ç??è¦?æ­¤è??¯ï?ä½?å¦????¯æ?¡å????»è?¦ï???å¿????»å?¥æ???½ç??è¦?????è¨??¯ã??<br>
		</tr>
  	<tr id="M_private" style="display: table-row">
			<td style="font-size: 10pt" bgColor="#ffffcc">èªªæ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#0000FF">
				1.?¨å?¯ä»¥?³é??ç§?äººè??¯çµ¦?¡å?§ç?????·å??ä»?ï¼??¶å??ä»??»å?¥æ?¡å??MSN?³å?¯æ?¥æ?¶å?°è??¯ã??<br>
				2.?¼é????è¨??¯å?¯ä»¥å¤¾å¸¶æª?æ¡?ï¼?ä½?ä¸??½å??å¤¾å¸¶æª?æ¡??»ä?å¡«å¯«è¨??¯å?§å®¹ï¼???ä»¥å»ºè­°å?¨è??¯å?§å®¹ä¸­å¡«?¥æ?æ¡???èªªæ????<br>
				3.??æª?å¤§å??¿è???<font color=red><?php echo $MAX_MB;?>MB</font></br>
				4.æ³¨æ??! ?¬è??¯å??ä¿???<font color=red><b><?php echo $PRESERVE_DAYS;?></b></font>å¤?!
			</td>
		</tr>
  	<tr id="M_email" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">èªªæ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#0000FF">
				1.?¨å?¯ä»¥?³é??E-mailçµ¦æ?¡å?§ç?????·å??ä»???<br>
				2.?¼é????E-mail?¯ä»¥å¤¾å¸¶æª?æ¡?ï¼?ä½?ä¸??½å??å¤¾å¸¶æª?æ¡??»ä?å¡«å¯«è¨??¯å?§å®¹ï¼???ä»¥å»ºè­°å?¨è??¯å?§å®¹ä¸­å¡«?¥æ?æ¡???èªªæ????<br>
				3.??æª?å¤§å?è«????®å??¹ä¿¡ç®±ç©º??ï¼?ä¸?è¦?å¤ªå¤§??</font></br>
			</td>
		</tr>

  	<tr id="M_fileshare" class="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">èªªæ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#FF00FF">
				1.è«???å¿??¸æ???©ç?¶ç??æª?æ¡?é¡???, ä»¥ä¾¿?¶ä?äººä?è¼???å®¹æ???¾å?°æ?æ¡???<br>
				2.?¯å????å¤¾å¸¶å¤???æª?æ¡?, æ¯???æª?æ¡?å¤§å?è«??¿è???<font color=red><?php echo $MAX_MB;?>MB</font>??<br>
				3.å±?ç¤ºæ?????¯æ???¨è??²å??è¦?çª?ä¸­å?¬å??å¹¾å¤©, å±?ç¤ºæ????å¾?ä»??¯ç?±æ?æ¡?ä¸?è¼????½ä¸­ä¸?è¼??¬æ?æ¡?.
			</td>
		</tr>	
  	<tr id="M_pic_sttime" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">??å§??¥æ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#0000FF"><input type="text" id="stdate" name="stdate" value="<?php echo date("Y-m-d");?>" size="10"></td>
					<script type="text/javascript">
					new Calendar({
  		    	inputField: "stdate",
   		    	dateFormat: "%Y-%m-%d",
    	    	trigger: "stdate",
    	    	bottomBar: true,
    	    	weekNumbers: false,
    	    	showTime: 24,
    	    	onSelect: function() {this.hide();}
		    	});
					</script>
		</tr>
  	<tr id="M_pic_endtime" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">çµ????¥æ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#0000FF"><input type="text" id="enddate" name="enddate" value="<?php echo date("Y-m-d");?>" size="10"></td>
					<script type="text/javascript">
					new Calendar({
  		    	inputField: "enddate",
   		    	dateFormat: "%Y-%m-%d",
    	    	trigger: "enddate",
    	    	bottomBar: true,
    	    	weekNumbers: false,
    	    	showTime: 24,
    	    	onSelect: function() {this.hide();}
		    	});
					</script>		</tr>
  	<tr id="M_pic_delay" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">å»¶é?²ç???</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#0000FF"><input type="text" name="delay_sec" value="5" size="2">ç§?</td>
		</tr>
		<tr id="M_pic_file" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">
			????æª?æ¡?
		  </td>
			<td>
			 <input type="file" name="pic_file">
			</td>
		</tr>
		
  	<tr id="M_pic" style="display:none">
			<td style="font-size: 10pt" bgColor="#ffffcc">èªªæ??</td>
			<td bgColor="#ccffcc" style="font-size: 10pt;color:#0000FF">
				1.??å­?èªªæ??, è«??¡é??ç°¡ç??<br>
				2.?ªè?½ä??? jpg/png/gif/swf/wmv ??ç¨®å?åª?é«?æª?æ¡?ï¼?æª?æ¡?å¤§å??¿è???<font color=red><?php echo $MAX_MB;?>MB</font></br>
				3.å±?ç¤ºæ??çµ???ç³»çµ±å°??ªå???ªé?¤æ?æ¡?ï¼?è«??ªè?ä¿?????å§?æª?æ¡???
			</td>
		</tr>
  </table>
  <table border="0">
   <tr id="wait" style="display:none;color:#FF0000">
    <td><br>è³???????ä¸?, è«?ç¨???...</td>
   </tr>
  </table>
  <table border="0" width="100%" bgcolor="#FFFFFF">
    <tr id="wait_post"> 
     <td colspan="2" align="right">
       <input type="button" onclick="b_submit()" value="????" name="B1">&nbsp;<input type="button" value="????" name="B2" onclick="window.close()">
      </td>
    </tr>
  </table>
 </form>
  </center>
</div>

</body>
</html>
<script language="javascript">


document.form1.m_to.focus();
chkprivate();

function OpenTeacherID() {
	if (document.form1.email.value=='1') {
	 dialogID=window.open('main_teachers_id.php?form_name=form1&email=1&item_name=m_to&selected_text=document.form1.m_to.value','test','toolbar=no,left=0,top=0,screenX=0,screenY=0,height=400,width=760,resizable=1,scrollbars');
	} else {
   dialogID=window.open('main_teachers_id.php?form_name=form1&item_name=m_to&selected_text=document.form1.m_to.value','test','toolbar=no,left=0,top=0,screenX=0,screenY=0,height=400,width=760,resizable=1,scrollbars');
  }
 if(window.dialogID) dialogID.focus();
}

//ç§?äººè???
function chkprivate() {
  Myprivate.style.display="table-row";
  Myfolder.style.display="none";
  Myfile.style.display="table-row";
  Mypublic.style.display="none";
  M_public.style.display="none";
  M_private.style.display=" table-row";
  M_fileshare.style.display="none";
  M_email.style.display="none";
  
  email_subject.style.display="none";
  
  M_pic_sttime.style.display="none";
  M_pic_endtime.style.display="none";
  M_pic_delay.style.display="none";
  M_pic_file.style.display="none";
  M_pic.style.display="none";

 document.form1.option1.value="1";
}

//E-mail
function chkemail() {
  Myprivate.style.display="table-row";
  Myfolder.style.display="none";
  Myfile.style.display="table-row";
  Mypublic.style.display="none";
  M_public.style.display="none";
  M_private.style.display="none";
  M_fileshare.style.display="none";
  M_email.style.display=" table-row";
  
  email_subject.style.display="table-row";
  
  M_pic_sttime.style.display="none";
  M_pic_endtime.style.display="none";
  M_pic_delay.style.display="none";
  M_pic_file.style.display="none";
  M_pic.style.display="none";

 document.form1.option1.value="4";
}


//?¬é??è¨???
function chkpublic() {
  Mypublic.style.display="table-row";
  Myprivate.style.display="none";
  Myfolder.style.display="none";
  Myfile.style.display="none";
  M_public.style.display="table-row";
  M_private.style.display="none";
  M_fileshare.style.display="none";
  email_subject.style.display="none";
  
  M_pic_sttime.style.display="none";
  M_pic_endtime.style.display="none";
  M_pic_delay.style.display="none";
  M_pic_file.style.display="none";
  M_pic.style.display="none";
  M_email.style.display="none";
  
  document.form1.option1.value="0";
}

//æª?æ¡???äº?
function chkfileshare() {
  Mypublic.style.display="table-row";
  Myprivate.style.display="none";
  Myfolder.style.display="table-row";
  Myfile.style.display="table-row";
  M_public.style.display="none";
  M_private.style.display="none";
  M_fileshare.style.display="table-row";
  email_subject.style.display="none"; 
  
  M_pic_sttime.style.display="none";
  M_pic_endtime.style.display="none";
  M_pic_delay.style.display="none";
  M_pic_file.style.display="none";
  M_pic.style.display="none";
  M_email.style.display="none";
  
  document.form1.option1.value="2";
}

function chkpic() {
  Mypublic.style.display="none";
  Myprivate.style.display="none";
  Myfolder.style.display="none";
  Myfile.style.display="none";
  M_public.style.display="none";
  M_private.style.display="none";  
  M_fileshare.style.display="none";
  email_subject.style.display="none";
  
  M_pic_sttime.style.display="table-row";
  M_pic_endtime.style.display="table-row";
  M_pic_delay.style.display="table-row";
  M_pic_file.style.display="table-row";
  M_pic.style.display="table-row";  
  M_email.style.display="none";
   document.form1.option1.value="3";
}

</script>
<?php
} // end if act=='post' ******************************************

if ($_GET['act']=='read') {
 //ä»? UTF8 ?¹å???ç·?
 mysql_query("SET NAMES 'utf8'");
 
 //mysqli
 $mysqliconn = get_mysqli_conn("sc_msn_data");
	
 if ($_GET['set']=="") {
   //$query="select id,idnumber,teach_id,post_date,data_kind,data,relay from sc_msn_data where to_id='".$_SESSION['MSN_LOGIN_ID']."' and ifread=0 order by post_date limit 0,1";
$query="select id,idnumber,teach_id,post_date,data_kind,data,relay from sc_msn_data where to_id='".$_SESSION['MSN_LOGIN_ID']."' and ifread=0 order by post_date limit 0,1";
$stmt="";
$stmt = $mysqliconn->prepare($query);
$stmt->execute();
$stmt->bind_result($id,$idnumber,$teach_id,$post_date,$data_kind,$data,$relay);
$stmt->fetch();
$stmt->close();
  
  } else {
   //$query="select id,idnumber,teach_id,post_date,data_kind,data,relay from sc_msn_data where to_id='".$_SESSION['MSN_LOGIN_ID']."' and idnumber='".$_GET['set']."' order by post_date limit 0,1";
  
$query="select id,idnumber,teach_id,post_date,data_kind,data,relay from sc_msn_data where to_id='".$_SESSION['MSN_LOGIN_ID']."' and idnumber=? order by post_date limit 0,1";
$stmt="";
$stmt = $mysqliconn->prepare($query);
$stmt->bind_param('s',$_GET['set']);
$stmt->execute();
$stmt->bind_result($id,$idnumber,$teach_id,$post_date,$data_kind,$data,$relay);
$stmt->fetch();
$stmt->close(); 
  }
 
 
 //$result=mysql_query($query);
 
 //ç¢ºå¯¦????è¨?
 //if ($row=mysql_fetch_row($result)) {
   if ($idnumber) {
	$row=array($id,$idnumber,$teach_id,$post_date,$data_kind,$data,$relay);
	list($id,$idnumber,$teach_id,$post_date,$data_kind,$data,$relay)=$row;
  mysql_query("update sc_msn_data set ifread=1 where id=$id");	
  $name=get_name_state($teach_id);
  //?¯æ?°ç??è¨?????è¦?
  if ($relay) {
  	$query_relay="select post_date,data from sc_msn_data where idnumber='".$relay."' and teach_id='".$_SESSION['MSN_LOGIN_ID']."' and to_id='".$teach_id."'";
  	$result_relay=mysql_query($query_relay);
  	list($r_post_date,$r_data)=mysql_fetch_row($result_relay);
  }
  //?¯å?¦æ????æª?
  $query_file="select filename,filename_r from sc_msn_file where idnumber='".$idnumber."'";
  $result_file=mysql_query($query_file);
  ?>
<html>
<head>
<title>è®???ç§?äººè???</title>
</head>
<script src="./include/jquery.js" type="text/javascript"></script>
<script src='./include/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>
<body>
 <form name="form1" method="post" action="main_message.php" onsubmit="return checkdata()" enctype="multipart/form-data">
  <input type="hidden" name="act" value="">
	<input type="hidden" name="m_to" value="<?php echo $teach_id;?>">
	<input type="hidden" name="relay" value="<?php echo $idnumber;?>">
	<input type="hidden" name="data_kind" value="1">
  <table border="1" cellpadding="3" cellspacing="0" width="100%" bordercolorlight="#FFFFFF" bordercolordark="#FFFFFF" bordercolor="#800000">
    <tr>
      <td width="41" bgcolor="#FFFFCC">?¥æ??</td>
      <td  bgcolor="#CCFFCC"><?php echo $post_date ?></td>
    </tr>
    <tr>
      <td width="41" bgcolor="#FFFFCC">ä¾???</td>
      <td  bgcolor="#CCFFCC"><?php echo $name[0];?>(<?php echo $teach_id;?>)</td>
    </tr>
    <tr>
      <td width="41" bgcolor="#FFFFCC">??è¨?</td>
      <td  bgcolor="#CCFFCC" style="font-size:10pt">
      	<?php
      	if ($relay) {
      	?>
      	<table border="1" cellpadding="5" cellspacing="0"  bordercolorlight="#FFFFFF" bordercolordark="#FFFFFF" bordercolor="#FFFFFF" width="100%">
         <tr>
           <td style="font-size: 9pt" bgcolor="#B5FFFF">
           	??<?php echo $r_post_date;?>,?¨èªª:<br><?php echo nl2br($r_data);?>
          </td>
         </tr>
        </table>
        <br>	
        <?php
         } //end if relay
        ?>
      	<?php echo AddLink2Text(nl2br($data));?>
      </td>
    </tr>
    <?php
     if (mysql_num_rows($result_file)) {
     ?>
     <tr>
      <td width="41" bgcolor="#FFFFCC">??æª?</td>
      <td bgcolor="#CCFFCC" style="font-size:10pt">?¬ç??è¨???<?php mysql_num_rows($result_file);?>????æª?ï¼?<br>
      	<?php 
      	 while ($row_file=mysql_fetch_row($result_file)) {
      	  list($filename,$filename_r)=$row_file;
      	  echo $filename_r;?>&nbsp;<a href="main_download.php?set=<?php echo $filename;?>">ä¸?è¼?</a><br>
      	  <?php
      	 } // end while
      	?>
      </td>
    </tr>
    
     <?
     }
    ?>    
  </table>
  <table border="0" cellpadding="0" cellspacing="0" width="100%" bordercolorlight="#800000" bordercolordark="#FFFFFF" bordercolor="#FFFFFF">
    <tr>
      <td>?¨ç????è¦?:</td>
    </tr>
    <tr>
      <td colspan='2'>
      <textarea rows="4" name="msg" cols="45"></textarea>
      </td>
    </tr>
    <tr id="Myfile" style="display:block">
			<td style="font-size: 10pt" valign='top' width='80'>????æª?æ¡?ï¼?</td>
			<td style="font-size: 10pt">
				<table border="0" width="100%">
					<tr>
						<td><input type="file" class="multi" name="thefile[]"></td>
						<td align="left"><input type="button" value="???¥æ­¤æª?" name="B1"></td>
					</tr>
				</table>		
			</td>
		</tr>
    <tr id='wait_post' style='display:block'>
       <td align="left" colspan='2'>
      <input type="button" onclick="b_submit()" value="????" name="B1">&nbsp;<input type="button" value="????" name="B2" onclick="window.close()">
      </td>
    </tr>
  	<tr>
			<td colspan='2' style="font-size: 10pt;color:#0000FF">
			èªªæ??ï¼?<br>
				1.?¼é????è¨??¯å?¯ä»¥å¤¾å¸¶æª?æ¡?ï¼?ä½?ä¸??½å??å¤¾å¸¶æª?æ¡??»ä?å¡«å¯«è¨??¯å?§å®¹ï¼???ä»¥å»ºè­°å?¨è??¯å?§å®¹ä¸­å¡«?¥æ?æ¡???èªªæ????<br>
				2.??æª?å¤§å??¿è???<font color=red><?php echo $MAX_MB;?>MB</font></br>
				3.æ³¨æ??! ç³»çµ±?§è??¯è?ªç?¼é???¥æ??èµ·ï???ä¿???<font color=red><b><?php echo $PRESERVE_DAYS;?></b></font>å¤?!
			</td>
		</tr>
		<tr id='wait' style='display:none'>
		  <td style='color:#FF0000' colspan='2'><br>è¨??¯è????ä¸?...</td>
		</tr>
  </table>
</form>
</body>
</html>
 <Script>
 function b_submit() {
	if (document.form1.msg.value=='') {
	  alert('?¨å???è¼¸å?¥å?§å®¹!!');
	  document.form1.msg.focus();
    return false;
	} else{
		wait_post.style.display="none";
  	wait.style.display="block";
  	document.form1.act.value='save';
  	document.form1.submit();
  }
 }
 

 
 </Script>
 
 <?php
 } // end if ($row=mysql_fetch_row($result))
} // end if $_GET['act']=='read'


//?²å?è¨??? *******************************************************
if ($_POST['act']=='save') {

mysql_query("SET NAMES 'utf8'");


$data_kind=$_POST['data_kind'];

$datetime=date("Y-m-d H:i:s");
$m_from=$_SESSION['MSN_LOGIN_ID'];
$m_to=$_POST['m_to'];
$relay=$_POST['relay'];
$msg=$_POST['msg'];
$lasttime=$_POST['lasttime'];
$folder=$_POST['folder'];

//$data_kind 
/***
0 ?¬é??è¨???(ä¸??½å¤¾æª?)
1 ç§?äººè???(?¯å¤¾æª?)
2 æª?æ¡???äº?(?¯å¤¾æª?, å¿????¸æ?æ¡?é¡???)
3 ?»å????? (å¿?å¤¾æ?)
4 E-mail
***/

if ($data_kind=='4') {
	
	require_once('./include/PHPMailer/class.phpmailer.php');
	
	$mail = new PHPMailer(); // defaults to using php "mail()"
  $mail->CharSet = "UTF-8";
	//$mail->IsSendmail(); // telling the class to use SendMail transport

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = $SMPTHost; // SMTP server
$mail->SMTPDebug  = 2;                     	// enables SMTP debug information (for testing)
                                           	// 1 = errors and messages
                                           	// 2 = messages only
$mail->SMTPAuth   = $SMPTAuth;              // enable SMTP authentication
$mail->Port       = $SMPTPort;              // set the SMTP port for the GMAIL server
$mail->Username   = $SMPTusername; 					// SMTP account username
$mail->Password   = $SMPTpassword;        	// SMTP account password
 
  //å¯?ä»¶è??è³???
	$mail->SetFrom($MYEMAIL,$MYNAME);
	$mail->AddReplyTo($MYEMAIL,$MYNAME);
  //?§å®¹
	$mail->Subject    = ($_POST['email_subject']=="")?"ä¾??ªæ?¡å??MSN??è¨???!":$_POST['email_subject'];
	$mail->AltBody    = $msg; 
	$body=$msg;
	$mail->MsgHTML($body);
	//?¶ä»¶??	
	  mysql_query("set names 'latin1';");
		$a=explode(";",$m_to);
		$Email_fail="";
		$Email_success="";
		 foreach($a as $g) {
		 	
		 	$query="select a.name,b.email,b.email2,b.email3 from teacher_base a,teacher_connect b where a.teacher_sn=b.teacher_sn and a.teach_id='".$g."'";
			$result=mysql_query($query);
			list($TONAME,$email,$email2,$email3)=mysql_fetch_row($result);
			$TOEMAIL=($email=="")?$email2:$email;
			if ($TOEMAIL=="") $TOEMAIL=$email3;
     
      if ($TOEMAIL!="") {
			  $address = $TOEMAIL;
			  $mail->AddAddress($address, $TONAME);
			  
			  //??æª?????
			   if (count($_FILES['thefile']['name'])>0) {
				 for ($i=0;$i<count($_FILES['thefile']['name']);$i++) {
     			$NowFile=$_FILES['thefile']['name'][$i]; //æª???
     			if ($NowFile!="") {
     				$mail->AddAttachment($_FILES['thefile']['tmp_name'][$i],$NowFile);
   				}
 					}// end for
 				 } //end if file 	
 
		    //å¯?ä¿?
				if(!$mail->Send()) {
 			 		//echo "Mailer Error: " . $mail->ErrorInfo;
 			 		$Email_fail.=$TONAME." ";
				} else {
					$Email_success.=$TONAME." ";
 	 				$save=1; 
 	 				$countMail+=1;
				}
      } // end if ($TOEMAIL!="")
  			$mail->ClearAddresses();
  			$mail->ClearAttachments();		 	
 		 } // end foreach
   //?¼é?????¥è??¯çµ¦ä½¿ç?¨è??
   	$idnumber=date("y").date("m").date("d").date("H").date("i").date("s");
 		//æ¸¬è©¦ä»?ç¢¼æ?¯å?¦é??è¦?
		do {
	 		$a=floor(rand(10,99));
	 		$idnumber_test=$idnumber.$a;
	 		$query="select id from sc_msn_data where idnumber='".$idnumber_test."'";
	 		$result=mysql_query($query);
	 		$exist=mysql_num_rows($result);
		} while ($exist>0);
		
    
 		$idnumber=$idnumber_test;
		$Email_success=iconv("big5","utf-8",$Email_success);
		$Email_fail=iconv("big5","utf-8",$Email_fail);
		mysql_query("SET NAMES 'utf8'");
 		$msg="?±æ?¼æ?¨ä½¿?¨ä?E-mail????, æ­¤ç?ºç³»çµ±è?ªå?????¥ç???:<br><br>?????¼é??E-mailçµ?: ".$Email_success." <br><br>?¼é??å¤±æ????:".$Email_fail;
    $sql="insert into sc_msn_data (idnumber,teach_id,to_id,data_kind,post_date,last_date,data,relay,folder) values ('$idnumber','$m_from','$m_from','$data_kind','$datetime','$lasttime','$msg','$relay','private')";
    mysql_query($sql);
} else {

$idnumber=date("y").date("m").date("d").date("H").date("i").date("s");
 //æ¸¬è©¦ä»?ç¢¼æ?¯å?¦é??è¦?
	do {
	 $a=floor(rand(10,99));
	 $idnumber_test=$idnumber.$a;
	 $query="select id from sc_msn_data where idnumber='".$idnumber_test."'";
	 $result=mysql_query($query);
	 $exist=mysql_num_rows($result);
	} while ($exist>0);

 $idnumber=$idnumber_test;

//ä¾?ç¨®é?????è¨??¯è???
$save=0; $post_count=0;
switch ($data_kind) {
  //?¬é??
  case '0':
    $query="insert into sc_msn_data (idnumber,teach_id,to_id,data_kind,post_date,last_date,data,relay,folder) values ('$idnumber','$m_from','','$data_kind','$datetime','$lasttime','$msg','','')";
 		if (mysql_query($query)) {
 		  $save=1;
 		}
  break;
  //ç§?äº?
  case '1':
		if ($data_kind==1 and $m_to!="" and $msg!="") {
			$a=explode(";",$m_to);
 			 foreach($a as $g) {
 				 	$query="select teach_id from teacher_base where teach_id='".$g."'";
  				$result=mysql_query($query);
  					if (mysql_num_rows($result)) {
 						   $query="insert into sc_msn_data (idnumber,teach_id,to_id,data_kind,post_date,last_date,data,relay,folder) values ('$idnumber','$m_from','$g','$data_kind','$datetime','$lasttime','$msg','$relay','private')";
 						   mysql_query($query);
 							 $save=1;
 							 $post_count++;
  				  }
  		 } 
		}  
  break;
  //æª?æ¡???äº?
  case '2':
  	if ($m_to=="" and $data_kind==2 and $msg!="" and count($_FILES['thefile']['name'])>0) {
 			$query="insert into sc_msn_data (idnumber,teach_id,to_id,data_kind,post_date,last_date,data,relay,folder) values ('$idnumber','$m_from','$m_to','$data_kind','$datetime','$lasttime','$msg','$relay','$folder')";
 			mysql_query($query);
 			$save=1;
		}
  break;

}

//??ç§?äººè??¯æ??æª?æ¡???äº«è?½å¤¾æª? 
//????æª?æ¡? , è¨??¯ç??????å­??¥å??????
if ($save==1 and ($data_kind==1 or $data_kind==2)) {
 if (count($_FILES['thefile']['name'])>0) {
 $countFile=0;	
 for ($i=0;$i<count($_FILES['thefile']['name']);$i++) {
     $NowFile=$_FILES['thefile']['name'][$i]; //æª???
     if ($NowFile!="") {
     	$countFile++;
    //æª¢é??¯æ???
    $expand_name=explode(".",$NowFile);
    $nn=count($expand_name)-1;
    //?°å?? , ??å±¬å?? $idnumber ??è¨?ä¸?
    $filename=$_SESSION['MSN_LOGIN_ID']."_f".date("y").date("m").date("d").date("H").date("i").date("s").$i.".".$expand_name[$nn];
     copy($_FILES['thefile']['tmp_name'][$i],$download_path.$filename);
     $query="insert into sc_msn_file (idnumber,filename,filename_r) values ('$idnumber','$filename','$NowFile')";
     mysql_query($query);
   }
 }// end for
 } //end if file 	
}
 
 //?¥ç?ºå?²å??»å????¿å??
 if ($data_kind==3) {
	
	//æª¢é?ä¸??³ç?®é???¯å?¦å???, ?ªå??¨è?ªå??å»ºç?
	 if (!file_exists($UPLOAD_PIC)) {
     mkdir(substr($UPLOAD_PIC,0,strlen($UPLOAD_PIC)-1),0777);
 	}
	
  $stdate=$_POST['stdate'];
  $enddate=$_POST['enddate'];
  $delay_sec=$_POST['delay_sec'];
  if ($stdate!='' and $enddate!='' and $delay_sec!='' and $msg!='') {
   //????æª?æ¡?
   if ($_FILES['pic_file']['name']!="") {
       //æª¢é??¯æ???
      $expand_name=explode(".",$_FILES['pic_file']['name']);
      $nn=count($expand_name)-1;
      $ATTR=strtolower($expand_name[$nn]); //è½?å°?å¯«å?¯æ???
   	  
      //æª¢æ¸¬?¯å?¦å??è¨±ä??³æ­¤é¡???æª?æ¡?
      if (check_file_attr($ATTR)) { 

      //?°æ??? 
      $filename_1=date('ymd').floor(rand(1000,9999)); //å¾??¢å????ç¢¼ä???
      $filename=$filename_1.".".$ATTR;
       if ($ATTR=='swf' or $ATTR=='wmv') {
        //????
        copy($_FILES['pic_file']['tmp_name'],$UPLOAD_PIC.$filename);
        $query="insert into sc_msn_board_pic (teach_id,stdate,enddate,delay_sec,file_text,filename) values ('$m_from','$stdate','$enddate','$delay_sec','$msg','$filename')";
        mysql_query($query);
        $save=1;      
       } else {
       	//??????
        $filename_s=$filename_1."_s.".$ATTR;
       	  if (!ImageResize($_FILES['pic_file']['tmp_name'], $UPLOAD_PIC.$filename, 800, 600, 100)) {
       	   echo "ErroR!";
       	   exit();
       	  } else {      	  
       	  	//ç¸®å??
       	  	ImageResize($_FILES['pic_file']['tmp_name'], $UPLOAD_PIC.$filename_s, 200, 150, 100);
            $query="insert into msn_board_pic (teach_id,stdate,enddate,delay_sec,file_text,filename) values ('$m_from','$stdate','$enddate','$delay_sec','$msg','$filename')";
            mysql_query($query);
					  $save=1;
          }
             
        } // end if swf
      }// end if attr
   }// end if files exist
  } 
 
	} // end if data_kind==3
	
} // end if else data_kind=4

 ?>
  <Script language="JavaScript">
	//????è¦?çª?????ç¤ºè???
 <?php
 switch ($data_kind) {
   case 0:
      if ($save) {
		    echo "alert('?????¼é???¬é??è¨???!');";
   		}else{
    		echo "alert('?¬é??è¨??¯ç?¼é??å¤±æ??!');";
   		}	 	
   break;

   case 1:
      if ($save) {
		    echo "alert('?????¼é??".$post_count."??è¨???!');";
   		}else{
    		echo "alert('è¨??¯ç?¼é??å¤±æ??!');";
   		}	 	

   break;

   case 2:
	   if ($save) {
  		  echo "alert('æª?æ¡?ä¸??³æ????!?±è?".$countFile."??æª?æ¡?.');";
   	 }else{
   		 echo "alert('æª?æ¡???äº«å¤±??!');";
   	 } 	
   break;

   case 3:
      if ($save) {
		    echo "alert('?????¼é??1???»å?????!');";
   		}else{
    		echo "alert('?»å??????¼é??å¤±æ??!');";
   		}	 	
   break;
   
   case 4:
      if ($save) {
		    echo "alert('?????¼é??".$countMail."å°? E-mail!');";
   		}else{
    		echo "alert('E-mail ?¼é??å¤±æ??! Error Message:\n".$S."');";
   		}	 	
   break;
 }
  ?>

	window.close();
	
</Script>
  <?php
} // end if ($_POST['act']=='save') ******************************


?>
<Script Language="JavaScript">
	//ç§»å??è¦?çª?ä½?ç½?
	window.resizeTo(450,560)
  var XX=screen.availWidth
  var YY=screen.availHeight

	<?php
        if ($POSITION=="") $POSITION=0;
        switch ($POSITION) {
          case 0:  //?³ä?
        		echo "var PX=XX-(390+450); \n";
        		echo "var PY=0;\n";
          break;
          case 1:  //å·¦ä?
        		echo "var PX=391; \n";
        		echo "var PY=0;\n";
          break;

          case 2:  //æ­?ä¸?
        		echo "var PX=0; \n";
        		echo "var PY=0;\n";
          break;

          case 3:  //?³ä?
        		echo "var PX=XX-(390+450); \n";
        		echo "var PY=YY-560;\n";
          break;
        	
          case 4:  //å·¦ä?
        		echo "var PX=391; \n";
        		echo "var PY=YY-560;\n";
          break;
        }
   ?>


window.moveTo(PX,PY);
</Script>  