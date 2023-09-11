<?php
  if (!function_exists('totalPriceCalc')) {
    function totalPriceCalc($beId, $numOfGuests, $from_y, $from_m, $from_d, $firstDay, $to_y, $to_m, $to_d, $lastDay) {
      global $link;
      $num_work_days = 0;
      $num_week_days = 0;
      $check_y = $from_y;
      $check_m = $from_m;
      $check_d = $from_d;
      $termCalcDone = false;
      while (!$termCalcDone) {
        if ($check_y == $to_y && $check_m == $to_m && $check_d == $to_d) {
          $termCalcDone = true;
        } else {
          if (date("w", strtotime($check_y."-".$check_m."-".$check_d)) <= 4) {
            ++$num_work_days;
          } else {
            ++$num_week_days;
          }
          ++$check_d;
          if ($check_d > cal_days_in_month(CAL_GREGORIAN, $check_m, $check_y)) {
            $check_d = 1;
            ++$check_m;
            if ($check_m > 12) {
              $check_m = 1;
              ++$check_y;
            }
          }
        }
      }
      if ($firstDay == "whole") {
        if (date("w", mktime(0, 0, 0, $from_m, $from_d, $from_y)) == 0 || date("w", mktime(0, 0, 0, $from_m, $from_d, $from_y)) == 6) {
          ++$num_week_days;
        } else {
          ++$num_work_days;
        }
      }
      if ($lastDay == "whole") {
        if (date("w", mktime(0, 0, 0, $to_m, $to_d, $to_y)) == 5 || date("w", mktime(0, 0, 0, $to_m, $to_d, $to_y)) == 6) {
          ++$num_week_days;
        } else {
          ++$num_work_days;
        }
      }
      $sqlPlc = $link->query("SELECT workDayPrice, weekDayPrice, priceMode FROM places WHERE beid='$beId' LIMIT 1");
      $rowPlc = $sqlPlc->fetch_assoc();
      if ($rowPlc['priceMode'] == "guests") {
        return ($rowPlc['workDayPrice'] * $num_work_days + $rowPlc['weekDayPrice'] * $num_week_days) * $numOfGuests;
      } else {
        return $rowPlc['workDayPrice'] * $num_work_days + $rowPlc['weekDayPrice'] * $num_week_days;
      }
    }
  }
?>
