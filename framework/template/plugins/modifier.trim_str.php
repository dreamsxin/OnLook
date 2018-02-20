<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_trim_str($string, $restr=''){
	if(trim($string)==""){
		return $restr;
	}else{
		return $string;
	}
}
 
?>