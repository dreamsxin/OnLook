<?php
	$cur_rights="";
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//用户信息
	$sql_users="select userid,alias,name,surname,url from users where userid=".$_SESSION["users"]["userid"];
	$arr_users=$con_dbo -> ifexists($sql_users);
	$tpl -> assign("arr_users",$arr_users);

	//服务器是否在运行
	$server_is_runing="停止";
	if( (exec("ps -ef|grep allview_server|grep -v grep|wc -l")>0) || (exec("ps -ax|grep allview_server|grep -v grep|wc -l")>0) ){
		$server_is_runing="正常";
	}
	$tpl -> assign("server_is_runing",$server_is_runing);

	//告警个数
	$sql_alarms_count="select count(*) as alarms_count from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and a.value=1 and a.clock >= ".mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	$arr_alarms_count=$con_dbo -> ifexists($sql_alarms_count);
	$alarms_count=$arr_alarms_count["alarms_count"];
	$tpl -> assign("alarms_count",$alarms_count);

	//提醒条数
	$sql_alerts_count="select count(*) as alerts_count from alerts where clock >= ".mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	$arr_alerts_count=$con_dbo -> ifexists($sql_alerts_count);
	$alerts_count=$arr_alerts_count["alerts_count"];
	$tpl -> assign("alerts_count",$alerts_count);

	$tpl -> display("index_tpl.htm");
?>