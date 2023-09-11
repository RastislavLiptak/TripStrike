<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/uni/code/php-backend/random-hash-maker.php";
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  if (!function_exists('cancelBookingArchive')) {
    function cancelBookingArchive($bookingBeId) {
      global $link, $linkBD;
      $date = date("Y-m-d H:i:s");
      $dateY = date("Y");
      $dateM = date("m");
      $dateD = date("d");
      $beIdBookingUpdtArchReady = false;
      while (!$beIdBookingUpdtArchReady) {
        $beIdBookingUpdtArch = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$beIdBookingUpdtArch'")->num_rows == 0) {
          $beIdBookingUpdtArchReady = true;
        } else {
          $beIdBookingUpdtArchReady = false;
        }
      }
      $idBookingUpdtArchReady = false;
      while (!$idBookingUpdtArchReady) {
        $idBookingUpdtArch = randomHash(11);
        if ($link->query("SELECT * FROM idlist WHERE id='$idBookingUpdtArch'")->num_rows == 0) {
          $idBookingUpdtArchReady = true;
        } else {
          $idBookingUpdtArchReady = false;
        }
      }
      $paymentStatus = 0;
      $hostBeID = "-";
      $plcBeID = "-";
      $fromdate = "0001-01-01";
      $fromy = "0";
      $fromm = "0";
      $fromd = "0";
      $firstday = "-";
      $todate = "0001-01-01";
      $toy = "0";
      $tom = "0";
      $tod = "0";
      $lastday = "-";
      $sqlBookingsArchive = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId'");
      if ($sqlBookingsArchive->num_rows > 0) {
        $bookArch = $sqlBookingsArchive->fetch_assoc();
        $paymentStatus = $bookArch['paymentStatus'];
        $hostBeID = $bookArch['hostbeid'];
        $plcBeID = $bookArch['plcbeid'];
        $fromdate = $bookArch['fromdate'];
        $fromy = $bookArch['fromy'];
        $fromm = $bookArch['fromm'];
        $fromd = $bookArch['fromd'];
        $firstday = $bookArch['firstday'];
        $todate = $bookArch['todate'];
        $toy = $bookArch['toy'];
        $tom = $bookArch['tom'];
        $tod = $bookArch['tod'];
        $lastday = $bookArch['lastday'];
      }
      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
      $sqlBookingUpdtArchBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$beIdBookingUpdtArch', '$backendIDNum', 'booking-update')";
      if (mysqli_query($link, $sqlBookingUpdtArchBeID)) {
        $sqlBookingUpdtArchID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$beIdBookingUpdtArch', '$idBookingUpdtArch', '$date', '$dateD', '$dateM', '$dateY')";
        if (mysqli_query($link, $sqlBookingUpdtArchID)) {
          $timeliness = checkTimelinessOfBooking($bookingBeId);
          if ($timeliness == "future") {
            $sqlCancel = "UPDATE bookingarchive SET usrbeid='-', status='canceled', source='-', guestnum='0', currency='eur', totalprice='0', fee='0', percentagefee='0', plcpricemode='-', plcworkprice='0', plcweekprice='0' WHERE beid='$bookingBeId'";
            if (mysqli_query($linkBD, $sqlCancel)) {
              $sqlUpdateDelete = "DELETE FROM bookingupdatearchive WHERE bookingbeid='$bookingBeId'";
              mysqli_query($linkBD, $sqlUpdateDelete);
              $sqlUpdateArchive = "INSERT INTO bookingupdatearchive (beid, bookingbeid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$beIdBookingUpdtArch', '$bookingBeId', '$hostBeID', '-', '$plcBeID', 'canceled', '$paymentStatus', '-', '0', 'eur', '0', '0', '0', '-', '0', '0', '$fromdate', '$fromy', '$fromm', '$fromd', '$firstday', '$todate', '$toy', '$tom', '$tod', '$lastday', '$date', '$dateY', '$dateM', '$dateD')";
              if (mysqli_query($linkBD, $sqlUpdateArchive)) {
                return "done";
              } else {
                return "Archive database cancelation info error: <br>".mysqli_error($linkBD);
              }
            } else {
              return "Archive database error: <br>".mysqli_error($linkBD);
            }
          } else {
            $sqlAdditionalUpdatesArchive = "INSERT INTO bookingadditionalupdatesarchive (beid, bookingbeid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$beIdBookingUpdtArch', '$bookingBeId', '$hostBeID', '-', '$plcBeID', 'canceled', '$paymentStatus', '-', '0', 'eur', '0', '0', '0', '-', '0', '0', '$fromdate', '$fromy', '$fromm', '$fromd', '$firstday', '$todate', '$toy', '$tom', '$tod', '$lastday', '$date', '$dateY', '$dateM', '$dateD')";
            if (mysqli_query($linkBD, $sqlAdditionalUpdatesArchive)) {
              return "done";
            } else {
              return "Additional updates archive database error: <br>".mysqli_error($linkBD);
            }
          }
        } else {
          return "Update archive database error (failed to save ID): <br>".mysqli_error($link);
        }
      } else {
        return "Update archive database error (failed to save backend ID): <br>".mysqli_error($link);
      }
    }
  }
?>
