<?php
/***************************************************************************
 *   copyright            :(C) 2005 by DREAMS	朱宗鑫                     *
 ***************************************************************************/
function m_date($pass_time){
	$minute=60;
	$hour=$minute*60; 
	$day=$hour*24;
	$days=floor($pass_time /$day);
	$hours=floor(($pass_time%$day)/$hour);
	$minutes=floor(($pass_time%$hour)/$minute);
	$str=($days>0 ? $days."天" : "").($hours>0 ?  $hours."时" : "").($minutes>0 ? $minutes."分" : "");
	return $str;
}
function pagination($curpage,$totalcount,$pcount,$ppage,$callback,$null_hiden=false,$select_hiden=true){
	if($totalcount <= 0 && $null_hiden===true){
		return "";
	}
	$total_page = (int)ceil($totalcount / $pcount);
	$con = $pcount."条/页&nbsp;|&nbsp;";
	if ($curpage > 1) {
		$con .= "<a href=\"javascript:$callback(1)\">首页</a>&nbsp;|&nbsp;<a href=\"javascript:$callback(".($curpage - 1).")\">上一页</a>&nbsp;|&nbsp;";
	}else{
		$con .= "首页&nbsp;|&nbsp;上一页</a>&nbsp;|&nbsp;";
	}
	if ($curpage < $total_page){
		$con .= "<a href=\"javascript:$callback(".($curpage + 1).")\">下一页</a>&nbsp;|&nbsp;<a href=\"javascript:$callback(".($total_page).")\">末页</a>&nbsp;|";
	}else{
		$con .= "下一页&nbsp;|&nbsp;末页&nbsp;|";
	}
	if($select_hiden===true){
		$con .= "&nbsp;页码：<select style=\"height:18px\" onChange=\"$callback(this.value)\">";
		for ($i = 1; $i <= $total_page; $i ++ ){
			if ($i == $curpage){
				$con .= "<option value=\"$i\" selected>$i/$total_page</option>";
			}else {
				$con .= "<option value=\"$i\">$i/$total_page</option>";
			}
		}

		$con .= "</select>";
	}
	return $con;
}

function guid(){
   if (function_exists('com_create_guid')){
       return com_create_guid();
   }
   mt_srand((double)microtime()*10000);
   $charid = strtoupper(md5(uniqid(rand(), true)));
   $hyphen = chr(45);
   $uuid = chr(123)
           .substr($charid, 0, 8).$hyphen
           .substr($charid, 8, 4).$hyphen
           .substr($charid,12, 4).$hyphen
           .substr($charid,16, 4).$hyphen
           .substr($charid,20,12)
           .chr(125);
   return $uuid;
}

function upload_image($root='',$filename=''){
	if($root==""){
		$root = $_SERVER["DOCUMENT_ROOT"];
	}
	$uploaddir = "";
	$uploadfile = "";
	$uploaddir = "$root/upload/";
	$uploadfile = $_FILES['mingju_img']['name'];
	if($uploadfile==""){
		return $filename;
	}
	if($filename==""){
		$filename = guid();
		$filename = substr($filename,10,strlen($filename)-12).substr($uploadfile,strrpos($uploadfile,"."));
	}
	if (move_uploaded_file($_FILES['mingju_img']['tmp_name'], $uploaddir.$filename)){
		resize_image($uploaddir.$filename,$uploaddir."short_".$filename,140,140);
		return $filename;
	}
	else {
		return false;
	}
}

