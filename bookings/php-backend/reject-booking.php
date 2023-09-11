<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/backdoor/uni/code/php-backend/edit-booking-archive/update-booking-archive.php";
  function rejectBooking($bookingBeId, $fold) {
    global $link, $linkBD;
    $date = date("Y-m-d H:i:s");
    $sqlBookings = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
    if ($sqlBookings->num_rows > 0) {
      $book = $sqlBookings->fetch_assoc();
      $plcBeID = $book['plcbeid'];
      $sqlPlaces = $link->query("SELECT * FROM places WHERE beid='$plcBeID' and status='active' LIMIT 1");
      if ($sqlPlaces->num_rows > 0) {
        $plc = $sqlPlaces->fetch_assoc();
        $sqlArchiveCheck = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
        if ($sqlArchiveCheck->num_rows > 0) {
          $rowArBooking = $sqlArchiveCheck->fetch_assoc();
          $editBookingArchiveSts = updateBookingArchive(
            $bookingBeId,
            $plc['usrbeid'],
            $book['usrbeid'],
            $plcBeID,
            $book['guestnum'],
            "rejected",
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
          $editBookingArchiveSts = "Booking was not found in the database";
        }
        if ($editBookingArchiveSts == "done") {
          $sqlBookingDatesUpdt = "UPDATE bookingdates SET status='canceled' WHERE beid='$bookingBeId'";
          if (mysqli_query($link, $sqlBookingDatesUpdt)) {
            $sqlBookingUpdt = "UPDATE booking SET status='canceled' WHERE beid='$bookingBeId'";
            if (mysqli_query($link, $sqlBookingUpdt)) {
              if ($book['firstday'] != "whole") {
                $f_date = $book['fromy']."-".sprintf("%02d", $book['fromm'])."-".sprintf("%02d", $book['fromd'])." 14:00:00";
              } else {
                $f_date = $book['fromy']."-".sprintf("%02d", $book['fromm'])."-".sprintf("%02d", $book['fromd'])." 00:00:00";
              }
              if ($f_date > $date) {
                hostRejectedTheBookingMail(
                  $fold,
                  $book['language'],
                  $book['email'],
                  $book['guestnum'],
                  getFrontendId($plcBeID),
                  $plc['usrbeid'],
                  getFrontendId($plc['usrbeid']),
                  $plc['name'],
                  $book['firstday'],
                  $book['lastday'],
                  $book['fromd'],
                  $book['fromm'],
                  $book['fromy'],
                  $book['tod'],
                  $book['tom'],
                  $book['toy']
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
          return "Booking archive error: ".$editBookingArchiveSts;
        }
      } else {
        return "place-not-exist";
      }
    } else {
      return "booking-not-exist";
    }
  }
?>
