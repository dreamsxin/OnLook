<?php
	$cur_rights=1301;
	include('config.php');
	$tpl -> assign("current_path",$cur_rights);


	$graphid="";
	if(isset($_GET["graphid"])){
		$graphid=$_GET["graphid"];
	}
	$tpl -> assign("graphid",$graphid);

	$now=time();

	$result=$con_dbo -> ifexists("select graphid,name,width,height,yaxistype,yaxismin,yaxismax from graphs where graphid=$graphid");

	$name=$result["name"];
	$tpl -> assign("name",$name);

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

	$from_date=$from_year."-".$from_month."-".$from_day."-";
	$tpl -> assign("from_date",$from_date);
	$tpl -> assign("from_hour",$from_hour);
	$tpl -> assign("from_minute",$from_minute);

	$from=mktime($from_hour,$from_minute,0,$from_month,$from_day,$from_year);

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

	$till_date=$till_year."-".$till_month."-".$till_day;
	$tpl -> assign("till_date",$till_date);
	$tpl -> assign("till_hour",$till_hour);
	$tpl -> assign("till_minute",$till_minute);

	$till=mktime($till_hour,$till_minute,0,$till_month,$till_day,$till_year);
	
	$date_title="从:[ $from_year 年 $from_month 月 $from_day 日 $from_hour 时 $from_minute 分] 到:[ $till_year 年 $till_month 月 $till_day 日 $till_hour 时 $till_minute 分]";

	$tpl -> assign("date_title",$date_title);

	$period=mktime($till_hour,$from_minute,0,$till_month,$till_day,$till_year)-mktime($from_hour,$from_minute,0,$from_month,$from_day,$from_year);

	$img_url='graph/graph_show.php?graph_type=1&graphid='.$graphid.'&period='.$period.'&from='.$from.'&till='.$till.'&width=760';
	$tpl -> assign("img_url",$img_url);

	$tpl -> display("graphs_show_tpl.htm");
?>