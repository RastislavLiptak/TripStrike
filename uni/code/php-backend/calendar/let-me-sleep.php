<?php
  function letMeSleep($y, $m, $d, $sts) {
    $currH = date('H');
    if ($y == date("Y") && $m == date("m") && $d == date("d")) {
      $sts = "unavailable";
    } else if ($y == calcNextYearByDays(date("d"), date("m"), date("Y"), 1) && $m == calcNextMonthByDays(date("d"), date("m"), date("Y"), 1) && $d == calcNextDay(date("d"), date("m"), date("Y"), 1)) {
      if ($currH >= 10 && $currH < 16) {
        if ($sts == "limited-secondhalf" || $sts == "unavailable") {
          $sts = "unavailable";
        } else {
          $sts = "limited-firsthalf";
        }
      } else if ($currH >= 16) {
        $sts = "unavailable";
      }
    }
    return $sts;
  }
?>
