<?php
	$cur_rights=2;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	
	//修改告警 提醒保留天数
	if($action=="update"){
		$alarm_history=365;
		if(isset($_POST["alarm_history"])){
			$alarm_history=$_POST["alarm_history"];
		}
		$alert_history=365;
		if(isset($_POST["alert_history"])){
			$alert_history=$_POST["alert_history"];
		}
		$sql_history_update="update config set alarm_history=".$alarm_history.",alert_history=".$alert_history;
		$con_dbo -> execsql($sql_history_update);
		header('location:'.$referer_url);
	}

	//删除提醒、方式
	if($action=="del"){
		$mediatypeid=0;
		if(isset($_GET["mediatypeid"])){
			$mediatypeid=$_GET["mediatypeid"];
			$sql_media_del="delete from media_type where mediatypeid=".$mediatypeid;
			$con_dbo -> execsql($sql_media_del);
		}
		header('location:'.$referer_url);
	}

	//添加 修改 提醒方式
	if($action=="add" || $action=="edit"){
		$mediatypeid=0;
		if(isset($_POST["mediatypeid"])){
			$mediatypeid=$_POST["mediatypeid"];
		}
		$type=0;
		if(isset($_POST["type"])){
			$type=$_POST["type"];
		}
		$description='';
		if(isset($_POST["description"])){
			$description=$_POST["description"];
		}
		$smtp_server='';
		if(isset($_POST["smtp_server"])){
			$smtp_server=$_POST["smtp_server"];
		}
		$smtp_helo='';
		if(isset($_POST["smtp_helo"])){
			$smtp_helo=$_POST["smtp_helo"];
		}
		$smtp_email='';
		if(isset($_POST["smtp_email"])){
			$smtp_email=$_POST["smtp_email"];
		}
		$exec_path='';
		if(isset($_POST["exec_path"])){
			$exec_path=$_POST["exec_path"];
		}
		
		if($action=="add"){	
			if($type==0){
				$sql_media_add="insert into media_type (type,description,smtp_server,smtp_helo,smtp_email) values (".$type.",'".$description."','".$smtp_server."','".$smtp_helo."','".$smtp_email."')";
				$con_dbo -> execsql($sql_media_add);
			}elseif($type==1){
				$sql_media_add="insert into media_type (type,description,exec_path) values (".$type.",'".$description."','".$exec_path."')";
				$con_dbo -> execsql($sql_media_add);
			}		
		}
		if($action=="edit"){
			if($type==0){
				$sql_media_update="update media_type set type=".$type.",description='".$description."',smtp_server='".$smtp_server."',smtp_helo='".$smtp_helo."',smtp_email='".$smtp_email."' where mediatypeid=".$mediatypeid;
				$con_dbo -> execsql($sql_media_update);
			}elseif($type==1){
				$sql_media_update="update media_type set type=".$type.",description='".$description."',exec_path='".$exec_path."' where mediatypeid=".$mediatypeid;
				$con_dbo -> execsql($sql_media_update);
			}	

		}
		header('location:'.$referer_url);
	}

	$page_type="media";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);
	//告警 提醒保留天数

	$sql_history="select alarm_history,alert_history from config";
	$arr_history=$con_dbo -> ifexists($sql_history);

	$alarm_history=$arr_history["alarm_history"];
	$alert_history=$arr_history["alert_history"];

	$tpl -> assign("alarm_history",$alarm_history);
	$tpl -> assign("alert_history",$alert_history);

	//提醒方式
	$sql_media_type="select mediatypeid,type,description,smtp_server,smtp_helo,smtp_email,exec_path from media_type";
	$arr_media_type=$con_dbo -> sel_arr($sql_media_type);
	$tpl -> assign("arr_media_type",$arr_media_type);

	$tpl -> display("system_config_tpl.htm");
?>