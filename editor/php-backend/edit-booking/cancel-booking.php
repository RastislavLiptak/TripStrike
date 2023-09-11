<?php
  include realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/email-sender/php-backend/delete-plc-mail-to-guest.php";
  include realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/email-sender/php-backend/cancel-booking-mail-to-guest.php";
  include realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/email-sender/php-backend/cancel-unpaid-booking-mail-to-guest.php";
  include realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/email-sender/php-backend/guest-canceled-booking-mail.php";
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/edit-booking-archive/cancel-booking-archive.php';
  function cancelBooking($plcBeID, $fromD, $fromM, $fromY, $toD, $toM, $toY, $hostName, $hostEmail, $hostPhone, $canceled, $sendEmail) {
    global $link;
    $sqlBooking = $link->query("SELECT * FROM booking WHERE plcbeid='$plcBeID' and fromd='$fromD' and fromm='$fromM' and fromy='$fromY' and tod='$toD' and tom='$toM' and toy='$toY' and (status='booked' || status='waiting')");
    if ($sqlBooking->num_rows > 0) {
      $booking = $sqlBooking->fetch_assoc();
      $bookingBeId = $booking['beid'];
      $cancelBookingArchiveSts = cancelBookingArchive($bookingBeId);
      if ($cancelBookingArchiveSts == "done") {
        $sqlCancelBookingDays = "UPDATE bookingdates SET status='canceled' WHERE beid='$bookingBeId'";
        mysqli_query($link, $sqlCancelBookingDays);
        $sqlCancelBooking = "UPDATE booking SET status='canceled' WHERE beid='$bookingBeId'";
        mysqli_query($link, $sqlCancelBooking);
        $sqlCancelBookingUpdateRequests = "UPDATE bookingupdaterequest SET status='rejected' WHERE bookingbeid='$bookingBeId'";
        mysqli_query($link, $sqlCancelBookingUpdateRequests);
        $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID' LIMIT 1");
        $plc = $sqlPlc->fetch_assoc();
        $hostBeID = $plc['usrbeid'];
        $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeID' LIMIT 1");
        $hst = $sqlHost->fetch_assoc();
        if ($booking['firstday'] == "half") {
          $fromDate = $booking['fromdate']." 14:00";
        } else {
          $fromDate = $booking['fromdate']." 00:00";
        }
        $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($fromDate));
        $hours = floor($diff / 3600);
        if (date("Y-m-d H:i:s") > $fromDate) {
          $hours = $hours * -1;
        }
        if ($hours >= 336) {
          $twoWeeksBefore = true;
        } else {
          $twoWeeksBefore = false;
        }
        if ((($booking['email'] != "-" && $booking['email'] != "" && $canceled != "by-the-guest") || ($canceled == "by-the-guest" && (($booking['email'] != "-" && $booking['email'] != "") || ($hostEmail != "-" && $hostEmail != "") ))) && $sendEmail) {
          if (date("Y-m-d") < $toY."-".$toM."-".$toD) {
            if ($canceled == "place") {
              deletePlcMailToGuest(
                getFrontendId($plcBeID),
                getFrontendId($plc['usrbeid']),
                $hostName,
                $hostEmail,
                $hostPhone,
                $booking['email'],
                $plc['name'],
                $booking['language'],
                $booking['fromy'],
                $booking['fromm'],
                $booking['fromd'],
                $booking['firstday'],
                $booking['toy'],
                $booking['tom'],
                $booking['tod'],
                $booking['lastday'],
                $booking['guestnum'],
                $booking['datey'],
                $booking['datem'],
                $booking['dated']
              );
            } else if ($canceled == "unpaid") {
              cancelUnpaidBookingMailToGuest(
                getFrontendId($plcBeID),
                getFrontendId($plc['usrbeid']),
                $hostName,
                $hostEmail,
                $hostPhone,
                $booking['email'],
                $plc['name'],
                $booking['language'],
                $booking['fromy'],
                $booking['fromm'],
                $booking['fromd'],
                $booking['firstday'],
                $booking['toy'],
                $booking['tom'],
                $booking['tod'],
                $booking['lastday'],
                $booking['guestnum'],
                $booking['datey'],
                $booking['datem'],
                $booking['dated']
              );
            } else if ($canceled == "by-the-guest") {
              if ($booking['email'] != "-" && $booking['email']) {
                cancelBookingMailToGuest(
                  "by-guest",
                  $booking['status'],
                  getFrontendId($plcBeID),
                  getFrontendId($plc['usrbeid']),
                  $booking['email'],
                  $hostName,
                  $hostEmail,
                  $hostPhone,
                  $plc['name'],
                  $booking['language'],
                  $booking['deposit'],
                  $booking['fullAmount'],
                  $booking['fromy'],
                  $booking['fromm'],
                  $booking['fromd'],
                  $booking['firstday'],
                  $booking['toy'],
                  $booking['tom'],
                  $booking['tod'],
                  $booking['lastday'],
                  $booking['guestnum'],
                  $booking['datey'],
                  $booking['datem'],
                  $booking['dated'],
                  $twoWeeksBefore
                );
              } else {
                mailDone("done", "booking-canceled-mail-to-guest");
              }
              if ($hostEmail != "-" && $hostEmail != "" && $booking['status'] == "booked") {
                guestCanceledBookingMail(
                  getFrontendId($bookingBeId),
                  $plc['name'],
                  getFrontendId($plcBeID),
                  $hostEmail,
                  $hst['language'],
                  $booking['name'],
                  $booking['email'],
                  $booking['phonenum'],
                  $booking['guestnum'],
                  $booking['totalprice'],
                  $booking['totalcurrency'],
                  $booking['deposit'],
                  $booking['fullAmount'],
                  $booking['fromy'],
                  $booking['fromm'],
                  $booking['fromd'],
                  $booking['firstday'],
                  $booking['toy'],
                  $booking['tom'],
                  $booking['tod'],
                  $booking['lastday'],
                  $twoWeeksBefore
                );
              } else {
                mailDone("done", "guest-canceled-booking-mail");
              }
            } else {
              cancelBookingMailToGuest(
                "by-host",
                $booking['status'],
                getFrontendId($plcBeID),
                getFrontendId($plc['usrbeid']),
                $booking['email'],
                $hostName,
                $hostEmail,
                $hostPhone,
                $plc['name'],
                $booking['language'],
                $booking['deposit'],
                $booking['fullAmount'],
                $booking['fromy'],
                $booking['fromm'],
                $booking['fromd'],
                $booking['firstday'],
                $booking['toy'],
                $booking['tom'],
                $booking['tod'],
                $booking['lastday'],
                $booking['guestnum'],
                $booking['datey'],
                $booking['datem'],
                $booking['dated'],
                $twoWeeksBefore
              );
            }
          } else {
            cancelBookingOutput("done", "good");
          }
        } else {
          cancelBookingOutput("done", "good");
        }
      } else {
        cancelBookingOutput("error", "Booking archive error: ".$cancelBookingArchiveSts);
      }
    } else {
      cancelBookingOutput("error", "Cancel booking failed: booking not found in database");
    }
  }
?>
