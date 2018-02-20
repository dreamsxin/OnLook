<?php
	$cur_rights=16;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$itemid="";
	$s_hostid="";
	$s_groupid="";
	$trigger="";

	if(isset($_GET["itemid"])){
		$itemid=$_GET["itemid"];
	}


	if(isset($_GET["s_hostid"])){
		$s_hostid=$_GET["s_hostid"];
	}

	if(isset($_GET["s_groupid"])){
		$s_groupid=$_GET["s_groupid"];
	}

	if(isset($_GET["trigger"])){
		$trigger=unescape($_GET["trigger"]);
	}
	
	if(isset($_GET["s_triggerid"])){
		$s_triggerid=$_GET["s_triggerid"];
	}

	//设备列表
	$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid) where a.status in (0,2) ";
	$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
	$tpl -> assign("arr_hosts",$arr_hosts);

	foreach($arr_hosts as $v){
		if(trim($s_groupid)=="" && trim($s_hostid)==""){
			$s_hostid=$v["hostid"];
			$s_groupid=$v["groupid"];
			break;
		}elseif(trim($s_groupid)=="" && trim($s_hostid)!=""){
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

	//告警列表
	$sql_alarms=" select distinct f.triggerid,t.description,a.clock,a.value,t.triggerid,t.expression,t.priority from alarms a,triggers t,functions f,items i where t.triggerid=a.triggerid and f.triggerid=t.triggerid and i.itemid=f.itemid and a.value=1 ";
	if(trim($itemid)!=""){
		$sql_alarms.="and i.itemid=".$itemid." ";
	}
	if(trim($s_hostid)!=""){
		$sql_alarms.="and i.hostid=".$s_hostid." ";
	}
	if(trim($trigger)!=""){
		$sql_alarms.="and t.description like '%".$trigger."%' ";
	}
	if(trim($s_triggerid)!=""){
		$sql_alarms.="and t.triggerid = ".$s_triggerid." ";
	}
	$sql_alarms.=" order by a.clock desc ";
	if (!isset($_GET["dreams_curpage"])){
		$dreams_curpage=1;
	}else{
		$dreams_curpage=$_GET["dreams_curpage"];
	}
	$page_count=100;//100每页
	$arr_alarms=$con_dbo -> sel_arr_page($sql_alarms,$dreams_curpage,$page_count);
	if($con_dbo ->lastpage<=0){
		$dreams_curpage=1;
	}

	$tpl->assign("dreams_curpage",$dreams_curpage);
	$tpl->assign("page_count",$page_count);
	$tpl->assign("pagination",pagination($dreams_curpage,$con_dbo -> totalcount,$page_count,10,"dreams_page"));
	$tpl->assign("alarms_count",$con_dbo -> totalcount);
	$tpl->assign("arr_alarms_count",count($arr_alarms));
	$tpl -> assign("arr_alarms",$arr_alarms);


	$tpl -> assign("itemid",$itemid);
	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);
	$tpl -> assign("trigger",$trigger);
	$tpl -> assign("trigger",$trigger);

	$tpl -> display("alarms_tpl.htm");
?>