<?php
	$cur_rights=6;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$s_hostid="";
	$s_groupid="";
	$sources="";
	if(isset($_GET["s_hostid"])){
		$s_hostid=$_GET["s_hostid"];
	}
	
	if(isset($_GET["s_groupid"])){
		$s_groupid=$_GET["s_groupid"];
	}
	
	if(isset($_GET["sources"])){
		$sources=unescape($_GET["sources"]);
	}

	if($action=="import_host"){
		$itemids=$_POST["itemids"];
		$hostid=$_GET["hostid"];
		import_host($hostid,$itemids);
		header('location:'.$referer_url);
	}

	//删除数据源
	if($action=="del" || $action=="dels"){
			if($action=="del"){
				$itemid=$_GET["itemid"];
			}else{
				$itemid=$_POST["itemids"];
			}
		
			$sql_item_del=array();
			$sql_item_del[]=" delete from graphs_items where itemid in (".$itemid.") ";
			$sql_item_del[]=" delete from actions where triggerid in (select triggerid from functions where itemid in (".$itemid.")) ";
			$sql_item_del[]="delete from trigger_depends where triggerid_down in (select triggerid from functions where itemid in (".$itemid.")) or triggerid_up in (select triggerid from functions where itemid in (".$itemid.")) ";
			$sql_item_del[]=" delete from triggers where triggerid in (select triggerid from functions where itemid in (".$itemid.")) ";
			$sql_item_del[]=" delete from functions where itemid in (".$itemid.") ";
			$sql_item_del[]=" delete from items where itemid in (".$itemid.") ";

			$con_dbo -> execsql($sql_item_del,true);
			header('location:'.$referer_url);
	}
	//修改数据源
	if($action=="add" || $action=="edit"){
		$itemid=$_POST["itemid"];
		$description=$_POST["description"];
		$hostid=$_POST["hostid"];
		$type=$_POST["type"];
		$snmp_community=$_POST["snmp_community"];
		$snmp_oid=$_POST["snmp_oid"];
		$snmp_port=$_POST["snmp_port"];
		$key_=$_POST["key_"];
		$snmpv3_securityname=$_POST["snmpv3_securityname"];
		$snmpv3_securitylevel=$_POST["snmpv3_securitylevel"];
		$snmpv3_authpassphrase=$_POST["snmpv3_authpassphrase"];
		$snmpv3_privpassphrase=$_POST["snmpv3_privpassphrase"];
		$units=$_POST["units"];
		$formula=$_POST["formula"];
		$delay=$_POST["delay"];
		$history=$_POST["history"];
		$trends=$_POST["trends"];
		$status=$_POST["status"];
		$value_type=$_POST["value_type"];
		$delta=$_POST["delta"];
		if($action=="add"){
			$sql_item_insert="insert into items (hostid,status,value_type,type,history,trends,key_,description,units,formula,delay";
			if(trim($snmp_community)!=""){
				$sql_item_insert.=",snmp_community";
			}
			if(trim($snmp_oid)!=""){
				$sql_item_insert.=",snmp_oid";
			}
			if(trim($snmp_port)!=""){
				$sql_item_insert.=",snmp_port";
			}
			if(trim($snmpv3_securityname)!=""){
				$sql_item_insert.=",snmpv3_securityname";
			}
			if(trim($snmpv3_securitylevel)!=""){
				$sql_item_insert.=",snmpv3_securitylevel";
			}
			if(trim($snmpv3_authpassphrase)!=""){
				$sql_item_insert.=",snmpv3_authpassphrase";
			}
			if(trim($snmpv3_privpassphrase)!=""){
				$sql_item_insert.=",snmpv3_privpassphrase";
			}
			$sql_item_insert.=") values (".$hostid.",".$status.",".$value_type.",".$type.",".$history.",".$trends.",'".$key_."','".$description."','".$units."','".$formula."','".$delay."'";
			if(trim($snmp_community)!=""){
				$sql_item_insert.=",'".$snmp_community."'";
			}
			if(trim($snmp_oid)!=""){
				$sql_item_insert.=",'".$snmp_oid."'";
			}
			if(trim($snmp_port)!=""){
				$sql_item_insert.=",".$snmp_port."";
			}
			if(trim($snmpv3_securityname)!=""){
				$sql_item_insert.=",'".$snmpv3_securityname."'";
			}
			if(trim($snmpv3_securitylevel)!=""){
				$sql_item_insert.=",".$snmpv3_securitylevel."";
			}
			if(trim($snmpv3_authpassphrase)!=""){
				$sql_item_insert.=",'".$snmpv3_authpassphrase."'";
			}
			if(trim($snmpv3_privpassphrase)!=""){
				$sql_item_insert.=",'".$snmpv3_privpassphrase."'";
			}
			$sql_item_insert.=")";
			$con_dbo -> execsql($sql_item_insert);
		}elseif($action=="edit"){
			$sql_item_update="update items set hostid=".$hostid.",status=".$status.",value_type=".$value_type.",type=".$type.",history=".$history.",trends=".$trends.",key_='".$key_."',description='".$description."',units='".$units."',formula='".$formula."',delay='".$delay."'";
			if(trim($snmp_community)!=""){
				$sql_item_update.=",snmp_community='".$snmp_community."'";
			}
			if(trim($snmp_oid)!=""){
				$sql_item_update.=",snmp_oid='".$snmp_oid."'";
			}
			if(trim($snmp_port)!=""){
				$sql_item_update.=",snmp_port=".$snmp_port."";
			}
			if(trim($snmpv3_securityname)!=""){
				$sql_item_update.=",snmpv3_securityname='".$snmpv3_securityname."'";
			}
			if(trim($snmpv3_securitylevel)!=""){
				$sql_item_update.=",snmpv3_securitylevel=".$snmpv3_securitylevel."";
			}
			if(trim($snmpv3_authpassphrase)!=""){
				$sql_item_update.=",snmpv3_authpassphrase='".$snmpv3_authpassphrase."'";
			}
			if(trim($snmpv3_privpassphrase)!=""){
				$sql_item_update.=",snmpv3_privpassphrase='".$snmpv3_privpassphrase."'";
			}
			$sql_item_update.=" where itemid=".$itemid;
			$con_dbo -> execsql($sql_item_update);
		}
		//echo $sql_item_insert;
		header('location:'.$referer_url);
	}


	//设备列表
	$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid) order by a.status,a.hostid";
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

	//数据源列表
	$sql_items="select h.host,h.port,h.hostid,h.status as host_status,i.* from hosts h,items i where h.hostid=i.hostid ";
	if(trim($s_hostid)!=""){
		$sql_items.="and i.hostid=".$s_hostid." ";
	}
	if(trim($sources)!=""){
		$sql_items.="and i.description like '%".$sources."%' ";
	}

	$sql_items.="order by h.host,i.description desc,i.key_";

	$arr_items=$con_dbo -> sel_arr($sql_items);
	$tpl -> assign("arr_items_count",count($arr_items));
	$tpl -> assign("arr_items",$arr_items);

	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);
	$tpl -> assign("sources",$sources);

	$tpl -> display("item_admin_tpl.htm");

	function import_host($hostid,$itemids){
		global $con_dbo,$dreams_msg;
		//搜索遍历 items
		$sql_items="select * from items where itemid in (".$itemids.") ";
		$arr_items=$con_dbo -> sel_arr($sql_items);
		foreach($arr_items as $key_items => $item){
			//插入 items
			$itemid=add_item($item["description"],$item["key_"],$hostid,$item["delay"],$item["history"],$item["status"],$item["type"],$item["snmp_community"],$item["snmp_oid"],$item["value_type"],"",161,$item["units"],$item["multiplier"],$item["delta"],$item["snmpv3_securityname"],$item["snmpv3_securitylevel"],$item["snmpv3_authpassphrase"],$item["snmpv3_privpassphrase"],$item["formula"]);
			if($itemid===false){
				//echo $dreams_msg;
				continue;
			}
			
			//搜索遍历 triggers
			//$sql_triggers="select * from triggers where triggerid in (select distinct t.triggerid from triggers t,functions f where f.itemid=".$item["itemid"]." and f.triggerid=t.triggerid) ";
			$sql_triggers="select distinct f.triggerid,t.* from triggers t,functions f where f.itemid=".$item["itemid"]." and f.triggerid=t.triggerid";
			$arr_triggers=$con_dbo -> sel_arr($sql_triggers);
			$expression=trim($arr_triggers[0]["expression"]);
			foreach($arr_triggers as $key_triggers => $trigger){
				
				//插入 triggers
				$sql_triggers_add=array();
				$sql_triggers_add[]="insert into triggers  (description,priority,status,comments,url,value) values ('".addslashes($trigger["description"])."',".$trigger["priority"].",".$trigger["status"].",'".addslashes($trigger["comments"])."','".addslashes($trigger["url"])."',2)";
				$sql_triggers_add[]=" select LAST_INSERT_ID() as id  ";
				$re_triggers = $con_dbo -> execsql($sql_triggers_add,true);
				$triggerid=$re_triggers[0][0]["id"];

				//搜索遍历 functions
				$sql_functions="select * from functions where triggerid=".$trigger["triggerid"]." and itemid=".$item["itemid"];
				$arr_functions=$con_dbo -> sel_arr($sql_functions);
				foreach($arr_functions as $key_functions => $function){
					//插入 functions
					$sql_functions_add=array();
					$sql_functions_add[]="insert into functions (itemid,triggerid,function,parameter) values ($itemid,$triggerid,'".$function["function"]."','".$function["parameter"]."')";
					$sql_functions_add[]=" select LAST_INSERT_ID() as id  ";
					$re_functions = $con_dbo -> execsql($sql_functions_add,true);
					$functionid=$re_functions[0][0]["id"];
					
					//更新 triggers expreesion字段
					$expression=str_replace("{".$function["functionid"]."}","{".$functionid."}",$expression);

				}
				$sql_triggers_update="update triggers set expression='".$expression."' where triggerid=".$triggerid;
				$con_dbo -> execsql($sql_triggers_update);

				$sql_actions="select * from actions where scope=0 and triggerid=".$trigger["triggerid"];
				$arr_actions=$con_dbo -> sel_arr($sql_actions);
				foreach($arr_actions as $key_actions => $action){

					$userid=$action["userid"];
					$scope=$action["scope"];
					$severity=$action["severity"];
					$good=$action["good"];
					$delay=$action["delay"];
					$subject=addslashes($action["subject"]);
					$message=addslashes($action["message"]);
					$recipient=$action["recipient"];
					$sql_actions_add[]="insert into actions (triggerid, userid, scope, severity, good, delay, subject, message,recipient) values ($triggerid,$userid,$scope,$severity,$good,$delay,'$subject','$message',$recipient)";
					$sql_actions_add[]=" select LAST_INSERT_ID() as id  ";
					$re_actions = $con_dbo -> execsql($sql_actions_add,true);
					$actionsid=$re_actions[0][0]["id"];
				}
			}
		}
	}

	function add_item($description,$key,$hostid,$delay,$history,$status,$type,$snmp_community,$snmp_oid,$value_type,$trapper_hosts,$snmp_port,$units,$multiplier,$delta,$snmpv3_securityname,$snmpv3_securitylevel,$snmpv3_authpassphrase,$snmpv3_privpassphrase,$formula)
	{
		global	$con_dbo,$dreams_msg;
		$sql="select * from hosts where hostid=$hostid";
		//echo $sql;
		$result=$con_dbo -> ifexists($sql);
		if($result===false)
		{
			$dreams_msg="没有找到该设备";
			return false;
		}
		$sql="select * from items where hostid=$hostid and key_='$key'";
		//echo $sql;
		$result=$con_dbo -> ifexists($sql);
		if($result!==false)
		{
			$dreams_msg="一个具有相同关键字的条目已经存在，条目的关键字必须唯一";
			return false;
		}
		if($delay<1)
		{
			$dreams_msg="延迟不能小于1秒";
			return false;
		}
		if( ($snmp_port<1)||($snmp_port>65535))
		{
			$dreams_msg="非法 SNMP 端口";
			return false;
		}
		if($value_type == 1)
		{
			$delta=0;
		}
		$key=addslashes($key);
		$description=addslashes($description);
		$snmpv3_securityname=addslashes($snmpv3_securityname);
		$snmpv3_authpassphrase=addslashes($snmpv3_authpassphrase);
		$snmpv3_privpassphrase=addslashes($snmpv3_privpassphrase);

		$sqls_item_add=array();
		$sqls_item_add[]="insert into items (description,key_,hostid,delay,history,nextcheck,status,type,snmp_community,snmp_oid,value_type,trapper_hosts,snmp_port,units,multiplier,delta,snmpv3_securityname,snmpv3_securitylevel,snmpv3_authpassphrase,snmpv3_privpassphrase,formula) values ('$description','$key',$hostid,$delay,$history,0,$status,$type,'$snmp_community','$snmp_oid',$value_type,'$trapper_hosts',$snmp_port,'$units',$multiplier,$delta,'$snmpv3_securityname',".$snmpv3_securitylevel.",'$snmpv3_authpassphrase','$snmpv3_privpassphrase','$formula')";
		$sqls_item_add[]=" select LAST_INSERT_ID() as id  ";
		$re = $con_dbo -> execsql($sqls_item_add,true);
		$itemid=$re[0][0]["id"];
		return $itemid;
	}
?>