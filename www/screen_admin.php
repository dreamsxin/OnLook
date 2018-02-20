<?php
	$cur_rights=9;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	//删除
	if($action=="del"){
		$screenid="";
		if(isset($_GET["screenid"])){
			$screenid=$_GET["screenid"];
		}
		$sql_del=array();
		$sql_del[]="delete from screens_items where screenid=".$screenid;
		$sql_del[]="delete from screens where screenid=".$screenid;
		$con_dbo -> execsql($sql_del,true);
		header('location:'.$referer_url);
	}
	if($action=="edit" || $action=="add"){
		$screenid="";
		if(isset($_POST["screenid"])){
			$screenid=$_POST["screenid"];
		}
		$name="";
		if(isset($_POST["name"])){
			$name=$_POST["name"];
		}
		$cols="";
		if(isset($_POST["cols"])){
			$cols=$_POST["cols"];
		}
		$rows="";
		if(isset($_POST["rows"])){
			$rows=$_POST["rows"];
		}
		
		if($action=="edit"){
			$sql_screen_edit="update screens set name='".$name."',cols=".$cols.",rows=".$rows." where screenid=".$screenid;
			$con_dbo -> execsql($sql_screen_edit);
		}else{
			$sql_screen_add="insert into screens (name,cols,rows) values ('".$name."',".$cols.",".$rows.")";
			$con_dbo -> execsql($sql_screen_add);
		}
		header('location:'.$referer_url);
	}
	//对照表列表
	$sql_screens="select screenid,name,cols,rows from screens order by screenid";
	$arr_screens=$con_dbo -> sel_arr($sql_screens);
	$tpl -> assign("arr_screens",$arr_screens);

	$tpl -> display("screen_admin_tpl.htm");
?>