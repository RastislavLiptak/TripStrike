<?php
  // https://gist.github.com/AlexPashley/5861213
  function convertLinkInString($string) {
    $string = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $string);
  	$ret = ' ' . $string;
  	// Replace Links with http://
  	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\" rel=\"noopener noreferrer\">\\2</a>", $ret);
  	// Replace Links without http://
  	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\" rel=\"noopener noreferrer\">\\2</a>", $ret);
  	// Replace Email Addresses
  	$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a target=\"_blank\" href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
  	$ret = substr($ret, 1);
  	return $ret;
  }
?>
