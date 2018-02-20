<?php
	$cur_rights=801;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);


	$graphid="";
	if(isset($_GET["graphid"])){
		$graphid=$_GET["graphid"];
	}
	$tpl -> assign("graphid",$graphid);

	//删除
	if($action=="del"){
		$sql_del="delete from graphs_items where gitemid=".$_GET["gitemid"]." and graphid=".$graphid." ";
		$con_dbo -> execsql($sql_del);
		header('location:'.$referer_url);
	}

	//添加修改 显示数据
	if($action=="add" || $action=="edit"){
		$gitemid="";
		if(isset($_POST["gitemid"])){
			$gitemid=$_POST["gitemid"];
		}
		$itemid="";
		if(isset($_POST["itemid"])){
			$itemid=$_POST["itemid"];
		}
		$drawtype="";
		if(isset($_POST["drawtype"])){
			$drawtype=$_POST["drawtype"];
		}
		$color="";
		if(isset($_POST["color"])){
			$color=$_POST["color"];
		}
		$sortorder="";
		if(isset($_POST["sortorder"])){
			$sortorder=$_POST["sortorder"];
		}


		if($action=="add"){
			$sql_insert="insert into graphs_items (graphid,itemid,drawtype,color,sortorder) value (".$graphid.",".$itemid.",".$drawtype.",'".$color."',".$sortorder.") ";
			$con_dbo -> execsql($sql_insert);
		}else{
			$sql_update="update graphs_items set itemid=".$itemid.",drawtype=".$drawtype.",color='".$color."',sortorder=".$sortorder." where gitemid=".$gitemid." and graphid=".$graphid." ";
			$con_dbo -> execsql($sql_update);
		}
		header('location:'.$referer_url);
	}

	//显示数据源
	$sql_graph_item="select g.gitemid,g.graphid,g.itemid,g.drawtype,g.sortorder,g.color,i.description from graphs_items g,items i where g.itemid=i.itemid and graphid=".$graphid." order by g.sortorder ";
	$arr_graph_items=$con_dbo -> sel_arr($sql_graph_item);
	$tpl -> assign("arr_graph_items",$arr_graph_items);

	//数据源列表
	$sql_items="select h.host,h.port,h.hostid,h.status as host_status,i.* from hosts h,items i where h.hostid=i.hostid and h.status in (0,2) ";
	$sql_items.="order by h.host,i.key_,i.description";
	$arr_items=$con_dbo -> sel_arr($sql_items);
	$tpl -> assign("arr_items",$arr_items);

	$tpl -> display("graph_item_admin_tpl.htm");
?>