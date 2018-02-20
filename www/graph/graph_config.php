<?php
	include('../../framework/conn.inc.php');
	$con_dbo = new dreams_dbo();
	session_start();
	$img_text="http://lizhen520.com";
	$font_path=dirname(__FILE__)."/simsun.ttc";
	function get_trigger_by_triggerid($triggerid){
		global $con_dbo;
		$sql="select * from triggers where triggerid=".$triggerid;
		$trigger=$con_dbo -> ifexists($sql);
		return	$trigger;
	}
	function get_item_by_itemid($itemid){
		global $con_dbo;
		$sql="select * from items where itemid=".$itemid;
		$item=$con_dbo -> ifexists($sql);
		return	$item;
	}
	function get_host_by_hostid($hostid){
		global $con_dbo;

		$sql="select hostid,host,useip,ip,port,status from hosts where hostid=".$hostid;
		$host=$con_dbo -> ifexists($sql);
		return	$host;
	}
	function getmicrotime(){
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	} 

	
	function DashedLine($image,$x1,$y1,$x2,$y2,$color){
		if(function_exists("imagesetstyle")){
			$style = array($color, $color, IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT);
			ImageSetStyle($image, $style);
			ImageLine($image,$x1,$y1,$x2,$y2,IMG_COLOR_STYLED);
		}else{
			ImageDashedLine($image,$x1,$y1,$x2,$y2,$color);
		}
	}
	function ImageOut($image){
//		ImageJPEG($image);
		ImagePNG($image);
	}
	function DBselect($sql){
		global $con_dbo;
		$result=$con_dbo -> sel_arr($sql);
		return	$result;
	}
	function DBfetch($arr){
		return $arr[0];
	}
	function DBnum_rows($arr){
		return count($arr);
	}
	function check_login(){
		if(isset($_SESSION["users"]) && is_array($_SESSION["users"])){
			return true;
		}else{
			return false;
		}
	}

	function expand_trigger_description_simple($triggerid){
		global $con_dbo;
		$sql="select distinct t.description,h.host from triggers t,functions f,items i,hosts h where t.triggerid=$triggerid and f.triggerid=t.triggerid and f.itemid=i.itemid and i.hostid=h.hostid";

		$row=$con_dbo -> ifexists($sql);

		$search=array("{HOSTNAME}");
		$replace=array($row["host"]);
		$description = str_replace("{HOSTNAME}", $row["host"],$row["description"]);

		return $description;
	}

	function expand_trigger_description($triggerid){
		$description=expand_trigger_description_simple($triggerid);
		$description=stripslashes(htmlspecialchars($description));

		return $description;
	}

	function get_image_by_name($imagetype,$name){
		global $con_dbo;
		$sql="select * from images where imagetype=$imagetype and name='$name'"; 
		$result=$con_dbo -> ifexists($sql);
		return $result;	
	}

	function calculate_availability($triggerid,$period_start,$period_end){
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
		return $ret;
	}
 
 	function convert_units($value,$units){
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
		
			return $ret;	
		}

		$u="";

		if($units==""){
			if(round($value)==$value){
				return sprintf("%.0f",$value);
			}else{
				return sprintf("%.2f",$value);
			}
		}

		$abs=abs($value);

		if($abs<1024){
			$u="";
		}else if($abs<1024*1024){
			$u="K";
			$value=$value/1024;
		}else if($abs<1024*1024*1024){
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
