<?php
  function calcNextDay($d, $m, $y, $plusD) {
    $dayCalcDone = false;
    while (!$dayCalcDone) {
      $nextD = $d + $plusD;
      $dInM = cal_days_in_month(CAL_GREGORIAN, $m, $y);
      if ($nextD > $dInM) {
        $plusD = $plusD - ($dInM - $d + 1);
        $d = 1;
        $m = calcNextMonth($m, 1);
        if ($m == 1) {
          ++$y;
        }
      } else {
        $d = $nextD;
        $dayCalcDone = true;
      }
    }
    return $d;
  }

  function calcPreviousDay($d, $m, $y, $minusD) {
    $dayCalcDone = false;
    while (!$dayCalcDone) {
      $prevD = $d - $minusD;
      if ($prevD <= 0) {
        $minusD = $minusD - $d;
        $m = calcPreviousMonth($m, 1);
        if ($m == 12) {
          --$y;
        }
        $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);
      } else {
        $d = $prevD;
        $dayCalcDone = true;
      }
    }
    return $d;
  }

  function calcNextMonth($m, $plusM) {
    $monthsLeft = 12 - $m;
    if ($plusM > $monthsLeft) {
      $m = $plusM - $monthsLeft;
      while ($m > 12) {
        $m = $m - 12;
      }
    } else {
      $m = $m + $plusM;
    }
    return $m;
  }

  function calcNextMonthByDays($d, $m, $y, $plusD) {
    $dayCalcDone = false;
    while (!$dayCalcDone) {
      $nextD = $d + $plusD;
      $dInM = cal_days_in_month(CAL_GREGORIAN, $m, $y);
      if ($nextD > $dInM) {
        $plusD = $plusD - ($dInM - $d + 1);
        $d = 1;
        $m = calcNextMonth($m, 1);
        if ($m == 1) {
          ++$y;
        }
      } else {
        $d = $nextD;
        $dayCalcDone = true;
      }
    }
    return $m;
  }

  function calcPreviousMonth($m, $minusM) {
    if ($m <= $minusM) {
      $x = $m - $minusM;
      if ($x != 0) {
        $calcDone = false;
        while (!$calcDone) {
          $x = abs($x);
          $x = 12 - $x;
          if ($x > 0) {
            $m = $x;
            $calcDone = true;
          } else if ($x == 0){
            $m = 12;
            $calcDone = true;
          }
        }
      } else {
        $m = 12;
      }
    } else {
      $m = $m - $minusM;
    }
    return $m;
  }

  function calcPreviousMonthByDays($d, $m, $y, $minusD) {
    $dayCalcDone = false;
    while (!$dayCalcDone) {
      $prevD = $d - $minusD;
      if ($prevD <= 0) {
        $minusD = $minusD - $d;
        $m = calcPreviousMonth($m, 1);
        if ($m == 12) {
          --$y;
        }
        $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);
      } else {
        $d = $prevD;
        $dayCalcDone = true;
      }
    }
    return $m;
  }

  function calcNextYearByMonth($m, $y, $plusM) {
    $m = $m + $plusM;
    $plusY = floor($m / 12);
    if ($m > 12) {
      $y = $y + $plusY;
    }
    return $y;
  }

  function calcNextYearByDays($d, $m, $y, $plusD) {
    $dayCalcDone = false;
    while (!$dayCalcDone) {
      $nextD = $d + $plusD;
      $dInM = cal_days_in_month(CAL_GREGORIAN, $m, $y);
      if ($nextD > $dInM) {
        $plusD = $plusD - ($dInM - $d + 1);
        $d = 1;
        $m = calcNextMonth($m, 1);
        if ($m == 1) {
          ++$y;
        }
      } else {
        $d = $nextD;
        $dayCalcDone = true;
      }
    }
    return $y;
  }

  function calcPreviousYearByMonth($m, $y, $minusM) {
    if ($m <= $minusM) {
      $diff = ($minusM - $m) / 12;
      if (is_numeric($diff) && floor($diff) != $diff) {
        $zeroY = 0;
      } else {
        $zeroY = 1;
      }
      $y = $y - ceil($diff) - $zeroY;
    }
    return $y;
  }

  function calcPreviousYearByDays($d, $m, $y, $minusD) {
    $dayCalcDone = false;
    while (!$dayCalcDone) {
      $prevD = $d - $minusD;
      if ($prevD <= 0) {
        $minusD = $minusD - $d;
        $m = calcPreviousMonth($m, 1);
        if ($m == 12) {
          --$y;
        }
        $d = cal_days_in_month(CAL_GREGORIAN, $m, $y);
      } else {
        $d = $prevD;
        $dayCalcDone = true;
      }
    }
    return $y;
  }
?>
