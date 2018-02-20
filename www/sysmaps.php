<?php
	$cur_rights=15;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//网络图表
	$sql_sysmaps="select s.sysmapid,s.name,s.width,s.height,s.background from sysmaps s";
	$arr_sysmaps=$con_dbo -> sel_arr($sql_sysmaps);
	$tpl -> assign("arr_sysmaps",$arr_sysmaps);

	$tpl -> display("sysmaps_tpl.htm");
?>