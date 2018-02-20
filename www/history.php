<?php
	$cur_rights=1101;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);

	$itemid="";
	if(isset($_GET["itemid"])){
		$itemid=$_GET["itemid"];
	}
	$tpl -> assign("itemid",$itemid);

	$now=time();

	$result=$con_dbo -> ifexists("select h.host,i.description,(i.nextcheck-$now) as step,h.hostid,i.value_type from items i,hosts h where i.itemid=".$itemid." and h.hostid=i.hostid");

	$host=$result["host"];
	$description=$result["description"];
	$value_type=$result["value_type"];
	$beforenextcheck=$result["step"]+5;
	if($beforenextcheck<=0){
		$beforenextcheck=5;
	}
	$hostid=$result["hostid"];
	$tpl -> assign("description",$description);
	$tpl -> assign("value_type",$value_type);

	

	//起始时间
	$from_year=date('Y', strtotime('-1 hour',time()));
	if(isset($_POST["year"])){
		$from_year=$_POST["year"];
	}

	$from_month=date('n', strtotime('-1 hour',time()));
	if(isset($_POST["month"])){
		$from_month=$_POST["month"];
	}

	$from_day=date('j', strtotime('-1 hour',time()));
	if(isset($_POST["day"])){
		$from_day=$_POST["day"];
	}


	$from_hour=date('H', strtotime('-1 hour',time()));
	if(isset($_POST["hour"])){
		$from_hour=$_POST["hour"];
	}

	$from_minute=date("i");
	if(isset($_POST["minute"])){
		$from_minute=$_POST["minute"];
	}

	$from=mktime($from_hour,$from_minute,0,$from_month,$from_day,$from_year);

	if(http_get("from")>0){
		$from_year=date("Y",http_get("from"));
		$from_month=date("m",http_get("from"));
		$from_day=date("d",http_get("from"));
		$from_hour=date("H",http_get("from"));
		$from_minute=date("i",http_get("from"));
		$from=http_get("from");
	}

	$from_date=$from_year."-".$from_month."-".$from_day."-";


	$tpl -> assign("from_date",$from_date);
	$tpl -> assign("from_hour",$from_hour);
	$tpl -> assign("from_minute",$from_minute);

	//结束时间
	$till_year=date('Y');
	if(isset($_POST["t_year"])){
		$till_year=$_POST["t_year"];
	}

	$till_month=date('n');
	if(isset($_POST["t_month"])){
		$till_month=$_POST["t_month"];
	}

	$till_day=date('j');
	if(isset($_POST["t_day"])){
		$till_day=$_POST["t_day"];
	}

	$till_hour=date('H');
	if(isset($_POST["t_hour"])){
		$till_hour=$_POST["t_hour"];
	}

	$till_minute=date("i");
	if(isset($_POST["t_minute"])){
		$till_minute=$_POST["t_minute"];
	}

	$till=mktime($till_hour,$till_minute,0,$till_month,$till_day,$till_year);
	if(http_get("till")>0){
		$till_year=date("Y",http_get("till"));
		$till_month=date("m",http_get("till"));
		$till_day=date("d",http_get("till"));
		$till_hour=date("H",http_get("till"));
		$till_minute=date("i",http_get("till"));
		$till=http_get("till");
	}

	$till_date=$till_year."-".$till_month."-".$till_day;

	$tpl -> assign("till_date",$till_date);
	$tpl -> assign("till_hour",$till_hour);
	$tpl -> assign("till_minute",$till_minute);

	
	
	$date_title="从:[ $from_year 年 $from_month 月 $from_day 日 $from_hour 时 $from_minute 分] 到:[ $till_year 年 $till_month 月 $till_day 日 $till_hour 时 $till_minute 分]";

	$tpl -> assign("date_title",$date_title);

	$period=$till-$from;
	
	//显示

	if($action=="graph"){
		$url_file="";
		show_history($itemid,$from,$till,$period,0);
		$tpl -> assign("url_file",$url_file);
	}
	if($action=="graph_diff"){
		$url_file="";
		show_history($itemid,$from,$till,$period,1);
		$tpl -> assign("url_file",$url_file);
	}
	if($action=="text"){
		if($value_type==0){
			$sql="select clock,value from history where itemid=$itemid and clock>=$from and clock<=$till order by clock";
		}else{
			$sql="select clock,value from history_str where itemid=$itemid and clock>=$from and clock<=$till order by clock";
		}

		$result=$con_dbo -> sel_arr($sql);
		$tpl -> assign("text_result",$result);
		$tpl -> assign("text_result_count",count($result));
	}
	$tpl -> display("history_tpl.htm");

	function show_history($itemid,$from,$till,$period,$diff){
		global $url_file;

		$url_file="";
		if($diff==0){
			$url_file='<img id="history_img" name="history_img" src="graph/graph_show.php?graph_type=0&itemid='.$itemid.'&from='.$from.'&till='.$till.'&period='.$period.'&width=760" alt="">';
		}else{
			$url_file='<img id="history_img" name="history_img" src="graph/graph_show.php?graph_type=diff&itemid='.$itemid.'&from='.$from.'&till='.$till.'&period='.$period.'&width=760" alt="">';
		}		
	}
?>