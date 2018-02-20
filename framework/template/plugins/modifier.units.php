<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_units($lastvalue,$units){
	$value=$lastvalue;

	if(trim($value)=="" && $value===0){
		return '-';
	}
	if(!is_numeric($value)){
		return $value;
	}
	if($units=="s"){
		$ret="";

		$t=floor($value/(365*24*3600));
		if($t>0){
			$ret=$t."y";
			$value=$value-$t*(365*24*3600);
		}
		$t=floor($value/(30*24*3600));
		if($t>0){
			$ret=$ret.$t."m";
			$value=$value-$t*(30*24*3600);
		}
		$t=floor($value/(24*3600));
		if($t>0){
			$ret=$ret.$t."d";
			$value=$value-$t*(24*3600);
		}
		$t=floor($value/(3600));
		if($t>0){
			$ret=$ret.$t."h";
			$value=$value-$t*(3600);
		}
		$t=floor($value/(60));
		if($t>0){
			$ret=$ret.$t."m";
			$value=$value-$t*(60);
		}
		$ret=$ret.$value."s";
		if($value<0){
			return $ret;
		}else{
			return "+".$ret;
		}
	}

	$u="";

	if($units==""){
		$str='';
		if(round($value)==$value){
			$str=sprintf("%.0f",$value);
		}else{
			$str=sprintf("%.2f",$value);
		}

		return $str;
	}

	$abs=abs($value);

	if($abs<1024){
		$u="";
	}elseif($abs<1024*1024){
		$u="K";
		$value=$value/1024;
	}elseif($abs<1024*1024*1024){
		$u="M";
		$value=$value/(1024*1024);
	}else{
		$u="G";
		$value=$value/(1024*1024*1024);
	}

	if(round($value)==$value){
		$s=sprintf("%.0f",$value);
	}else{
		$s=sprintf("%.2f",$value);
	}


	return "$s $u$units";
}
 
?>