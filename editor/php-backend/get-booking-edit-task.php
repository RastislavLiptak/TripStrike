<?php
  $unaffectedBookingTermReady = false;
  $unaffectedBookingTermStatus = false;
  $affectedBookingTermReady = false;
  $affectedBookingTermStatus = false;
  $unaffectedBookingSplitReady = false;
  $unaffectedBookingSplitStatus = false;
  function getBookingEditTask($blockingBooking, $type, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount) {
    global $link, $unaffectedBookingTermReady, $unaffectedBookingTermStatus, $affectedBookingTermStatus, $affectedBookingTermReady, $unaffectedBookingSplitReady, $unaffectedBookingSplitStatus;
    $sqlBLockingData = $link->query("SELECT * FROM booking WHERE beid='$blockingBooking' and (status='booked' or status='waiting') LIMIT 1");
    if ($sqlBLockingData->num_rows > 0) {
      $blockingRow = $sqlBLockingData->fetch_assoc();
      $f_d = sprintf("%02d", $f_d);
      $f_m = sprintf("%02d", $f_m);
      $f_y = sprintf("%02d", $f_y);
      $t_d = sprintf("%02d", $t_d);
      $t_m = sprintf("%02d", $t_m);
      $t_y = sprintf("%02d", $t_y);
      if ($g_name == "") {
        $g_name = "-";
      }
      if ($g_email == "") {
        $g_email = "-";
      }
      if ($g_phone == "") {
        $g_phone = "-";
      }
      $unaffectedBookingTermReady = false;
      $unaffectedBookingTermStatus = false;
      $affectedBookingTermReady = false;
      $affectedBookingTermStatus = false;
      $unaffectedBookingSplitReady = false;
      $unaffectedBookingSplitStatus = false;
      $sqlBlockingDays = $link->query("SELECT * FROM bookingdates WHERE beid='$blockingBooking' and (status='booked' or status='waiting') ORDER BY fulldate ASC");
      if ($sqlBlockingDays->num_rows > 0) {
        while($blockingDays = $sqlBlockingDays->fetch_assoc()) {
          $blocking_day_y = $blockingDays['year'];
          $blocking_day_m = sprintf("%02d", $blockingDays['month']);
          $blocking_day_d = sprintf("%02d", $blockingDays['day']);
          if (
            $blocking_day_y."-".$blocking_day_m."-".$blocking_day_d > $f_y."-".$f_m."-".$f_d &&
            $blocking_day_y."-".$blocking_day_m."-".$blocking_day_d < $t_y."-".$t_m."-".$t_d
          ) {
            getBookingEditTaskAffectedDay();
          } else if ($blocking_day_y == $f_y && $blocking_day_m == $f_m && $blocking_day_d == $f_d) {
            if ($blocking_day_y."-".$blocking_day_m."-".$blocking_day_d != $blockingRow['fromy']."-".sprintf("%02d", $blockingRow['fromm'])."-".sprintf("%02d", $blockingRow['fromd'])) {
              if ($firstDay == "half") {
                if ($affectedBookingTermReady) {
                  getBookingEditTaskAffectedDay();
                } else {
                  $affectedBookingTermReady = true;
                }
                getBookingEditTaskUnaffectedDay();
              } else {
                getBookingEditTaskAffectedDay();
              }
            } else {
              getBookingEditTaskAffectedDay();
            }
          } else if ($blocking_day_y == $t_y && $blocking_day_m == $t_m && $blocking_day_d == $t_d) {
            if ($blocking_day_y."-".$blocking_day_m."-".$blocking_day_d != $blockingRow['toy']."-".sprintf("%02d", $blockingRow['tom'])."-".sprintf("%02d", $blockingRow['tod'])) {
              if ($lastDay == "half") {
                if ($affectedBookingTermReady) {
                  getBookingEditTaskAffectedDay();
                } else {
                  $affectedBookingTermReady = true;
                }
                getBookingEditTaskUnaffectedDay();
              } else {
                getBookingEditTaskAffectedDay();
              }
            } else {
              getBookingEditTaskAffectedDay();
            }
          } else {
            getBookingEditTaskUnaffectedDay();
          }
        }
        if ($blockingRow['status'] == "booked") {
          if ($unaffectedBookingSplitStatus || $unaffectedBookingTermStatus) {
            if ($type == "booking" && $blockingRow['name'] == $g_name && $blockingRow['email'] == $g_email && $blockingRow['phonenum'] == $g_phone && $blockingRow['guestnum'] == $g_guest && $g_notes == $blockingRow['notes'] && $deposit == $blockingRow['deposit'] && $fullAmount == $blockingRow['fullAmount']) {
              getBookingEditTaskOutput("task", $blockingBooking, "connect");
            } else {
              if ($unaffectedBookingSplitStatus) {
                getBookingEditTaskOutput("task", $blockingBooking, "split");
              } else {
                getBookingEditTaskOutput("task", $blockingBooking, "shorten");
              }
            }
          } else {
            getBookingEditTaskOutput("task", $blockingBooking, "delete");
          }
        } else {
          getBookingEditTaskOutput("task", $blockingBooking, "reject");
        }
      } else {
        getBookingEditTaskOutput("error", "Get task for edit: Days of a booking not found in database", "");
      }
    } else {
      getBookingEditTaskOutput("error", "Get task for edit: Booking not found in database", "");
    }
  }

  function getBookingEditTaskAffectedDay() {
    global $affectedBookingTermReady, $affectedBookingTermStatus, $unaffectedBookingTermReady, $unaffectedBookingTermStatus, $unaffectedBookingSplitReady;
    $affectedBookingTermReady = false;
    $affectedBookingTermStatus = true;
    $unaffectedBookingTermReady = false;
    if ($unaffectedBookingTermStatus) {
      $unaffectedBookingSplitReady = true;
    }
  }

  function getBookingEditTaskUnaffectedDay() {
    global $unaffectedBookingTermReady, $unaffectedBookingTermStatus, $affectedBookingTermStatus, $unaffectedBookingSplitReady, $unaffectedBookingSplitStatus;
    if ($unaffectedBookingTermReady) {
      if ($unaffectedBookingTermStatus) {
        if ($affectedBookingTermStatus && $unaffectedBookingSplitReady) {
          $unaffectedBookingSplitStatus = true;
        }
      } else {
        $unaffectedBookingTermStatus = true;
      }
    } else {
      $unaffectedBookingTermReady = true;
    }
  }
?>
