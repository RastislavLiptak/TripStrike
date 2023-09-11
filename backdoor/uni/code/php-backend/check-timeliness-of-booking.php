<?php
  if (!function_exists('checkTimelinessOfBooking')) {
    function checkTimelinessOfBooking($bookingBeId) {
      global $link;
      $date = date("Y-m-d H:i:s");
      $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
      if ($sqlBooking->num_rows > 0) {
        $rowBooking = $sqlBooking->fetch_assoc();
        if ($rowBooking['firstday'] == "half") {
          $fromDate = $rowBooking['fromdate']." 14:00";
        } else {
          $fromDate = $rowBooking['fromdate']." 00:00";
        }
        $diff = abs(strtotime($date) - strtotime($fromDate));
        $hours = floor($diff / 3600);
        if ($date > $fromDate) {
          $hours = $hours * -1;
        }
        if ($hours > 0) {
          return "future";
        } else {
          return "past";
        }
      } else {
        return "data not found";
      }
    }
  }
?>
