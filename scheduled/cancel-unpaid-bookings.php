<?php
  include "../editor/php-backend/edit-booking/cancel-booking.php";
  function cancelUnpaidBookingsHandler() {
    global $link;
    $date = date("Y-m-d H:i:s");
    $sqlAllBookings = $link->query("SELECT plcbeid, fromy, fromm, fromd, firstday, toy, tom, tod, fulldate FROM booking WHERE deposit='0' and fullAmount='0' and lessthan48h='0' and status='booked'");
    if ($sqlAllBookings->num_rows > 0) {
      while($allBookings = $sqlAllBookings->fetch_assoc()) {
        $diff = abs(strtotime($date) - strtotime($allBookings['fulldate']));
        $hours = floor($diff / 3600);
        if ($hours >= 48) {
          $bookingPlcBeID = $allBookings['plcbeid'];
          $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$bookingPlcBeID' LIMIT 1");
          $plcRow = $sqlPlace->fetch_assoc();
          $plcHostBeID = $plcRow['usrbeid'];
          if (getAccountData($plcHostBeID, "cancel-unpaid-bookings") == "1") {
            $sqlHostData = $link->query("SELECT * FROM users WHERE beid='$plcHostBeID' LIMIT 1");
            $aboutHost = $sqlHostData->fetch_assoc();
            cancelBooking($bookingPlcBeID, $allBookings['fromd'], $allBookings['fromm'], $allBookings['fromy'], $allBookings['tod'], $allBookings['tom'], $allBookings['toy'], $aboutHost['firstname']." ".$aboutHost['lastname'], $aboutHost['contactemail'], $aboutHost['contactphonenum'], "unpaid", true);
          }
        }
      }
    }
  }
?>
