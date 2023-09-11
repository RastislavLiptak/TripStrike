<?php
  function bookingVerification($bookingBeId) {
    global $link, $usrBeId;
    $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
    if ($sqlBooking->num_rows > 0) {
      $bookingRow = $sqlBooking->fetch_assoc();
      if ($usrBeId == $bookingRow['usrbeid']) {
        if ($bookingRow['status'] == "booked" || $bookingRow['status'] == "waiting") {
          if (new DateTime() < new DateTime($bookingRow['fromdate'])) {
            $plcBeId = $bookingRow['plcbeid'];
            $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
            if ($sqlPlc->num_rows > 0) {
              $plcRow = $sqlPlc->fetch_assoc();
              $hostBeId = $plcRow['usrbeid'];
              $sqlHst = $link->query("SELECT * FROM users WHERE beid='$hostBeId'");
              if ($sqlHst->num_rows > 0) {
                $hstRow = $sqlHst->fetch_assoc();
                if ($hstRow['status'] == "active") {
                  return "good";
                } else {
                  return "Host's profile status is not valid";
                }
              } else {
                return "Host's profile was not found in the database";
              }
            } else {
              return "The booked place was not found in the database";
            }
          } else {
            return "The booking has already taken place or is still in progress";
          }
        } else {
          return "Booking status is not valid";
        }
      } else {
        return "This booking was not created by this profile";
      }
    } else {
      return "Booking not found in our database";
    }
  }
?>
