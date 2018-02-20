<?php
	$cur_rights=1401;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);
	
	$screenid="";
	if(isset($_GET["screenid"])){
		$screenid=$_GET["screenid"];
	}
	$tpl -> assign("screenid",$screenid);

	//对照表列表
	$sql_screens="select screenid,name,cols,rows from screens where screenid=".$screenid;
	$arr_screens=$con_dbo -> ifexists($sql_screens);
	$tpl -> assign("arr_screens",$arr_screens);
	
	//对照表图表
	$sql_screen_items="select screenitemid,screenid,resource,resourceid,width,height,x,y from screens_items where screenid=".$screenid." order by y,x";
	$arr_screen_items=$con_dbo -> sel_arr($sql_screen_items);
	
	$arr_img=array();
	foreach($arr_screen_items as $k => $v){
		$arr_img[$v["y"]][$v["x"]]["screenitemid"]=$v["screenitemid"];
		$arr_img[$v["y"]][$v["x"]]["screenid"]=$v["screenid"];
		$arr_img[$v["y"]][$v["x"]]["resource"]=$v["resource"];
		$arr_img[$v["y"]][$v["x"]]["resourceid"]=$v["resourceid"];
		$arr_img[$v["y"]][$v["x"]]["width"]=$v["width"];
		$arr_img[$v["y"]][$v["x"]]["height"]=$v["height"];
	}

	//html
	$screen_html='<table width="100%" border="0" cellspacing="1" cellpadding="0">';
	for($y=0;$y<$arr_screens["rows"];$y++){
		$screen_html.="<tr>";
		for($x=0;$x<$arr_screens["cols"];$x++){
			$screenitemid=0;
			$resource=0;
			$resourceid=0;
			$width=0;
			$height=0;
			if(isset($arr_img[$y][$x])){
				$screenitemid=$arr_img[$y][$x]["screenitemid"];
				$resource=$arr_img[$y][$x]["resource"];
				$resourceid=$arr_img[$y][$x]["resourceid"];
				$width=$arr_img[$y][$x]["width"];
				$height=$arr_img[$y][$x]["height"];
			}
			
			if($y==0 && $x==0){
				$tpl -> assign("screenitemid",$screenitemid);
				$tpl -> assign("resource",$resource);
				$tpl -> assign("resourceid",$resourceid);
				$tpl -> assign("width",$width);
				$tpl -> assign("height",$height);
			}

			$img_str='';
			if($screenitemid!=0&&$resource==0&&$resourceid!=0){
				$img_str="<a href=graphs_show.php?graphid=$resourceid><img src='graph/graph_show.php?graph_type=1&graphid=$resourceid&width=$width&height=$height&period=3600' border=0></a><br>";
			}elseif($screenitemid!=0&&$resource==1&&$resourceid!=0){
				$img_str="<a href=history.php?action=graph&itemid=$resourceid><img src='graph/graph_show.php?graph_type=0&itemid=$resourceid&width=$width&height=$height&period=3600' border=0></a><br>";
			}elseif($screenitemid!=0&&$resource==2&&$resourceid!=0){
				$img_str="<a href=\"graph/graph_show.php?graph_type=map&noedit=1&sysmapid=$resourceid\" target=\"_blank\"><img src='graph/graph_show.php?graph_type=map&noedit=1&sysmapid=$resourceid&width=$width&height=$height' border=0></a><br>";
			}elseif($screenitemid!=0&&$resource==3&&$resourceid!=0){
				$img_str=show_screen_plaintext($resourceid);
			}else{
				$img_str="无";
			}
			$screen_html.='<td class="body_td_gray_two" align="center" valign="top">'.$img_str.'</td>';
		}
		$screen_html.="</tr>";
	}
	$screen_html.="</table>";
	$tpl -> assign("screen_html",$screen_html);

	$tpl -> display("screens_show_tpl.htm");

	function show_screen_plaintext($itemid){
		global $con_dbo;
		$sql="select * from items where itemid=".$itemid;
		$item=$con_dbo -> ifexists($sql);
		if($item["value_type"]==0){
			$sql="select clock,value from history where itemid=$itemid order by clock desc limit 25";
		}else{
			$sql="select clock,value from history_str where itemid=$itemid order by clock desc limit 25";
		}
        $result=$con_dbo -> sel_arr($sql);

		$html='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		$html.='<tr>';
		$html.='<td class="body_td_gray">数据源</td>';
		$html.='<td class="body_td_gray">日期</td>';
		$html.='<td class="body_td_gray">值</td>';
		$html.='</tr>';
		if(!is_array($result) || count($result)<1){
			$html.='<tr>';
			$html.='<td class="body_td_gray_two" colspan="3" align="center">'.$item["description"].' -- 没有数据</td>';
			$html.='</tr>';
		}
		$class="body_td_gray_two";
		while($row=$result){
			$html.='<tr>';
			$html.='<td class="'.$class.'">&nbsp;'.$item["description"].'</td>';
			$html.='<td class="'.$class.'">&nbsp;'.date("d M H:i:s",$row["clock"]).'</td>';
			$html.='<td class="'.$class.'">&nbsp;'.$row["value"].'</td>';
			$html.='</tr>';
			if($class=='body_td_gray_two'){
				$class=='body_td_gray';
			}else{
				$class="body_td_gray_two";
			}
		}
		$html.='</table>';
		return $html;
	}
?>