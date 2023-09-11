<?php
  function convertMonthNumToText($fold, $lang, $monthNum) {
    include $fold."uni/dictionary/langs/".$lang.".php";
    if ($monthNum == 1) {
      return $wrd_january;
    } else if ($monthNum == 2) {
      return $wrd_february;
    } else if ($monthNum == 3) {
      return $wrd_march;
    } else if ($monthNum == 4) {
      return $wrd_april;
    } else if ($monthNum == 5) {
      return $wrd_may;
    } else if ($monthNum == 6) {
      return $wrd_june;
    } else if ($monthNum == 7) {
      return $wrd_july;
    } else if ($monthNum == 8) {
      return $wrd_august;
    } else if ($monthNum == 9) {
      return $wrd_september;
    } else if ($monthNum == 10) {
      return $wrd_october;
    } else if ($monthNum == 11) {
      return $wrd_november;
    } else if ($monthNum == 12) {
      return $wrd_december;
    }
  }
?>
