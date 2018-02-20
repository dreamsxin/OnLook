<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_htmlspecialchars($string){
	return htmlspecialchars($string,ENT_QUOTES);
}
?>