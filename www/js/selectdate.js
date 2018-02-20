/*
<body onLoad="YYYYMMDDstart(30,0,'year','month','day')">
<form name="form1">
            <select id="year" name="year" onchange="YYYYDD(this.value)">
                <option value="" selected>请选择 年</option>
            </select>
            <select id="month" name="month" onchange="MMDD(this.value)">
                <option value="" selected>选择 月</option>
            </select>
            <select id="day" name="day">
                <option value="" selected>选择 日</option>
            </select>
        </form>
*/
function YYYYMMDDstart(byear,fyear,yearid,monthid,dayid,date){
	var y_obj=document.getElementById(yearid);
	var m_obj=document.getElementById(monthid);
	var d_obj=document.getElementById(dayid);

	if(byear==""){
		byear=30;
	}
	if(fyear==""){
		fyear=30;
	}
	MonHead = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	

	var date_arr=date.split("-");

	//先给年下拉框赋内容
	var y = new Date().getFullYear();	
	for (var i = (y-byear); i < (y+fyear); i++){ //以今年为准，前byear年，后fyear年
		y_obj.options.add(new Option(" "+ i +"年", i));
	}

	//赋月份的下拉框
	for (var i = 1; i < 13; i++){
		m_obj.options.add(new Option(" " + i + "月", i));
	}
	if(date_arr[0]!=""){		
		y_obj.value = date_arr[0];
	}else{
		y_obj.value = y;
	}

	if(date_arr[1]!=""){		
		m_obj.value = date_arr[1];
	}else{
		m_obj.value = new Date().getMonth() + 1;
	}
	
	var n = MonHead[new Date().getMonth()];
	if (new Date().getMonth() ==1 && IsPinYear(YYYYvalue)){
		n++;
	}

	writeDay(n,yearid,monthid,dayid); //赋日期下拉框
	d_obj.value = new Date().getDate();
	if(date_arr[2]!=""){		
		d_obj.value =  date_arr[2];
	}else{
		d_obj.value = new Date().getDate();
	}
}

function YYYYDD(str,yearid,monthid,dayid){ //年发生变化时日期发生变化(主要是判断闰平年)
	var y_obj=document.getElementById(yearid);
	var m_obj=document.getElementById(monthid);
	var d_obj=document.getElementById(dayid);

	var MMvalue = m_obj.options[m_obj.selectedIndex].value;
	if (MMvalue == ""){ 
		var e = d_obj; 
		optionsClear(e); 
		return;
	}
	var n = MonHead[MMvalue - 1];
	if (MMvalue ==2 && IsPinYear(str)){
		n++;		
	}
	writeDay(n,yearid,monthid,dayid,hourid);
}

function MMDD(str,yearid,monthid,dayid){  //月发生变化时日期联动
	var y_obj=document.getElementById(yearid);
	var m_obj=document.getElementById(monthid);
	var d_obj=document.getElementById(dayid);

	var YYYYvalue = y_obj.options[y_obj.selectedIndex].value;
	if (YYYYvalue == ""){ 
		var e = d_obj; 
		optionsClear(e); 
		return;
	}
	var n = MonHead[str - 1];
	if (str ==2 && IsPinYear(YYYYvalue)) {
		n++;
	}
	writeDay(n,yearid,monthid,dayid,hourid);
}

function writeDay(n,yearid,monthid,dayid){  //据条件写日期的下拉框
	var y_obj=document.getElementById(yearid);
	var m_obj=document.getElementById(monthid);
	var d_obj=document.getElementById(dayid);
	var e = d_obj;
	optionsClear(e);
	for (var i=1; i<(n+1); i++){
		e.options.add(new Option(" "+ i + " 日", i));
	}
}

function IsPinYear(year){  //判断是否闰平年
	return(0 == year%4 && (year%100 !=0 || year%400 == 0));
}

function optionsClear(e){
	e.options.length = 1;
}
/*
if(document.attachEvent){
	window.attachEvent("onload", );
}else{
	window.addEventListener('load', YYYYMMDDstart(30,0,"year","month","day"), false);
}
*/