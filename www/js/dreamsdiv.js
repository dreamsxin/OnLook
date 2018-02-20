/****************************
时间	2005-8-20 
email	dreamsxin@126.com
作者	紫痕
主页	http://lizhen520.com
功能	tooltip sound
****************************/
document.write("<style type='text/css' id='defaultPopStyle'>");
document.write(".cPopText {");
document.write("	color:#030303;");
document.write("	border: 1px solid #000000;");
document.write("	padding-right: 4px;");
document.write("	padding-left: 4px;");
document.write("	padding-top: 3px;");
document.write("	padding-bottom: 1px;");
document.write("	background-color: #FFFFFF;");
document.write("}");
document.write("</style>");
document.write('<iframe id="dreams_tool" style="position:absolute;z-index:7998;display:none;height:20px;width:200px;" frameborder=\"0\" ></iframe>');
document.write('<div id="dreams_tool_tip" style="position:absolute;z-index:7999;display:none;height:auto;width:200px;font-size:12px;" class="cPopText"></div>');
document.write('<div id="sound_box"></div>');

var isIE = (document.all)?1:0;

var tPopWait=0;
var tPopShow=4000;
var showPopStep=20;
var popOpacity=99;

//***************内部变量定义*****************
var sPop=null;
var curShow=null;
var tFadeOut=null;
var tFadeIn=null;
var tFadeWaiting=null;

var MouseX=0;MouseY=0;
var scrollY=0;scrollX=0;
var cliH=0;cliW=0;
var popLeft=0;popTop=0;
var popWidth=0;popHeight=0;

function showPopupText(dreamsevent){
	document.getElementById("dreams_tool_tip").style.display = "none";
	if(dreamsevent == null){
		dreamsevent=window.event;
	}
	var o=dreamsevent.srcElement?dreamsevent.srcElement:dreamsevent.target;
	MouseX=dreamsevent.x?dreamsevent.x:dreamsevent.pageX;
	MouseY=dreamsevent.y?dreamsevent.y:dreamsevent.pageY;

	if(o.alt!=null && o.alt!=""){
		o.dypop=o.alt;
		o.alt="";
	};
    if(o.title!=null && o.title!=""){
		o.dypop=o.title;
		o.title="";
	};

	if(o.dypop!=sPop) {
			sPop=o.dypop;
			if(sPop==null || sPop=="") {
				document.getElementById("dreams_tool_tip").innerHTML="";
				document.getElementById("dreams_tool_tip").style.display = "none";
				document.getElementById("dreams_tool").style.display = "none";
			}else{
				showIt();
			}
	}else{
			if(sPop==null || sPop=="") {
				document.getElementById("dreams_tool_tip").innerHTML="";
				document.getElementById("dreams_tool_tip").style.display = "none";
				document.getElementById("dreams_tool").style.display = "none";
			}else{
				showIt();
			}
	}
}

function showIt(){
		//兼容滚动条
		scrollY = document.body.scrollTop;
		scrollX = document.body.scrollLeft;


		if(self.pageYOffset) {
			scrollX = self.pageXOffset;
			scrollY = self.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){
			scrollX = document.documentElement.scrollLeft;
			scrollY = document.documentElement.scrollTop; 
		} else if (document.body) {
			scrollX = document.body.scrollLeft;
			scrollY = document.body.scrollTop;
		}

		if(isIE){
			cliH = document.documentElement.offsetHeight;
			cliW = document.documentElement.offsetWidth;
		}else{
			cliH = document.body.offsetHeight;
			cliW = document.body.offsetWidth;
		}
		document.getElementById("dreams_tool_tip").style.display = "";
		document.getElementById("dreams_tool").style.display = "";

		document.getElementById("dreams_tool_tip").innerHTML=sPop;
		popWidth=document.getElementById("dreams_tool_tip").offsetWidth;
		popHeight=document.getElementById("dreams_tool_tip").offsetHeight;

		document.getElementById("dreams_tool").style.width = popWidth+"px";
		document.getElementById("dreams_tool").style.height = popHeight+"px";	

		if(MouseX+20+12+popWidth > cliW){
			popLeft=-popWidth-12;
		}else{
			popLeft=12;
		}
		if(MouseY+20+12+popHeight>cliH){
			popTop=-popHeight-12;
		}else{
			popTop=12;
		}

		var moveleft=0;
		if(isIE){
			document.getElementById("dreams_tool_tip").style.left=MouseX+scrollX+popLeft+moveleft+"px";
			document.getElementById("dreams_tool_tip").style.top=MouseY+scrollY+popTop+"px";
			document.getElementById("dreams_tool").style.left=MouseX+scrollX+popLeft+moveleft+"px";
			document.getElementById("dreams_tool").style.top=MouseY+scrollY+popTop+"px";
		}else{
			document.getElementById("dreams_tool_tip").style.left=MouseX+popLeft+moveleft+"px";
			document.getElementById("dreams_tool_tip").style.top=MouseY+popTop+"px";
			document.getElementById("dreams_tool").style.left=MouseX+popLeft+moveleft+"px";
			document.getElementById("dreams_tool").style.top=MouseY+popTop+"px";
		}
}

//播放sound
function sound_toplay(url,num){
	if(isIE){
		document.getElementById("sound_box").innerHTML='<object id="wmsound" width="0" height="0" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715"  type="application/x-oleobject"><param name="FileName" value="'+url +'"><param name="AutoStart" value="1"><param name="loop" value="false"><param name="PlayCount" value="'+num+'"></object>';
	}else{
		document.getElementById("sound_box").innerHTML='<embed src="'+url+'" type="application/x-mplayer2" width="0" height="0" autostart="true" loop="false" PlayCount="'+num+'"></embed>';
	}
}

if(isIE){
	document.onmouseover=showPopupText;
}else{
	window.onmousemove=showPopupText;
}
