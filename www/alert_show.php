<?php
	$cur_rights=1701;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$alertid="";
	if(isset($_GET["alertid"])){
		$alertid=$_GET["alertid"];
	}
	$tpl -> assign("alertid",$alertid);

	//提醒详细资料
	$sql_alert=" select a.alertid,a.clock,mt.description as media_name,a.sendto,a.subject,a.message,ac.triggerid,a.status,a.retries,ac.scope,a.error,(select name from users where userid=ac.userid) as username,(select name from usrgrp where usrgrpid=ac.userid) as groupname,t.triggerid,t.description as trigger_name from alerts a,actions ac,media_type mt,triggers t where a.actionid=ac.actionid and mt.mediatypeid=a.mediatypeid and t.triggerid=ac.triggerid and a.alertid=".$alertid;

	$arr_alert=$con_dbo -> ifexists($sql_alert);
	$tpl -> assign("arr_alert",$arr_alert);

	$tpl -> display("alert_show_tpl.htm");
?>