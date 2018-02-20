<?php
	$cur_rights=101;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//设备列表
	$sql_hosts="select a.hostid,a.host,a.ip,a.port,a.status,a.error,a.available,a.disable_until,d.group_name,d.groupid from hosts a left join (select b.groupid,b.hostid,c.name as group_name from hosts_groups b,groups c where b.groupid=c.groupid) d on (a.hostid=d.hostid) where a.status in (0,1) order by a.status,a.hostid";
	$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
	$tpl -> assign("arr_hosts",$arr_hosts);

	$hostid="";
	if(http_get("hostid")!=""){
		$hostid=http_get("hostid");
	}
	$ip="";

	$page_type="unix";
	if(isset($_GET["page_type"])){
		$page_type=trim($_GET["page_type"]);
	}
	foreach($arr_hosts as $v){
		if(trim($hostid)==""){
			$hostid=$v["hostid"];
			$ip=$v["ip"];
			$page_type=strtolower($v["group_name"]);
			break;
		}elseif($hostid==$v["hostid"]){
			$ip=$v["ip"];
			$page_type=strtolower($v["group_name"]);
			break;
		}
	}
	
	$tpl -> assign("hostid",$hostid);	//设备id
	$tpl -> assign("host_ip",$ip);		//设备IP

	$tpl -> assign("page_type",$page_type);
  
	if($page_type=="unix"){
		//设备信息-------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.hostname'";
		$arr=$con_dbo -> ifexists($sql);
		$host_name="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$host_name="N/A";
		}else{
			$host_name=$arr["lastvalue"];
		}
		$tpl -> assign("host_name",$host_name);	//设备名 


		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.uptime'";
		$arr=$con_dbo -> ifexists($sql);
		$check_time="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$check_time="N/A";
		}else{
			$check_time=$arr["lastvalue"];
		}
		$tpl -> assign("check_time",$check_time);	//检测时间


		//操作系统--------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.uname'";
		$arr=$con_dbo -> ifexists($sql);
		$host_system="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$host_system="N/A";
		}else{
			$host_system=$arr["lastvalue"];
		}
		$tpl -> assign("host_system",$host_system);	//操作系统
		
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
		$tpl -> assign("user_use_cpu_img",img_creat($user_use_cpu,100));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.cpu.sys'";
		$arr=$con_dbo -> ifexists($sql);
		$system_use_cpu="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$system_use_cpu="N/A";
		}else{
			$system_use_cpu=$arr["lastvalue"];
		}
		$tpl -> assign("system_use_cpu",$system_use_cpu);	//系统CPU
		$tpl -> assign("system_use_cpu_img",img_creat($system_use_cpu,100));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.cpu.wait'";
		$arr=$con_dbo -> ifexists($sql);
		$wait_cpu="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$wait_cpu="N/A";
		}else{
			$wait_cpu=$arr["lastvalue"];
		}
		$tpl -> assign("wait_cpu",$wait_cpu);	//等待CPU
		$tpl -> assign("wait_cpu_img",img_creat($wait_cpu,100));	//使用率img

		//内存---------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.av'";
		$arr=$con_dbo -> ifexists($sql);
		$memory_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$memory_total="N/A";
		}else{
			$memory_total=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("memory_total",format_num($memory_total));	//内存总量
		$tpl -> assign("memory_total_img",img_creat($memory_total,$memory_total));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.used'";
		$arr=$con_dbo -> ifexists($sql);
		$use_memory="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$use_memory="N/A";
		}else{
			$use_memory=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("use_memory",format_num($use_memory));	//使用内存

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.free'";
		$arr=$con_dbo -> ifexists($sql);
		$free_memory="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_memory="N/A";
		}else{
			$free_memory=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("free_memory",format_num($free_memory));	//空闲内存
		$tpl -> assign("free_memory_img",img_creat($free_memory,$memory_total));	//空闲img
		$tpl -> assign("use_memory_per",percent($use_memory,$memory_total));//内存使用率
		$tpl -> assign("use_memory_img",img_creat($use_memory,$memory_total));//内存使用率img

		//交换空间-------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.av'";
		$arr=$con_dbo -> ifexists($sql);
		$swap_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$swap_total="N/A";
		}else{
			$swap_total=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("swap_total",format_num($swap_total));	//交换空间总量
		$tpl -> assign("swap_total_img",img_creat($swap_total,$swap_total));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.used'";
		$arr=$con_dbo -> ifexists($sql);
		$use_swap="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$use_swap="N/A";
		}else{
			$use_swap=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("use_swap",format_num($use_swap));	//使用交换空间

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.free'";
		$arr=$con_dbo -> ifexists($sql);
		$free_swap="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_swap="N/A";
		}else{
			$free_swap=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("free_swap",format_num($free_swap));	//空闲交换空间
		$tpl -> assign("free_swap_img",img_creat($free_swap,$swap_total));	//空闲img
		$tpl -> assign("use_swap_per",percent($use_swap,$swap_total));	//使用率
		$tpl -> assign("use_swap_img",img_creat($use_swap,$swap_total));	//使用率img

		////虚拟内存---------------未完成
		//$tpl -> assign("virtual_memory_total",format_num($virtual_memory_total));	//总量
		//$tpl -> assign("free_virtual_memory",format_num($free_virtual_memory));	//空闲
		//$tpl -> assign("use_virtual_memory_pre",percent($virtual_memory_total - $free_virtual_memory, $virtual_memory_total));	//使用率


		//网络-------------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.connect'";
		$arr=$con_dbo -> ifexists($sql);
		$network_link_num="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$network_link_num="N/A";
		}else{
			$network_link_num=$arr["lastvalue"];
		}
		$tpl -> assign("network_link_num",$network_link_num);	//连接数

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.timewait'";
		$arr=$con_dbo -> ifexists($sql);
		$newwork_wait_num="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$newwork_wait_num="N/A";
		}else{
			$newwork_wait_num=$arr["lastvalue"];
		}
		$tpl -> assign("newwork_wait_num",$newwork_wait_num);	//等待数

		//网络接口
		$newwork=array();
		for($i=0;$i<2;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifname".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$newwork_name="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$newwork_name="N/A";
			}else{
				$newwork_name=$arr["lastvalue"];
			}
			//$tpl -> assign("newwork_name",$newwork_name);	//名称1
			$newwork[$i]["newwork_name"]=$newwork_name;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.okif".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$newwork_width="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$newwork_width="N/A";
			}else{
				$newwork_width=$arr["lastvalue"];
			}
			//$tpl -> assign("newwork_width1",$newwork_width1);	//带宽1
			$newwork[$i]["newwork_width"]=$newwork_width;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.errif".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$newwork_error_num="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$newwork_error_num="N/A";
			}else{
				$newwork_error_num=$arr["lastvalue"]."pkgs/min";
			}
			//$tpl -> assign("newwork_error_num1",$newwork_error_num1);	//错误包1
			$newwork[$i]["newwork_error_num"]=$newwork_error_num;
			if($newwork_error_num=="N/A"){
				break;
			}

		}
		$tpl -> assign("newwork",$newwork);

		//线程-------------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.pr.count'";
		$arr=$con_dbo -> ifexists($sql);
		$processes_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$processes_total="N/A";
		}else{
			$processes_total=$arr["lastvalue"];
		}
		$tpl -> assign("processes_total",$processes_total);	//总数进程
		$tpl -> assign("processes_total_img",img_creat($processes_total,$processes_total));	

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.pr.sleep'";
		$arr=$con_dbo -> ifexists($sql);
		$free_processes="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_processes="N/A";
		}else{
			$free_processes=$arr["lastvalue"];
		}
		$tpl -> assign("free_processes",$free_processes);	//空闲进程
		$tpl -> assign("free_processes_img",img_creat($free_processes,$processes_total));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.pr.zombie'";
		$arr=$con_dbo -> ifexists($sql);
		$die_processes="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$die_processes="N/A";
		}else{
			$die_processes=$arr["lastvalue"];
		}
		$tpl -> assign("die_processes",$die_processes);	//僵死进程
		$tpl -> assign("die_processes_img",img_creat($die_processes,$processes_total));	//使用率img

		//磁盘空间---------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.fs.size[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$disk_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$disk_total="N/A";
		}else{
			$disk_total=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("disk_total",format_num($disk_total));	//磁盘空间总量
		$tpl -> assign("disk_total_img",img_creat($disk_total,$disk_total));

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.fs.size[free]'";
		$arr=$con_dbo -> ifexists($sql);
		$free_disk="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$free_disk="N/A";
		}else{
			$free_disk=$arr["lastvalue"]*1024;
		}
		$tpl -> assign("free_disk",format_num($free_disk));	//空闲磁盘空间
		$tpl -> assign("free_disk_img",img_creat($free_disk,$disk_total));	//空闲img

		$tpl -> assign("use_disk_per",percent($disk_total - $free_disk,$disk_total));	//使用率
		$tpl -> assign("use_disk_img",img_creat($disk_total - $free_disk,$disk_total));	//使用率img
	}
	
	if($page_type=="windows"){
		//设备信息-------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysName'";
		$arr=$con_dbo -> ifexists($sql);
		$host_name="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$host_name="N/A";
		}else{
			$host_name=$arr["lastvalue"];
		}
		$tpl -> assign("host_name",$host_name);	//设备名 


		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysUpTime'";
		$arr=$con_dbo -> ifexists($sql);
		$check_time="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$check_time="N/A";
		}else{
			$check_time=$arr["lastvalue"];
		}
		$tpl -> assign("check_time",$check_time);	//检测时间


		//操作系统--------------未完成
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysDescr'";
		$arr=$con_dbo -> ifexists($sql);
		$host_system="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$host_system="N/A";
		}else{
			$host_system=$arr["lastvalue"];
		}
		$tpl -> assign("host_system",$host_system);	//操作系统
		
		

		//内存---------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.unit'";
		$arr=$con_dbo -> ifexists($sql);
		$memory_unit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$memory_unit="N/A";
		}else{
			$memory_unit=$arr["lastvalue"];
		}
		
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.size'";
		$arr=$con_dbo -> ifexists($sql);
		$memory_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$memory_total="N/A";
		}else{
			$memory_total=$memory_unit*$arr["lastvalue"];
		}
		$tpl -> assign("memory_total",format_num($memory_total));	//内存总量
		$tpl -> assign("memory_total_img",img_creat($memory_total,$memory_total));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.mem.used'";
		$arr=$con_dbo -> ifexists($sql);
		$use_memory="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$use_memory="N/A";
		}else{
			$use_memory=$memory_unit*$arr["lastvalue"];
		}
		$tpl -> assign("use_memory",format_num($use_memory));	//使用内存

		
		$free_memory="";
		if($use_memory=="N/A" || $memory_total=="N/A"){
			$free_memory="N/A";
		}else{
		  $free_memory=$memory_total-$use_memory;
		}
		
		$tpl -> assign("free_memory",format_num($free_memory));	//空闲内存
		$tpl -> assign("free_memory_img",img_creat($free_memory,$memory_total));	//空闲img
		$tpl -> assign("use_memory_per",percent($use_memory,$memory_total));//内存使用率
		$tpl -> assign("use_memory_img",img_creat($use_memory,$memory_total));//内存使用率img

		//交换空间-------------------
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.unit'";
		$arr=$con_dbo -> ifexists($sql);
		$swap_unit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$swap_unit="N/A";
		}else{
			$swap_unit=$arr["lastvalue"];
		}
		
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.size'";
		$arr=$con_dbo -> ifexists($sql);
		$swap_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$swap_total="N/A";
		}else{
			$swap_total=$swap_unit*$arr["lastvalue"];
		}
		$tpl -> assign("swap_total",format_num($swap_total));	//交换空间总量
		$tpl -> assign("swap_total_img",img_creat($swap_total,$swap_total));	//使用率img

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.swap.used'";
		$arr=$con_dbo -> ifexists($sql);
		$use_swap="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$use_swap="N/A";
		}else{
			$use_swap=$swap_unit*$arr["lastvalue"];
		}
		$tpl -> assign("use_swap",format_num($use_swap));	//使用交换空间

		
		$free_swap="";
		if($swap_total=="N/A" || $use_swap=="N/A" ){
			$free_swap="N/A";
		}else{
			$free_swap=$swap_total-$use_swap;
		}
		$tpl -> assign("free_swap",format_num($free_swap));	//空闲交换空间
		$tpl -> assign("free_swap_img",img_creat($free_swap,$swap_total));	//空闲img
		$tpl -> assign("use_swap_per",percent($use_swap,$swap_total));	//使用率
		$tpl -> assign("use_swap_img",img_creat($use_swap,$swap_total));	//使用率img

		////虚拟内存---------------未完成
		//$tpl -> assign("virtual_memory_total",format_num($virtual_memory_total));	//总量
		//$tpl -> assign("free_virtual_memory",format_num($free_virtual_memory));	//空闲
		//$tpl -> assign("use_virtual_memory_pre",percent($virtual_memory_total - $free_virtual_memory, $virtual_memory_total));	//使用率


		//网络
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.connect'";
		$arr=$con_dbo -> ifexists($sql);
		$network_link_num="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$network_link_num="N/A";
		}else{
			$network_link_num=$arr["lastvalue"];
		}
		$tpl -> assign("network_link_num",$network_link_num);	//连接数
		
		$network=array();
		$sql="select count(*) from items i where i.hostid = $hostid and i.status = 0 and i.key_ like 'system.net.ifname%'";
		$count=$con_dbo -> ifexists($sql);
		for($i=1;$i<=$count;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system_net_ifname".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifDescr="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifDescr="N/A";
			}else{
				$ifDescr=$arr["lastvalue"];
			}
			$network[$i]["ifDescr"]=$ifDescr;	//名称

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifspeed".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifSpeed="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifSpeed="N/A";
			}else{
				$ifSpeed=$arr["lastvalue"];
			}
			$network[$i]["ifSpeed"]=$ifSpeed;	//速度

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifinoctets".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifInOctets="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifInOctets="N/A";
			}else{
				$ifInOctets=$arr["lastvalue"];
			}
			$network[$i]["ifInOctets"]=$ifInOctets;	//输入字节数

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifoutoctets".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifOutOctets="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifOutOctets="N/A";
			}else{
				$ifOutOctets=$arr["lastvalue"];
			}
			$network[$i]["ifOutOctets"]=$ifOutOctets;	//输出字节数

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifphysaddress".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifPhysAddress="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifPhysAddress="N/A";
			}else{
				$ifPhysAddress=$arr["lastvalue"];
			}
			$network[$i]["ifPhysAddress"]=$ifPhysAddress;	//物理地址
			
			
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.net.ifipaddress".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifIpAddress="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifPhysAddress="N/A";
			}else{
				$ifPhysAddress=$arr["lastvalue"];
			}
			$network[$i]["ifIpAddress"]=$ifIpAddress;	//IP地址
			if($ifIndex=="N/A"){
				break;
			}
		}

		$tpl -> assign("network",$network);//网络
		
		
		//文件系统
		$disk=array();
		$sql="select count(*) from items i where i.hostid = $hostid and i.status = 0 and i.key_ like 'system.disk.volname%'";
		$count=$con_dbo -> ifexists($sql);
		for($i=0;$i<=$count;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.disk.volname".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$volname="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$volname="N/A";
			}else{
				$volname=$arr["lastvalue"];//卷名
			}
			$disk[$i]["volname"]=$volname;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.disk.volunit".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$volunit="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$volunit="N/A";
			}else{
				$volunit=format_num($arr["lastvalue"]);
			}
			$disk[$i]["volunit"]=$volunit;//卷单元
			
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.disk.volused".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$volused="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$volused="N/A";
			}else{
				$volused=format_num($arr["lastvalue"]);
			}
			$disk[$i]["volused"]=$volused;//已使用卷大小

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'system.disk.volsize".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$volsize="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$volsize="N/A";
			}else{
				$volsize=format_num($arr["lastvalue"]);
			}
			$disk[$i]["volsize"]=$volsize;//总卷大小
			if($volname=="N/A"){
				break;
			}

		}
		$tpl -> assign("disk",$disk);

	}
	
	if($page_type=="oracle"){
	//oralce
		//sga
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.size[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_size="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_size="N/A";
		}else{
			$sga_size=$arr["lastvalue"];
		}
		$tpl -> assign("sga_size",$sga_size);
		echo $sga_size;

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.sp[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_sp="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_sp="N/A";
		}else{
			$sga_sp=$arr["lastvalue"];
		}
		$tpl -> assign("sga_sp",$sga_sp);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.jp[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_jp="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_jp="N/A";
		}else{
			$sga_jp=$arr["lastvalue"];
		}
		$tpl -> assign("sga_jp",$sga_jp);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.lp[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_lp="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_lp="N/A";
		}else{
			$sga_lp=$arr["lastvalue"];
		}
		$tpl -> assign("sga_lp",$sga_lp);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.db[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_db="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_db="N/A";
		}else{
			$sga_db=$arr["lastvalue"];
		}
		$tpl -> assign("sga_db",$sga_db);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.lb[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_lb="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_lb="N/A";
		}else{
			$sga_lb=$arr["lastvalue"];
		}
		$tpl -> assign("sga_lb",$sga_lb);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sga.sp[free]'";
		$arr=$con_dbo -> ifexists($sql);
		$sga_spfree="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sga_spfree="N/A";
		}else{
			$sga_spfree=$arr["lastvalue"];
		}
		$tpl -> assign("sga_spfree",$sga_spfree);

		//pga
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.pga.size[total]'";
		$arr=$con_dbo -> ifexists($sql);
		$pga_size_total="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$pga_size_total="N/A";
		}else{
			$pga_size_total=$arr["lastvalue"];
		}
		$tpl -> assign("pga_size_total",$pga_size_total);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.pga.size[used]'";
		$arr=$con_dbo -> ifexists($sql);
		$pga_size_used="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$pga_size_used="N/A";
		}else{
			$pga_size_used=$arr["lastvalue"];
		}
		$tpl -> assign("pga_size_used",$pga_size_used);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.pga.size[sort]'";
		$arr=$con_dbo -> ifexists($sql);
		$pga_size_sort="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$pga_size_sort="N/A";
		}else{
			$pga_size_sort=$arr["lastvalue"];
		}
		$tpl -> assign("pga_size_sort",$pga_size_sort);

		//Server Processes
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.servproc.dedicated'";
		$arr=$con_dbo -> ifexists($sql);
		$servproc_dedicated="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$servproc_dedicated="N/A";
		}else{
			$servproc_dedicated=$arr["lastvalue"];
		}
		$tpl -> assign("servproc_dedicated",$servproc_dedicated);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.servproc.shared'";
		$arr=$con_dbo -> ifexists($sql);
		$servproc_shared="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$servproc_shared="N/A";
		}else{
			$servproc_shared=$arr["lastvalue"];
		}
		$tpl -> assign("servproc_shared",$servproc_shared);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.servproc.dispatchers'";
		$arr=$con_dbo -> ifexists($sql);
		$servproc_dispatchers="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$servproc_dispatchers="N/A";
		}else{
			$servproc_dispatchers=$arr["lastvalue"];
		}
		$tpl -> assign("servproc_dispatchers",$servproc_dispatchers);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.servproc.pq'";
		$arr=$con_dbo -> ifexists($sql);
		$servproc_pq="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$servproc_pq="N/A";
		}else{
			$servproc_pq=$arr["lastvalue"];
		}
		$tpl -> assign("servproc_pq",$servproc_pq);

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.servproc.jobs'";
		$arr=$con_dbo -> ifexists($sql);
		$servproc_jobs="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$servproc_jobs="N/A";
		}else{
			$servproc_jobs=$arr["lastvalue"];
		}
		$tpl -> assign("servproc_jobs",$servproc_jobs);


		//session
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sess.system'";
		$arr=$con_dbo -> ifexists($sql);
		$total_system_sessions="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$total_system_sessions="N/A";
		}else{
			$total_system_sessions=$arr["lastvalue"];
		}
		$tpl -> assign("total_system_sessions",$total_system_sessions);	//系统会话

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sess.user'";
		$arr=$con_dbo -> ifexists($sql);
		$inaction_users_sessions="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$inaction_users_sessions="N/A";
		}else{
			$inaction_users_sessions=$arr["lastvalue"];
		}
		$tpl -> assign("inaction_users_sessions",$inaction_users_sessions);	//用户会话

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.sess.active'";
		$arr=$con_dbo -> ifexists($sql);
		$action_users_sessions="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$action_users_sessions="N/A";
		}else{
			$action_users_sessions=$arr["lastvalue"];
		}
		$tpl -> assign("action_users_sessions",$action_users_sessions);	//用户会话
		for($i=1;$i<4;$i++){
		//Log table
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.log.group[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$log_group="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$log_group="N/A";
			}else{
				$log_group=$arr["lastvalue"];
			}
			$tpl -> assign("log_group".$i,$log_group);	//group_id

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.log.member[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$log_path="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$log_path="N/A";
			}else{
				$log_path=$arr["lastvalue"];
			}
			$tpl -> assign("log_path".$i,$log_path);	//path

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.log.status[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$log_status="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$log_status="N/A";
			}else{
				$log_status=$arr["lastvalue"];
			}
			$tpl -> assign("log_status".$i,$log_status);	//log_status

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.log.size[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$log_size="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$log_size="N/A";
			}else{
				$log_size=$arr["lastvalue"];
			}
			$tpl -> assign("log_size".$i,$log_size);	//log_size

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.log.archived[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$log_archived="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$log_archived="N/A";
			}else{
				$log_archived=$arr["lastvalue"];
			}
			$tpl -> assign("log_archived".$i,$log_archived);	//log_archived
		}

		//Performance
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.buffer'";
		$arr=$con_dbo -> ifexists($sql);
		$buffer_hit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$buffer_hit="N/A";
		}else{
			$buffer_hit=$arr["lastvalue"];
		}
		$tpl -> assign("buffer_hit",$buffer_hit);	//buffer_hit

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.library'";
		$arr=$con_dbo -> ifexists($sql);
		$library_hit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$library_hit="N/A";
		}else{
			$library_hit=$arr["lastvalue"];
		}
		$tpl -> assign("library_hit",$library_hit);	//library_hit

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.sql'";
		$arr=$con_dbo -> ifexists($sql);
		$execute_to_parse="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$execute_to_parse="N/A";
		}else{
			$execute_to_parse=$arr["lastvalue"];
		}
		$tpl -> assign("execute_to_parse",$execute_to_parse);	//execute_to_parse

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.redo'";
		$arr=$con_dbo -> ifexists($sql);
		$redo_nowait="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$redo_nowait="N/A";
		}else{
			$redo_nowait=$arr["lastvalue"];
		}
		$tpl -> assign("redo_nowait",$redo_nowait);	//redo_nowait

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.sort'";
		$arr=$con_dbo -> ifexists($sql);
		$in_memory_sort="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$in_memory_sort="N/A";
		}else{
			$in_memory_sort=$arr["lastvalue"];
		}
		$tpl -> assign("in_memory_sort",$in_memory_sort);	//in_memory_sort

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.perf.latch'";
		$arr=$con_dbo -> ifexists($sql);
		$latch_hit="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$latch_hit="N/A";
		}else{
			$latch_hit=$arr["lastvalue"];
		}
		$tpl -> assign("latch_hit",$latch_hit);	//latch_hit
		for($i=1;$i<4;$i++){
			//control table
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.cf.status[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$cf_status="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$cf_status="N/A";
			}else{
				$cf_status=$arr["lastvalue"];
			}
			$tpl -> assign("cf_status".$i,$cf_status);	//状态

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.cf.name[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$cf_name="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$cf_name="N/A";
			}else{
				$cf_name=$arr["lastvalue"];
			}
			$tpl -> assign("cf_name".$i,$cf_name);	//名称
		}

		//Tablespace table
		$tbs_tables=array();
		for($i=0;$i<10;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.name[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_name="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_name="N/A";
			}else{
				$tbs_name=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_name".$i,$tbs_name);
			$tbs_tables[$i]["tbs_name"]=$tbs_name;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.path[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_path="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_path="N/A";
			}else{
				$tbs_path=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_path".$i,$tbs_path);
			$tbs_tables[$i]["tbs_path"]=$tbs_path;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.size[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_size="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_size="N/A";
			}else{
				$tbs_size=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_size".$i,$tbs_size);
			$tbs_tables[$i]["tbs_size"]=$tbs_size;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.used[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_used="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_used="N/A";
			}else{
				$tbs_used=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_used".$i,$tbs_used);
			$tbs_tables[$i]["tbs_used"]=$tbs_used;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.type[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_type="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_type="N/A";
			}else{
				$tbs_type=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_type".$i,$tbs_type);
			$tbs_tables[$i]["tbs_type"]=$tbs_type;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.status[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_status="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_status="N/A";
			}else{
				$tbs_status=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_status".$i,$tbs_status);
			$tbs_tables[$i]["tbs_status"]=$tbs_status;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.extman[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_extman="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_extman="N/A";
			}else{
				$tbs_extman=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_extman".$i,$tbs_extman);
			$tbs_tables[$i]["tbs_extman"]=$tbs_extman;

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'oracle.tbs.segman[".$i."]'";
			$arr=$con_dbo -> ifexists($sql);
			$tbs_segman="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$tbs_segman="N/A";
			}else{
				$tbs_segman=$arr["lastvalue"];
			}
			//$tpl -> assign("tbs_segman".$i,$tbs_segman);
			$tbs_tables[$i]["tbs_segman"]=$tbs_segman;
			if($tbs_segman=="N/A"){
				break;
			}
		}
		$tpl -> assign("tbs_tables",$tbs_tables);
	}
	
	//cisco
	if($page_type=="cisco"){
		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysName'";
		$arr=$con_dbo -> ifexists($sql);
		$sysName="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sysName="N/A";
		}else{
			$sysName=$arr["lastvalue"];
		}
		$tpl -> assign("sysName",$sysName);//设备名

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysDescr'";
		$arr=$con_dbo -> ifexists($sql);
		$sysDescr="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sysDescr="N/A";
		}else{
			$sysDescr=$arr["lastvalue"];
		}
		$tpl -> assign("sysDescr",$sysDescr);//系统描述

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'sysUpTime'";
		$arr=$con_dbo -> ifexists($sql);
		$sysUpTime="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$sysUpTime="N/A";
		}else{
			$sysUpTime=$arr["lastvalue"];
		}
		$tpl -> assign("sysUpTime",date("H:i:s",$sysUpTime));//系统运行时间

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifNumber'";
		$arr=$con_dbo -> ifexists($sql);
		$ifNumber="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ifNumber="N/A";
		}else{
			$ifNumber=$arr["lastvalue"];
		}
		$tpl -> assign("ifNumber",$ifNumber);//网络接口数

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipForwDatagrams'";
		$arr=$con_dbo -> ifexists($sql);
		$ipForwarding="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipForwarding="N/A";
		}else{
			$ipForwarding=$arr["lastvalue"];
		}
		$tpl -> assign("ipForwarding",$ipForwarding);//转发数据包数

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipInReceives'";
		$arr=$con_dbo -> ifexists($sql);
		$ipInReceives="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipInReceives="N/A";
		}else{
			$ipInReceives=$arr["lastvalue"];
		}
		$tpl -> assign("ipInReceives",$ipInReceives);//输入数据包数

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipInDiscards'";
		$arr=$con_dbo -> ifexists($sql);
		$ipInDiscards="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipInDiscards="N/A";
		}else{
			$ipInDiscards=$arr["lastvalue"];
		}
		$tpl -> assign("ipInDiscards",$ipInDiscards);//输入丢弃数

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipOutRequests'";
		$arr=$con_dbo -> ifexists($sql);
		$ipOutRequests="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipOutRequests="N/A";
		}else{
			$ipOutRequests=$arr["lastvalue"];
		}
		$tpl -> assign("ipOutRequests",$ipOutRequests);//输出数据包数

		$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipOutDiscards'";
		$arr=$con_dbo -> ifexists($sql);
		$ipOutDiscards="";
		if(!is_array($arr) || $arr["lastvalue"]===null){
			$ipOutDiscards="N/A";
		}else{
			$ipOutDiscards=$arr["lastvalue"];
		}
		$tpl -> assign("ipOutDiscards",$ipOutDiscards);//输出丢弃数

		//接口
		$ip_arr=array();
		for($i=1;$i<26;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifIndex".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifIndex="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifIndex="N/A";
			}else{
				$ifIndex=$arr["lastvalue"];
			}
			$ip_arr[$i]["ifIndex"]=$ifIndex;	//序号
			
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifDescr".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifDescr="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifDescr="N/A";
			}else{
				$ifDescr=iconv('utf-8','ISO-8859-1',$arr["lastvalue"]);
			}
			$ip_arr[$i]["ifDescr"]=$ifDescr;	//名称

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifSpeed".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifSpeed="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifSpeed="N/A";
			}else{
				$ifSpeed=$arr["lastvalue"];
			}
			$ip_arr[$i]["ifSpeed"]=$ifSpeed;	//速度

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifInOctets".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifInOctets="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifInOctets="N/A";
			}else{
				$ifInOctets=$arr["lastvalue"];
			}
			$ip_arr[$i]["ifInOctets"]=$ifInOctets;	//输入字节数

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifOutOctets".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifOutOctets="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifOutOctets="N/A";
			}else{
				$ifOutOctets=$arr["lastvalue"];
			}
			$ip_arr[$i]["ifOutOctets"]=$ifOutOctets;	//输出字节数

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ifPhysAddress".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ifPhysAddress="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ifPhysAddress="N/A";
			}else{
				$ifPhysAddress=$arr["lastvalue"];
			}
			$ip_arr[$i]["ifPhysAddress"]=$ifPhysAddress;	//物理地址
			if($ifIndex=="N/A"){
				break;
			}
		}

		$tpl -> assign("ip_arr",$ip_arr);//接口列表

		//路由列表
		$iproute_arr=array();
		for($i=1;$i<21;$i++){
			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipRouteDest".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ipRouteDest="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ipRouteDest="N/A";
			}else{
				$ipRouteDest=$arr["lastvalue"];
			}
			$iproute_arr[$i]["ipRouteDest"]=$ipRouteDest;	//目的网络

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipRouteMask".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ipRouteMask="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ipRouteMask="N/A";
			}else{
				$ipRouteMask=$arr["lastvalue"];
			}
			$iproute_arr[$i]["ipRouteMask"]=$ipRouteMask;	//网络掩码

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipRouteNextHop".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ipRouteNextHop="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ipRouteNextHop="N/A";
			}else{
				$ipRouteNextHop=$arr["lastvalue"];
			}
			$iproute_arr[$i]["ipRouteNextHop"]=$ipRouteNextHop;	//下一跳地址

			$sql="select i.hostid,i.lastvalue,i.key_,i.lastclock from items i where i.hostid = $hostid and i.key_ = 'ipRouteType".$i."'";
			$arr=$con_dbo -> ifexists($sql);
			$ipRouteType="";
			if(!is_array($arr) || $arr["lastvalue"]===null){
				$ipRouteType="N/A";
			}else{
				$ipRouteType=$arr["lastvalue"];
			}
			$iproute_arr[$i]["ipRouteType"]=$ipRouteType;	//路由类型
			if($ipRouteType=="N/A"){
				break;
			}
		}
		$tpl -> assign("iproute_arr",$iproute_arr);//路由列表
	}
	$tpl -> display("host_performance_tpl.htm");
	function img_creat($part=0,$total=0,$type=''){
		if($type=='all'){
			$img_str='<img src="img/line_blue.gif" width="100" height="8" border="0">';
			return $img_str;
		}
		if(!is_numeric($part)){
			$part=0;
		}
		if(!is_numeric($total) || $total==0){
			$total=100;
		}
		$total_width=100;
		$img_with=($part/$total)*$total_width;
		if($img_with<1){
			$part_width=ceil($img_with);
		}else{
			$part_width=floor($img_with);
		}
		$img_str='<img src="img/line_blue.gif" width="'.$part_width.'" height="8" border="0"><img src="img/line_hui.gif" width="'.($total_width-$part_width).'" height="8">';
		return $img_str;
	}
	function percent($part,$total){
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
