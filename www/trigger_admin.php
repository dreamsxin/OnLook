<?php
	$cur_rights=7;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$itemid="";
	$s_hostid="";
	$s_groupid="";
	$trigger="";
	$host_name=";
	$key_=";
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

	if(isset($_GET["host_name"])){
		$host_name=$_GET["host_name"];
	}

	if(isset($_GET["key_"])){
		$key_=$_GET["key_"];
	}
	//删除告警配置
	if($action=="del"){
		if(isset($_GET["triggerid"])){
			$triggerid=$_GET["triggerid"];

			$sql_del=array();
			$sql_del[]="delete from triggers where triggerid=".$triggerid;
			$sql_del[]="delete from functions where triggerid=".$triggerid;
			$sql_del[]="delete from trigger_depends where triggerid_down=".$triggerid." or triggerid_up=".$triggerid;
			$sql_del[]="delete from actions where triggerid=".$triggerid;
			$con_dbo -> execsql($sql_del);
		}
		header('location:'.$referer_url);
	}

	//添加 修改告警配置
	if($action=="add" || $action=="edit"){
		$triggerid="";
		if(isset($_POST["triggerid"])){
			$triggerid=$_POST["triggerid"];
		}
		$description="";
		if(isset($_POST["description"])){
			$description=$_POST["description"];
		}
		$expression="";
		if(isset($_POST["expression"])){
			$expression=$_POST["expression"];
		}
		$priority="";
		if(isset($_POST["priority"])){
			$priority=$_POST["priority"];
		}
		$comments="";
		if(isset($_POST["comments"])){
			$comments=$_POST["comments"];
		}
		$url="";
		if(isset($_POST["url"])){
			$url=$_POST["url"];
		}
		$status="";
		if(isset($_POST["status"])){
			$status=$_POST["status"];
		}
		
		$exp_arr=implode_exp($expression,$action);
		$expression=$exp_arr["exp"];
		$triggerid=$exp_arr["triggerid"];
		$sql_trigger_update="update triggers set description='".$description."',priority=".$priority.",comments='".$comments."',url='".$url."',status=".$status.",expression='".$expression."' where triggerid=".$triggerid;
		$con_dbo -> execsql($sql_trigger_update);
		//echo $sql_trigger_update;

		//header('location:trigger_admin.php?s_hostid='.$arr_host["hostid"]);
		header('location:'.$referer_url);
		//echo "sdf";
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

	//告警列表
	$sql_triggers=" select distinct t.triggerid,t.* from triggers t,hosts h,items i,functions f where f.itemid=i.itemid  and t.triggerid=f.triggerid and i.hostid=".$s_hostid." ";
	if(trim($itemid)!=""){
		$sql_triggers.="and i.itemid=".$itemid." ";
	}

	if(trim($trigger)!=""){
		$sql_triggers.="and t.description like '%".$trigger."%' ";
	}
	if(trim($s_triggerid)!=""){
		$sql_triggers.="and t.triggerid = ".$s_triggerid." ";
	}
	$sql_triggers.=" order by h.host,t.expression,t.triggerid";
	//echo $sql_triggers;
	$arr_triggers=$con_dbo -> sel_arr($sql_triggers);
	$tpl -> assign("arr_triggers",$arr_triggers);
	$tpl -> assign("arr_triggers_count",count($arr_triggers));

	$tpl -> assign("itemid",$itemid);
	$tpl -> assign("s_hostid",$s_hostid);
	$tpl -> assign("s_groupid",$s_groupid);
	$tpl -> assign("trigger",$trigger);
	$tpl -> assign("s_triggerid",$s_triggerid);
	$tpl -> assign("host_name",$host_name);
	$tpl -> assign("key_",$key_);

	$tpl -> display("trigger_admin_tpl.htm");

	function implode_exp ($expression,$action){
		global $tpl,$con_dbo,$triggerid,$description,$priority,$comments,$url,$status;
		if($action=="edit" && trim($triggerid)!=""){
			$sql_del=array();
			$sql_del[]="delete functions where triggerid=".$triggerid;
			$con_dbo -> execsql($sql_del,true);
		}
//		echo "Expression:$expression<br>";
		$exp_arr=array();
		$exp='';
		$state="";
		for($i=0;$i<strlen($expression);$i++){
			if($expression[$i] == '{'){
				if($state=="")
				{
					$host='';
					$key='';
					$function='';
					$parameter='';
					$state='HOST';
					continue;
				}
			}
			if( ($expression[$i] == '}')&&($state=="") ){
				$state='';

				$sql_item="select i.key_,i.itemid,i.hostid from items i,hosts h where i.key_='".$key."' and h.host='".$host."' and h.hostid=i.hostid";
				$arr_item=$con_dbo -> ifexists($sql_item);
				//echo $sql_item;
				if($arr_item===false){
					$dreams_msg="表达式错误：没有该设备或没有此监控命令";
					$tpl -> assign("dreams_msg",$dreams_msg);
					$tpl -> display("error_tpl.htm");
					exit;
				}
				if($triggerid==""){
					$sql_trigger_add=array();
					$sql_trigger_add[]="insert into triggers (description,priority,comments,url,status) values ('".$description."',".$priority.",'".$comments."','".$url."',".$status.")";		
					$sql_trigger_add[] = " select LAST_INSERT_ID() as id  ";
					$re=$con_dbo -> execsql($sql_trigger_add,true);
					$triggerid=$re[0][0]["id"];
					
				}
				$exp_arr["triggerid"]=$triggerid;

				$sql_function_add=array();
				$sql_function_add[]="insert into functions (itemid,triggerid,function,parameter) values (".$arr_item["itemid"].",".$triggerid.",'".$function."','".$parameter."') ";
				$sql_function_add[] = " select LAST_INSERT_ID() as id  ";
				$re=$con_dbo -> execsql($sql_function_add,true);

				$functionid=$re[0][0]["id"];

				$exp=$exp.'{'.$functionid.'}';
	
				continue;
			}
			if($expression[$i] == '('){
				if($state == "FUNCTION")
				{
					$state='PARAMETER';
					continue;
				}
			}
			if($expression[$i] == ')'){
				if($state == "PARAMETER")
				{
					$state='';
					continue;
				}
			}
			if(($expression[$i] == ':') && ($state == "HOST")){
				$state="KEY";
				continue;
			}
			if($expression[$i] == '.'){
				if($state == "KEY")
				{
					$state="FUNCTION";
					continue;
				}
				// Support for '.' in KEY
				if($state == "FUNCTION")
				{
					$state="FUNCTION";
					$key=$key.".".$function;
					$function="";
					continue;
				}
			}
			if($state == "HOST"){
				$host=$host.$expression[$i];
				continue;
			}
			if($state == "KEY"){
				$key=$key.$expression[$i];
				continue;
			}
			if($state == "FUNCTION"){
				$function=$function.$expression[$i];
				continue;
			}
			if($state == "PARAMETER"){
				$parameter=$parameter.$expression[$i];
				continue;
			}
			$exp=$exp.$expression[$i];
		}
		//echo $exp;
		$exp_arr["host"]=$host;
		$exp_arr["key"]=$key;
		$exp_arr["function"]=$function;
		$exp_arr["parameter"]=$parameter;
		$exp_arr["exp"]=$exp;
		
		return $exp_arr;
	}
?>