<?php
	$cur_rights=12;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	if(isset($_GET["sort"])){
		$sort=$_GET["sort"];
	}

	if(isset($_GET["desc"])){
		$desc=$_GET["desc"];
	}

	if(isset($_GET["s_hostid"])){
		$s_hostid=$_GET["s_hostid"];
	}

	if(isset($_GET["s_groupid"])){
		$s_groupid=$_GET["s_groupid"];
	}

	if(isset($_GET["sources"])){
		$sources=unescape($_GET["sources"]);
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

	$sql_report="select distinct h.hostid,h.host,i.description as item_name,t.triggerid,t.expression,t.description,t.value from triggers t,hosts h,items i,functions f where f.itemid=i.itemid and h.hostid=i.hostid and t.status=0 and t.triggerid=f.triggerid and h.status in (0,2) and i.status=0 ";
	if(trim($sources)!=""){
		$sql_report.=" and t.description like '%".$sources."%' ";
	}
	if(trim($s_hostid)!=""){
		$sql_report.=" and h.hostid = ".$s_hostid." ";
	}
	$sql_report.=" order by h.host, t.description";
	$result=$con_dbo -> sel_arr($sql_report);
	$tpl -> assign("result",$result);
	$tpl -> assign("result_count",count($result));

	$tpl -> assign("sort",$sort);
	$tpl -> assign("desc",$desc);
	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);
	$tpl -> assign("sources",$sources);
	$tpl -> display("report_tpl.htm");
?>