<?php
  include "../data.php";
  include "../get-frontend-id.php";
  include "../get-account-data.php";
  include "calendar-verification-function.php";
  $id = $_POST['id'];
  $m = $_POST['month'];
  $y = $_POST['year'];
  $output = [];
  $calendar_verification = calendarVerification($id, "host");
  if ($calendar_verification == "good") {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $y);
    $sqlPlcIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$id' LIMIT 1");
    if ($sqlPlcIdToBeId->num_rows > 0) {
      $plcBeId = $sqlPlcIdToBeId->fetch_assoc()["beid"];
      for ($d = 1; $d <= $daysInMonth; $d++) {
        $bookingReservedSts = false;
        $bookingReservedFirstdaySts = false;
        $bookingReservedLastdaySts = false;
        $bookingReservedPaymentSts = "none";
        $sqlBookingDates = $link->query("SELECT beid FROM bookingdates WHERE plcbeid='$plcBeId' and (status='booked' or status='waiting') and year='$y' and month='$m' and day='$d' LIMIT 2");
        if ($sqlBookingDates->num_rows > 0) {
          if ($sqlBookingDates->num_rows == 1) {
            $bookingBeId = $sqlBookingDates->fetch_assoc()["beid"];
            $sqlBooking = $link->query("SELECT fromy, fromm, fromd, firstday, toy, tom, tod, lastday, deposit, fullAmount FROM booking WHERE beid='$bookingBeId' LIMIT 1");
            if ($sqlBooking->num_rows > 0) {
              $bookingRow = $sqlBooking->fetch_assoc();
              if ($bookingRow['fullAmount'] == "1") {
                $paymentSts = "fullAmount";
              } else if ($bookingRow['deposit'] == "1") {
                $paymentSts = "deposit";
              } else {
                $paymentSts = "none";
              }
              if ($y == $bookingRow['fromy'] && $m == $bookingRow['fromm'] && $d == $bookingRow['fromd']) {
                if ($bookingRow['firstday'] == "whole") {
                  pushDateToArray("booking", $y, $m, $d, "reserved-firstday", "no", $paymentSts);
                  $bookingReservedSts = true;
                } else {
                  $bookingReservedPaymentSts = $paymentSts;
                  $bookingReservedFirstdaySts = true;
                  $bookingReservedSts = true;
                }
              } else if ($y == $bookingRow['toy'] && $m == $bookingRow['tom'] && $d == $bookingRow['tod']) {
                if ($bookingRow['lastday'] == "whole") {
                  pushDateToArray("booking", $y, $m, $d, "reserved-lastday", "no", $paymentSts);
                  $bookingReservedSts = true;
                } else {
                  $bookingReservedPaymentSts = $paymentSts;
                  $bookingReservedLastdaySts = true;
                  $bookingReservedSts = true;
                }
              } else {
                pushDateToArray("booking", $y, $m, $d, "reserved", "no", $paymentSts);
                $bookingReservedSts = true;
              }
            } else {
              echo "Data about booking not found in database (".$d."/".$m."/".$y.")<br>";
            }
          } else {
            $oneDayBookingArr = [];
            while($bookingBeId = $sqlBookingDates->fetch_assoc()) {
              array_push($oneDayBookingArr, $bookingBeId["beid"]);
            }
            $allBookingBeIds_string = join("','", $oneDayBookingArr);
            $paymentString = "";
            $sqlBooking = $link->query("SELECT deposit, fullAmount FROM booking WHERE beid IN ('$allBookingBeIds_string') ORDER BY fromdate DESC");
            if ($sqlBooking->num_rows == 2) {
              while($bookingRow = $sqlBooking->fetch_assoc()) {
                if ($bookingRow['fullAmount'] == "1") {
                  $paymentSts = "fullAmount";
                } else if ($bookingRow['deposit'] == "1") {
                  $paymentSts = "deposit";
                } else {
                  $paymentSts = "none";
                }
                if ($paymentString == "") {
                  $paymentString = $paymentSts;
                } else {
                  $paymentString = $paymentString."-".$paymentSts;
                }
              }
            } else {
              $paymentString = "none";
            }
            pushDateToArray("booking", $y, $m, $d, "reserved", "yes", $paymentString);
            $bookingReservedSts = true;
          }
        }
        $sqlTechnicalShutdownDates = $link->query("SELECT beid FROM technicalshutdowndates WHERE plcbeid='$plcBeId' and status='active' and year='$y' and month='$m' and day='$d' LIMIT 2");
        if ($sqlTechnicalShutdownDates->num_rows > 0) {
          if ($sqlTechnicalShutdownDates->num_rows == 1) {
            $technicalShutdownBeId = $sqlTechnicalShutdownDates->fetch_assoc()["beid"];
            $sqlTechnicalShutdown = $link->query("SELECT fromy, fromm, fromd, firstday, toy, tom, tod, lastday FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
            if ($sqlTechnicalShutdown->num_rows > 0) {
              $technicalShutdownRow = $sqlTechnicalShutdown->fetch_assoc();
              if ($y == $technicalShutdownRow['fromy'] && $m == $technicalShutdownRow['fromm'] && $d == $technicalShutdownRow['fromd']) {
                if ($technicalShutdownRow['firstday'] == "whole") {
                  pushDateToArray("technical-shutdown", $y, $m, $d, "reserved-firstday", "no", "none");
                } else {
                  if ($bookingReservedLastdaySts) {
                    pushDateToArray("technical-shutdown", $y, $m, $d, "reserved", "yes", "none-".$bookingReservedPaymentSts);
                  } else {
                    pushDateToArray("technical-shutdown", $y, $m, $d, "reserved-secondhalf-only", "no", "none");
                  }
                }
              } else if ($y == $technicalShutdownRow['toy'] && $m == $technicalShutdownRow['tom'] && $d == $technicalShutdownRow['tod']) {
                if ($technicalShutdownRow['lastday'] == "whole") {
                  pushDateToArray("technical-shutdown", $y, $m, $d, "reserved-lastday", "no", "none");
                } else {
                  if ($bookingReservedFirstdaySts) {
                    pushDateToArray("technical-shutdown", $y, $m, $d, "reserved", "yes", $bookingReservedPaymentSts."-none");
                  } else {
                    pushDateToArray("technical-shutdown", $y, $m, $d, "reserved-firsthalf-only", "no", "none");
                  }
                }
              } else {
                pushDateToArray("technical-shutdown", $y, $m, $d, "reserved", "no", "none");
              }
            } else {
              echo "Data about technical shutdown not found in database (".$d."/".$m."/".$y.")<br>";
            }
          } else {
            pushDateToArray("technical-shutdown", $y, $m, $d, "reserved", "yes", "none");
          }
        } else {
          if (!$bookingReservedSts) {
            pushDateToArray("technical-shutdown", $y, $m, $d, "not-reserved", "no", "none");
          } else {
            if ($bookingReservedFirstdaySts) {
              pushDateToArray("booking", $y, $m, $d, "reserved-secondhalf-only", "no", $paymentSts);
            }
            if ($bookingReservedLastdaySts) {
              pushDateToArray("booking", $y, $m, $d, "reserved-firsthalf-only", "no", $paymentSts);
            }
          }
        }
      }
      returnOutput();
    } else {
      echo "ID verification faild <br>".mysqli_error($link);
    }
  } else {
    echo $calendar_verification;
  }

  function pushDateToArray($category, $y, $m, $d, $sts, $busyDay, $payment) {
    global $output;
    array_push($output, [
      "type" => "date",
      "category" => $category,
      "y" => $y,
      "m" => $m,
      "d" => $d,
      "sts" => $sts,
      "busy" => $busyDay,
      "payment" => $payment
    ]);
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
