<?php
	$cur_rights=18;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$s_hostid="";
	$s_groupid="";
	$level="";
	$msg="";

	if(isset($_GET["s_hostid"])){
		$s_hostid=$_GET["s_hostid"];
	}

	if(isset($_GET["s_groupid"])){
		$s_groupid=$_GET["s_groupid"];
	}

	if(isset($_GET["level"])){
		$level=unescape($_GET["level"]);
	}
	
	if(isset($_GET["msg"])){
		$msg=trim($_GET["msg"]);
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
			if($v["groupid"]==$s_groupid){
				$s_hostid=$v["hostid"];
				break;
			}
		}
	}

	//分组
	$sql_groups="select groupid,name from groups order by groupid desc";
	$arr_groups=$con_dbo -> sel_arr($sql_groups);
	$tpl -> assign("arr_groups",$arr_groups);

	//告警列表
	$sql_logs="select h.host,h.ip,l.facility,l.priority,l.level,l.tag,l.date,l.time,l.program,l.msg,l.seq from logs l,hosts h where (l.host=h.host or l.host=h.ip) and h.status<>3 ";
	if($msg!=""){
		$sql_logs.="and l.msg like '%".$msg."%' ";
	}
	if(trim($level)!=""){
		$sql_logs.="and l.level=$level ";
	}
	if(trim($s_hostid)!=""){
		$sql_logs.="and h.hostid=".$s_hostid." ";
	}
	$sql_logs.=" order by l.date desc,l.time desc ";
	if (!isset($_GET["dreams_curpage"])){
		$dreams_curpage=1;
	}else{
		$dreams_curpage=$_GET["dreams_curpage"];
	}
	//echo $sql_logs;
	$page_count=100;//100每页
	$arr_alarms=$con_dbo -> sel_arr_page($sql_logs,$dreams_curpage,$page_count);
	if($con_dbo ->lastpage<=0){
		$dreams_curpage=1;
	}
	$tpl->assign("dreams_curpage",$dreams_curpage);
	$tpl->assign("page_count",$page_count);
	$tpl->assign("pagination",pagination($dreams_curpage,$con_dbo -> totalcount,$page_count,10,"dreams_page"));
	$tpl->assign("alarms_count",$con_dbo -> totalcount);
	$tpl->assign("arr_alarms_count",count($arr_alarms));
	$tpl -> assign("arr_alarms",$arr_alarms);


	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);
	$tpl -> assign("level",$level);
	$tpl -> assign("msg",$msg);

	$tpl -> display("syslogs_tpl.htm");
?>