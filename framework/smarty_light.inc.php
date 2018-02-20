<?php
/***************************************************************************
 *   copyright            :(C) 2005 by DREAMS	朱宗鑫                     *
 ***************************************************************************/
	require_once ('template/class.template.php');
	$tpl = new template;	
	$rootx =dirname(dirname(__FILE__));	
	$tpl->compile_dir = $rootx."/cache";	//缓存文件目录
	$tpl->template_dir = $rootx."/www";		//html文件目录
?>