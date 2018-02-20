<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/

function tpl_modifier_unescape($str) { //返回结果编码为UTF-8
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
?>