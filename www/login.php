<?php
	require('../framework/smarty_light.inc.php');
	include('../framework/conn.inc.php');
	include('../framework/tools.php');

	session_start();
	$con_dbo = new dreams_dbo();
	$dreams_msg="";

	$action="";
	if(isset($_GET["action"])){
		$action=trim($_GET["action"]);
	}
	if($action=="login"){
		$alias="";
		if(isset($_POST["alias"])){
			$alias=trim($_POST["alias"]);
		}
		$passwd="";
		if(isset($_POST["passwd"])){
			$passwd=trim($_POST["passwd"]);
		}
		$sql_login="select userid,alias,name,surname,url from users where alias='".$alias."' and passwd='".md5($passwd)."' ";
		$arr_login=$con_dbo -> ifexists($sql_login);
		if ($arr_login === false) {
			$dreams_msg="用户名或密码错误,请重新输入!";
		}else{
			$_SESSION["users"]=$arr_login;
		}
	}

	if($action=="logout"){
		if(isset($_SESSION["users"])){
			unset($_SESSION["users"]);
		}
	}

	if(isset($_SESSION["users"]) && is_array($_SESSION["users"])){
		if(isset($_SESSION["users"]["url"])!=""){
			header("Location:".$_SESSION["users"]["url"]);
		}elseif(isset($_SESSION["referer_url"])!=""){
			header("Location:".$_SESSION["referer_url"]);
			//echo $_SESSION["referer_url"];
		}else{
			header("Location:index.php");
		}
		exit;
	}

	$tpl -> assign("msg",$dreams_msg);
	$tpl -> display("login_tpl.htm");
?>