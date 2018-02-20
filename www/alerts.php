<?php
	$cur_rights=17;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	//提醒列表
	$sql_alerts=" select a.alertid,a.clock,mt.description,a.sendto,a.subject,a.message,ac.triggerid,a.status,a.retries,ac.scope,a.error from alerts a,actions ac,media_type mt where a.actionid=ac.actionid and mt.mediatypeid=a.mediatypeid order by a.clock desc";
	if (!isset($_GET["dreams_curpage"])){
		$dreams_curpage=1;
	}else{
		$dreams_curpage=$_GET["dreams_curpage"];
	}
	$page_count=100;//100每页
	$arr_alerts=$con_dbo -> sel_arr_page($sql_alerts,$dreams_curpage,$page_count);
	if($con_dbo ->lastpage<=0){
		$dreams_curpage=1;
	}
	$tpl->assign("dreams_curpage",$dreams_curpage);
	$tpl->assign("page_count",$page_count);
	$tpl->assign("pagination",pagination($dreams_curpage,$con_dbo -> totalcount,$page_count,10,"dreams_page"));
	$tpl->assign("alerts_count",$con_dbo -> totalcount);
	$tpl->assign("arr_alerts_count",count($arr_alerts));
	$tpl -> assign("arr_alerts",$arr_alerts);

	$tpl -> display("alerts_tpl.htm");
?>