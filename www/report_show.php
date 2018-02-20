<?php
	$cur_rights=1201;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$hostid="";
	if(isset($_GET["hostid"])){
		$hostid=$_GET["hostid"];
	}
	$tpl -> assign("hostid",$hostid);

	$triggerid="";
	if(isset($_GET["triggerid"])){
		$triggerid=$_GET["triggerid"];
	}
	$tpl -> assign("triggerid",$triggerid);
	
	$sql_report="select t.triggerid,t.expression,t.description,t.value from triggers t where t.triggerid=$triggerid ";
	$result=$con_dbo -> ifexists($sql_report);
	$tpl -> assign("description",$result["description"]);
	
	$img_url="graph/graph_show.php?graph_type=4&triggerid=".$triggerid;
	$tpl -> assign("img_url",$img_url);

	$tpl -> display("report_show_tpl.htm");
?>