<?php
  include "../email-sender/php-backend/unpaid-full-amount-call-mail.php";
  function unpaidFullAmountCallHandler() {
    global $link;
    $date = date("Y-m-d H:i:s");
    $sqlAllBookings = $link->query("SELECT * FROM booking WHERE deposit='1' and fullAmount='0' and lessthan48h='0' and status='booked'");
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
        $plcBeID = $allBookings['plcbeid'];
        $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID'");
        if ($sqlPlc->num_rows > 0) {
          $plc = $sqlPlc->fetch_assoc();
          $usrBeID = $plc['usrbeid'];
          if (getAccountData($usrBeID, "unpaid-full-amount-call") == "1") {
            $sqlUsr = $link->query("SELECT * FROM users WHERE beid='$usrBeID' and status='active'");
            if ($sqlUsr->num_rows > 0) {
              $usr = $sqlUsr->fetch_assoc();
              if ($allBookings['email'] != "-") {
                if ($hours == 48) {
                  unpaidFullAmountCallMail(
                    getFrontendId($allBookings['beid']),
                    $allBookings['email'],
                    $allBookings['language'],
                    $allBookings['fromd'],
                    $allBookings['fromm'],
                    $allBookings['fromy'],
                    $allBookings['firstday'],
                    $allBookings['tod'],
                    $allBookings['tom'],
                    $allBookings['toy'],
                    $allBookings['lastday'],
                    $allBookings['totalprice'],
                    $allBookings['totalcurrency'],
                    getFrontendId($plcBeID),
                    $plc['name'],
                    getFrontendId($usrBeID),
                    $usr['firstname'],
                    $usr['lastname'],
                    $usr['contactemail'],
                    $usr['contactphonenum']
                  );
                }
              }
            }
          }
        }
      }
    }
  }
?>
