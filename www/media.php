<?php
	$cur_rights=301;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$userid=$_SESSION["user"]["userid"];
	if(isset($_GET["userid"]) && is_numeric ($_GET["userid"])){
		$userid=$_GET["userid"];
	}
	$tpl -> assign("userid",$userid);

	//提醒方式删除
	if($action=="del"){
		if(isset($_GET["mediaid"])){
			$mediaid=$_GET["mediaid"];
			$sql_media_del="delete from media where mediaid=".$mediaid;
			$con_dbo -> execsql($sql_media_del);
		}
		header('location:'.$referer_url);
	}
	//提醒方式修改 增加
	if($action=="add" || $action=="edit"){
		$mediaid=$_POST["mediaid"];
		$mediatypeid=$_POST["mediatypeid"];
		$sendto=$_POST["sendto"];
		$severity=$_POST["severity"];
		$active=$_POST["active"];
		if($action=="add"){
			$sql_media_add="insert into media (userid,mediatypeid,sendto,active,severity) values (".$userid.",".$mediatypeid.",'".$sendto."',".$active.",'".$severity."')";
			$con_dbo -> execsql($sql_media_add);
		}else{
			$sql_media_edit="update media set mediatypeid=".$mediatypeid.",sendto='".$sendto."',active=".$active.",severity='".$severity."' where userid=".$userid." and mediaid=".$mediaid;
			$con_dbo -> execsql($sql_media_edit);
		}
		header('location:'.$referer_url);

	}



	//提醒方式
	$sql_media="select a.mediaid,a.userid,a.mediatypeid,a.sendto,a.active,a.severity,b.description,b.type from media a left join media_type b on (a.mediatypeid=b.mediatypeid) where a.userid=".$userid;

	$arr_media=$con_dbo -> sel_arr($sql_media);
	$tpl -> assign("arr_media",$arr_media);

	//提醒类型
	$sql_media_type="select mediatypeid,type,description,smtp_server,smtp_helo,smtp_email,exec_path from media_type";
	$arr_media_type=$con_dbo -> sel_arr($sql_media_type);
	$tpl -> assign("arr_media_type",$arr_media_type);

	$tpl -> display("media_tpl.htm");
?>