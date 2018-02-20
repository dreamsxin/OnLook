<?php
	$cur_rights=3;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	//组删除
	if($action=="group_del"){
		if(isset($_GET["group_id"])){
			$usrgrpid=$_GET["group_id"];
			$sql_group_del="delete from usrgrp where usrgrpid=".$usrgrpid;
			$con_dbo -> execsql($sql_group_del);
		}
		header('location:'.$referer_url);
	}
	//组 修改 添加
	if($action=="group_add" || $action=="group_edit"){
		$usrgrpid=$_POST["group_id"];
		$name=$_POST["group_name"];
		$rights="1,".$_POST["rights"];

		if($action=="group_add"){
			$sql_group_insert = "insert into usrgrp (name,rights) values ('".$name."','".$rights."')  ";
			$con_dbo -> execsql($sql_group_insert);
		}else{
			$sql_group_update="update usrgrp set name='".$name."',rights='".$rights."' where usrgrpid=".$usrgrpid;
			$con_dbo -> execsql($sql_group_update);
		}
		//echo $sql_group_update;
		header('location:'.$referer_url);
	}
	//用户删除
	if($action=="del"){
		if(isset($_GET["userid"])){
			$userid=$_GET["userid"];
			$sql_user_del="delete from users where userid=".$userid;
			$con_dbo -> execsql($sql_user_del);
		}
		header('location:'.$referer_url);
	}
	//用户修改 增加
	if($action=="add" || $action=="edit"){
		$userid=$_POST["userid"];
		$alias=$_POST["alias"];
		$name=$_POST["name"];
		$surname=$_POST["surname"];
		$passwd=$_POST["passwd"];
		$usrgrpid=$_POST["usrgrpid"];
		$url=$_POST["url"];
		if($action=="add"){

			$sql_user_insert=array();
			$sql_user_insert[] = "insert into users (alias,name,surname,passwd,url) values ('".$alias."','".$name."','".$surname."','".md5($passwd)."','".$url."')  ";
			$sql_user_insert[] = " select LAST_INSERT_ID() as id  ";
			$re = $con_dbo -> execsql($sql_user_insert,true);
			$userid=$re[0][0]["id"];
			if(trim($userid)!="" && $userid>0){
				$sql_usrgrp_insert="insert into users_groups (usrgrpid,userid) values (".$usrgrpid.",".$userid.") ";
				$con_dbo -> execsql($sql_usrgrp_insert);
			}else{
				$dreams_msg="已经相同的帐户存在！";
				$tpl -> assign("dreams_msg",$dreams_msg);
				$tpl -> display("error_tpl.htm");
				exit;
			}

		}else{
			if(trim($passwd)!=""){
				$sql_user_update="update users set alias='".$alias."',name='".$name."',surname='".$surname."',passwd='".md5($passwd)."',url='".$url."' where userid=".$userid;
				$con_dbo -> execsql($sql_user_update);
			}else{
				$sql_user_update="update users set alias='".$alias."',name='".$name."',surname='".$surname."' where userid=".$userid;
				$con_dbo -> execsql($sql_user_update);
			}
			$sql_sel="select * from users_groups where userid=".$userid;
			$re=$con_dbo -> ifexists($sql_sel);
			if(!$re){
				$sql_usrgrp_insert="insert into users_groups (usrgrpid,userid) values (".$usrgrpid.",".$userid.") ";
				$con_dbo -> execsql($sql_usrgrp_insert);
			}else{
				$sql_usrgrp_update="update users_groups set usrgrpid=".$usrgrpid." where userid=".$userid;
				$con_dbo -> execsql($sql_usrgrp_update);
			}
		}
		header('location:'.$referer_url);
	}

	$page_type="user";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);

	//用户分组

	$sql_usrgrp="select usrgrpid,name,rights from usrgrp";
	$arr_usrgrp=$con_dbo -> sel_arr($sql_usrgrp);

	$tpl -> assign("arr_usrgrp",$arr_usrgrp);

	//用户列表
	$sql_users="select a.userid,a.alias,a.name,a.surname,a.passwd,a.url,d.group_name,d.usrgrpid from users a left join (select b.usrgrpid,b.userid,c.name as group_name from users_groups b,USRGRP c where b.usrgrpid=c.usrgrpid) d on (a.userid=d.userid)";
	$arr_users=$con_dbo -> sel_arr($sql_users);
	$tpl -> assign("arr_users",$arr_users);

	$tpl -> display("user_admin_tpl.htm");
?>