<?php
	$cur_rights=13;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//图表列表
	$sql_graphs="select graphid,name,width,height,yaxistype,yaxismin,yaxismax from graphs";
	$arr_graphs=$con_dbo -> sel_arr($sql_graphs);
	$tpl -> assign("arr_graphs",$arr_graphs);

	$tpl -> display("graphs_tpl.htm");
?>