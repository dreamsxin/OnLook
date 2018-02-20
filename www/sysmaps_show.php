<?php
	$cur_rights=1501;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	$sysmapid="";
	if(isset($_GET["sysmapid"])){
		$sysmapid=$_GET["sysmapid"];
	}
	$tpl -> assign("sysmapid",$sysmapid);

	//对照表列表
	$sql_sysmaps="select s.sysmapid,s.name,s.width,s.height,s.background from sysmaps s where sysmapid=$sysmapid";
	$arr_sysmaps=$con_dbo -> ifexists($sql_sysmaps);
	$tpl -> assign("arr_sysmaps",$arr_sysmaps);

	//图
	$tpl -> assign("map_img",map_img($sysmapid));

	$tpl -> display("sysmaps_show_tpl.htm");

	function map_img($sysmapid){
		global $con_dbo;		
		$result=$con_dbo -> sel_arr("select h.host,h.status,sh.shostid,sh.sysmapid,sh.hostid,sh.label,sh.x,sh.y,sh.url from sysmaps_hosts sh,hosts h where sh.sysmapid=".$sysmapid." and h.hostid=sh.hostid");

		$map="\n<map name=links>";
		for($i=0;$i<count($result);$i++){
			$host_=$result[$i]["host"];
			$status_=$result[$i]["status"];
			$shostid_=$result[$i]["shostid"];
			$sysmapid_=$result[$i]["sysmapid"];
			$hostid_=$result[$i]["hostid"];
			$label_=$result[$i]["label"];
			$x_=$result[$i]["x"];
			$y_=$result[$i]["y"];
			$url=$result[$i]["url"];
			

			if(function_exists("imagecreatetruecolor")&&@imagecreatetruecolor(1,1)){
				$map=$map."\n<area shape=rect coords=$x_,$y_,".($x_+48).",".($y_+48)." href=\"".$url."\" alt=\"标签:$label_<br>设备名:$host_\">";
			}else{
				$map=$map."\n<area shape=rect coords=$x_,$y_,".($x_+32).",".($y_+32)." href=\"".$url."\" alt=\"标签:$label_<br>设备名:$host_\">";
			}
		}
		$map.="\n</map>";
		$map.="<IMG SRC=\"graph/graph_show.php?graph_type=map&sysmapid=".$_GET["sysmapid"]."\" border=0 usemap=#links>";
		return $map;
	}
?>