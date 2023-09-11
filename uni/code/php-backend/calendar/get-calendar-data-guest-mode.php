<?php
  include "../data.php";
  include "../get-frontend-id.php";
  include "../get-account-data.php";
  include "calc-date.php";
  include "let-me-sleep.php";
  include "calendar-verification-function.php";
  $id = $_POST['id'];
  $m = $_POST['month'];
  $y = $_POST['year'];
  $output = [];
  $calendar_verification = calendarVerification($id, "guest");
  if ($calendar_verification == "good") {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $y);
    $sqlPlcIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$id' LIMIT 1");
    if ($sqlPlcIdToBeId->num_rows > 0) {
      $plcBeId = $sqlPlcIdToBeId->fetch_assoc()["beid"];
      for ($d = 1; $d <= $daysInMonth; $d++) {
        $bookingReservedSts = false;
        $bookingReservedFirstdaySts = false;
        $bookingReservedLastdaySts = false;
        if (new DateTime($y."-".$m."-".$d) > new DateTime() || ($y == date("Y") && $m == date("m") && $d == date("d"))) {
          $sqlBookingDates = $link->query("SELECT beid FROM bookingdates WHERE plcbeid='$plcBeId' and (status='booked' or status='waiting') and year='$y' and month='$m' and day='$d'");
          if ($sqlBookingDates->num_rows > 0) {
            if ($sqlBookingDates->num_rows == 1) {
              $bookingBeId = $sqlBookingDates->fetch_assoc()["beid"];
              $sqlBooking = $link->query("SELECT fromy, fromm, fromd, firstday, toy, tom, tod, lastday FROM booking WHERE beid='$bookingBeId' LIMIT 1");
              if ($sqlBooking->num_rows > 0) {
                $bookingRow = $sqlBooking->fetch_assoc();
                if ($y == $bookingRow['fromy'] && $m == $bookingRow['fromm'] && $d == $bookingRow['fromd']) {
                  if ($bookingRow['firstday'] == "whole") {
                    pushDateToArray($y, $m, $d, "unavailable", "no");
                    $bookingReservedSts = true;
                  } else {
                    $bookingReservedFirstdaySts = true;
                    $bookingReservedSts = true;
                  }
                } elseif ($y == $bookingRow['toy'] && $m == $bookingRow['tom'] && $d == $bookingRow['tod']) {
                  if ($bookingRow['lastday'] == "whole") {
                    pushDateToArray($y, $m, $d, "unavailable", "no");
                    $bookingReservedSts = true;
                  } else {
                    $bookingReservedLastdaySts = true;
                    $bookingReservedSts = true;
                  }
                } else {
                  pushDateToArray($y, $m, $d, "unavailable", "no");
                  $bookingReservedSts = true;
                }
              } else {
                echo "Data about booking not found in database (".$d."/".$m."/".$y.")<br>";
              }
            } else {
              pushDateToArray($y, $m, $d, "unavailable", "yes");
              $bookingReservedSts = true;
            }
          }
          $sqlTechnicalShutdownDates = $link->query("SELECT beid FROM technicalshutdowndates WHERE plcbeid='$plcBeId' and status='active' and year='$y' and month='$m' and day='$d'");
          if ($sqlTechnicalShutdownDates->num_rows > 0) {
            if ($sqlTechnicalShutdownDates->num_rows == 1) {
              $technicalShutdownBeId = $sqlTechnicalShutdownDates->fetch_assoc()["beid"];
              $sqlTechnicalShutdown = $link->query("SELECT fromy, fromm, fromd, firstday, toy, tom, tod, lastday FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
              if ($sqlTechnicalShutdown->num_rows > 0) {
                $technicalShutdownRow = $sqlTechnicalShutdown->fetch_assoc();
                if ($y == $technicalShutdownRow['fromy'] && $m == $technicalShutdownRow['fromm'] && $d == $technicalShutdownRow['fromd']) {
                  if ($technicalShutdownRow['firstday'] == "whole") {
                    pushDateToArray($y, $m, $d, "unavailable", "no");
                  } else {
                    if ($bookingReservedLastdaySts) {
                      pushDateToArray($y, $m, $d, "unavailable", "yes");
                    } else {
                      pushDateToArray($y, $m, $d, "limited-secondhalf", "no");
                    }
                  }
                } elseif ($y == $technicalShutdownRow['toy'] && $m == $technicalShutdownRow['tom'] && $d == $technicalShutdownRow['tod']) {
                  if ($technicalShutdownRow['lastday'] == "whole") {
                    pushDateToArray($y, $m, $d, "unavailable", "no");
                  } else {
                    if ($bookingReservedFirstdaySts) {
                      pushDateToArray($y, $m, $d, "unavailable", "yes");
                    } else {
                      pushDateToArray($y, $m, $d, "limited-firsthalf", "no");
                    }
                  }
                } else {
                  pushDateToArray($y, $m, $d, "unavailable", "no");
                }
              } else {
                echo "Data about technical shutdown not found in database (".$d."/".$m."/".$y.")<br>";
              }
            } else {
              pushDateToArray($y, $m, $d, "unavailable", "yes");
            }
          } else {
            if (!$bookingReservedSts) {
              pushDateToArray($y, $m, $d, "available", "no");
            } else {
              if ($bookingReservedFirstdaySts) {
                pushDateToArray($y, $m, $d, "limited-secondhalf", "no");
              }
              if ($bookingReservedLastdaySts) {
                pushDateToArray($y, $m, $d, "limited-firsthalf", "no");
              }
            }
          }
        } else {
          pushDateToArray($y, $m, $d, "unavailable", "no");
        }
      }
      returnOutput();
    } else {
      echo "ID verification faild <br>".mysqli_error($link);
    }
  } else {
    echo $calendar_verification;
  }

  function pushDateToArray($y, $m, $d, $sts, $busyDay) {
    global $output, $link, $plcBeId;
    $sts = letMeSleep($y, $m, $d, $sts);
    $sqlPlace = $link->query("SELECT operation, operationFrom, operationTo FROM places WHERE beid='$plcBeId' and status='active' LIMIT 1");
    $plcRow = $sqlPlace->fetch_assoc();
    $operation = $plcRow['operation'];
    if ($operation == "summer") {
      if ($m < $plcRow['operationFrom'] || $m > $plcRow['operationTo']) {
        $sts = "unavailable";
      }
    } else if ($operation == "winter") {
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
      if (!in_array($m, $winterOperationMonthsList)) {
        $sts = "unavailable";
      }
    }
    array_push($output, [
      "type" => "date",
      "y" => $y,
      "m" => $m,
      "d" => $d,
      "sts" => $sts,
      "busy" => $busyDay
    ]);
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
