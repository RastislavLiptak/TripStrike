<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/backdoor/uni/code/php-backend/edit-booking-archive/add-booking-archive.php";
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/backdoor/uni/code/php-backend/edit-booking-archive/update-booking-archive.php";
  function confirmBooking($bookingBeId, $fold) {
    global $link, $linkBD;
    $date = date("Y-m-d H:i:s");
    $dateY = date("Y");
    $dateM = date("m");
    $dateD = date("d");
    $sqlBookings = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
    if ($sqlBookings->num_rows > 0) {
      $book = $sqlBookings->fetch_assoc();
      $plcBeID = $book['plcbeid'];
      $sqlPlaces = $link->query("SELECT * FROM places WHERE beid='$plcBeID' and status='active' LIMIT 1");
      if ($sqlPlaces->num_rows > 0) {
        $plc = $sqlPlaces->fetch_assoc();
        $hostBeID = $plc['usrbeid'];
        $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeID' and status='active' LIMIT 1");
        if ($sqlHost->num_rows > 0) {
          $hst = $sqlHost->fetch_assoc();
          $sqlBookingDatesUpdt = "UPDATE bookingdates SET status='booked' WHERE beid='$bookingBeId'";
          if (mysqli_query($link, $sqlBookingDatesUpdt)) {
            if ($book['firstday'] != "whole") {
              $f_date = $book['fromy']."-".sprintf("%02d", $book['fromm'])."-".sprintf("%02d", $book['fromd'])." 14:00:00";
            } else {
              $f_date = $book['fromy']."-".sprintf("%02d", $book['fromm'])."-".sprintf("%02d", $book['fromd'])." 00:00:00";
            }
            $from_diff = abs(strtotime($date) - strtotime($f_date));
            $from_hours = floor($from_diff / 3600);
            if ($date > $f_date) {
              $lessThan48h = 0;
            } else {
              if ($from_hours < 48) {
                $lessThan48h = 1;
              } else {
                $lessThan48h = 0;
              }
            }
            $feePerc = 0;
            $sqlBookingUpdt = "UPDATE booking SET status='booked', lessthan48h='$lessThan48h', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE beid='$bookingBeId'";
            if (mysqli_query($link, $sqlBookingUpdt)) {
              $sqlArchiveCheck = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
              if ($sqlArchiveCheck->num_rows > 0) {
                $rowArBooking = $sqlArchiveCheck->fetch_assoc();
                $feePerc = $rowArBooking['percentagefee'];
                $editBookingArchiveSts = updateBookingArchive(
                  $bookingBeId,
                  $plc['usrbeid'],
                  $book['usrbeid'],
                  $plcBeID,
                  $book['guestnum'],
                  "booked",
                  $rowArBooking['paymentStatus'],
                  $rowArBooking['source'],
                  $plc['currency'],
                  $book['totalprice'],
                  $rowArBooking['percentagefee'],
                  $plc['priceMode'],
                  $plc['workDayPrice'],
                  $plc['weekDayPrice'],
                  $book['fromdate'],
                  $book['fromd'],
                  $book['fromm'],
                  $book['fromy'],
                  $book['firstday'],
                  $book['todate'],
                  $book['tod'],
                  $book['tom'],
                  $book['toy'],
                  $book['lastday']
                );
              } else {
                $editBookingArchiveSts = addBookingArchive(
                  $bookingBeId,
                  $plc['usrbeid'],
                  $book['usrbeid'],
                  $plcBeID,
                  $book['guestnum'],
                  "booked",
                  1,
                  "booking-form",
                  $plc['currency'],
                  $book['totalprice'],
                  0,
                  $plc['priceMode'],
                  $plc['workDayPrice'],
                  $plc['weekDayPrice'],
                  $book['fromdate'],
                  $book['fromd'],
                  $book['fromm'],
                  $book['fromy'],
                  $book['firstday'],
                  $book['todate'],
                  $book['tod'],
                  $book['tom'],
                  $book['toy'],
                  $book['lastday']
                );
              }
              if ($editBookingArchiveSts != "done") {
                return "Booking archive error: ".$editBookingArchiveSts;
              }
              if ($f_date > $date) {
                hostConfirmedTheBookingMail(
                  $fold,
                  getFrontendId($bookingBeId),
                  $book['language'],
                  $book['email'],
                  $book['guestnum'],
                  getFrontendId($plcBeID),
                  $hostBeID,
                  getFrontendId($hostBeID),
                  $plc['name'],
                  $book['firstday'],
                  $book['lastday'],
                  $book['fromd'],
                  $book['fromm'],
                  $book['fromy'],
                  $book['tod'],
                  $book['tom'],
                  $book['toy'],
                  $book['totalprice'],
                  $book['totalcurrency']
                );
                bookingPaymentDetailsMail(
                  $fold,
                  getFrontendId($bookingBeId),
                  $book['language'],
                  $book['name'],
                  $book['email'],
                  getFrontendId($plcBeID),
                  $hostBeID,
                  getFrontendId($hostBeID),
                  $plc['name'],
                  $book['firstday'],
                  $book['lastday'],
                  $book['fromd'],
                  $book['fromm'],
                  $book['fromy'],
                  $book['tod'],
                  $book['tom'],
                  $book['toy'],
                  $book['fulldate'],
                  $book['totalprice'],
                  $book['totalcurrency'],
                  $lessThan48h
                );
                bookingDetailsMail(
                  $fold,
                  getFrontendId($bookingBeId),
                  $book['language'],
                  $hostBeID,
                  $hst['contactemail'],
                  getFrontendId($plcBeID),
                  $plc['name'],
                  $book['name'],
                  $book['email'],
                  $book['phonenum'],
                  $book['guestnum'],
                  $book['firstday'],
                  $book['lastday'],
                  $book['fromd'].". ".$book['fromm'].". ".$book['fromy'],
                  $book['tod'].". ".$book['tom'].". ".$book['toy'],
                  $book['fulldate'],
                  $book['totalprice'],
                  $feePerc,
                  $book['totalcurrency']
                );
                return "mails";
              } else {
                return "done";
              }
            } else {
              return "update-booking-database-failed";
            }
          } else {
            return "update-booking-dates-failed";
          }
        } else {
          return "host-not-exist";
        }
      } else {
        return "place-not-exist";
      }
    } else {
      return "booking-not-exist";
    }
  }
?>
