<?php
/***************************************************************************
 *   copyright            :(C) 2005 by DREAMS	������                     *
 ***************************************************************************/
	require_once ('template/class.template.php');
	$tpl = new template;	
	$rootx =dirname(dirname(__FILE__));	
	$tpl->compile_dir = $rootx."/cache";	//�����ļ�Ŀ¼
	$tpl->template_dir = $rootx."/www";		//html�ļ�Ŀ¼
?>