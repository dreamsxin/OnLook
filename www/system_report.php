<?php
	$cur_rights=1;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$page_type="system";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	$tpl -> assign("page_type",$page_type);

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

		//N/A
	$sql_items_count="select count(*) as items_count from items i,hosts h where i.status=2 and i.hostid=h.hostid and h.status in (0,1)";
	$arr_items_count=$con_dbo -> ifexists($sql_items_count);
	$items_count_trapper=$arr_items_count["items_count"];
	$tpl -> assign("items_count_trapper",$items_count_trapper);
		
		//总数
	$items_count=$items_count_active + $items_count_not_active + $items_count_not_supported + $items_count_trapper;
	$tpl -> assign("items_count",$items_count);

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

		//总数
	$hosts_count=$hosts_count_monitored + $hosts_count_not_monitored ;//$hosts_count_template
	$tpl -> assign("hosts_count",$hosts_count);	

//性能 

	//设备列表
	$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid) where a.status in (0,1) order by a.status,a.hostid";
	$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
	$tpl -> assign("arr_hosts",$arr_hosts);

	$host_info=array();
	foreach($arr_hosts as $host_key => $host_v){
		$hostid=$host_v["hostid"];
		$page_type=strtolower($host_v["group_name"]);
		$host_info[$host_key]["page_type"]=$page_type;
		//设备信息-------------------
		$host_name="";
		if($page_type=="host"){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.hostname'";
			$arr=$con_dbo -> ifexists($sql);
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$host_name="N/A";
			}else{
				$host_name=$arr["lastvalue"];
			}
		}elseif($page_type=="oracle"){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sid'";
			$arr=$con_dbo -> ifexists($sql);
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$host_name="N/A";
			}else{
				$host_name=$arr["lastvalue"];
			}
		}elseif($page_type=="cisco"){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysName'";
			$arr=$con_dbo -> ifexists($sql);
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$host_name="N/A";
			}else{
				$host_name=$arr["lastvalue"];
			}
		}
		//$tpl -> assign("host_name",$host_name);	//设备名 
		$host_info[$host_key]["host_id"]=$host_v["hostid"];
		$host_info[$host_key]["host_name"]=$host_name;
		$host_info[$host_key]["host_ip"]=$host_v["ip"];

		if($page_type=="cisco"){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysUpTime'";
			$arr=$con_dbo -> ifexists($sql);
			$check_time="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$check_time="N/A";
			}else{
				$check_time=m_date(round($arr["lastvalue"]/100));
			}
		}else{
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.uptime'";
			$arr=$con_dbo -> ifexists($sql);
			$check_time="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$check_time="N/A";
			}else{
				$check_time=$arr["lastvalue"];			
			}
		}
		
		$host_info[$host_key]["check_time"]=$check_time;

		//操作系统--------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.uname'";
		$arr=$con_dbo -> ifexists($sql);
		$host_system="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$host_system="N/A";
		}else{
			$host_system=$arr["lastvalue"];
		}
		//$tpl -> assign("host_system",$host_system);	//操作系统
		$host_info[$host_key]["host_system"]=$host_system;


		//CPU-------------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.cpu.user'";
		$arr=$con_dbo -> ifexists($sql);
		$user_use_cpu="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$user_use_cpu="N/A";
		}else{
			$user_use_cpu=$arr["lastvalue"];
		}
		$tpl -> assign("user_use_cpu",$user_use_cpu);	//用户CPU
		

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.cpu.sys'";
		$arr=$con_dbo -> ifexists($sql);
		$system_use_cpu="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$system_use_cpu="N/A";
		}else{
			$system_use_cpu=$arr["lastvalue"];
		}
		$tpl -> assign("system_use_cpu",$system_use_cpu);	//系统CPU

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.cpu.wait'";
		$arr=$con_dbo -> ifexists($sql);
		$wait_cpu="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$wait_cpu="N/A";
		}else{
			$wait_cpu=$arr["lastvalue"];
		}
		$tpl -> assign("wait_cpu",$wait_cpu);	//等待CPU
		if($user_use_cpu=="N/A"){
			$host_info[$host_key]["cpu_pre"]="N/A";//cpu使用率
		}else{
			$host_info[$host_key]["cpu_pre"]=$user_use_cpu + $host_system + $wait_cpu;//cpu使用率
		}

		//内存---------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.av'";
		$arr=$con_dbo -> ifexists($sql);
		$memory_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$memory_total="N/A";
		}else{
			$memory_total=$arr["lastvalue"]*1024;
		}
		//$tpl -> assign("memory_total",format_num($memory_total));	//内存总量


		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.free'";
		$arr=$con_dbo -> ifexists($sql);
		$free_memory="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_memory="N/A";
		}else{
			$free_memory=$arr["lastvalue"]*1024;
		}


		//$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.used'";
		//$arr=$con_dbo -> ifexists($sql);
		$use_memory="";
		if($memory_total=="N/A" || $free_memory=="N/A"){
			$use_memory="N/A";
		}else{
			$use_memory=$memory_total-$free_memory;
		}
		//$tpl -> assign("use_memory",format_num($use_memory));	//使用内存

		//$tpl -> assign("free_memory",format_num($free_memory));	//空闲内存
		//$tpl -> assign("use_memory_per",percent($use_memory,$memory_total));//内存使用率
		$host_info[$host_key]["use_memory_per"]=percent($use_memory,$memory_total);


		//交换空间-------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.av'";
		$arr=$con_dbo -> ifexists($sql);
		$swap_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$swap_total="N/A";
		}else{
			$swap_total=$arr["lastvalue"]*1024;
		}
		//$tpl -> assign("swap_total",format_num($swap_total));	//交换空间总量

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.used'";
		$arr=$con_dbo -> ifexists($sql);
		$use_swap="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$use_swap="N/A";
		}else{
			$use_swap=$arr["lastvalue"]*1024;
		}
		//$tpl -> assign("use_swap",format_num($use_swap));	//使用交换空间

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.free'";
		$arr=$con_dbo -> ifexists($sql);
		$free_swap="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_swap="N/A";
		}else{
			$free_swap=$arr["lastvalue"]*1024;
		}
		//$tpl -> assign("free_swap",format_num($free_swap));	//空闲交换空间

		//$tpl -> assign("use_swap_per",percent($use_swap,$swap_total));	//使用率
		$host_info[$host_key]["use_swap_per"]=percent($use_swap,$swap_total);



		//线程-------------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.pr.count'";
		$arr=$con_dbo -> ifexists($sql);
		$processes_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$processes_total="N/A";
		}else{
			$processes_total=$arr["lastvalue"];
		}
		//$tpl -> assign("processes_total",$processes_total);	//总数进程
		$host_info[$host_key]["processes_total"]=$processes_total;

		//网络-------------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.connect'";
		$arr=$con_dbo -> ifexists($sql);
		$network_link_num="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$network_link_num="N/A";
		}else{
			$network_link_num=$arr["lastvalue"];
		}
		//$tpl -> assign("network_link_num",$network_link_num);	//连接数
		$host_info[$host_key]["network_link_num"]=$network_link_num;

		//网络接口
		for($i=0;$i<2;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifname".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$newwork_name="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$newwork_name="";
			}else{
				$newwork_name=$arr["lastvalue"];
			}
			//$tpl -> assign("newwork_name",$newwork_name);	//名称1
			$host_info[$host_key]["newwork_name".$i]=$newwork_name;
			//echo $sql."\n";
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.okif".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$newwork_width="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$newwork_width="";
			}else{
				$newwork_width=$arr["lastvalue"];
			}
			//$tpl -> assign("newwork_width1",$newwork_width1);	//带宽1
			$host_info[$host_key]["newwork_width".$i]=$newwork_width;
			//echo $sql."\n";
		}
		
		//磁盘空间---------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.fs.size[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$disk_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$disk_total="N/A";
		}else{
			$disk_total=$arr["lastvalue"]*1024;
		}
		//$tpl -> assign("disk_total",format_num($disk_total));	//磁盘空间总量

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.fs.size[free]'";
		$arr=$con_dbo -> ifexists($sql);
		$free_disk="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_disk="N/A";
		}else{
			$free_disk=$arr["lastvalue"]*1024;
		}
		//$tpl -> assign("free_disk",format_num($free_disk));	//空闲磁盘空间

		//$tpl -> assign("use_disk_per",percent($disk_total - $free_disk,$disk_total));	//使用率
		$host_info[$host_key]["use_disk_per"]=percent($disk_total - $free_disk,$disk_total);


	//oralce

		//Performance
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.buffer'";
		$arr=$con_dbo -> ifexists($sql);
		$buffer_hit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$buffer_hit="N/A";
		}else{
			$buffer_hit=$arr["lastvalue"]."%";
		}
		//$tpl -> assign("buffer_hit",$buffer_hit);	//buffer_hit
		$host_info[$host_key]["buffer_hit"]=$buffer_hit;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.library'";
		$arr=$con_dbo -> ifexists($sql);
		$library_hit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$library_hit="N/A";
		}else{
			$library_hit=$arr["lastvalue"]."%";
		}
		//$tpl -> assign("library_hit",$library_hit);	//library_hit
		$host_info[$host_key]["library_hit"]=$library_hit;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.sql'";
		$arr=$con_dbo -> ifexists($sql);
		$execute_to_parse="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$execute_to_parse="N/A";
		}else{
			$execute_to_parse=$arr["lastvalue"]."%";
		}
		//$tpl -> assign("execute_to_parse",$execute_to_parse);	//execute_to_parse
		$host_info[$host_key]["execute_to_parse"]=$execute_to_parse;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.redo'";
		$arr=$con_dbo -> ifexists($sql);
		$redo_nowait="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$redo_nowait="N/A";
		}else{
			$redo_nowait=$arr["lastvalue"]."%";
		}
		//$tpl -> assign("redo_nowait",$redo_nowait);	//redo_nowait
		$host_info[$host_key]["redo_nowait"]=$redo_nowait;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.sort'";
		$arr=$con_dbo -> ifexists($sql);
		$in_memory_sort="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$in_memory_sort="N/A";
		}else{
			$in_memory_sort=$arr["lastvalue"]."%";
		}
		//$tpl -> assign("in_memory_sort",$in_memory_sort);	//in_memory_sort
		$host_info[$host_key]["in_memory_sort"]=$in_memory_sort;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.latch'";
		$arr=$con_dbo -> ifexists($sql);
		$latch_hit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$latch_hit="N/A";
		}else{
			$latch_hit=$arr["lastvalue"]."%";
		}
		//$tpl -> assign("latch_hit",$latch_hit);	//latch_hit
		$host_info[$host_key]["latch_hit"]=$latch_hit;

		//pga
		//$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.pga.size[total]'";
		//$arr=$con_dbo -> ifexists($sql);
		//$pga_total="";
		//if(!is_array($arr) || $arr["lastvalue"]===null){
		//$pga_total="N/A";
		//}else{
		//$pga_total=$arr["lastvalue"];
		//}
		//$tpl -> assign("pga_total",$pga_total);	//pga_total

		//$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.pga.size[used]'";
		//$arr=$con_dbo -> ifexists($sql);
		//$pga_used="";
		//if(!is_array($arr) || $arr["lastvalue"]===null){
		//	$pga_used="N/A";
		//}else{
		//	$pga_used=$arr["lastvalue"];
		//}
		//$tpl -> assign("pga_used",$pga_used);	//pga_used
		
		//$host_info[$host_key]["pga_pre"]=percent($pga_used,$pga_total);

		//sga
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.sp[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_total="N/A";
		}else{
			$sga_total=$arr["lastvalue"];
		}
		//$tpl -> assign("sga_total",$sga_total);	//sga_total

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.sp[free]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_free="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_free="N/A";
		}else{
			$sga_free=$arr["lastvalue"];
		}
		//$tpl -> assign("sga_free",$sga_free);	//sga_used
		$host_info[$host_key]["sga_pre"]=percent($sga_total-$sga_free,$sga_total);

		//cisco
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifNumber'";
		$arr=$con_dbo -> ifexists($sql);
		$ifNumber="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ifNumber="N/A";
		}else{
			$ifNumber=$arr["lastvalue"];
		}
		//$tpl -> assign("ifNumber",$ifNumber);//网络接口数
		$host_info[$host_key]["ifNumber"]=$ifNumber;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipForwDatagrams'";
		$arr=$con_dbo -> ifexists($sql);
		$ipForwarding="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipForwarding="N/A";
		}else{
			$ipForwarding=$arr["lastvalue"];
		}
		//$tpl -> assign("ipForwarding",$ipForwarding);//转发数据包数
		$host_info[$host_key]["ipForwarding"]=$ipForwarding;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipInReceives'";
		$arr=$con_dbo -> ifexists($sql);
		$ipInReceives="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipInReceives="N/A";
		}else{
			$ipInReceives=$arr["lastvalue"];
		}
		//$tpl -> assign("ipInReceives",$ipInReceives);//输入数据包数
		$host_info[$host_key]["ipInReceives"]=$ipInReceives;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipInDiscards'";
		$arr=$con_dbo -> ifexists($sql);
		$ipInDiscards="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipInDiscards="N/A";
		}else{
			$ipInDiscards=$arr["lastvalue"];
		}
		//$tpl -> assign("ipInDiscards",$ipInDiscards);//输入丢弃数
		$host_info[$host_key]["ipInDiscards"]=$ipInDiscards;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipOutRequests'";
		$arr=$con_dbo -> ifexists($sql);
		$ipOutRequests="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipOutRequests="N/A";
		}else{
			$ipOutRequests=$arr["lastvalue"];
		}
		//$tpl -> assign("ipOutRequests",$ipOutRequests);//输出数据包数
		$host_info[$host_key]["ipOutRequests"]=$ipOutRequests;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipOutDiscards'";
		$arr=$con_dbo -> ifexists($sql);
		$ipOutDiscards="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipOutDiscards="N/A";
		}else{
			$ipOutDiscards=$arr["lastvalue"];
		}
		//$tpl -> assign("ipOutDiscards",$ipOutDiscards);//输出丢弃数
		$host_info[$host_key]["ipOutDiscards"]=$ipOutDiscards;

	}
	//print_r($host_info);
	$tpl -> assign("host_info",$host_info);
	$tpl -> display("system_report_tpl.htm");

	function percent($part,$total){
		$part=trim($part);
		$total=trim($total);
		if(!is_numeric($part) || !is_numeric($total) || $total==0){
			return "N/A";
		}
		$per=number_format(($part/$total)*100,1,'.', '');
		return $per."%";
	}
	function format_num($num,$type=''){
		if(!is_numeric($num)){
			return $num;
		}
		if($num/(1024*1024*1024*1024) > 1){
			$num=round($num/(1024*1024*1024*1024),2)."T";
		}elseif($num/(1024*1024*1024)>1){
			$num=round($num/(1024*1024*1024),2)."G";
		}elseif($num/(1024*1024)>1){
			$num=round($num/(1024*1024),2)."M";
		}elseif($num/1024>1){
			$num=round($num/1024,2)."K";
		}else{
			$num.="B";
		}
		return $num;
	}
?>
