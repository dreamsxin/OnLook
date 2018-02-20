<?php
	$cur_rights=14;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//对照表列表
	$sql_screens="select screenid,name,cols,rows from screens order by screenid";
	$arr_screens=$con_dbo -> sel_arr($sql_screens);
	$tpl -> assign("arr_screens",$arr_screens);

	$tpl -> display("screens_tpl.htm");
?>