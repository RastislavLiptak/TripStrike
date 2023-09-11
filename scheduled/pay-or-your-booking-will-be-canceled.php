<?php
  include "../uni/code/php-backend/add-currency.php";
  include "../email-sender/php-backend/last-call-before-booking-cancelation-to-guest.php";
  include "../email-sender/php-backend/last-call-before-booking-cancelation-to-host.php";
  function payOrYourBookingWillBeCanceled() {
    global $link;
    $date = date("Y-m-d H:i:s");
    $sqlAllBookings = $link->query("SELECT * FROM booking WHERE deposit='0' and fullAmount='0' and lessthan48h='0' and status='booked'");
    if ($sqlAllBookings->num_rows > 0) {
      while($allBookings = $sqlAllBookings->fetch_assoc()) {
        $diff = abs(strtotime($date) - strtotime($allBookings['fulldate']));
        $hours = floor($diff / 3600);
        if ($hours == 43) {
          $bookingPlcBeID = $allBookings['plcbeid'];
          $sqlAboutPlace = $link->query("SELECT * FROM places WHERE beid='$bookingPlcBeID' LIMIT 1");
          $rowPlc = $sqlAboutPlace->fetch_assoc();
          $plcHostBeID = $rowPlc['usrbeid'];
          if (getAccountData($plcHostBeID, "pay-or-your-booking-will-be-canceled") == "1") {
            $sqlHostData = $link->query("SELECT * FROM users WHERE beid='$plcHostBeID' LIMIT 1");
            $aboutHost = $sqlHostData->fetch_assoc();
            lastCallBeforeBookingCancelationToGuest(
              getFrontendId($allBookings['beid']),
              getFrontendId($bookingPlcBeID),
              $rowPlc['name'],
              $rowPlc['currency'],
              $allBookings['name'],
              $allBookings['email'],
              $allBookings['language'],
              $allBookings['totalprice'],
              $allBookings['fromy'],
              $allBookings['fromm'],
              $allBookings['fromd'],
              $allBookings['firstday'],
              $allBookings['toy'],
              $allBookings['tom'],
              $allBookings['tod'],
              $allBookings['lastday'],
              getFrontendId($plcHostBeID),
              $aboutHost['firstname'],
              $aboutHost['lastname'],
              $aboutHost['contactemail'],
              $aboutHost['contactphonenum'],
              $aboutHost['bankaccount'],
              $aboutHost['iban'],
              $aboutHost['bicswift']
            );
            lastCallBeforeBookingCancelationToHost(
              getFrontendId($allBookings['beid']),
              getFrontendId($bookingPlcBeID),
              $rowPlc['name'],
              $rowPlc['currency'],
              $allBookings['name'],
              $allBookings['email'],
              $allBookings['phonenum'],
              $allBookings['notes'],
              $allBookings['guestnum'],
              $allBookings['totalprice'],
              $allBookings['fromy'],
              $allBookings['fromm'],
              $allBookings['fromd'],
              $allBookings['firstday'],
              $allBookings['toy'],
              $allBookings['tom'],
              $allBookings['tod'],
              $allBookings['lastday'],
              $aboutHost['contactemail'],
              $aboutHost['language']
            );
          }
        }
      }
    }
  }
?>
