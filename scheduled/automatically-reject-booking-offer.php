<?php
  include "../bookings/php-backend/reject-booking.php";
  include "../email-sender/php-backend/host-rejected-the-booking-mail.php";
  function automaticallyRejectBookingOffer() {
    global $link;
    $date = date("Y-m-d H:i:s");
    $sqlAllBookings = $link->query("SELECT * FROM booking WHERE status='waiting'");
    if ($sqlAllBookings->num_rows > 0) {
      while($allBookings = $sqlAllBookings->fetch_assoc()) {
        if ($allBookings['firstday'] == "half") {
          $fromDate = $allBookings['fromdate']." 14:00";
        } else {
          $fromDate = $allBookings['fromdate']." 00:00";
        }
        $diff = abs(strtotime($date) - strtotime($fromDate));
        $hours = floor($diff / 3600);
        if ($date > $fromDate) {
          $hours = $hours * -1;
        }
        if ($hours <= 12) {
          rejectBooking($allBookings['beid'], "../");
        }
      }
    }
  }
?>
