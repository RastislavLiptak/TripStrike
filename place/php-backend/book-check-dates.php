<?php
  function checkDates($beId, $from_y, $from_m, $from_d, $firstDay, $to_y, $to_m, $to_d, $lastDay) {
    global $link;
    $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$beId' and status='active' LIMIT 1");
    if ($sqlPlace->num_rows > 0) {
      $plcRow = $sqlPlace->fetch_assoc();
      if ($from_y."-".$from_m."-".$from_d != $to_y."-".$to_m."-".$to_d) {
        if (new DateTime($from_y."-".$from_m."-".$from_d) < new DateTime($to_y."-".$to_m."-".$to_d)) {
          $allChecked = false;
          $check_y = $from_y;
          $check_m = $from_m;
          $check_d = $from_d;
          $availableStatus = "";
          while (!$allChecked) {
            if ($plcRow['operation'] == "summer") {
              if ($check_m < $plcRow['operationFrom'] || $check_m > $plcRow['operationTo']) {
                $operationBlocking = true;
              } else {
                $operationBlocking = false;
              }
            } else if ($plcRow['operation'] == "winter") {
              $winterOperationMonthsList = [];
              $winterUnavailableReady = false;
              $winterOperationFrom = $plcRow['operationFrom'];
              while (!$winterUnavailableReady) {
                array_push($winterOperationMonthsList, $winterOperationFrom);
                if ($winterOperationFrom == 4) {
                  $winterOperationFrom = 9;
                } else if ($winterOperationFrom == 12) {
                  $winterOperationFrom = 1;
                } else {
                  ++$winterOperationFrom;
                }
                if ($winterOperationFrom == $plcRow['operationTo']) {
                  $winterUnavailableReady = true;
                }
              }
              if (!in_array($check_m, $winterOperationMonthsList)) {
                $operationBlocking = true;
              } else {
                $operationBlocking = false;
              }
            } else {
              $operationBlocking = false;
            }
            if (!$operationBlocking) {
              if (strtotime($check_y."-".$check_m."-".$check_d) > strtotime('now') || $check_y == date("Y") && $check_m == date("m") && $check_d == date("d")) {
                if ($firstDay == "half") {
                  $firstDayNeededSts = "limited-firsthalf";
                  $firstDaySts = letMeSleep($from_y, $from_m, $from_d, $firstDayNeededSts);
                } else {
                  $firstDayNeededSts = "available";
                  $firstDaySts = letMeSleep($from_y, $from_m, $from_d, $firstDayNeededSts);
                }
                if ($firstDay == "half") {
                  $lastDayNeededSts = "limited-secondhalf";
                  $lastDaySts = letMeSleep($to_y, $to_m, $to_d, $lastDayNeededSts);
                } else {
                  $lastDayNeededSts = "available";
                  $lastDaySts = letMeSleep($to_y, $to_m, $to_d, $lastDayNeededSts);
                }
                if ($firstDayNeededSts == $firstDaySts && $lastDayNeededSts == $lastDaySts) {
                  $sqlbook = $link->query("SELECT * FROM bookingdates WHERE plcbeid='$beId' and status='booked' and year='$check_y' and month='$check_m' and day='$check_d'");
                  if ($sqlbook->num_rows > 0) {
                    if (($check_y == $from_y && $check_m == $from_m && $check_d == $from_d) || ($check_y == $to_y && $check_m == $to_m && $check_d == $to_d)) {
                      if ($sqlbook->num_rows == 1) {
                        $sqlbookBeId = $link->query("SELECT beid FROM bookingdates WHERE plcbeid='$beId' and status='booked' and year='$check_y' and month='$check_m' and day='$check_d'");
                        $bookingBeID = $sqlbookBeId->fetch_assoc()['beid'];
                        $sqlbookFrom = $link->query("SELECT * FROM booking WHERE beid='$bookingBeID' and fromy='$check_y' and fromm='$check_m' and fromd='$check_d' and firstday='whole'");
                        if ($sqlbookFrom->num_rows > 0) {
                          $availableStatus = "unavailable";
                        } else {
                          $sqlbookTo = $link->query("SELECT * FROM booking WHERE beid='$bookingBeID' and toy='$check_y' and tom='$check_m' and tod='$check_d' and lastday='whole'");
                          if ($sqlbookTo->num_rows > 0) {
                            $availableStatus = "unavailable";
                          }
                        }
                      } else {
                        $availableStatus = "unavailable";
                      }
                    } else {
                      $availableStatus = "unavailable";
                    }
                  }
                  if ($check_y == $to_y && $check_m == $to_m && $check_d == $to_d) {
                    $availableStatus = "good";
                  }
                  if ($availableStatus != "") {
                    $allChecked = true;
                  } else {
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
                } else {
                  $availableStatus = "unavailable";
                  $allChecked = true;
                }
              } else {
                $availableStatus = "past";
                $allChecked = true;
              }
            } else {
              $availableStatus = "unavailable";
              $allChecked = true;
            }
          }
        } else {
          $availableStatus = "dates-order";
        }
      } else {
        $availableStatus = "dates-same";
      }
    } else {
      $availableStatus = "failed-to-get-data-about-place";
    }
    return $availableStatus;
  }
?>
