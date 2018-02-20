<?php
	$cur_rights=5;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//组删除
	if($action=="group_del"){
		if(isset($_GET["group_id"])){
			$usrgrpid=$_GET["group_id"];
			$sql_group_del="delete from groups where type<>'system' and groupid=".$usrgrpid;
			$con_dbo -> execsql($sql_group_del);
		}
		header('location:'.$referer_url);
	}
	//组 修改 添加
	if($action=="group_add" || $action=="group_edit"){
		$groupid=$_POST["group_id"];
		$name=$_POST["group_name"];

		if($action=="group_add"){
			$sql_group_insert = "insert into groups (name) values ('".$name."')  ";
			$con_dbo -> execsql($sql_group_insert);
		}else{
			$sql_group_update="update groups set name='".$name."' where type<>'system' and groupid=".$groupid;
			$con_dbo -> execsql($sql_group_update);
		}
		header('location:'.$referer_url);
	}
	//设备删除
	if($action=="del"){
		if(isset($_GET["hostid"])){
			$hostid=$_GET["hostid"];
			$sql_host_del=array();
			$sql_host_del[]=" delete from graphs_items where itemid in (select itemid from items where hostid=".$hostid.") ";
			$sql_host_del[]=" delete from actions where triggerid in (select triggerid from functions where itemid in (select itemid from items where hostid=".$hostid.")) ";
			$sql_host_del[]="delete from trigger_depends where triggerid_down in (select triggerid from functions where itemid in (select itemid from items where hostid=".$hostid.")) or triggerid_up in (select triggerid from functions where itemid in (select itemid from items where hostid=".$hostid.")) ";
			$sql_host_del[]=" delete from triggers where triggerid in (select triggerid from functions where itemid in (select itemid from items where hostid=".$hostid.")) ";
			$sql_host_del[]=" delete from functions where itemid in (select itemid from items where hostid=".$hostid.") ";
			$sql_host_del[]=" delete from items where hostid=".$hostid;
			$sql_host_del[]=" delete from hosts where hostid=".$hostid;
			$sql_host_del[]=" delete from hosts_groups where hostid=".$hostid;
			$sql_host_del[]="delete from sysmaps_links where shostid1=$hostid or shostid2=$hostid";
			$sql_host_del[]="delete from sysmaps_hosts where shostid=$hostid";
			$con_dbo -> execsql($sql_host_del,true);
		}
		header('location:'.$referer_url);
	}
	//设备 修改添加
	if($action=="add" || $action=="edit"){
		$hostid=$_POST["hostid"];
		$host=$_POST["host"];
		$ip=$_POST["ip"];
		$port=$_POST["port"];
		$host_type=$_POST["host_type"];
		$status=$_POST["status"];
		if($host_type==3){
			$status=3;
		}
		$groupid=$_POST["groupid"];
		if($action=="add"){
			$sql_host_insert=array();
			if($host_type!=3){
				$sql_host_insert[] = "insert into hosts (host,ip,port,status) values ('".$host."','".$ip."',".$port.",".$status.")  ";
			}else{
				$sql_host_insert[] = "insert into hosts (host,status) values ('".$host."',".$status.")  ";
			}
			$sql_host_insert[] = " select LAST_INSERT_ID() as id  ";
			$re = $con_dbo -> execsql($sql_host_insert,true);
			$hostid=$re[0][0]["id"];
			$sql_host_group_insert="insert into hosts_groups (hostid,groupid) values (".$hostid.",".$groupid.") ";
			$con_dbo -> execsql($sql_host_group_insert);


		}else{
			if($host_type!=3){
				$sql_host_update = "update hosts set host='".$host."',ip='".$ip."',port=".$port.",status=".$status." where hostid=".$hostid;
			}else{
				$sql_host_update = "update hosts set host='".$host."',status=".$status." where hostid=".$hostid;
			}

			$con_dbo -> execsql($sql_host_update);

			$sql_host_group_delete="delete from hosts_groups where hostid=".$hostid;
			$con_dbo -> execsql($sql_host_group_delete);
			
			$sql_host_group_insert="insert into hosts_groups (hostid,groupid) values (".$hostid.",".$groupid.") ";
			$con_dbo -> execsql($sql_host_group_insert);
		}
		
		//模板导入 
		$import_hostid=$_POST["import_hostid"];
		if(trim($import_hostid)!=""){
			import_host($hostid,$import_hostid);
		}
		header('location:'.$referer_url);
	}

	$page_type="host";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);
	
	//设备分组

	$sql_groups="select * from groups order by groupid desc";
	$arr_groups=$con_dbo -> sel_arr($sql_groups);

	$tpl -> assign("arr_groups",$arr_groups);

	//设备列表
	$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid)";
	$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
	$tpl -> assign("arr_hosts",$arr_hosts);

	//设备模板列表
	$sql_hosts_templates="select a.hostid,a.host from hosts a where status=3 ";
	$arr_hosts_templates=$con_dbo -> sel_arr($sql_hosts_templates);
	$tpl -> assign("arr_hosts_templates",$arr_hosts_templates);

	$tpl -> display("host_admin_tpl.htm");

	function import_host($hostid,$host_templateid){
		global $con_dbo,$dreams_msg;
		//搜索遍历 items
		$sql_items="select * from items where hostid=$host_templateid";
		////echo $sql_items;
		$arr_items=$con_dbo -> sel_arr($sql_items);
		foreach($arr_items as $key_items => $item){
			//插入 items
			$itemid=add_item($item["description"],$item["key_"],$hostid,$item["delay"],$item["history"],$item["status"],$item["type"],$item["snmp_community"],$item["snmp_oid"],$item["value_type"],"",161,$item["units"],$item["multiplier"],$item["delta"],$item["snmpv3_securityname"],$item["snmpv3_securitylevel"],$item["snmpv3_authpassphrase"],$item["snmpv3_privpassphrase"],$item["formula"]);
			//echo "itemid--".$itemid."--\n";
			if($itemid===false){
				//echo "dreams_msg--".$dreams_msg."--\n";
				continue;
			}
			
			//搜索遍历 triggers
			//$sql_triggers="select * from triggers where triggerid in (select distinct t.triggerid from triggers t,functions f where f.itemid=".$item["itemid"]." and f.triggerid=t.triggerid) ";
			$sql_triggers="select * from triggers where triggerid in (select distinct t.triggerid from triggers t,functions f where f.itemid=".$item["itemid"]." and f.triggerid=t.triggerid) ";

			$arr_triggers=$con_dbo -> sel_arr($sql_triggers);

			//exit;
			$expression=trim($arr_triggers[0]["expression"]);
			foreach($arr_triggers as $key_triggers => $trigger){
				////echo $expression;
				////echo "\n";
				////echo $sql_triggers;
				////echo "\n";

				//插入 triggers
				$sql_triggers_add=array();
				$sql_triggers_add[]="insert into triggers  (description,priority,status,comments,url,value) values ('".addslashes($trigger["description"])."',".$trigger["priority"].",".$trigger["status"].",'".addslashes($trigger["comments"])."','".addslashes($trigger["url"])."',2)";
				$sql_triggers_add[]=" select LAST_INSERT_ID() as id  ";
				$re_triggers = $con_dbo -> execsql($sql_triggers_add,true);
				$triggerid=$re_triggers[0][0]["id"];
				//echo "triggerid--".$triggerid."--\n";

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
					//echo "functionid--".$functionid."--\n";
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

					$sql_actions_add=array();
					$sql_actions_add[]="insert into actions (triggerid, userid, scope, severity, good, delay, subject, message,recipient) values ($triggerid,$userid,$scope,$severity,$good,$delay,'$subject','$message',$recipient)";
					$sql_actions_add[]="select LAST_INSERT_ID() as id";
					$re_actions = $con_dbo -> execsql($sql_actions_add,true);
					$actionsid=$re_actions[0][0]["id"];
					//echo "actionsid--".$actionsid."--\n";
				}
			}
		}
	}

	function add_item($description,$key,$hostid,$delay,$history,$status,$type,$snmp_community,$snmp_oid,$value_type,$trapper_hosts,$snmp_port,$units,$multiplier,$delta,$snmpv3_securityname,$snmpv3_securitylevel,$snmpv3_authpassphrase,$snmpv3_privpassphrase,$formula)
	{
		global	$con_dbo,$dreams_msg;
		$sql="select * from hosts where hostid=$hostid";
		$result=$con_dbo -> ifexists($sql);
		if($result===false)
		{
			$dreams_msg="没有找到该设备";
			return false;
		}
		$sql="select * from items where hostid=$hostid and key_='$key'";
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
		//echo "itemid--".$itemid."--\n";
		return $itemid;
	}

?>