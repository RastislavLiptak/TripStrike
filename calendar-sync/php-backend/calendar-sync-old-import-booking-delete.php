<?php
  function allOldDateImportDelete($currCallBeID) {
    global $link;
    $allOldDateImportDeleteOutput = "";
    $sqlImportedBookings = $link->query("SELECT * FROM booking WHERE source!='none'");
    if ($sqlImportedBookings->num_rows > 0) {
      while($rowImBooking = $sqlImportedBookings->fetch_assoc()) {
        $imBookingBeID = $rowImBooking['beid'];
        $sqlImportCheck = $link->query("SELECT * FROM icalimportlog WHERE callbeid='$currCallBeID' and bookingbeid='$imBookingBeID'");
        if ($sqlImportCheck->num_rows > 0) {
          // freshly imported reservation, do nothing
        } else {
          $deleteCall = oldDateImportDelete($imBookingBeID);
          if ($deleteCall != "done") {
            if ($allOldDateImportDeleteOutput == "") {
              $allOldDateImportDeleteOutput = $deleteCall;
            } else {
              $allOldDateImportDeleteOutput = $allOldDateImportDeleteOutput."<br>".$deleteCall;
            }
          }
        }
      }
    }
    if ($allOldDateImportDeleteOutput == "") {
      $allOldDateImportDeleteOutput = "done";
    }
    return $allOldDateImportDeleteOutput;
  }

  function oldDateImportDelete($bookingBeId) {
    global $link;
    $sqlCancelBooking = "UPDATE booking SET status='canceled' WHERE beid='$bookingBeId'";
    if (mysqli_query($link, $sqlCancelBooking)) {
      return "done";
    } else {
      return "Failed to detete blocking booking from different import (SQL error: ".mysqli_error($link).")";
    }
  }
?>
