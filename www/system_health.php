<?php
	$cur_rights=1;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

//主机
	if(isset($_POST["s_hostid"])){
		$s_hostid=$_POST["s_hostid"];
	}

	if(isset($_POST["s_groupid"])){
		$s_groupid=$_POST["s_groupid"];
	}

//起始时间
	$from_year=date('Y', strtotime('-1 hour',time()));
	if(isset($_POST["year"])){
		$from_year=$_POST["year"];
	}

	$from_month=date('n', strtotime('-1 hour',time()));
	if(isset($_POST["month"])){
		$from_month=$_POST["month"];
	}

	$from_day=date('j', strtotime('-1 hour',time()));
	if(isset($_POST["day"])){
		$from_day=$_POST["day"];
	}
	$tpl -> assign("from_year",$from_year);
	$tpl -> assign("from_month",$from_month);
	$tpl -> assign("from_day",$from_day);

	$from_date=$from_year."-".$from_month."-".$from_day;
	$tpl -> assign("from_date",$from_date);
	$from_date=mktime(0,0,0,$from_month,$from_day,$from_year);
	$to_date=mktime(23,59,59,$from_month,$from_day,$from_year);

	$sql_str=" a.clock >= $from_date and a.clock <= $to_date ";

	//设备列表
	$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid) where a.status in (0,2) ";
	$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
	$tpl -> assign("arr_hosts",$arr_hosts);

	foreach($arr_hosts as $v){
		if(trim($s_hostid)!=""){
			if($s_hostid==$v["hostid"]){
				$s_groupid=$v["groupid"];
				break;
			}
		}else{
			break;
		}
	}

	//分组
	$sql_groups="select groupid,name from groups order by groupid desc";
	$arr_groups=$con_dbo -> sel_arr($sql_groups);
	$tpl -> assign("arr_groups",$arr_groups);

	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);

	//接口告警列表
	$cisco_alarms_sql=" select distinct f.triggerid,t.description,a.clock,a.value,t.triggerid,t.expression,t.priority from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and a.value=1 and $sql_str and t.description like '接口状态%'";
	if(trim($s_hostid)!=""){
		$cisco_alarms_sql.=" and i.hostid = ".$s_hostid." ";
	}
	$cisco_alarms=$con_dbo -> sel_arr($cisco_alarms_sql);
	$tpl -> assign("cisco_alarms",$cisco_alarms);


	//内存使用率告警列表
	$memory_alarms_sql=" select distinct f.triggerid,t.description,a.clock,a.value,t.triggerid,t.expression,t.priority from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and a.value=1 and $sql_str and t.description like '内存使用率%'";
	if(trim($s_hostid)!=""){
		$memory_alarms_sql.=" and i.hostid = ".$s_hostid." ";
	}
	$memory_alarms=$con_dbo -> sel_arr($memory_alarms_sql);
	$tpl -> assign("memory_alarms",$memory_alarms);

	//磁盘使用率告警列表
	$disk_alarms_sql=" select distinct f.triggerid,t.description,a.clock,a.value,t.triggerid,t.expression,t.priority from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and i.hostid=h.hostid and a.value=1 and $sql_str and t.description like '磁盘使用率%'";
	if(trim($s_hostid)!=""){
		$disk_alarms_sql.=" and i.hostid = ".$s_hostid." ";
	}
	$disk_alarms=$con_dbo -> sel_arr($disk_alarms_sql);
	$tpl -> assign("disk_alarms",$disk_alarms);

	//进程状态告警列表
	$processes_alarms_sql=" select distinct f.triggerid,t.description,a.clock,a.value,t.triggerid,t.expression,t.priority from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and i.hostid=h.hostid and a.value=1 and t.description like '进程状态%'";
	if(trim($s_hostid)!=""){
		$processes_alarms.=" and i.hostid = ".$s_hostid." ";
	}
	$processes_alarms=$con_dbo -> sel_arr($processes_alarms_sql);
	$tpl -> assign("processes_alarms",$processes_alarms);

	//CPU负荷告警列表
	$cpu_alarms_sql=" select distinct f.triggerid,t.description,a.clock,a.value,t.triggerid,t.expression,t.priority from alarms a,triggers t,functions f,items i,host h where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and i.hostid=h.hostid and a.value=1 and $sql_str and  t.description like 'CPU负荷%'";
	if(trim($s_hostid)!=""){
		$cpu_alarms_sql.=" and i.hostid = ".$s_hostid." ";
	}
	$cpu_alarms=$con_dbo -> sel_arr($cpu_alarms_sql);
	$tpl -> assign("cpu_alarms",$cpu_alarms);

	$tpl -> display("system_health_tpl.htm");

?>
