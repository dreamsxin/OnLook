<?php
	$pre_rights=3;
	$cur_rights=4;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	if($action=="edit"){
		$passwd=$_POST["passwd"];
		$oldpwd=$_POST["oldpwd"];

		$sql_pwd_select = "select * from users where userid=".$_SESSION["users"]["userid"]." and passwd='".md5($oldpwd)."'";
		$re=$con_dbo -> ifexists($sql_pwd_select);
		if($re===false){
			$dreams_msg="原始密码错误！";
		}else{
			$sql_pwd_update="update users set passwd='".md5($passwd)."' where userid=".$_SESSION["users"]["userid"];
			$con_dbo -> execsql($sql_pwd_update);
			$dreams_msg="密码修改成功！";
		}
		header('location:'.$referer_url);

	}
	if($action=="info_edit"){
		$name=$_POST["name"];
		$surname=$_POST["surname"];
		$url=$_POST["url"];
		$sql_user_update="update users set name='".$name."',surname='".$surname."',url='".$url."' where userid=".$_SESSION["users"]["userid"];
		$con_dbo -> execsql($sql_user_update);
		header('location:'.$referer_url);
	}

	//提醒方式删除
	if($action=="media_del"){
		if(isset($_GET["mediaid"])){
			$mediaid=$_GET["mediaid"];
			$sql_media_del="delete from media where mediaid=".$mediaid." and userid=".$_SESSION["users"]["userid"];
			$con_dbo -> execsql($sql_media_del);
		}
		header('location:'.$referer_url);
	}
	//提醒方式修改 增加
	if($action=="media_add" || $action=="media_edit"){
		$mediaid=$_POST["mediaid"];
		$mediatypeid=$_POST["mediatypeid"];
		$sendto=$_POST["sendto"];
		$severity=$_POST["severity"];
		$active=$_POST["active"];
		if($action=="media_add"){
			$sql_media_add="insert into media (userid,mediatypeid,sendto,active,severity) values (".$_SESSION["users"]["userid"].",".$mediatypeid.",'".$sendto."',".$active.",'".$severity."')";
			$con_dbo -> execsql($sql_media_add);
		}else{
			$sql_media_edit="update media set mediatypeid=".$mediatypeid.",sendto='".$sendto."',active=".$active.",severity='".$severity."' where userid=".$_SESSION["users"]["userid"]." and mediaid=".$mediaid;
			$con_dbo -> execsql($sql_media_edit);
		}
		header('location:'.$referer_url);
	}


	$page_type="info";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);


	//用户信息
	$sql_users="select userid,alias,name,surname,url from users where userid=".$_SESSION["users"]["userid"];
	$arr_users=$con_dbo -> ifexists($sql_users);
	$tpl -> assign("arr_users",$arr_users);

	//提醒方式
	$sql_media="select a.mediaid,a.userid,a.mediatypeid,a.sendto,a.active,a.severity,b.description,b.type from media a left join media_type b on (a.mediatypeid=b.mediatypeid) where a.userid=".$_SESSION["users"]["userid"];

	$arr_media=$con_dbo -> sel_arr($sql_media);
	$tpl -> assign("arr_media",$arr_media);

	//提醒类型
	$sql_media_type="select mediatypeid,type,description,smtp_server,smtp_helo,smtp_email,exec_path from media_type";
	$arr_media_type=$con_dbo -> sel_arr($sql_media_type);
	$tpl -> assign("arr_media_type",$arr_media_type);

	$tpl -> assign("dreams_msg",$dreams_msg);
	$tpl -> display("passwd_tpl.htm");
?>