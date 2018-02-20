<?php
	$cur_rights=10;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	if($action=="del"){
		$sysmapid="";
		if(isset($_GET["sysmapid"])){
			$sysmapid=$_GET["sysmapid"];
		}
		$sql_del=array();
		$sql_del[]="delete from sysmaps_links where sysmapid=".$sysmapid;
		$sql_del[]="delete from sysmaps_hosts where sysmapid=".$sysmapid;
		$sql_del[]="delete from sysmaps where sysmapid=".$sysmapid;
		$con_dbo -> execsql($sql_del,true);
		//print_r($sql_del);
		header('location:'.$referer_url);
	}
	if($action=="add" || $action=="edit"){
		$sysmapid="";
		if(isset($_POST["sysmapid"])){
			$sysmapid=$_POST["sysmapid"];
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
		$background="";
		if(isset($_POST["background"])){
			$background=$_POST["background"];
		}
		if($action=="add"){
			$sql_insert_sysmaps="insert into sysmaps (name,width,height,background) values ('$name',$width,$height,'$background')";
			$con_dbo -> execsql($sql_insert_sysmaps);
			//echo $sql_insert_sysmaps;
		}else{
			$sql_update_sysmaps="update sysmaps set name='$name',width=$width,height=$height,background='$background' where sysmapid=$sysmapid";
			$con_dbo -> execsql($sql_update_sysmaps);
			//echo $sql_update_sysmaps;
		}
		header('location:'.$referer_url);
	}

	//对照表列表
	$sql_sysmaps="select s.sysmapid,s.name,s.width,s.height,s.background from sysmaps s";
	$arr_sysmaps=$con_dbo -> sel_arr($sql_sysmaps);
	$tpl -> assign("arr_sysmaps",$arr_sysmaps);

	$tpl -> display("sysmap_admin_tpl.htm");
?>