<?php
  function numForm($num) {
    global $wrd_thousand_short, $wrd_million_short;
    if ($num < 1000) {
      return $num;
    } else if ($num < 1000000) {
      return floor($num / 1000).$wrd_thousand_short;
    } else if ($num < 1000000000) {
      return floor($num / 1000000).$wrd_million_short;
    } else {
      return $num;
    }
  }
?>
