/****************************
时间	2006-8-20 
email	dreamsxin@126.com
作者	紫痕
主页	http://lizhen520.com
功能	tooltip sound
****************************/

//shut_host_left_div
function shut_host_left_div(){
	if(document.getElementById("host_left_div").style.display=="none"){
		document.getElementById("host_left_img").src="img/shut_top.gif";
		document.getElementById("host_left_text").innerHTML="关闭";
		document.getElementById("host_left_div").style.display="";
	}else{
		document.getElementById("host_left_img").src="img/shut_top2.gif";
		document.getElementById("host_left_text").innerHTML="展开";
		document.getElementById("host_left_div").style.display="none";
	}
}

//link_add
function link_add(sysmapid){
	document.getElementById("link_form").action="sysmap_item_admin.php?action=link_add&sysmapid="+sysmapid;
	document.getElementById("link_title").innerHTML="添加设备连接线";
	document.getElementById("link_btn").value="添加";

	document.getElementById("link_btn").focus();
	document.getElementById("shostid1").focus();
}

//link_edit
function link_edit(linkid,shostid1,shostid2,triggerid,drawtype_off,color_off,drawtype_on,color_on,sysmapid){
	document.getElementById("link_form").action="sysmap_item_admin.php?action=link_edit&sysmapid="+sysmapid;
	document.getElementById("link_title").innerHTML="修改设备连接线";
	document.getElementById("link_btn").value="修改";

	document.getElementById("linkid").value=linkid;

	var o=document.getElementById("shostid1").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==shostid1){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("shostid2").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==shostid2){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("triggerid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==triggerid){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("drawtype_off").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==drawtype_off){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("color_off").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==color_off){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("drawtype_on").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==drawtype_on){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("color_on").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==color_on){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	document.getElementById("link_btn").focus();
	document.getElementById("shostid1").focus();
}

//shost_add
function shost_add(sysmapid){
	document.getElementById("shost_form").action="sysmap_item_admin.php?action=shost_add&sysmapid="+sysmapid;
	document.getElementById("shost_title").innerHTML="添加设备";
	document.getElementById("shost_btn").value="添加";

	document.getElementById("shost_btn").focus();
	document.getElementById("hostid").focus();
}

//shost_edit
function shost_edit(shostid,hostid,label,x,y,icon,icon_on,url,sysmapid){
	document.getElementById("shost_form").action="sysmap_item_admin.php?action=shost_edit&sysmapid="+sysmapid;
	document.getElementById("shost_title").innerHTML="修改设备";
	document.getElementById("shost_btn").value="修改";
	
	document.getElementById("shostid").value=shostid;
	var o=document.getElementById("hostid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==hostid){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("icon").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==icon){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("icon_on").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==icon_on){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("label").value=label;
	document.getElementById("x").value=x;
	document.getElementById("y").value=y;
	document.getElementById("url").value=url;

	document.getElementById("shost_btn").focus();
	document.getElementById("hostid").focus();
}

//sysmap_add
function sysmap_add(){
	document.getElementById("sysmap_form").action="sysmap_admin.php?action=add";
	document.getElementById("sysmap_title").innerHTML="添加网络图表";
	document.getElementById("sysmap_btn").value="添加";

	document.getElementById("sysmap_btn").focus();
	document.getElementById("name").focus();
}

//sysmap_edit
function sysmap_edit(sysmapid,name,width,height,background){
	document.getElementById("sysmap_form").action="sysmap_admin.php?action=edit";
	document.getElementById("sysmap_title").innerHTML="修改网络图表";
	document.getElementById("sysmap_btn").value="修改";

	document.getElementById("sysmapid").value=sysmapid;
	document.getElementById("name").value=name;
	document.getElementById("width").value=width;
	document.getElementById("height").value=height;
	var o=document.getElementById("background").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==background){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("sysmap_btn").focus();
	document.getElementById("name").focus();
}




//set_xy
function set_xy(x,y){
	var o=document.getElementById("x").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==x){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("y").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==y){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	change_screen_item_yx();
	
	document.getElementById("screen_item_btn").focus();
	document.getElementById("x").focus();
}
//change_resoure
function change_resoure(resource){
	if(resource!=0){
		document.getElementById("graphid").style.display='none';
	}else{
		document.getElementById("graphid").style.display='';
	}
	if(resource!=2){
		document.getElementById("sysmapid").style.display='none';
	}else{
		document.getElementById("sysmapid").style.display='';
	}
	if(resource!=1 && resource!=3){
		document.getElementById("itemid").style.display='none';
	}else{
		document.getElementById("itemid").style.display='';
	}
}

//change_screen_item_yx
function change_screen_item_yx(){
	var oo=document.getElementById("screen_img_"+document.getElementById("x").value+''+document.getElementById("y").value);

	var screenitemid=oo.getAttribute("screenitemid");
	var resource=oo.getAttribute("resource");
	var resourceid=oo.getAttribute("resourceid");
	var width=oo.getAttribute("width");
	var height=oo.getAttribute("height");

	document.getElementById("screenitemid").value=screenitemid;

	var o=document.getElementById("resource").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==resource){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	change_resoure(resource);

	if(resource==0){
		var o=document.getElementById("graphid").options;
	}
	if(resource==2){
		var o=document.getElementById("sysmapid").options;
	}
	if(resource==1 || resource==3){
		var o=document.getElementById("itemid").options;
	}

	for(i=0;i<o.length;i++){
		if(o[i].value==resourceid){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	document.getElementById("width").value=width;
	document.getElementById("height").value=height;
}

//screen_add
function screen_add(){
	document.getElementById("screen_form").action="screen_admin.php?action=add";
	document.getElementById("screen_title").innerHTML="添加对照表";
	document.getElementById("screen_btn").value="添加";

	document.getElementById("screen_btn").focus();
	document.getElementById("name").focus();
}

//screen_edit
function screen_edit(screenid,name,cols,rows){
	document.getElementById("screen_form").action="screen_admin.php?action=edit&screenid="+screenid;
	document.getElementById("screen_title").innerHTML="修改对照表";
	document.getElementById("screen_btn").value="修改";

	document.getElementById("screenid").value=screenid;
	document.getElementById("name").value=name;
	document.getElementById("cols").value=cols;
	document.getElementById("rows").value=rows;

	document.getElementById("screen_btn").focus();
	document.getElementById("name").focus();
}

//graph_item_add
function graph_item_add(graphid){
	document.getElementById("graph_item_form").action="graph_item_admin.php?action=add&graphid="+graphid;
	document.getElementById("graph_item_title").innerHTML="添加显示数据源";
	document.getElementById("graph_item_btn").value="添加";

	document.getElementById("graph_item_btn").focus();
	document.getElementById("itemid").focus();
}

//graph_item_edit
function graph_item_edit(gitemid,graphid,itemid,drawtype,color,sortorder){
	document.getElementById("graph_item_form").action="graph_item_admin.php?action=edit&graphid="+graphid+"&gitemid="+gitemid;
	document.getElementById("graph_item_title").innerHTML="修改图表";
	document.getElementById("graph_item_btn").value="修改";

	document.getElementById("gitemid").value=gitemid;

	var o=document.getElementById("itemid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==itemid){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("drawtype").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==drawtype){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("color").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==color){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("sortorder").value=sortorder;
	document.getElementById("graph_item_btn").focus();
	document.getElementById("itemid").focus();
}

//graph_add
function graph_add(){
	document.getElementById("graph_form").action="graph_admin.php?action=add";
	document.getElementById("graph_title").innerHTML="添加图表";
	document.getElementById("graph_btn").value="添加";

	document.getElementById("graph_btn").focus();
	document.getElementById("name").focus();
}

//graph_edit
function graph_edit(graphid,name,width,height,yaxistype){
	document.getElementById("graph_form").action="graph_admin.php?action=edit&graphid="+graphid;
	document.getElementById("graph_title").innerHTML="修改图表";
	document.getElementById("graph_btn").value="修改";

	document.getElementById("graphid").value=graphid;
	document.getElementById("name").value=name;
	document.getElementById("width").value=width;
	document.getElementById("height").value=height;
	var o=document.getElementById("yaxistype").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==yaxistype){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("graph_btn").focus();
	document.getElementById("name").focus();
}

//action_add
function action_add(triggerid){
	document.getElementById("action_form").action="action_admin.php?action=action_add&triggerid="+triggerid;
	document.getElementById("action_title").innerHTML="添加处理方式";
	document.getElementById("action_btn").value="添加";

	document.getElementById("action_btn").focus();
	//document.getElementById("description").focus();
}

//action_edit
function action_edit(actionid,recipient,userid,good,delay,subject,message,scope,severity,triggerid){
	document.getElementById("action_form").action="action_admin.php?action=action_edit&triggerid="+triggerid;
	document.getElementById("action_title").innerHTML="修改处理方式";
	document.getElementById("action_btn").value="修改";

	document.getElementById("actionid").value=actionid;
	var o=document.getElementById("recipient").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==recipient){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	change_recipient(recipient);

	if(recipient==0){
		var o=document.getElementById("userid").options;
		for(i=0;i<o.length;i++){
			if(o[i].value==userid){
				 o[i].selected=true;
			}else{
				o[i].selected=false;
			}
		}
	}else{
		var o=document.getElementById("usrgrpid").options;
		for(i=0;i<o.length;i++){
			if(o[i].value==userid){
				 o[i].selected=true;
			}else{
				o[i].selected=false;
			}
		}
	}
	

	var o=document.getElementById("good").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==good){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	document.getElementById("delay").value=delay;
	document.getElementById("subject").value=unescape(subject);
	document.getElementById("message").value=unescape(message);

	var o=document.getElementById("scope").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==scope){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	change_scope(scope);
	var o=document.getElementById("severity").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==severity){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("action_btn").focus();
	//document.getElementById("description").focus();
}

//change_recipient
function change_recipient(recipient){
	if(recipient==0){
		document.getElementById("userid").style.display="";
		document.getElementById("usrgrpid").style.display="none";
	}else{
		document.getElementById("userid").style.display="none";
		document.getElementById("usrgrpid").style.display="";
	}
}

//change_severity
function change_scope(scope){
	if(scope==0){
		var o=document.getElementById("severity").options;
		for(i=0;i<o.length;i++){
			if(o[i].value==0){
				 o[i].selected=true;
			}else{
				o[i].selected=false;
			}
		}
		document.getElementById("severity").disabled=true;
		document.getElementById("severity").className="disabled_input";		
	}
	if(scope==1 || scope==2){
		document.getElementById("severity").disabled=false;
		document.getElementById("severity").className="text_input";
	}
}

//search_logs
function search_logs(url){
	location.href=url+"?msg="+escape(document.getElementById("msg").value)+"&s_groupid="+document.getElementById("s_groupid").value+"&s_hostid="+document.getElementById("s_hostid").value;
}

//search_trigger
function search_trigger(url){
	location.href=url+"?trigger="+escape(document.getElementById("trigger").value)+"&s_groupid="+document.getElementById("s_groupid").value+"&s_hostid="+document.getElementById("s_hostid").value;
}

//trigger_add
function trigger_add(itemid,s_groupid,s_hostid,trigger){
	document.getElementById("trigger_form").action="trigger_admin.php?action=add&itemid="+itemid+"&s_groupid="+s_groupid+"&trigger="+escape(trigger);
	document.getElementById("trigger_title").innerHTML="添加告警配置";
	document.getElementById("trigger_btn").value="添加";

	document.getElementById("trigger_btn").focus();
	document.getElementById("description").focus();
}


//trigger_edit
function trigger_edit(triggerid,description,expression,priority,comments,url,status,itemid,s_groupid,s_hostid,trigger,s_triggerid){

	document.getElementById("trigger_form").action="trigger_admin.php?action=edit&s_hostid="+s_hostid+"&itemid="+itemid+"&s_groupid="+s_groupid+"&trigger="+escape(trigger)+"&s_triggerid="+s_triggerid;
	document.getElementById("trigger_title").innerHTML="修改告警配置";
	document.getElementById("trigger_btn").value="修改";

	document.getElementById("triggerid").value=triggerid;
	document.getElementById("description").value=unescape(description);
	document.getElementById("expression").value=unescape(expression);
	var o=document.getElementById("priority").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==priority){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("comments").value=comments;
	document.getElementById("url").value=url;
	var o=document.getElementById("status").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==status){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	document.getElementById("trigger_btn").focus();
	document.getElementById("description").focus();
}

//del_items
function del_items(itemids,s_hostid,s_groupid,sources){
	if(document.getElementById(itemids).value.Trim()==""){
		alert("请选择数据源");
		return false;
	}
	if(!confirm("是否真的要删除！")){
		return false;
	}
	document.getElementById("items_form").action="item_admin.php?action=dels&s_hostid="+s_hostid+"&s_groupid="+s_groupid+"&sources="+escape(sources);

	document.getElementById("items_form").submit();
}

//import_items
function import_items(hostid,itemids,s_hostid,s_groupid,sources){
	if(hostid.Trim()==""){
		return false;
	}

	document.getElementById("items_form").action="item_admin.php?action=import_host&hostid="+hostid+"&s_hostid="+s_hostid+"&s_groupid="+s_groupid+"&sources="+escape(sources);

	document.getElementById("items_form").submit();
}



//item_add
function item_add(type,value_type,s_hostid,s_groupid,sources){
	document.getElementById("item_form").action="item_admin.php?action=add&s_hostid="+s_hostid+"&s_groupid="+s_groupid+"&sources="+escape(sources);
	document.getElementById("item_title").innerHTML="添加数据源";
	document.getElementById("item_btn").value="添加";
	change_item_type(type);
	change_item_value_type(value_type);
	document.getElementById("item_btn").focus();
	document.getElementById("description").focus();
}

//item_edit
function item_edit(itemid,description,hostid,type,key_,units,formula,delay,xhistory,trends,status,value_type,delta,snmp_community,snmp_oid,snmp_port,snmpv3_securityname,snmpv3_securitylevel,snmpv3_authpassphrase,snmpv3_privpassphrase,s_hostid,s_groupid,sources){
	document.getElementById("item_form").action="item_admin.php?action=edit&s_hostid="+s_hostid+"&s_groupid="+s_groupid+"&sources="+escape(sources);
	document.getElementById("item_title").innerHTML="修改数据源";
	document.getElementById("item_btn").value="修改";
	
	document.getElementById("itemid").value=itemid;
	document.getElementById("description").value=description;
	var o=document.getElementById("hostid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==hostid){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("type").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==type){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	change_item_type(type);
	
	document.getElementById("key_").value=key_;
	document.getElementById("units").value=units;
	document.getElementById("formula").value=formula;
	document.getElementById("delay").value=delay;
	document.getElementById("history").value=xhistory;
	document.getElementById("trends").value=trends;
	var o=document.getElementById("status").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==status){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementById("value_type").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==value_type){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	change_item_value_type(value_type);

	var o=document.getElementById("delta").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==delta){
			 o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("snmp_community").value=snmp_community;
	document.getElementById("snmp_oid").value=snmp_oid;
	document.getElementById("snmp_port").value=snmp_port;
	document.getElementById("snmpv3_securityname").value=snmpv3_securityname;
	document.getElementById("snmpv3_securitylevel").value=snmpv3_securitylevel;
	document.getElementById("snmpv3_authpassphrase").value=snmpv3_authpassphrase;
	document.getElementById("snmpv3_privpassphrase").value=snmpv3_privpassphrase;

	document.getElementById("item_btn").focus();
	document.getElementById("description").focus();
}

//search_data
function search_data(url,sort,desc){
	var sort_str='&sort='+sort;
	var desc_str='&desc='+desc;

	location.href=url+"?sources="+escape(document.getElementById("sources").value)+"&s_groupid="+document.getElementById("s_groupid").value+"&s_hostid="+document.getElementById("s_hostid").value+sort_str+desc_str;
}

//select_host
function select_host(groupid){
	var o=document.getElementById("s_hostid").options;
	i=o.length;
	while(i--){
		o[i]=null;
	}
	o.add(new Option("所有设备",""));
	var io=document.getElementById("import_hostid").options;
	for(i=0;i<io.length;i++){
		if(io[i].getAttribute("groupid")==groupid || groupid==""){
			 o.add (new Option(io[i].text, io[i].value));
		}
	}
}

//change_item_type
function change_item_type(type){
	var o=document.getElementsByTagName("*");
	for(i=0;i<o.length;i++){
		if(type==1 || type==4 ){
			if(o[i].getAttribute("dreams_id")=="snmp"){
				o[i].style.display="";
			}else if(o[i].getAttribute("dreams_id")=="snmp_3"){
				o[i].style.display="none";
			}
		}else if(type==6){
			if(o[i].getAttribute("dreams_id")=="snmp"){
				o[i].style.display="";
			}else if(o[i].getAttribute("dreams_id")=="snmp_3"){
				o[i].style.display="";
			}
		}else{
			if(o[i].getAttribute("dreams_id")=="snmp"){
				o[i].style.display="none";
			}else if(o[i].getAttribute("dreams_id")=="snmp_3"){
				o[i].style.display="none";
			}
		}
	}
}

//change_item_value_type
function change_item_value_type(type){
	if(type==0){
		document.getElementById("delta").disabled=false;
		document.getElementById("delta").className="text_input";
	}else{
		document.getElementById("delta").disabled=true;
		document.getElementById("delta").className="disabled_input";
		var o=document.getElementById("delta").options;
		for(i=0;i<o.length;i++){
			if(o[i].value==0){
				o[i].selected=true;
			}else{
				o[i].selected=false;
			}
		}
	}
}

//host_edit
function host_edit(host,hostid,status,groupid,ip,port){
	document.getElementById("host_form").action="host_admin.php?action=edit";
	document.getElementById("host_title").innerHTML="修改设备";
	document.getElementById("host_btn").value="修改";

	document.getElementById("host").value=host;
	document.getElementById("hostid").value=hostid;

	var o=document.getElementById("host_type").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==status){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	if(status==3){
		document.getElementById("host_ip").style.display="none";
		document.getElementById("host_port").style.display="none";
		document.getElementById("host_status").style.display="none";
	}else{
		document.getElementById("host_ip").style.display="";
		document.getElementById("host_port").style.display="";
		document.getElementById("host_status").style.display="";
	}

	document.getElementById("ip").value=ip;
	document.getElementById("port").value=port;
	var o=document.getElementById("status").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==status){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	var o=document.getElementById("groupid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==groupid){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("host_btn").focus();
	document.getElementById("host").focus();
}

//host_add
function host_add(status){
	document.getElementById("host_form").action="host_admin.php?action=add";
	document.getElementById("host_title").innerHTML="添加设备";
	document.getElementById("host_btn").value="添加";

	document.getElementById("host_btn").focus();
	document.getElementById("host").focus();
}
//change_host_type
function change_host_type(type){
	if(type==0){
		document.getElementById("host_ip").style.display="";
		document.getElementById("host_port").style.display="";
		document.getElementById("host_status").style.display="";
	}else{
		document.getElementById("host_ip").style.display="none";
		document.getElementById("host_port").style.display="none";
		document.getElementById("host_status").style.display="none";
	}
}


//host_group_add
function host_group_add(){
	document.getElementById("group_form").action="host_admin.php?action=group_add";
	document.getElementById("group_title").innerHTML="添加组";
	document.getElementById("group_btn").value="添加";

	document.getElementById("group_id").value="";
	document.getElementById("group_name").value="";
	
	document.getElementById("group_btn").focus();
	document.getElementById("group_name").focus();
}
//host_group_edit
function host_group_edit(group_id,group_name){
	document.getElementById("group_form").action="host_admin.php?action=group_edit";
	document.getElementById("group_title").innerHTML="修改组";
	document.getElementById("group_btn").value="修改";

	document.getElementById("group_id").value=group_id;
	document.getElementById("group_name").value=group_name;

	document.getElementById("group_btn").focus();
	document.getElementById("group_name").focus();
}

//user_media_add
function user_media_add(userid){
	document.getElementById("media_form").action="passwd.php?action=media_add";
	document.getElementById("media_title").innerHTML="添加提醒方式";
	document.getElementById("media_btn").value="添加";

	for(i=0;i<6;i++){
		document.getElementById('severity'+i).checked=false;
	}
	document.getElementById("media_btn").focus();
	document.getElementById("mediatypeid").focus();
}

//user_media_edit
function user_media_edit(mediaid,mediatypeid,sendto,active,severity,userid){
	document.getElementById("media_form").action="passwd.php?action=media_edit";
	document.getElementById("media_title").innerHTML="修改提醒方式";
	document.getElementById("media_btn").value="修改";
	var o=document.getElementById("mediatypeid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==mediatypeid){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("sendto").value=sendto;
	var o=document.getElementById("active").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==active){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	for(i=0;i<6;i++){
		if(severity.indexOf(i)!=-1){
			document.getElementById('severity'+i).checked=true;
		}else{
			document.getElementById('severity'+i).checked=false;
		}
	}
	document.getElementById("severity").value=severity;
	document.getElementById("mediaid").value=mediaid;
	document.getElementById("media_btn").focus();
	document.getElementById("mediatypeid").focus();
}

//media_add
function media_add(userid){
	document.getElementById("media_form").action="media.php?action=add&userid="+userid;
	document.getElementById("media_title").innerHTML="添加提醒方式";
	document.getElementById("media_btn").value="添加";

	for(i=0;i<6;i++){
		document.getElementById('severity'+i).checked=false;
	}
	document.getElementById("media_btn").focus();
	document.getElementById("mediatypeid").focus();
}

//media_edit
function media_edit(mediaid,mediatypeid,sendto,active,severity,userid){
	document.getElementById("media_form").action="media.php?action=edit&userid="+userid;
	document.getElementById("media_title").innerHTML="修改提醒方式";
	document.getElementById("media_btn").value="修改";
	var o=document.getElementById("mediatypeid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==mediatypeid){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("sendto").value=sendto;
	var o=document.getElementById("active").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==active){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}

	for(i=0;i<6;i++){
		if(severity.indexOf(i)!=-1){
			document.getElementById('severity'+i).checked=true;
		}else{
			document.getElementById('severity'+i).checked=false;
		}
	}
	document.getElementById("severity").value=severity;
	document.getElementById("mediaid").value=mediaid;
	document.getElementById("media_btn").focus();
	document.getElementById("mediatypeid").focus();
}

//severity
function check_severity(){
	var severity="";
	for(i=0;i<6;i++){
		if(document.getElementById('severity'+i).checked){
			severity+=document.getElementById('severity'+i).value;
		}
	}
	document.getElementById('severity').value=severity;
}

//group_add
function group_add(){
	document.getElementById("group_form").action="user_admin.php?action=group_add";
	document.getElementById("group_title").innerHTML="添加组";
	document.getElementById("group_btn").value="添加";

	document.getElementById("group_id").value="";
	document.getElementById("group_name").value="";
	
	document.getElementById("group_btn").focus();
	document.getElementById("group_name").focus();
}
//group_edit
function group_edit(group_id,group_name,rights){
	document.getElementById("group_form").action="user_admin.php?action=group_edit";
	document.getElementById("group_title").innerHTML="修改组";
	document.getElementById("group_btn").value="修改";

	document.getElementById("group_id").value=group_id;
	document.getElementById("group_name").value=group_name;

var arr_rights=rights.split(",");

	if(arr_rights.length>0 && arr_rights!=""){
		for(i=0;i<arr_rights.length;i++){
			if(document.getElementById('rights_'+(i+1))){
				if(arr_rights[i+1]==1){
					document.getElementById('rights_'+(i+1)).checked=true;
				}else{
					document.getElementById('rights_'+(i+1)).checked=false;
				}
			}
		}
	}else{
		var o=document.getElementsByTagName("*");
		for(i=0;i<o.length;i++){
			if(o[i].getAttribute("type")=="checkbox" && o[i].getAttribute("name")=="rights_checkbox"){
				o[i].checked=false;
			}
		}	
	}
	document.getElementById("rights").value=rights;

	document.getElementById("group_btn").focus();
	document.getElementById("group_name").focus();
}

//user_edit
function user_edit(alias,userid,name,surname,usrgrpid,url){
	document.getElementById("user_form").action="user_admin.php?action=edit";
	document.getElementById("user_title").innerHTML="修改用户";
	document.getElementById("user_btn").value="修改";

	document.getElementById("userid").value=userid;
	document.getElementById("alias").value=alias;
	document.getElementById("name").value=name;
	document.getElementById("surname").value=surname;
	var o=document.getElementById("usrgrpid").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==usrgrpid){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("url").value=url;
	
	document.getElementById("user_btn").focus();
	document.getElementById("alias").focus();
}

//user_add
function user_add(){
	document.getElementById("user_form").action="user_admin.php?action=add";
	document.getElementById("user_title").innerHTML="添加用户";
	document.getElementById("user_btn").value="添加";

	document.getElementById("userid").value="";
	document.getElementById("alias").value="";
	document.getElementById("name").value="";
	document.getElementById("surname").value="";

	var o=document.getElementsByTagName("*");
	for(i=0;i<o.length;i++){
		if(o[i].getAttribute("type")=="checkbox" && o[i].getAttribute("name")=="rights_checkbox"){
			o[i].checked=false;
		}
	}	

	document.getElementById("rights").value="";
	document.getElementById("passwd").value="";
	document.getElementById("user_btn").focus();
	document.getElementById("alias").focus();
}

//system_config
function date_edit(mediatypeid,description,type,smtp_server,smtp_helo,smtp_email,exec_path){
	document.getElementById("mediatypeid").value=mediatypeid;
	document.getElementById("description").value=description;
	var o=document.getElementById("type").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==type){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	document.getElementById("smtp_server").value=smtp_server;
	document.getElementById("smtp_helo").value=smtp_helo;
	document.getElementById("smtp_email").value=smtp_email;
	document.getElementById("exec_path").value=exec_path;
}
function change_action(action){
	if(action=="add"){
		document.getElementById("media_form").action="system_config.php?action=add";
		document.getElementById("media_title").innerHTML="添加提醒方式";
		document.getElementById("media_btn").value="添加";
	}
	if(action=="edit"){
		document.getElementById("media_form").action="system_config.php?action=edit";
		document.getElementById("media_title").innerHTML="修改提醒方式";
		document.getElementById("media_btn").value="修改";
	}
}

function change_type(type){
	var o=document.getElementById("type").options;
	for(i=0;i<o.length;i++){
		if(o[i].value==type){
			o[i].selected=true;
		}else{
			o[i].selected=false;
		}
	}
	var o=document.getElementsByTagName("*");
	switch(type){
		case "0":
			for(i=0;i<o.length;i++){
				if(o[i].getAttribute("dreams_id")=="media_0"){
					o[i].style.display="";
				}
				if(o[i].getAttribute("dreams_id")=="media_1"){
					o[i].style.display="none";
				}
			}			

			break;
		case "1":
			for(i=0;i<o.length;i++){
				if(o[i].getAttribute("dreams_id")=="media_1"){
					o[i].style.display="";
				}
				if(o[i].getAttribute("dreams_id")=="media_0"){
					o[i].style.display="none";
				}
			}
			break;
		default:
			break;
	}
}

/*
文件名:	tool.js
作者:	紫痕
日期：	2006-08-05

使用中有任何疑问请联系
2006年8月18日 紫痕 msn:zise_xin@hotmail.com
*/
//checkbox select_all
function ckb_select_all(id){
	var type=document.getElementById(id).checked;
	var name=document.getElementById(id).value;
	var o=document.getElementsByName(name);

	for(i=0;i<o.length;i++){
		if(o[i].getAttribute("type")=="checkbox"){
			if(type==true){
				o[i].checked=true;
			}else{
				o[i].checked=false;
			}
		}
	}
}
//checkbox select
function ckb_select(name,to_id,true_value,false_value){
	var str="";
	var o=document.getElementsByName(name);
	for(i=0;i<o.length;i++){
		if(o[i].getAttribute("type")=="checkbox"){
			if(o[i].checked){
				if(true_value.Trim()==""){
					str+=o[i].value+",";
				}else{
					str+=true_value+",";
				}
			}else{
				if(false_value.Trim()!=""){
					str+=false_value+",";
				}
			}
		}
	}
	if(str.length>1){
		str=str.substr(0,str.length-1);
	}
	document.getElementById(to_id).value=str;
}

function check_do(type,msg,url){
	switch(type){
		case "del":
			if(msg.Trim()==""){
				msg='请确认是否要删除！';
			}
			break;
		default:
			if(msg.Trim()==""){
				msg='请确认！';
			}
			break;
	}
	if(confirm(msg)){
		location.href=url;
	}
}
function dreams_ck_form(ids,form_id,method){
	if(ids.Trim()==""){
		document.getElementById(form_id).submit();
		return false;
	}
	var id_arr=ids.split(",");
	for(i=0;i<id_arr.length;i++){
		var Dreams_Obj=document.getElementById(id_arr[i]);
		//是否为空
		if(Dreams_Obj.getAttribute("notnull")=="true"){			
			if(Dreams_Obj.value.Trim()==""){
				switch(method){
					case "span":
						document.getElementById(id_arr[i]+"_span").innerHTML=Dreams_Obj.getAttribute("msg_name")+"不能为空！";
						break;
					default:
						alert(Dreams_Obj.getAttribute("msg_name")+"不能为空！");
				}
				return false;
			}
		}

		//是否为数字
		if(Dreams_Obj.value.Trim()!="" && Dreams_Obj.getAttribute("isnum")=="true"){
			if(!Dreams_Obj.value.IsNumber()){
				switch(method){
					case "alert":
						alert(Dreams_Obj.getAttribute("msg_name")+"必须为数字！");
						break;
					case "span":
						document.getElementById(id_arr[i]+"_span").innerHTML=Dreams_Obj.getAttribute("msg_name")+"必须为数字！";
						break;
					default:
						alert(Dreams_Obj.getAttribute("msg_name")+"必须为数字！");
				}
				return false;
			}
		}
		//是否为数字
		if(Dreams_Obj.value.Trim()!="" && Dreams_Obj.getAttribute("link")!="" && Dreams_Obj.getAttribute("linktype")!=""){
			var Dreams_Obj2=document.getElementById(Dreams_Obj.getAttribute("link"));
			if(Dreams_Obj.getAttribute("linktype")=="key"){
				if(Dreams_Obj.value==Dreams_Obj2.value){
					alert(Dreams_Obj.getAttribute("msg_name")+"和"+Dreams_Obj2.getAttribute("msg_name")+"不能相同！");
					return false;
				}
			}
		}
	}
	document.getElementById(form_id).submit();
}

function img_check(id,file_type){
	var re_sult=false;
	var file_name = document.getElementById(id).value;
	
	if (file_name == "")
	{
		alert("请选择文件");
	}else{
		var str = file_name;
		var lens = str.length;
		
		extname1 = str.substr(lens-4,lens) ;
		extname2 = str.substr(lens-5,lens) ;		
		
		var file_arr=file_type.split(",");
		for(i=0;i<file_arr.length;i++){
			if(re_sult==false){
				if(extname1 == "."+file_arr[i] || extname2 == "."+file_arr[i]){
					re_sult=true;
				}
			}else{
				break;
			}
		}
		if(re_sult==false)
		{
			alert("文件类型不正确！请上传"+file_type+"类型的文件！");
			document.getElementById('mingju_img_span').innerHTML='<input name="mingju_img" type="file" id="mingju_img" onChange="img_check(\'mingju_img\',\'jpg,gif,jpeg,JPG,GIF,JPEG\');">';

		}else{
			document.getElementById('img_pic').src=document.getElementById('mingju_img').value;
		}
	}
	return re_sult;
}

function dreamsimgsize(img,size) 
{
	if(img.width > size)
	{
		img.width=size;
	}
}

String.prototype.IsDate = function()
{
	var myReg = /^(\d{4})(-|\/|.)(\d{1,2})\2(\d{1,2})$/; 
	var result=this.match(myReg);
    if(result==null) return false;
	var test= new Date(result[1],result[3]-1,result[4]);
	if ((test.getFullYear()==result[1]) && (test.getMonth()+1==result[3]) && (test.getDate()==result[4])){
		ActRs.Clear();
		ActRs[0]=result[1];ActRs[1]=result[3];ActRs[2]=result[4];
		return true;
	}
	else return false;
}

String.prototype.IsTime = function()
{
	var myReg = /^(\d{1,2})(:)(\d{1,2})\2(\d{1,2})$/; 
	var result=this.match(myReg);
    if(result==null) return false;
	var test= new Date(2000,1,1,result[1],result[3],result[4]);
	if ((test.getHours()==result[1]) && (test.getMinutes()==result[3]) && (test.getSeconds()==result[4])){
		ActRs[3]=result[1];ActRs[4]=result[3];ActRs[5]=result[4];
		return true;
	}
	else return false;
}

String.prototype.IsDateTime = function()
{
	var myReg = this.split(" ");
	if(myReg.length!=2) return false;
	if(myReg[0].IsDate() && myReg[1].IsTime()) return true;
	return false;
}

String.prototype.IsEmail = function()
{
	var myReg = /[\u4e00-\u9fa5]/;
	if(!myReg.test(this)){
		myReg = /^[_a-zA-Z0-9][-._a-zA-Z0-9]*@[-._a-zA-Z0-9]+\.[-._a-zA-Z0-9]+(\.[-._a-zA-Z])*$/;
		if (myReg.test(this)) return true;
	}else{
		myReg = /^[_a-zA-Z0-9\u4e00-\u9fa5][-_.a-zA-Z0-9\u4e00-\u9fa5]*@[-._a-zA-Z0-9\u4e00-\u9fa5]+(\.[-._0-9a-zA-Z\u4e00-\u9fa5]+)*$/;
		if (myReg.test(this)) return true;
	}
	return false;
}

String.prototype.IsIdcard = function()
{
    var myReg = /^[1-9][0-9]{14}$|^[1-9][0-9]{16}[0-9a-zA-Z]$/;
	if(myReg.test(this)) return true;
    return false;
}

String.prototype.IsTelephone = function()
{
	myReg = /[(]/;
	if (!myReg.test(this))
	{
		myReg = /^[1-9][0-9]{6,7}$|^[0-9]{3,4}-[1-9][0-9]{6,7}$|^[0-9]{3,4}-[1-9][0-9]{6,7}-[0-9]{2,8}$/;
		if (myReg.test(this)) return true;
	}
	//else {
		//myReg = /^[1-9][0-9]{6,7}$|^([0-9]{3,4})[1-9][0-9]{6,7}$|^([0-9]{3,4})[1-9][0-9]{6,7}-[0-9]{2,8}$/;
		//if (myReg.test(this)) return true;
	//}
	return false;
}

String.prototype.IsNumber = function()
{
	var myReg = /^[0-9]+$/;
	if(!myReg.test(this)) return false;
	ActRd=parseInt(this)	
	return true;
}

String.prototype.IsPhone = function()
{
	if(!this.IsNumber()) return false;
	if(this.length != 11 || this<13000000000 || this>13999999999) return false;
	return true;
}

String.prototype.IsDomain = function()
{
    var myReg = /^[0-9a-zA-Z]+$/;
    if(myReg.test(this)) return true;
    return false;
}


String.prototype.IsEn = function()
{
    var myReg = /^[a-zA-Z]+$/;
    if(myReg.test(this)) return true;
    return false;
}

String.prototype.IsCn = function()
{
    var ch,temp,isCN,isTrue;
    isTrue = true;
    for(var i=0;i<this.length;i++)
    {
        ch = this.substring(i,i+1);
        temp = escape(ch);
        isCN = (temp.length == 6)? true:false;
        if(!isCN)
        {
            isTrue = false;
            break;
        }
    }
    return isTrue;
}

String.prototype.Trim = function()
{
	var tmp = this.replace(/(^\s*)|(\s*$)/g, "");
	return tmp.replace(/(^\2005-9-28*)|(\　*$)/g,"");
}