function resize_image($src_name,$dest_name,$w,$h){
	$s_w=0;
	$s_h=0;
	$cut_width=0;
	$cut_height=0;

	$filename = $src_name;
	list($width, $height) = getimagesize($filename);

	//图片长宽均小于规定大小 则不变
	if ($width<=$w&&$height<=$h) {
		copy($src_name,$dest_name);
		return false;
	}

	//如果宽：长比例大于1.1
	if ($width>$height*1.1) {
		$s_w=ceil(($width-$height*1.1)/2);
		$s_h=0;
		$width=$height*1.1;
	}

	//图片长小于规定大小，宽大于规定大小，左右截取
	if ($width>$w&&$height<=$h) {
		$cut_width = $w;
		$cut_height = floor($height*$w/$width);
	}

	//图片宽度小于规定大小，长度大于规定大小，上下截取
	if ($width<=$w&&$height>$h) {
		$cut_width = $width;
		$cut_height = $h;
		$s_h = ceil(($height-$h)/2);    
	}

	//图片宽度长度均大于规定大小，按宽度缩放，缩放之后，如果长度仍超过最大长度，上下截取
	if ($width>$w&&$height>$h) {
	$cut_width = $w;
	$cut_height = floor($height*$w/$width);
		if ($cut_height>$h) { 
			$s_h = ceil(($cut_height-$h)/2);    
			$cur_height = $h;
		} 
	}

	$thumb = imagecreatetruecolor($cut_width, $cut_height);
	$name_main = substr($src_name,0,strrpos($src_name,"."));
	$ext_name = substr($src_name,strrpos($src_name,".")+1);
	if (strtolower($ext_name) == "jpg" || strtolower($ext_name) == "jpeg"){
		$source = imagecreatefromjpeg($filename);
		imagecopyresized($thumb, $source, 0, 0, $s_w, $s_h, $cut_width, $cut_height, $width, $height);
		imagejpeg($thumb,$dest_name,100);
		return true;
	} elseif (strtolower($ext_name) == "gif") {
		$source = imagecreatefromgif($filename);
		imagecopyresized($thumb, $source, 0, 0, $s_w, $s_h, $cut_width, $cut_height, $width, $height);
		imagegif($thumb,$dest_name,100);
		return true;
	}
	else {
		return false;
	}
}

function escape($str,$encode="UTF-8") {
  if ($encode=="" && !(function_exists("mb_detect_encoding"))) {
      echo "error You must enter the string's encoding or extend the php for mb_string";
   return ;
  }
  elseif($encode=="") {
   echo "Use mb_string function to detect the string's encoding <br/>";
      $encode = mb_detect_encoding($str);
  }
  preg_match_all("/[\xC0-\xE0].|[\xE0-\xF0]..|[\x01-\x7f]+/",$str,$r);
  //prt($r);
  $ar = $r[0];
  foreach($ar as $k=>$v) {
 $ord = ord($v[0]);
    if( $ord<=0x7F)
      $ar[$k] = rawurlencode($v);
    elseif ($ord<0xE0) {
      $ar[$k] = "%u".bin2hex(iconv($encode,"UCS-2",$v));
    }
  elseif ($ord<0xF0) {
      $ar[$k] = "%u".bin2hex(iconv($encode,"UCS-2",$v));
 }
  }//foreach
  return join("",$ar);
}

function unescape($str) { //返回结果编码为UTF-8
  $str = rawurldecode($str);
  preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U",$str,$r);
  $ar = $r[0];
  foreach($ar as $k=>$v) {
    if(substr($v,0,2) == "%u")
      $ar[$k] = mb_convert_encoding(pack("H4",substr($v,-4)), "UTF-8", "UCS-2");
    elseif(substr($v,0,3) == "&#x")
      $ar[$k] = mb_convert_encoding(pack("H4",substr($v,3,-1)),"UTF-8", "UCS-2");
    elseif(substr($v,0,2) == "&#") {
      $ar[$k] = mb_convert_encoding(pack("n",substr($v,2,-1)), "UTF-8", "UCS-2");
    }
  }
  return join("",$ar);
}

function cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
{
	if($code == 'UTF-8')
	{
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $string, $t_string);

		if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."...";
		return join('', array_slice($t_string[0], $start, $sublen));
	}
	else
	{
		$start = $start*2;
		$sublen = $sublen*2;
		$strlen = strlen($string);
		$tmpstr = '';
		for($i=0; $i<$strlen; $i++)
		{
			if($i>=$start && $i<($start+$sublen))
			{
				if(ord(substr($string, $i, 1))>129) {
					$tmpstr.= substr($string, $i, 2);
				}else {
					$tmpstr.= substr($string, $i, 1);
				}
			} 
			if(ord(substr($string, $i, 1))>129) $i++;
		}
		if(strlen($tmpstr)<$strlen ) $tmpstr.= "...";
		return $tmpstr;
	}
}

function http_get($str,$type=""){
	$restr="";
	if(isset($_GET[$str]) && ($type=="" || $type=="get")){
		$restr=$_GET[$str];
	}
	if(isset($_POST[$str]) && ($type=="" || $type=="post")){
		$restr=$_POST[$str];
	}
	return trim($restr);
}
?>
