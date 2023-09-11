<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  if (!function_exists('addBookingArchive')) {
    function addBookingArchive($bookingBeId, $hostBeId, $guestBeId, $plcBeId, $numOfGuests, $status, $paymentStatus, $source, $currency, $total, $feeInPerc, $plcPriceMode, $plcWorkDayPrice, $plcWeekDayPrice, $fullFrom, $fromD, $fromM, $fromY, $firstDay, $fullTo, $toD, $toM, $toY, $lastDay) {
      global $link, $linkBD;
      $date = date("Y-m-d H:i:s");
      $dateY = date("Y");
      $dateM = date("m");
      $dateD = date("d");
      $timeliness = checkTimelinessOfBooking($bookingBeId);
      if ($timeliness != "future") {
        $feeInPerc = 0;
      }
      $fee = $feeInPerc * $total / 100;
      $sqlSave = "INSERT INTO bookingarchive (beid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$bookingBeId', '$hostBeId', '$guestBeId', '$plcBeId', '$status', '$paymentStatus', '$source', '$numOfGuests', '$currency', '$total', '$fee', '$feeInPerc', '$plcPriceMode', '$plcWorkDayPrice', '$plcWeekDayPrice', '$fullFrom', '$fromY', '$fromM', '$fromD', '$firstDay', '$fullTo', '$toY', '$toM', '$toD', '$lastDay', '$date', '$dateY', '$dateM', '$dateD')";
      if (mysqli_query($linkBD, $sqlSave)) {
        $updtArchBeIdReady = false;
        while (!$updtArchBeIdReady) {
          $updtArchBeId = randomHash(64);
          if ($link->query("SELECT * FROM backendidlist WHERE beid='$updtArchBeId'")->num_rows == 0) {
            $updtArchBeIdReady = true;
          } else {
            $updtArchBeIdReady = false;
          }
        }
        $updtArchIDReady = false;
        while (!$updtArchIDReady) {
          $updtArchID = randomHash(11);
          if ($link->query("SELECT * FROM idlist WHERE id='$updtArchID'")->num_rows == 0) {
            $updtArchIDReady = true;
          } else {
            $updtArchIDReady = false;
          }
        }
        $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
        $sqlBookingUpdtArchBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$updtArchBeId', '$backendIDNum', 'booking-update')";
        if (mysqli_query($link, $sqlBookingUpdtArchBeID)) {
          $sqlBookingUpdtArchID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$updtArchBeId', '$updtArchID', '$date', '$dateD', '$dateM', '$dateY')";
          if (mysqli_query($link, $sqlBookingUpdtArchID)) {
            $sqlUpdateArchive = "INSERT INTO bookingupdatearchive (beid, bookingbeid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$updtArchBeId', '$bookingBeId', '$hostBeId', '$guestBeId', '$plcBeId', '$status', '$paymentStatus', '$source', '$numOfGuests', '$currency', '$total', '$fee', '$feeInPerc', '$plcPriceMode', '$plcWorkDayPrice', '$plcWeekDayPrice', '$fullFrom', '$fromY', '$fromM', '$fromD', '$firstDay', '$fullTo', '$toY', '$toM', '$toD', '$lastDay', '$date', '$dateY', '$dateM', '$dateD')";
            if (mysqli_query($linkBD, $sqlUpdateArchive)) {
              return "done";
            } else {
              return "Add booking update archive database error: <br>".mysqli_error($linkBD);
            }
          } else {
            return "Add booking update archive database error (failed to save ID): <br>".mysqli_error($linkBD);
          }
        } else {
          return "Add booking update archive database error (failed to save backend ID): <br>".mysqli_error($linkBD);
        }
      } else {
        return "Database error: <br>".mysqli_error($linkBD);
      }
    }
  }
?>
