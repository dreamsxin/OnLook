<?php
	$cur_rights=8;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	if($action=="del"){
		$graphid="";
		if(isset($_GET["graphid"])){
			$graphid=$_GET["graphid"];
		}
		$sql_graph_del=array();
		$sql_graph_del[]=" delete from graphs where graphid=".$graphid." ";
		$sql_graph_del[]=" delete from graphs_items where graphid=".$graphid." ";
		$con_dbo -> execsql($sql_graph_del);
		header('location:'.$referer_url);
	}
	if($action=="edit" || $action=="add"){
		$graphid="";
		if(isset($_POST["graphid"])){
			$graphid=$_POST["graphid"];
		}
		$name="";
		if(isset($_POST["name"])){
			$name=$_POST["name"];
		}
		$width="";
		if(isset($_POST["width"])){
			$width=$_POST["width"];
		}
		$height="";
		if(isset($_POST["height"])){
			$height=$_POST["height"];
		}
		$yaxistype="";
		if(isset($_POST["yaxistype"])){
			$yaxistype=$_POST["yaxistype"];
		}
		if($action=="edit"){
			$sql_graph_edit="update graphs set name='".$name."',width=".$width.",height=".$height.",yaxistype=".$yaxistype." where graphid=".$graphid;
			$con_dbo -> execsql($sql_graph_edit);
		}else{
			$sql_graph_add="insert into graphs (name,width,height,yaxistype) values ('".$name."',".$width.",".$height.",".$yaxistype.")";
			$con_dbo -> execsql($sql_graph_add);
		}
		header('location:'.$referer_url);
	}
	//图表列表
	$sql_graphs="select graphid,name,width,height,yaxistype,yaxismin,yaxismax from graphs";
	$arr_graphs=$con_dbo -> sel_arr($sql_graphs);
	$tpl -> assign("arr_graphs",$arr_graphs);

	$tpl -> display("graph_admin_tpl.htm");
?>