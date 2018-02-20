<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_availability($triggerid, $k, $type = '',$period_start,$period_end){
		global $con_dbo;
		if(($period_start==0)&&($period_end==0)){
	        	$sql="select count(*) as c_c,min(clock) as min_c,max(clock) as max_c from alarms where triggerid=$triggerid";
		}else{
	        	$sql="select count(*) as c_c,min(clock) as min_c,max(clock) as max_c from alarms where triggerid=$triggerid and clock>=$period_start and clock<=$period_end";
		}
		
	    $result=$con_dbo -> ifexists($sql);
		if($result["c_c"]>0){
			$min=$result["min_c"];
			$max=$result["max_c"];
		}else{
			if(($period_start==0)&&($period_end==0)){
				$max=time();
				$min=$max-24*3600;
			}else{
				$ret["true_time"]=0;
				$ret["false_time"]=0;
				$ret["unknown_time"]=0;
				$ret["true"]=0;
				$ret["false"]=0;
				$ret["unknown"]=100;
				return $ret;
			}
		}

		$sql="select clock,value from alarms where triggerid=$triggerid and clock>=$min and clock<=$max";
		$result=$con_dbo -> sel_arr($sql);

		$state=-1;
		$true_time=0;
		$false_time=0;
		$unknown_time=0;
		$time=$min;
		if(($period_start==0)&&($period_end==0)){
			$max=time();
		}
		for($i=0;$i<count($result);$i++){
			$clock=$result[$i]["clock"];
			$value=$result[$i]["value"];

			$diff=$clock-$time;

			$time=$clock;

			if($state==-1){
				$state=$value;
				if($state == 0){
					$false_time+=$diff;
				}
				if($state == 1){
					$true_time+=$diff;
				}
				if($state == 2){
					$unknown_time+=$diff;
				}
			}elseif($state==0){
				$false_time+=$diff;
				$state=$value;
			}elseif($state==1){
				$true_time+=$diff;
				$state=$value;
			}elseif($state==2){
				$unknown_time+=$diff;
				$state=$value;
			}
		}

		if(count($result)==0){
			$false_time=$max-$min;
		}else{
			if($state==0){
				$false_time=$false_time+$max-$time;
			}elseif($state==1){
				$true_time=$true_time+$max-$time;
			}elseif($state==3){
				$unknown_time=$unknown_time+$max-$time;
			}

		}

		$total_time=$true_time+$false_time+$unknown_time;
		if($total_time==0){
			$ret["true_time"]=0;
			$ret["false_time"]=0;
			$ret["unknown_time"]=0;
			$ret["true"]=0;
			$ret["false"]=0;
			$ret["unknown"]=100;
		}else{
			$ret["true_time"]=$true_time;
			$ret["false_time"]=$false_time;
			$ret["unknown_time"]=$unknown_time;
			$ret["true"]=(100*$true_time)/$total_time;
			$ret["false"]=(100*$false_time)/$total_time;
			$ret["unknown"]=(100*$unknown_time)/$total_time;
		}
		return sprintf($type,$ret[$k]);
}
 
?>