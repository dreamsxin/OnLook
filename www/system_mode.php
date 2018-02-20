<?php
	$cur_rights=1;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$page_type="system";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);
	if($page_type=="system"){
		//服务器是否在运行
		$server_is_runing="停止";
		if( (exec("ps -ef|grep allview_server|grep -v grep|wc -l")>0) || (exec("ps -ax|grep allview_server|grep -v grep|wc -l")>0) ){
			$server_is_runing="正常";
		}
		$tpl -> assign("server_is_runing",$server_is_runing);

		//已储存数值个数
		$sql_history_count="select count(*) as history_count from history";
		$arr_history_count=$con_dbo -> ifexists($sql_history_count);
		$history_count=$arr_history_count["history_count"];
		$tpl -> assign("history_count",$history_count);

		//已储存趋势个数
		$sql_trends_count="select count(*) as trends_count from trends";
		$arr_trends_count=$con_dbo -> ifexists($sql_trends_count);
		$trends_count=$arr_trends_count["trends_count"];
		$tpl -> assign("trends_count",$trends_count);

		//告警个数
		$sql_alarms_count="select count(*) as alarms_count from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and a.value=1 ";
		$arr_alarms_count=$con_dbo -> ifexists($sql_alarms_count);
		$alarms_count=$arr_alarms_count["alarms_count"];
		$tpl -> assign("alarms_count",$alarms_count);

		//提醒条数
		$sql_alerts_count="select count(*) as alerts_count from alerts";
		$arr_alerts_count=$con_dbo -> ifexists($sql_alerts_count);
		$alerts_count=$arr_alerts_count["alerts_count"];
		$tpl -> assign("alerts_count",$alerts_count);
		
		//告警设置----

			//已启动
		$sql_triggers_count="select count(*) as triggers_count from triggers where status=0";
		$arr_triggers_count=$con_dbo -> ifexists($sql_triggers_count);
		$triggers_count_enabled=$arr_triggers_count["triggers_count"];
		$tpl -> assign("triggers_count_enabled",$triggers_count_enabled);

			//未启动
		$sql_triggers_count="select count(*) as triggers_count from triggers where status=1";
		$arr_triggers_count=$con_dbo -> ifexists($sql_triggers_count);
		$triggers_count_disabled=$arr_triggers_count["triggers_count"];
		$tpl -> assign("triggers_count_disabled",$triggers_count_disabled);

			//总数
		$triggers_count=$triggers_count_enabled + $triggers_count_disabled;
		$tpl -> assign("triggers_count",$triggers_count);

		//数据源个数----
			//启用的
		$sql_items_count="select count(*) as items_count from items i,hosts h where i.status=0 and i.hostid=h.hostid and h.status in (0,1)";
		$arr_items_count=$con_dbo -> ifexists($sql_items_count);
		$items_count_active=$arr_items_count["items_count"];
		$tpl -> assign("items_count_active",$items_count_active);

			//未启用的
		$sql_items_count="select count(*) as items_count from items i,hosts h where i.status=1 and i.hostid=h.hostid and h.status in (0,1)";
		$arr_items_count=$con_dbo -> ifexists($sql_items_count);
		$items_count_not_active=$arr_items_count["items_count"];
		$tpl -> assign("items_count_not_active",$items_count_not_active);

			//不支持
		$sql_items_count="select count(*) as items_count from items i,hosts h where i.status=3 and i.hostid=h.hostid and h.status in (0,1)";
		$arr_items_count=$con_dbo -> ifexists($sql_items_count);
		$items_count_not_supported=$arr_items_count["items_count"];
		$tpl -> assign("items_count_not_supported",$items_count_not_supported);

			//未知
		$sql_items_count="select count(*) as items_count from items i,hosts h where i.status=2 and i.hostid=h.hostid and h.status in (0,1)";
		$arr_items_count=$con_dbo -> ifexists($sql_items_count);
		$items_count_trapper=$arr_items_count["items_count"];
		$tpl -> assign("items_count_trapper",$items_count_trapper);
			
			//总数
		$items_count=$items_count_active + $items_count_not_active + $items_count_not_supported + $items_count_trapper;
		$tpl -> assign("items_count",$items_count);

		//用户个数
		$sql_users_count="select count(*) as users_count from users";
		$arr_users_count=$con_dbo -> ifexists($sql_users_count);
		$users_count=$arr_users_count["users_count"];
		$tpl -> assign("users_count",$users_count);

		//设备个数----

			//已监控
		$sql_hosts_count="select count(*) as hosts_count from hosts where status=0";
		$arr_hosts_count=$con_dbo -> ifexists($sql_hosts_count);
		$hosts_count_monitored=$arr_hosts_count["hosts_count"];
		$tpl -> assign("hosts_count_monitored",$hosts_count_monitored);

			//未监控
		$sql_hosts_count="select count(*) as hosts_count from hosts where status=1";
		$arr_hosts_count=$con_dbo -> ifexists($sql_hosts_count);
		$hosts_count_not_monitored=$arr_hosts_count["hosts_count"];
		$tpl -> assign("hosts_count_not_monitored",$hosts_count_not_monitored);

			//模板
		$sql_hosts_count="select count(*) as hosts_count from hosts where status=3";
		$arr_hosts_count=$con_dbo -> ifexists($sql_hosts_count);
		$hosts_count_template=$arr_hosts_count["hosts_count"];
		$tpl -> assign("hosts_count_template",$hosts_count_template);
			//总数
		$hosts_count=$hosts_count_monitored + $hosts_count_not_monitored ;//$hosts_count_template
		$tpl -> assign("hosts_count",$hosts_count);	

	}else{
		//设备列表
		$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid,(select count(*) as items_count from items where hostid=a.hostid and status=0) as items_count,(select count(*) as alarms_count from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and a.value=1 and i.hostid=a.hostid) as alarms_count from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid) where a.status in (0,1)";
		$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
		$tpl -> assign("arr_hosts",$arr_hosts);
	}
	$tpl -> display("system_mode_tpl.htm");
?>