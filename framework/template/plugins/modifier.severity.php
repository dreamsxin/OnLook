<?php
/**
 * Smarty-Light replace modifier plugin
 *
 * Type:     modifier
 * Name:     replace
 * Purpose:  Wrapper for the PHP 'str_replace' function
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 */
function tpl_modifier_severity($string,$str_split=' ',$type=0) {
	$str="";
	if($type==0){
		for($i=0;$i<strlen($string);$i++){
			$severity=substr($string,$i,1);
			switch($severity){
				case "0":
					$str.="未分类".$str_split;
					break;
				case "1":
					$str.="普通信息".$str_split;
					break;
				case "2":
					$str.="警告".$str_split;
					break;
				case "3":
					$str.="一般告警".$str_split;
					break;
				case "4":
					$str.='<span class="font_yellow">'."严重告警".$str_split.'</span>';
					break;
				case "5":
					$str.='<span class="font_yellow">'."重大告警".$str_split.'</span>';
					break;
				default:
					break;
			}
		}
	}else{
		switch($string){
			case "0":
				$str='未分类';
				break;
			case "1":
				$str='普通信息';
				break;
			case "2":
				$str='警告';
				break;
			case "3":
				$str='一般';
				break;
			case "4":
				$str='<span class="font_yellow">高</span>';
				break;
			case "5":
				$str='<span class="font_red">灾难性的</span>';
				break;
			default:
				$str="未分类";
				break;
		}
	}
	return $str;
}
?>