<?php
  function transformNum($num) {
    global $wrd_shortmillion, $wrd_shortthousand;
    if ($num >= 1000000) {
      $num = $num / 1000000;
      if ($num >= 100) {
        $num = floor($num);
      } else {
        $num = floor($num * 10) / 10;
      }
      $num = $num."".$wrd_shortmillion;
    } else if ($num >= 1000) {
      $num = $num / 1000;
      if ($num >= 100) {
        $num = floor($num);
      } else {
        $num = floor($num * 10) / 10;
      }
      $num = $num."".$wrd_shortthousand;
    }
    return $num;
  }
?>
