<?php
/**
 * code by dreamsxin
 *
 * Type:     modifier
 * Name:     htmltostr
 */
function tpl_modifier_htmltostr($string) {
	$string=str_replace('"', '&quot;', $string);
	$string=str_replace('\\', '\\\\', $string);
	$string=str_replace("\r\n", "\\n", $string);
	$string=str_replace("'", "\'", $string);

	
	return $string;
}
?>