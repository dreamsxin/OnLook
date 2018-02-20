<?php
	$cur_rights=11;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$sort="i.description";
	$desc="desc";
	$s_hostid="";
	$s_groupid="";
	$sources="";

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

	//数据
	$sql_date="select h.host,i.itemid,i.description,i.lastvalue,i.prevvalue,i.lastclock,i.status,h.hostid,i.value_type,i.units,i.multiplier,i.key_ from items i,hosts h where h.hostid=i.hostid and h.status in (0,2) and i.status in (0,3) ";
	if(trim($sources)!=""){
		$sql_date.=" and i.description like '%".$sources."%' ";
	}
	if(trim($s_hostid)!=""){
		$sql_date.=" and i.hostid = ".$s_hostid." ";
	}
	if(trim($sort)!=""){
		$sql_date.=" order by i.status,$sort $desc";
	}
	$arr_date=$con_dbo -> sel_arr($sql_date);
	$tpl -> assign("arr_date",$arr_date);
	$tpl -> assign("arr_date_count",count($arr_date));


	$tpl -> assign("sort",$sort);
	$tpl -> assign("desc",$desc);
	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);
	$tpl -> assign("sources",$sources);

	$tpl -> display("show_date_tpl.htm");
?>