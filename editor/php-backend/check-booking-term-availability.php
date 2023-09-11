<?php
  include "check-technical-shutdown-day-availability.php";
  function checkBookingTermAvailability($plcBeID, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay) {
    global $link;
    $check_d = $f_d;
    $check_m = $f_m;
    $check_y = $f_y;
    $check_output = "available";
    while ($check_d."/".$check_m."/".$check_y != calcNextDay($t_d, $t_m, $t_y, 1)."/".calcNextMonthByDays($t_d, $t_m, $t_y, 1)."/".calcNextYearByDays($t_d, $t_m, $t_y, 1)) {
      $sqlBookingDates = $link->query("SELECT beid FROM bookingdates WHERE plcbeid='$plcBeID' and (status='booked' or status='waiting') and year='$check_y' and month='$check_m' and day='$check_d'");
      if ($sqlBookingDates->num_rows > 0) {
        while($bookingDates = $sqlBookingDates->fetch_assoc()) {
          $bookingBeId = $bookingDates["beid"];
          $sqlBooking = $link->query("SELECT fromy, fromm, fromd, firstday, toy, tom, tod, lastday FROM booking WHERE beid='$bookingBeId' LIMIT 1");
          if ($sqlBooking->num_rows > 0) {
            $bookingRow = $sqlBooking->fetch_assoc();
            if ($check_d == $f_d && $check_m == $f_m && $check_y == $f_y) {
              if ($firstDay == "half") {
                if ($check_y == $bookingRow['fromy'] && $check_m == $bookingRow['fromm'] && $check_d == $bookingRow['fromd']) {
                  $check_output = "unavailable";
                  checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
                } else if ($check_y == $bookingRow['toy'] && $check_m == $bookingRow['tom'] && $check_d == $bookingRow['tod']) {
                  if ($bookingRow['lastday'] != "half") {
                    $check_output = "unavailable";
                    checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
                  }
                } else {
                  $check_output = "unavailable";
                  checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
                }
              } else {
                $check_output = "unavailable";
                checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
              }
            } else if ($check_d == $t_d && $check_m == $t_m && $check_y == $t_y) {
              if ($lastDay == "half") {
                if ($check_y == $bookingRow['fromy'] && $check_m == $bookingRow['fromm'] && $check_d == $bookingRow['fromd']) {
                  if ($bookingRow['firstday'] != "half") {
                    $check_output = "unavailable";
                    checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
                  }
                } else if ($check_y == $bookingRow['toy'] && $check_m == $bookingRow['tom'] && $check_d == $bookingRow['tod']) {
                  $check_output = "unavailable";
                  checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
                } else {
                  $check_output = "unavailable";
                  checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
                }
              } else {
                $check_output = "unavailable";
                checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
              }
            } else {
              $check_output = "unavailable";
              checkBookingTermAvailabilityOutput("unavailable", $bookingBeId, "booking");
            }
          }
        }
      }
      $unavailablaTechnicalShutdownBeIdArr = checkTechnicalShutdownDayAvailability($plcBeID, $check_d, $check_m, $check_y, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
      foreach ($unavailablaTechnicalShutdownBeIdArr as $unavailablaTechnicalShutdownBeId) {
        $check_output = "unavailable";
        checkBookingTermAvailabilityOutput("unavailable", $unavailablaTechnicalShutdownBeId, "technical-shutdown");
      }
      $check_d = calcNextDay($check_d, $check_m, $check_y, 1);
      if ($check_d == 1) {
        ++$check_m;
        if ($check_m == 13) {
          $check_m = 1;
          ++$check_y;
        }
      }
    }
    if ($check_output == "available") {
      checkBookingTermAvailabilityOutput("available", "good", "");
    } else {
      checkBookingTermAvailabilityOutput("loop-unavailable", "", "");
    }
  }
?>
