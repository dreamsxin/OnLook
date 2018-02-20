<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_explode_exp($expression, $html=0){
	global $con_dbo;
	$functionids=function_id($expression);

	foreach($functionids as $k => $functionid){
		$sql="select h.host,i.key_,f.function,f.parameter,i.itemid,i.value_type from items i,functions f,hosts h where f.functionid=".$functionid." and i.itemid=f.itemid and h.hostid=i.hostid";
		$res1=$con_dbo -> ifexists($sql);
		if($html == 0){
			$exp="{".$res1["host"].":".$res1["key_"].".".$res1["function"]."(".$res1["parameter"].")}";
		}else{
			if($res1["value_type"] ==0){
				$exp="{<A HREF=\"history.php?action=showhistory&itemid=".$res1["itemid"]."\">".$res1["host"].":".$res1["key_"]."</A>.<B>".$res1["function"]."(</B>".$res1["parameter"]."<B>)</B>}";
			}else{
				$exp="{<A HREF=\"history.php?action=showvalues&period=3600&itemid=".$res1["itemid"]."\">".$res1["host"].":".$res1["key_"]."</A>.<B>".$res1["function"]."(</B>".$res1["parameter"]."<B>)</B>}";
			}
		}
		//echo $sql."<br>\n";
		$expression=str_replace("{".$functionid."}",$exp,$expression);
	}
	return $expression;
}
function function_id ($expression){
		global $con_dbo;
		$function_id_arr=array();
		$function_id='';
		$state="";
		$exp='';
		$k=0;
		for($i=0;$i<strlen($expression);$i++){
			if($expression[$i] == '{'){
				if($state==""){
					$function_id='';
					$state='FUNCTION_ID';
					continue;
				}
			}
			if( ($expression[$i] == '}')){
				$state='';
				$function_id_arr[$k]=$function_id;
				//$exp=$exp.'function_'.$k;
				$k++;
				//$exp=$exp.$expression[$i];
				continue;
			}
			if($state == "FUNCTION_ID"){
				$function_id=$function_id.$expression[$i];
				continue;
			}
			//$exp=$exp.$expression[$i];
		}
		//print_r($function_id_arr);
		return $function_id_arr;
	}
?>