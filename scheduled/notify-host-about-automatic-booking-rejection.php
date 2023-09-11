<?php
  include "../email-sender/php-backend/automatic-booking-rejection-alert-mail.php";
  function notifyHostAboutAutomaticBookingRejection() {
    global $link, $linkBD;
    $date = date("Y-m-d H:i:s");
    $sqlAllBookings = $link->query("SELECT * FROM booking WHERE status='waiting'");
    if ($sqlAllBookings->num_rows > 0) {
      while($allBookings = $sqlAllBookings->fetch_assoc()) {
        $bookingBeId = $allBookings['beid'];
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
        $sqlArchive = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
        if ($sqlArchive->num_rows > 0) {
          $arch = $sqlArchive->fetch_assoc();
          $feePerc = $arch['percentagefee'];
        } else {
          $feePerc = 0;
        }
        $plcBeID = $allBookings['plcbeid'];
        $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID' and status='active'");
        if ($sqlPlc->num_rows > 0) {
          $plc = $sqlPlc->fetch_assoc();
          $usrBeID = $plc['usrbeid'];
          $sqlUsr = $link->query("SELECT * FROM users WHERE beid='$usrBeID' and status='active'");
          if ($sqlUsr->num_rows > 0) {
            $usr = $sqlUsr->fetch_assoc();
            if ($hours == 17) {
              automaticBookingRejectionAlertMail(
                $usr['language'],
                $usr['email'],
                $allBookings['guestnum'],
                getFrontendId($plcBeID),
                $plc['name'],
                getFrontendId($allBookings['beid']),
                $allBookings['firstday'],
                $allBookings['lastday'],
                $allBookings['fromd'],
                $allBookings['fromm'],
                $allBookings['fromy'],
                $allBookings['tod'],
                $allBookings['tom'],
                $allBookings['toy'],
                $allBookings['totalprice'],
                $feePerc,
                $plc['currency']
              );
            }
          }
        }
      }
    }
  }
?>
