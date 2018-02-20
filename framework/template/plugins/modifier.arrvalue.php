<?php
function tpl_modifier_arrvalue($value,$arr1,$arr2){
	if(array_search($value,$arr1)===false){
		return "";
	}
	return $arr2[array_search($value,$arr1)];
}
?>