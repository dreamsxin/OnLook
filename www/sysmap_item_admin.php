<?php
	$cur_rights=1001;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	$sysmapid="";
	if(isset($_GET["sysmapid"])){
		$sysmapid=$_GET["sysmapid"];
	}
	$tpl -> assign("sysmapid",$sysmapid);
	
	//删除 设备
	if($action=="shost_del"){
		$shostid="";
		if(isset($_GET["shostid"])){
			$shostid=$_GET["shostid"];
		}
		$sql_del=array();
		$sql_del[]="delete from sysmaps_links where shostid1=$shostid or shostid2=$shostid";
		$sql_del[]="delete from sysmaps_hosts where shostid=$shostid";
		$con_dbo -> execsql($sql_del,true);
		header('location:'.$referer_url);
	}
	//添加修改 设备
	if($action=="shost_add" || $action=="shost_edit"){
		$shostid="";
		if(isset($_POST["shostid"])){
			$shostid=$_POST["shostid"];
		}
		$hostid="";
		if(isset($_POST["hostid"])){
			$hostid=$_POST["hostid"];
		}
		$icon="";
		if(isset($_POST["icon"])){
			$icon=$_POST["icon"];
		}
		$icon_on="";
		if(isset($_POST["icon_on"])){
			$icon_on=$_POST["icon_on"];
		}
		$label="";
		if(isset($_POST["label"])){
			$label=$_POST["label"];
		}
		$x="";
		if(isset($_POST["x"])){
			$x=$_POST["x"];
		}
		$y="";
		if(isset($_POST["y"])){
			$y=$_POST["y"];
		}
		$url="";
		if(isset($_POST["url"])){
			$url=$_POST["url"];
		}
		if($action=="shost_add"){
			/*
			$sql="select * from sysmaps_hosts where sysmapid=$sysmapid and hostid=$hostid";
			$re=$con_dbo -> ifexists($sql);
			if($re===false){	
			}else{
				$dreams_msg="已经添加该设备!";
			}
			*/
			$sql_host_add="insert into sysmaps_hosts (sysmapid,hostid,icon,icon_on,label,x,y,url) values ($sysmapid,$hostid,'$icon','$icon_on','$label',$x,$y,'$url')";
			$con_dbo -> execsql($sql_host_add);
			

		}else{
			$sql_host_update="update sysmaps_hosts set hostid=$hostid,icon='$icon',icon_on='$icon_on',label='$label',x=$x,y=$y,url='$url' where sysmapid=$sysmapid and shostid=$shostid";
			$con_dbo -> execsql($sql_host_update);
		}
		header('location:'.$referer_url);
	}
	
	//删除 连接线
	if($action=="link_del"){
		$linkid="";
		if(isset($_GET["linkid"])){
			$linkid=$_GET["linkid"];
		}
		$sql_del="delete from sysmaps_links where linkid=$linkid";
		//echo $sql_del;
		$con_dbo -> execsql($sql_del);
		header('location:'.$referer_url);
	}
	//添加修改 连接线
	if($action=="link_add" || $action=="link_edit"){
		$linkid="";
		if(isset($_POST["linkid"])){
			$linkid=$_POST["linkid"];
		}
		$shostid1="";
		if(isset($_POST["shostid1"])){
			$shostid1=$_POST["shostid1"];
		}
		$shostid2="";
		if(isset($_POST["shostid2"])){
			$shostid2=$_POST["shostid2"];
		}
		$triggerid="";
		if(isset($_POST["triggerid"])){
			$triggerid=$_POST["triggerid"];
		}
		$drawtype_off="";
		if(isset($_POST["drawtype_off"])){
			$drawtype_off=$_POST["drawtype_off"];
		}
		$color_off="";
		if(isset($_POST["color_off"])){
			$color_off=$_POST["color_off"];
		}
		$drawtype_on="";
		if(isset($_POST["drawtype_on"])){
			$drawtype_on=$_POST["drawtype_on"];
		}
		$color_on="";
		if(isset($_POST["color_on"])){
			$color_on=$_POST["color_on"];
		}
		if($action=="link_add"){
			$sql="select * from sysmaps_links where shostid1=$shostid1 and shostid2=$shostid2 ";
			$arr=$con_dbo -> sel_arr($sql);
			if(count($arr)<1){			
				$sql_link_add="insert into sysmaps_links (sysmapid,shostid1,shostid2,";
				if(trim($triggerid)!=""){
					$sql_link_add.="triggerid,";
				}
				$sql_link_add.="drawtype_off,color_off,drawtype_on,color_on) values ($sysmapid,$shostid1,$shostid2,";
				if(trim($triggerid)!=""){
					$sql_link_add.="$triggerid,";
				}
				$sql_link_add.="$drawtype_off,'$color_off',$drawtype_on,'$color_on')";
				$con_dbo -> execsql($sql_link_add);
			}
		}else{
			$sql_link_update="update sysmaps_links set shostid1=$shostid1,shostid2=$shostid2,";
			if(trim($triggerid)!=""){
				$sql_link_update.="triggerid=$triggerid,";
			}else{
				$sql_link_update.="triggerid=null,";
			}
			$sql_link_update.="drawtype_off=$drawtype_off,color_off='$color_off',drawtype_on=$drawtype_on,color_on='$color_on' where sysmapid=$sysmapid and linkid=$linkid";
			$con_dbo -> execsql($sql_link_update);
		}
	
		header('location:'.$referer_url);
	}

	//图
	$tpl -> assign("map_img",map_img($sysmapid));

	//图 设备列表
	$arr_host=$con_dbo -> sel_arr("select h.host,sh.shostid,sh.sysmapid,sh.hostid,sh.label,sh.x,sh.y,sh.icon from sysmaps_hosts sh,hosts h where sh.sysmapid=".$sysmapid." and h.hostid=sh.hostid order by h.host");
	$tpl -> assign("arr_host",$arr_host);

	//设备列表
	$sql_hosts="select h.host,h.port,h.hostid,h.status as host_status from hosts h where h.hostid and h.status<>3 and h.hostid order by h.host";
	$arr_hosts=$con_dbo -> sel_arr($sql_hosts);
	$tpl -> assign("arr_hosts",$arr_hosts);

	//图 连接线
	$sql_links=" select a.*,(select label from sysmaps_hosts where shostid=a.shostid1) as label1,(select label from sysmaps_hosts where shostid=a.shostid2) as label2,t.description from sysmaps_links a left join triggers t on (a.triggerid=t.triggerid) where a.sysmapid=".$sysmapid." ";
	$arr_links=$con_dbo -> sel_arr($sql_links);
	$tpl -> assign("arr_links",$arr_links);

	//连接对应触发条件
	$sql_triggers=" select h.hostid,h.host,h.status as host_status,t.*,i.description as item_name from triggers t,hosts h,items i,functions f where f.itemid=i.itemid and h.hostid=i.hostid and t.triggerid=f.triggerid and h.status in (0,2) ";
	$sql_triggers.=" order by h.host,t.expression,t.triggerid";
	$arr_triggers=$con_dbo -> sel_arr($sql_triggers);
	$tpl -> assign("arr_triggers",$arr_triggers);

	$tpl -> display("sysmap_item_admin_tpl.htm");

	function map_img($sysmapid){
		global $con_dbo;		
		$result=$con_dbo -> sel_arr("select h.host,sh.shostid,sh.sysmapid,sh.hostid,sh.label,sh.x,sh.y,sh.icon,sh.icon_on,sh.url,h.status from sysmaps_hosts sh,hosts h where sh.sysmapid=".$sysmapid." and h.hostid=sh.hostid");

		$map="\n<map name=links>";
		for($i=0;$i<count($result);$i++){
			$host_=$result[$i]["host"];
			$shostid_=$result[$i]["shostid"];
			$sysmapid_=$result[$i]["sysmapid"];
			$name=$result[$i]["sysmapid"];
			$hostid_=$result[$i]["hostid"];
			$label_=$result[$i]["label"];
			$x_=$result[$i]["x"];
			$y_=$result[$i]["y"];
			$status_=$result[$i]["status"];

			if(function_exists("imagecreatetruecolor")&&@imagecreatetruecolor(1,1)){
				$map=$map."\n<area shape=rect coords=$x_,$y_,".($x_+48).",".($y_+48)." href=\"javascript:shost_edit('".$shostid_."','".$hostid_."','".$label_."','".$x_."','".$y_."','".$result[$i]["icon"]."','".$result[$i]["icon_on"]."','".$result[$i]["url"]."','".$sysmapid_."')\" alt=\"标签:$label_<br>设备名:$host_\">";
			}else{
				$map=$map."\n<area shape=rect coords=$x_,$y_,".($x_+32).",".($y_+32)." href=\"javascript:shost_edit('".$shostid_."','".$hostid_."','".$label_."','".$x_."','".$y_."','".$result[$i]["icon"]."','".$result[$i]["icon_on"]."','".$result[$i]["url"]."','".$sysmapid_."')\" alt=\"标签:$label_<br>设备名:$host_\">";
			}
		}
		$map.="\n</map>";
		$map.="<IMG SRC=\"graph/graph_show.php?graph_type=map&sysmapid=".$_GET["sysmapid"]."\" border=0 usemap=#links>";
		return $map;
	}
?>