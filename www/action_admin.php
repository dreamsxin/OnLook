<?php
	$cur_rights=701;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	
	$triggerid="";
	if(isset($_GET["triggerid"])){
		$triggerid=$_GET["triggerid"];
	}
	$tpl -> assign("triggerid",$triggerid);

	//删除依赖关系
	if($action=="del"){
		$triggerid_up="";
		if(isset($_GET["triggerid_up"])){
			$triggerid_up=$_GET["triggerid_up"];
		}
		$sql_del=array();
		$sql_del[]="delete from trigger_depends where triggerid_down=".$triggerid." and triggerid_up=".$triggerid_up." ";
		$sql_del[]="update triggers set dep_level=dep_level-1 where triggerid=".$triggerid_up." ";
		$con_dbo -> execsql($sql_del,true);
		header('location:'.$referer_url);
	}
	//添加依赖关系
	if($action=="add"){
		$triggerid_up="";
		if(isset($_POST["triggerid_up"])){
			$triggerid_up=$_POST["triggerid_up"];
		}
		$sql_add=array();
		$sql_add[]="insert into trigger_depends (triggerid_down,triggerid_up) values (".$triggerid.",".$triggerid_up.") ";
		$sql_add[]="update triggers set dep_level=dep_level+1 where triggerid=".$triggerid_up." ";
		$con_dbo -> execsql($sql_add,true);
		header('location:'.$referer_url);
	}

	//删除 处理方式
	if($action=="action_del"){
		$actionid="";
		if(isset($_GET["actionid"])){
			$actionid=$_GET["actionid"];
		}
		$sql_del="delete from actions where actionid=".$actionid;
		$con_dbo -> execsql($sql_del);
		header('location:'.$referer_url);
	}
	//添加 修改处理方式
	if($action=="action_add" || $action=="action_edit"){
		$actionid="";
		if(isset($_POST["actionid"])){
			$actionid=$_POST["actionid"];
		}
		$recipient="";
		if(isset($_POST["recipient"])){
			$recipient=$_POST["recipient"];
		}

		$userid="";
		if(isset($_POST["userid"])){
			$userid=$_POST["userid"];
		}
		$usrgrpid="";
		if(isset($_POST["usrgrpid"])){
			$usrgrpid=$_POST["usrgrpid"];
		}
		
		if($recipient==1){
			$userid=$usrgrpid;
		}
		$good="";
		if(isset($_POST["good"])){
			$good=$_POST["good"];
		}
		$delay="";
		if(isset($_POST["delay"])){
			$delay=$_POST["delay"];
		}
		$subject="";
		if(isset($_POST["subject"])){
			$subject=addslashes($_POST["subject"]);
		}
		$message="";
		if(isset($_POST["message"])){
			$message=addslashes($_POST["message"]);
		}
		$scope="";
		if(isset($_POST["scope"])){
			$scope=$_POST["scope"];
		}
		$severity="";
		if(isset($_POST["severity"])){
			$severity=$_POST["severity"];
		}else{
			$severity=0;
		}
		if($action=="action_add"){
			$sql_action_add="insert into actions (recipient,userid,good,delay,subject,message,scope,severity,triggerid) values (".$recipient.",".$userid.",".$good.",".$delay.",'".$subject."','".$message."',".$scope.",".$severity.",".$triggerid.") ";
			$con_dbo -> execsql($sql_action_add);
		}else{
			$sql_action_update="update actions set recipient=".$recipient.",userid=".$userid.",good=".$good.",delay=".$delay.",subject='".$subject."',message='".$message."',scope=".$scope.",severity=".$severity." where actionid=".$actionid;
			$con_dbo -> execsql($sql_action_update);
		}
		header('location:'.$referer_url);
	}

	$page_type="depends";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);

	//告警信息
	$sql_trigger=" select h.hostid,h.host,h.status as host_status,t.*,i.description as item_name from triggers t,hosts h,items i,functions f where f.itemid=i.itemid and h.hostid=i.hostid and t.triggerid=f.triggerid and t.triggerid=".$triggerid;
	$arr_trigger=$con_dbo -> ifexists($sql_trigger);
	$tpl -> assign("arr_trigger",$arr_trigger);

	//告警列表 依赖列表
	$sql_triggers=" select h.hostid,h.host,h.status as host_status,t.*,i.description as item_name from triggers t,hosts h,items i,functions f where f.itemid=i.itemid and h.hostid=i.hostid and t.triggerid=f.triggerid and h.status<>3 and t.triggerid <> ".$triggerid." and t.triggerid not in (select triggerid_up from trigger_depends where triggerid_down=".$triggerid.") ";
	$sql_triggers.=" order by h.host,t.expression,t.triggerid";
	$arr_triggers=$con_dbo -> sel_arr($sql_triggers);
	$tpl -> assign("arr_triggers",$arr_triggers);

	//依赖项
	$sql_trigger_depends="select a.*,b.* from trigger_depends a left join triggers b on (a.triggerid_up = b.triggerid) where a.triggerid_down=".$triggerid;
	$arr_trigger_depends=$con_dbo -> sel_arr($sql_trigger_depends);
	$tpl -> assign("arr_trigger_depends",$arr_trigger_depends);
	$tpl -> assign("count_depends",count($arr_trigger_depends));

	//处理方式
	$sql_actions="select a.*,(select name from users where userid=a.userid) as username,(select name from usrgrp where usrgrpid=a.userid) as groupname from actions a where a.triggerid=".$triggerid;

	$arr_actions=$con_dbo -> sel_arr($sql_actions);
	$tpl -> assign("arr_actions",$arr_actions);
	$tpl -> assign("count_actions",count($arr_actions));

	//用户分组

	$sql_usrgrp="select usrgrpid,name from usrgrp";
	$arr_usrgrp=$con_dbo -> sel_arr($sql_usrgrp);

	$tpl -> assign("arr_usrgrp",$arr_usrgrp);

	//用户列表
	$sql_users="select a.userid,a.alias,a.name,a.surname,a.passwd from users a ";
	$arr_users=$con_dbo -> sel_arr($sql_users);
	$tpl -> assign("arr_users",$arr_users);

	$sql_trigger="select description from triggers where triggerid=".$triggerid;
	$result=$con_dbo -> ifexists($sql_trigger);
	$subject=$result["description"];
	$tpl -> assign("subject",$subject);

	$sql="select i.description, h.host, i.key_ from hosts h, items i,functions f where f.triggerid=".$triggerid." and h.hostid=i.hostid and f.itemid=i.itemid order by i.description";
	$result=$con_dbo -> sel_arr($sql);

	$message="INSERT YOUR MESSAGE HERE\n\n------Latest data------\n\n";
	foreach($result as $row){
		$message=$message.$row["description"].": {".$row["host"].":".$row["key_"].".last(0)}  (latest value)\n";
		$message=$message.$row["description"].": {".$row["host"].":".$row["key_"].".max(300)} (maximum value for last 5 min)\n";
		$message=$message.$row["description"].": {".$row["host"].":".$row["key_"].".min(300)} (minimum value for last 5 min)\n\n";
	}
	$message=$message."---------End--------\n";
	$tpl -> assign("message",$message);

	$tpl -> display("action_admin_tpl.htm");
?>