<?php
	include('graph_config.php');
	include "classes.inc.php";



	$graph_type="";
	if(isset($_GET["graph_type"])){
		$graph_type=$_GET["graph_type"];
	}

	if($graph_type=="1" || $graph_type=="0"){
		$graph=new Graph();
		$now=time(null);
		$period=3600;	
		if(isset($_GET["period"])){
			$period=$_GET["period"];
		}
		$graph->setPeriod($_GET["period"]);

		$from=$now-$period;
		if(isset($_GET["from"])){
			$from=$_GET["from"];
		}
		$graph->set_from_time($from);

		$till=$now;
		if(isset($_GET["till"])){
			$till=$_GET["till"];
		}
		$graph->set_to_time($till);

		$stime="";
		if(isset($_GET["stime"])){
			$stime=$_GET["stime"];
		}
		$graph->setSTime($stime);
		
		$border=0;
		if(isset($_GET["border"])){
			$border=$_GET["border"];
		}
		$graph->setBorder($border);
		
		$graph->set_img_text($img_text);

		$graph->set_font_path($font_path);

		////////图表
		if($graph_type=="1"){
			$sql_graph="select name,width,height,yaxistype,yaxismin,yaxismax from graphs where graphid=".$_GET["graphid"];
			$arr_graph=$con_dbo -> ifexists($sql_graph);
			$name=$arr_graph["name"];
			if(isset($_GET["width"])&&$_GET["width"]>0){
				$width=$_GET["width"];
			}else{
				$width=$arr_graph["width"];
			}
			if(isset($_GET["height"])&&$_GET["height"]>0){
				$height=$_GET["height"];
			}else{
				$height=$arr_graph["height"];
			}
			$graph->setWidth($width);
			$graph->setHeight($height);
			$graph->setHeader($name);
			$graph->setYAxisType($arr_graph["name"]);
			$graph->setYAxisMin($arr_graph["yaxismin"]);
			$graph->setYAxisMax($arr_graph["yaxismax"]);

			$sql="select gi.itemid,i.description,gi.color,h.host,gi.drawtype from graphs_items gi,items i,hosts h where gi.itemid=i.itemid and gi.graphid=".$_GET["graphid"]." and i.hostid=h.hostid order by gi.sortorder";

			$arr=$con_dbo -> sel_arr($sql);

			foreach($arr as $k => $v){
				$graph->addItem($v["itemid"]);
				$graph->setColor($v["itemid"],$v["color"]);
				$graph->setDrawtype($v["itemid"],$v["drawtype"]);
			}
			$graph->Draw();
		}

		////////////数据源
		if($graph_type=="0"){
			if(isset($_GET["width"]) && $_GET["width"]>0){
				$graph->setWidth($_GET["width"]);
			}else{
				$graph->setWidth(520);
			}
			if(isset($_GET["height"]) && $_GET["height"]>0){
				$graph->setHeight($_GET["height"]);
			}else{
				$graph->setHeight(220);
			}
			$graph->addItem($_GET["itemid"]);

			$graph->Draw();
		}
	}
	
	////可用性报告
	if($graph_type=="4"){
		$start_time=time(NULL);

		$triggerid="";
		if(isset($_GET["triggerid"])){
			$triggerid=$_GET["triggerid"];
		}		

		if(!isset($_GET["type"])){
			$_GET["type"]="week";
		}

		if($_GET["type"] == "month"){
			$period=30*24*3600;
		}elseif($_GET["type"] == "week"){
			$period=7*24*3600;
		}else if($_GET["type"] == "year"){
			$period=365*24*3600;
		}else{
			$period=7*24*3600;
			$type="week";
		}

		$sizeX=900;
		$sizeY=300;

		$shiftX=12;
		$shiftYup=17;
		$shiftYdown=25+16*3;

	//	Header( "Content-type:  text/html"); 
		Header( "Content-type:  image/png"); 
		Header( "Expires:  Mon, 17 Aug 1998 12:51:50 GMT");

		$im = imagecreate($sizeX+$shiftX+61,$sizeY+$shiftYup+$shiftYdown+10); 
	  
		$red=ImageColorAllocate($im,255,0,0); 
		$darkred=ImageColorAllocate($im,150,0,0); 
		$green=ImageColorAllocate($im,0,255,0); 
		$darkgreen=ImageColorAllocate($im,0,150,0); 
		$blue=ImageColorAllocate($im,0,0,255); 
		$darkblue=ImageColorAllocate($im,0,0,150); 
		$yellow=ImageColorAllocate($im,255,255,0); 
		$darkyellow=ImageColorAllocate($im,150,150,0); 
		$cyan=ImageColorAllocate($im,0,255,255); 
		$black=ImageColorAllocate($im,0,0,0); 
		$gray=ImageColorAllocate($im,150,150,150); 
		$white=ImageColorAllocate($im,255,255,255); 
		$bg=ImageColorAllocate($im,6+6*16,7+7*16,8+8*16);

		$x=imagesx($im); 
		$y=imagesy($im);
	  
		ImageFilledRectangle($im,0,0,$x,$y,$white);
		ImageRectangle($im,0,0,$x-1,$y-1,$black);


		$str=expand_trigger_description($triggerid);


		$str=$str." (年份 ".date("Y").")";
		$x=imagesx($im)/2-ImageFontWidth(4)*strlen($str)/2;
		//ImageString($im, 4,$x,1, $str , $darkred);
		imagettftext($im, 12, 0, $x, 15, $darkred, $font_path,$str);

		$now = time(NULL);
		$to_time=$now;
		$from_time=$to_time-$period;
		$from_time_now=$to_time-24*3600;

		$count_now=array();
		$true=array();

		$year=date("Y");
		$start=mktime(0,0,0,1,1,$year);

		$wday=date("w",$start);
		if($wday==0) $wday=7;
		$start=$start-($wday-1)*24*3600;

		for($i=0;$i<52;$i++){
			$period_start=$start+7*24*3600*$i;
			$period_end=$start+7*24*3600*($i+1);
			$stat=calculate_availability($triggerid,$period_start,$period_end);
			
			$true[$i]=$stat["true"];
			$false[$i]=$stat["false"];
			$unknown[$i]=$stat["unknown"];
			$count_now[$i]=1;
		}

		for($i=0;$i<=$sizeY;$i+=$sizeY/10){
			DashedLine($im,$shiftX,$i+$shiftYup,$sizeX+$shiftX,$i+$shiftYup,$gray);
		}

		$j=0;
		for($i=0;$i<=$sizeX;$i+=$sizeX/52){
			DashedLine($im,$i+$shiftX,$shiftYup,$i+$shiftX,$sizeY+$shiftYup,$gray);
			$period_start=$start+7*24*3600*$j;
			ImageStringUp($im, 1,$i+$shiftX-4, $sizeY+$shiftYup+32, date("d.M",$period_start) , $black);
			$j++;
		}

		$maxY=100;
		$tmp=max($true);
		if($tmp>$maxY){
			$maxY=$tmp;
		}
		$minY=0;

		$maxX=900;
		$minX=0;

		for($i=0;$i<52;$i++){

			$x1=(900/52)*$sizeX*($i-$minX)/($maxX-$minX);

			ImageFilledRectangle($im,$x1+$shiftX,$shiftYup,$x1+$shiftX+8,$sizeY+$shiftYup,ImageColorAllocate($im,200,200,120));
			$y1=$sizeY*$true[$i]/100;

			ImageFilledRectangle($im,$x1+$shiftX,$shiftYup,$x1+$shiftX+8,$y1+$shiftYup,ImageColorAllocate($im,200,120,120));

			$y1=$sizeY*$false[$i]/100;
			$y1=$sizeY-$y1;

			ImageFilledRectangle($im,$x1+$shiftX,$y1+$shiftYup,$x1+$shiftX+8,$sizeY+$shiftYup,ImageColorAllocate($im,120,200,120));

			ImageRectangle($im,$x1+$shiftX,$shiftYup,$x1+$shiftX+8,$sizeY+$shiftYup,$black);
		}

		for($i=0;$i<=$sizeY;$i+=$sizeY/10){
			ImageString($im, 1, $sizeX+5+$shiftX, $sizeY-$i-4+$shiftYup, $i*($maxY-$minY)/$sizeY+$minY , $darkred);
		}

		ImageFilledRectangle($im,$shiftX,$sizeY+$shiftYup+39+15*0,$shiftX+8,$sizeY+$shiftYup+39+8+15*0,ImageColorAllocate($im,120,200,120));
		ImageRectangle($im,$shiftX,$sizeY+$shiftYup+39+15*0,$shiftX+8,$sizeY+$shiftYup+39+8+15*0,$black);
		//ImageString($im, 2,$shiftX+9,$sizeY+$shiftYup+15*0+35, "假 (%)", $black);
		imagettftext($im, 10, 0, $shiftX+10, $sizeY+$shiftYup+15*0+35+15, $black, $font_path,"假 (%)");

		ImageFilledRectangle($im,$shiftX,$sizeY+$shiftYup+39+15*1,$shiftX+8,$sizeY+$shiftYup+39+8+15*1,ImageColorAllocate($im,200,120,120));
		ImageRectangle($im,$shiftX,$sizeY+$shiftYup+39+15*1,$shiftX+8,$sizeY+$shiftYup+39+8+15*1,$black);
		//ImageString($im, 2,$shiftX+9,$sizeY+$shiftYup+15*1+35, "真 (%)", $black);
		imagettftext($im, 10, 0, $shiftX+10, $sizeY+$shiftYup+15*1+35+15, $black, $font_path,"真 (%)");

		ImageFilledRectangle($im,$shiftX,$sizeY+$shiftYup+39+15*2,$shiftX+8,$sizeY+$shiftYup+39+8+15*2,ImageColorAllocate($im,200,200,120));
		ImageRectangle($im,$shiftX,$sizeY+$shiftYup+39+15*2,$shiftX+8,$sizeY+$shiftYup+39+8+15*2,$black);
		//ImageString($im, 2,$shiftX+9,$sizeY+$shiftYup+15*2+35, "未知 (%)", $black);
		imagettftext($im, 10, 0, $shiftX+10, $sizeY+$shiftYup+15*2+35+15, $black, $font_path,"未知 (%)");

		ImageStringUp($im,0,imagesx($im)-10,imagesy($im)-50, $img_text, $gray);

		$end_time=time(NULL);
		//ImageString($im, 0,imagesx($im)-100,imagesy($im)-12,"产生于 ".($end_time-$start_time)." 秒", $gray);
		imagettftext($im, 10, 0, imagesx($im)-310,imagesy($im)-12, $gray, $font_path,"产生于".date("Y-m-d H:i:s:")." 耗时".($end_time-$start_time)."秒");

		ImageOut($im); 
		ImageDestroy($im); 
	}
	////////////////////diff
	if($graph_type=="diff"){
		if(!isset($_GET["period"])){
			$period=0;
		}else{
			$period=$_GET["period"];
		}

		if(!isset($_GET["from"])){
			$from_time=0;
		}else{
			$from_time=$_GET["from"];
		}

		if(!isset($_GET["till"])){
			$to_time=0;
		}else{
			$to_time=$_GET["till"];
		}

		if(isset($_GET["width"])){
			$sizeX=$_GET["width"];
		}else{
			$sizeX=200;
		}

		$sizeY=200;

		$shiftX=10;
		$shiftY=17;

		$nodata=1;	

	//	Header( "Content-type:  text/html"); 
		Header( "Content-type:  image/png"); 
		Header( "Expires:  Mon, 17 Aug 1998 12:51:50 GMT"); 

		$im = imagecreate($sizeX+$shiftX+61,$sizeY+2*$shiftY+10); 
	  
		$red=ImageColorAllocate($im,255,0,0); 
		$darkred=ImageColorAllocate($im,150,0,0); 
		$green=ImageColorAllocate($im,0,255,0); 
		$darkgreen=ImageColorAllocate($im,0,150,0); 
		$blue=ImageColorAllocate($im,0,0,255); 
		$yellow=ImageColorAllocate($im,255,255,0); 
		$cyan=ImageColorAllocate($im,0,255,255); 
		$black=ImageColorAllocate($im,0,0,0); 
		$gray=ImageColorAllocate($im,150,150,150); 
		$white=ImageColorAllocate($im,255,255,255); 

		$x=imagesx($im); 
		$y=imagesy($im);
	  
		ImageFilledRectangle($im,0,0,$sizeX+$shiftX+61,$sizeY+2*$shiftY+10,$white);
		ImageRectangle($im,0,0,$x-1,$y-1,$black);

		for($i=0;$i<=$sizeY;$i+=$sizeY/5){
			DashedLine($im,$shiftX,$i+$shiftY,$sizeX+$shiftX,$i+$shiftY,$gray);
		}
		for($i=0;$i<=$sizeX;$i+=$sizeX/24){
			DashedLine($im,$i+$shiftX,$shiftY,$i+$shiftX,$sizeY+$shiftY,$gray);
		}
		$item=get_item_by_itemid($_GET["itemid"]);
		$host=get_host_by_hostid($item["hostid"]);

		$str=$host["host"].":".$item["description"]." (diff)";
		$x=imagesx($im)/2-ImageFontWidth(4)*strlen($str)/2;
		ImageString($im, 4,$x,1, $str , $darkred);

		$result=$con_dbo -> ifexists("select count(clock) as count_c,min(clock) as min_c,max(clock) as max_c,min(value) as min_v,max(value) as max_v from history where itemid=".$_GET["itemid"]." and clock>=$from_time and clock<=$to_time ");
		$count=$result["count_c"];
		
		if($count>0){
			$nodata=0;
			$minX=$result["min_c"];
			$maxX=$result["max_c"];
			$minY=$result["min_v"];
			$maxY=$result["max_v"];
		}else{
			unset($maxX);
			unset($maxY);
			unset($minX);
			unset($minY);
		}

		$result=$con_dbo -> sel_arr("select clock,value from history where itemid=".$_GET["itemid"]." and clock>=$from_time and clock<=$to_time order by clock");
		if(isset($minX)&&($minX!=$maxX)&&($minY!=$maxY)){
			for($i=0;$i<count($result)-3;$i++){
				$x=$result[$i]["clock"];
				$x_next=$result[$i+1]["clock"];
				$x_next_next=$result[$i+2]["clock"];
				$y=$result[$i]["value"];
				$y_next=$result[$i+1]["value"];
				$y_next_next=$result[$i+2]["value"];

				if(!isset($minY)||($y_next-$y<$minY)){
					$minY=$y_next-$y;
				}	
				if(!isset($maxY)||($y_next-$y>$maxY)){
					$maxY=$y_next-$y;
				}

				$x1=$sizeX*($x-$minX)/($maxX-$minX);
				$y1=$sizeY*(($y_next-$y)-$minY)/($maxY-$minY);
				$x2=$sizeX*($x_next-$minX)/($maxX-$minX);
				$y2=$sizeY*(($y_next_next-$y_next)-$minY)/($maxY-$minY);

				$y1=$sizeY-$y1;
				$y2=$sizeY-$y2;

				ImageLine($im,$x1+$shiftX,$y1+$shiftY,$x2+$shiftX,$y2+$shiftY,$darkgreen);

			}
			$y1=$sizeY*(-$minY)/($maxY-$minY);
			$y1=$sizeY-$y1;
			DashedLine($im,$shiftX,$y1+$shiftY,$sizeX+$shiftX,$y1+$shiftY,$darkred);
		}else{
			if(isset($minX)){
				ImageLine($im,$shiftX,$shiftY+$sizeY/2,$sizeX+$shiftX,$shiftY+$sizeY/2,$darkgreen);
			}
		}

		if($nodata == 0){
			for($i=0;$i<=$sizeY;$i+=$sizeY/5){
				ImageString($im, 1, $sizeX+5+$shiftX, $sizeY-$i-4+$shiftY, $i*($maxY-$minY)/$sizeY+$minY , $darkred);
			}

			ImageString($im, 1,10,$sizeY+$shiftY+5, date("Y-m-d H:i:s",$minX) , $darkred);
			ImageString($im, 1,$sizeX+$shiftX-168,$sizeY+$shiftY+5, date("Y-m-d H:i:s",$maxX) , $darkred);
		}else{
			//ImageString($im, 2,$sizeX/2-50,$sizeY+$shiftY+3, "这一周期未找到数据" , $darkred);
			$str="没有数据";
			imagettftext($im, 12, 0, $sizeX/2-50, $sizeY+$shiftY+3+15, $darkred, $font_path,$str);
		}

		ImageStringUp($im,0,imagesx($im)-10,imagesy($im)-50, $img_text, $gray);

		ImageOut($im); 
		ImageDestroy($im); 
	}

	////////map
	if($graph_type=="map"){
		$grid=50;
		$sql_sysmap="select name,width,height,background from sysmaps where sysmapid=".$_GET["sysmapid"];
		$arr_sysmap=$con_dbo -> ifexists($sql_sysmap);

		$name=$arr_sysmap["name"];
		$width=$arr_sysmap["width"];
		$height=$arr_sysmap["height"];
		$background=$arr_sysmap["background"];
		if(isset($_GET["width"])&&$_GET["width"]>0){
			$width=$_GET["width"];
		}
		if(isset($_GET["height"])&&$_GET["height"]>0){
			$height=$_GET["height"];
		}
	//	Header( "Content-type:  text/html"); 
		Header( "Content-type:  image/png"); 
		Header( "Expires:  Mon, 17 Aug 1998 12:51:50 GMT"); 

		if(function_exists("imagecreatetruecolor")&&@imagecreatetruecolor(1,1)){
			$im = imagecreatetruecolor($width,$height);
		}else{
			$im = imagecreate($width,$height);
		}
	  
		$red=ImageColorAllocate($im,255,0,0); 
		$darkred=ImageColorAllocate($im,150,0,0); 
		$green=ImageColorAllocate($im,0,255,0);
		$darkgreen=ImageColorAllocate($im,0,150,0); 
		$blue=ImageColorAllocate($im,0,0,255);
		$yellow=ImageColorAllocate($im,255,255,0);
		$darkyellow=ImageColorAllocate($im,150,127,0);
		$cyan=ImageColorAllocate($im,0,255,255);
		$white=ImageColorAllocate($im,255,255,255); 
		$black=ImageColorAllocate($im,0,0,0); 
		$gray=ImageColorAllocate($im,150,150,150);

		$colors["Red"]=ImageColorAllocate($im,255,0,0); 
		$colors["Dark Red"]=ImageColorAllocate($im,150,0,0); 
		$colors["Green"]=ImageColorAllocate($im,0,255,0); 
		$colors["Dark Green"]=ImageColorAllocate($im,0,150,0); 
		$colors["Blue"]=ImageColorAllocate($im,0,0,255); 
		$colors["Dark Blue"]=ImageColorAllocate($im,0,0,150); 
		$colors["Yellow"]=ImageColorAllocate($im,255,255,0); 
		$colors["Dark Yellow"]=ImageColorAllocate($im,150,150,0); 
		$colors["Cyan"]=ImageColorAllocate($im,0,255,255); 
		$colors["Black"]=ImageColorAllocate($im,0,0,0); 
		$colors["Gray"]=ImageColorAllocate($im,150,150,150); 
		$colors["White"]=ImageColorAllocate($im,255,255,255);

		$x=imagesx($im); 
		$y=imagesy($im);
	  
	#	ImageFilledRectangle($im,0,0,$width,$height,$black);
		if($background!=""){
			$sql="select image from images where imagetype=2 and name='$background'";
			$result2=$con_dbo -> sel_arr($sql);
			if(count($result2)==1){
				$back=ImageCreateFromString(DBget_field($result2,0,0));
				ImageCopy($im,$back,0,0,0,0,imagesx($back),imagesy($back));
			}else{
				ImageFilledRectangle($im,0,0,$width,$height,$white);
				$x=imagesx($im)/2-ImageFontWidth(4)*strlen($name)/2;
				ImageString($im, 4,$x,1, $name , $colors["Dark Red"]);
			}
		}else{
			ImageFilledRectangle($im,0,0,$width,$height,$white);
			$x=imagesx($im)/2-ImageFontWidth(4)*strlen($name)/2;
			ImageString($im, 4,$x,1, $name , $colors["Dark Red"]);
		}

		if(!isset($_GET["border"])){
			ImageRectangle($im,0,0,$width-1,$height-1,$colors["Black"]);
		}
		
		//日期
		$str=date("m.d.Y H:i:s",time(NULL));
		ImageString($im, 0,imagesx($im)-120,imagesy($im)-12,"$str", $gray);

		//编辑线
		if(!isset($_GET["noedit"])){
			for($x=$grid;$x<$width;$x+=$grid){
				DashedLine($im,$x,0,$x,$height,$black);
				ImageString($im, 2, $x+2,2, $x , $black);
			}
			for($y=$grid;$y<$height;$y+=$grid){
				DashedLine($im,0,$y,$width,$y,$black);
				ImageString($im, 2, 2,$y+2, $y , $black);
			}

			ImageString($im, 2, 1,1, "Y X:" , $black);
		}

	# Draw connectors 
		$sql_sysmap="select shostid1,shostid2,triggerid,color_off,drawtype_off,color_on,drawtype_on from sysmaps_links where sysmapid=".$_GET["sysmapid"];
		$result=$con_dbo -> sel_arr($sql_sysmap);
		for($i=0;$i<count($result);$i++){
			$shostid1=$result[$i]["shostid1"];
			$shostid2=$result[$i]["shostid2"];
			$triggerid=$result[$i]["triggerid"];
			$color_off=$result[$i]["color_off"];
			$drawtype_off=$result[$i]["drawtype_off"];
			$color_on=$result[$i]["color_on"];
			$drawtype_on=$result[$i]["drawtype_on"];

			$result1=$con_dbo -> ifexists("select x,y,icon from sysmaps_hosts where shostid=$shostid1");
			$x1=$result1["x"];
			$y1=$result1["y"];
			$image1=get_image_by_name(1,$result1["icon"]);

			$result1=$con_dbo -> ifexists("select x,y,icon from sysmaps_hosts where shostid=$shostid2");
			$x2=$result1["x"];
			$y2=$result1["y"];
			$image2=get_image_by_name(1,$result1["icon"]);

			$icon=dirname(dirname(__FILE__))."/img/host_online.gif";

	//画连接线

			if($image1!=0){
				//$icon=ImageCreateFromString($image1["image"]);
				$sizex1=imagesx($icon);
				$sizey1=imagesx($icon);
			}else{
				$sizex1=0;
				$sizey1=0;
			}
			if($image2!=0){
				//$icon=ImageCreateFromString($image2["image"]);
				$sizex2=imagesx($icon);
				$sizey2=imagesx($icon);
			}else{
				$sizex2=0;
				$sizey2=0;
			}

			if(isset($triggerid)){			
				$trigger=get_trigger_by_triggerid($triggerid);
				if($trigger["value"] == 1){
					if($drawtype_on == 2){
						ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors[$color_on]);
						ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2+1,$x2+$sizex2/2,$y2+$sizey2/2+1,$colors[$color_on]);
					}else if($drawtype_on == 4){
						DashedLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors[$color_on]);
					}else{
						ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors[$color_on]);
					}
				}else{
					if($drawtype_off == 2){
						ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors[$color_off]);
						ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2+1,$x2+$sizex2/2,$y2+$sizey2/2+1,$colors[$color_off]);
					}elseif($drawtype_off == 4){
						DashedLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors[$color_off]);
					}else{
						ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2+1,$x2+$sizex2/2,$y2+$sizey2/2+1,$colors[$color_off]);
					}
				}
			}else{
				if($drawtype_off == 2){
					ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors["Black"]);
					ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2+1,$x2+$sizex2/2,$y2+$sizey2/2+1,$colors["Black"]);
				}elseif($drawtype_off == 4){
					DashedLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors["Black"]);
				}else{
					ImageLine($im,$x1+$sizex1/2,$y1+$sizey1/2,$x2+$sizex2/2,$y2+$sizey2/2,$colors["Black"]);
				}
				
			}
		}
	# Draw hosts

		$result=$con_dbo -> sel_arr("select h.host,sh.shostid,sh.sysmapid,sh.hostid,sh.label,sh.x,sh.y,h.status,sh.icon,sh.icon_on from sysmaps_hosts sh,hosts h where sh.sysmapid=".$_GET["sysmapid"]." and h.hostid=sh.hostid");

		for($i=0;$i<count($result);$i++){
			$host=$result[$i]["host"];
			$shostid=$result[$i]["shostid"];
			$sysmapid=$result[$i]["sysmapid"];
			$hostid=$result[$i]["hostid"];
			$label=$result[$i]["label"];
			$x=$result[$i]["x"];
			$y=$result[$i]["y"];
			$status=$result[$i]["status"];
			//$icon=$result[$i]["icon"];
			//$icon_on=$result[$i]["icon_on"];


			$result1=$con_dbo -> ifexists("select count(distinct t.triggerid) as re_count from items i,functions f,triggers t,hosts h where h.hostid=i.hostid and i.hostid=$hostid and i.itemid=f.itemid and f.triggerid=t.triggerid and t.value=1 and t.status=0 and h.status in (0,2) and i.status=0");
			$count=$result1["re_count"];

			if( ($status!=1)&&($count>0)){
				//$icon=$icon_on;
				$icon=dirname(dirname(__FILE__))."/img/host_online.gif";
			}else{
				$icon=dirname(dirname(__FILE__))."/img/host_off.gif";
			}
			
			/*
			$icons=array();
			if(@gettype($icons["$icon"])!="resource"){
				$sql="select image from images where imagetype=1 and name='$icon'";
				$result2=$con_dbo -> sel_arr($sql);
				if(count($result2)!=1){
					$icons[$icon] = imagecreatetruecolor(48,48);
				}else{
					$icons[$icon]=ImageCreateFromString($result2[0]["image"]);
				}
			}


			$img=$icons[$icon];

			ImageCopy($im,$img,$x,$y,0,0,ImageSX($img),ImageSY($img));
			*/
            if(function_exists('imagecreatefromgif')){
				 $img=@ImageCreateFromGIF($icon);				
			}
			ImageCopy($im,$img,$x,$y,0,0,ImageSX($img),ImageSY($img));
			if($label!=""){
				$x1=$x+ImageSX($img)/2-ImageFontWidth(2)*strlen($label)/2;
				$y1=$y+ImageSY($img);
				ImageFilledRectangle($im,$x1-2, $y1,$x1+ImageFontWidth(2)*strlen($label), $y1+ImageFontHeight(2),$white);
				//ImageString($im, 2, $x1, $y1, $label,$black);
				imagettftext($im, 10, 0, $x1, $y1+15, $black, $font_path,$label);
			}

			if($status == 1){
				$color=$darkred;

				$label="";
			}else{
				if($count==1){
					$result1=DBselect("select distinct t.description,t.triggerid, t.priority from items i,functions f,triggers t,hosts h where h.hostid=i.hostid and i.hostid=$hostid and i.itemid=f.itemid and f.triggerid=t.triggerid and t.value=1 and t.status=0 and h.status in (0,2) and i.status=0");
					$label=$result1[0]["description"];
					if($result1[0]["priority"] > 3){
						$color=$red;
					}else{
						$color=$darkyellow;
					}

					$label=expand_trigger_description_simple($result1[0]["triggerid"]);
				}else if($count>1){
					$color=$red;
					$label=$count." problems";
				}else{
					$color=$darkgreen;
					$label="OK";
				}
			}
			$x1=$x+ImageSX($img)/2-ImageFontWidth(2)*strlen($label)/2;
			$y1=$y+ImageSY($img)+ImageFontHeight(2);
			ImageFilledRectangle($im,$x1-2, $y1+5,$x1+ImageFontWidth(2)*strlen($label), $y1+ImageFontHeight(2)+5,$white);
			//ImageString($im, 2, $x1, $y1, $label,$color);
			imagettftext($im, 10, 0, $x1, $y1+15, $color, $font_path,$label);
		}

		ImageStringUp($im,0,imagesx($im)-10,imagesy($im)-50, $img_text, $gray);

		ImageOut($im);
		ImageDestroy($im);
	}
?>
