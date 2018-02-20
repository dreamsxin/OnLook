<?php
session_start();
	include('../framework/conn.inc.php');
	include('../framework/tools.php');
	require('../framework/smarty_light.inc.php');

	$con_dbo = new dreams_dbo();
	$dreams_msg="";
	$action="";
	if(http_get("action")!=""){
		$action=http_get("action");
	}
	$tpl -> assign("action",$action);

	//检查登陆
	if(isset($_SESSION["users"]["alias"]) && is_array($_SESSION["users"])){
		$tpl -> assign("users",$_SESSION["users"]);
	}else{
		$tpl -> display("login_tpl.htm");
		exit;
	}
	//print_r($_SESSION["users"]);
	//检查权限
	$arr_rights=array();
	$arr_rights[1]="系统状态";//1
	$arr_rights[]="系统设置";//2
	$arr_rights[]="用户管理";//3
	$arr_rights[]="个人设置";//4

	$arr_rights[]="设备配置";//5
	$arr_rights[]="数据源配置";//6
	$arr_rights[]="告警配置";//7
	$arr_rights[]="图表配置";//8
	$arr_rights[]="对照表配置";//9
	$arr_rights[]="网络图表配置";//10

	$arr_rights[]="监控数据浏览";//11
	$arr_rights[]="可用性报告";//12
	$arr_rights[]="图表浏览";//13
	$arr_rights[]="对照表浏览";//14
	$arr_rights[]="网络图表浏览";//15
	$arr_rights[]="告警列表";//16
	$arr_rights[]="提醒列表";//17

	$arr_rights[]="系统日志";

	$user_sql="select u.*,g.rights from users u,users_groups ug,usrgrp g where ug.userid=u.userid and ug.usrgrpid=g.usrgrpid and u.alias='".$_SESSION["users"]["alias"]."' ";
	$arr_user=$con_dbo -> ifexists($user_sql);
	if ($arr_user === false) {
		$dreams_msg="账户不存在,请重新登陆!";
		$referer_url=$_SESSION["referer_url"];
		$tpl -> assign("referer_url",$referer_url);
		$tpl -> assign("dreams_msg",$dreams_msg);
		$tpl -> display("error_tpl.htm");
		exit;
	}
	$user_rights=explode(",",$arr_user["rights"]);

	$tpl -> assign("arr_rights",$arr_rights);
	$tpl -> assign("user_rights",$user_rights);

	if(isset($cur_rights) && trim($cur_rights)!=""){
		if($cur_rights>100){
			$cur_right=substr($cur_rights,0,strlen($cur_rights)-2);
		}else{
			$cur_right=$cur_rights;
		}
		$tpl -> assign("cur_right",$cur_right);

		if(isset($user_rights[$cur_right]) && $user_rights[$cur_right]!=1){
			if(!isset($user_rights[$pre_rights]) || ($user_rights[$pre_rights]!=1)){
				$dreams_msg="您没有该项功能的管理权限！";
				$referer_url="index.php";
				$tpl -> assign("referer_url",$referer_url);
				$tpl -> assign("dreams_msg",$dreams_msg);
				$tpl -> display("error_tpl.htm");
				exit;
			}
		}
	}

	//站点信息
	$web_name='OnLook';
	$web_url='http://www.utstar.com';
	$web_email='admin@utstar.com';
	$web_ver="ver 1.0";
	$web_copyright="杭州纬地";

	$tpl -> assign("web_name",$web_name);
	$tpl -> assign("web_url",$web_url);
	$tpl -> assign("web_email",$web_email);
	$tpl -> assign("web_ver",$web_ver);
	$tpl -> assign("web_copyright",$web_copyright);
	
	//图片客户端缓存
	$tpl -> assign("nowtime","?".time());


	//返回上一页
	if(trim($action)==""){
		$referer_url="";
		$referer_url=$_SERVER['HTTP_HOST'];//HTTP_HOST SERVER_NAME
		if($_SERVER['SERVER_PORT']!=80){
			$referer_url.=":".$_SERVER['SERVER_PORT'];
		}
		$referer_url.=$_SERVER['REQUEST_URI'];
		$_SESSION["referer_url"]="http://".$referer_url;
		
	}
	$referer_url=$_SESSION["referer_url"];
	//echo $referer_url;
	$tpl -> assign("referer_url",$referer_url);
?>