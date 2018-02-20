<?php
/***************************************************************************
 *   HOME		          : lizhen520.com			                       *
 *   copyright            :(C) 2005 by DREAMS		                       *
 ***************************************************************************/
function tpl_modifier_escape($str,$encode="UTF-8"){
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
?>