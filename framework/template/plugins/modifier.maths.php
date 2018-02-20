<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_maths($num, $num2, $type){
	switch($type){
		case '-':
			return $num - $num2;
		case '+':
			return $num + $num2;
		case '*':
			return $num * $num2;
		case '/':
			return floor($num / $num2);
		case '%':
			return $num % $num2;
		default:
			return $num;
	}
}
 
?>