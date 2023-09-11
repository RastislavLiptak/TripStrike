<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/uni/code/php-backend/random-hash-maker.php";
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/edit-booking-archive/add-booking-archive.php';
  if (!function_exists('updateBookingArchive')) {
    function updateBookingArchive($bookingBeId, $hostBeId, $guestBeId, $plcBeId, $numOfGuests, $status, $paymentStatus, $source, $currency, $total, $feeInPerc, $plcPriceMode, $plcWorkDayPrice, $plcWeekDayPrice, $fullFrom, $fromD, $fromM, $fromY, $firstDay, $fullTo, $toD, $toM, $toY, $lastDay) {
      global $link, $linkBD;
      $date = date("Y-m-d H:i:s");
      $dateY = date("Y");
      $dateM = date("m");
      $dateD = date("d");
      $fee = $feeInPerc * $total / 100;
      $sqlArchiveCheck = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
      if ($sqlArchiveCheck->num_rows > 0) {
        $rowArBooking = $sqlArchiveCheck->fetch_assoc();
        $orgHostBeId = $rowArBooking['hostbeid'];
        $orgGuestBeId = $rowArBooking['usrbeid'];
        $orgPlcBeId = $rowArBooking['plcbeid'];
        $orgStatus = $rowArBooking['status'];
        $orgPaymentStatus = $rowArBooking['paymentStatus'];
        $orgSource = $rowArBooking['source'];
        $orgNumOfGuests = $rowArBooking['guestnum'];
        $orgCurrency = $rowArBooking['currency'];
        $orgTotal = $rowArBooking['totalprice'];
        $orgFee = $rowArBooking['fee'];
        $orgFeeInPerc = $rowArBooking['percentagefee'];
        $orgPlcPriceMode = $rowArBooking['plcpricemode'];
        $orgPlcWorkDayPrice = $rowArBooking['plcworkprice'];
        $orgPlcWeekDayPrice = $rowArBooking['plcweekprice'];
        $orgFullFrom = $rowArBooking['fromdate'];
        $orgFromY = $rowArBooking['fromy'];
        $orgFromM = $rowArBooking['fromm'];
        $orgFromD = $rowArBooking['fromd'];
        $orgFirstDay = $rowArBooking['firstday'];
        $orgFullTo = $rowArBooking['todate'];
        $orgToY = $rowArBooking['toy'];
        $orgToM = $rowArBooking['tom'];
        $orgToD = $rowArBooking['tod'];
        $orgLastDay = $rowArBooking['lastday'];
        if (
          $orgHostBeId != $hostBeId ||
          $orgGuestBeId != $guestBeId ||
          $orgPlcBeId != $plcBeId ||
          $orgStatus != $status ||
          $orgPaymentStatus != $paymentStatus ||
          $orgSource != $source ||
          $orgNumOfGuests != $numOfGuests ||
          $orgCurrency != $currency ||
          $orgTotal != $total ||
          $orgFee != $fee ||
          $orgFeeInPerc != $feeInPerc ||
          $orgPlcPriceMode != $plcPriceMode ||
          $orgPlcWorkDayPrice != $plcWorkDayPrice ||
          $orgPlcWeekDayPrice != $plcWeekDayPrice ||
          $orgFullFrom != $fullFrom ||
          $orgFromY != $fromY ||
          $orgFromM != $fromM ||
          $orgFromD != $fromD ||
          $orgFirstDay != $firstDay ||
          $orgFullTo != $fullTo ||
          $orgToY != $toY ||
          $orgToM != $toM ||
          $orgToD != $toD ||
          $orgLastDay != $lastDay
        ) {
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
          $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
          $sqlBookingUpdtArchBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$beIdBookingUpdtArch', '$backendIDNum', 'booking-update')";
          if (mysqli_query($link, $sqlBookingUpdtArchBeID)) {
            $sqlBookingUpdtArchID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$beIdBookingUpdtArch', '$idBookingUpdtArch', '$date', '$dateD', '$dateM', '$dateY')";
            if (mysqli_query($link, $sqlBookingUpdtArchID)) {
              $timeliness = checkTimelinessOfBooking($bookingBeId);
              if ($timeliness == "future" || $orgPaymentStatus != $paymentStatus) {
                if ($status != "rejected") {
                  $sqlUpdateArchive = "INSERT INTO bookingupdatearchive (beid, bookingbeid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$beIdBookingUpdtArch', '$bookingBeId', '$hostBeId', '$guestBeId', '$plcBeId', '$status', '$paymentStatus', '$source', '$numOfGuests', '$currency', '$total', '$fee', '$feeInPerc', '$plcPriceMode', '$plcWorkDayPrice', '$plcWeekDayPrice', '$fullFrom', '$fromY', '$fromM', '$fromD', '$firstDay', '$fullTo', '$toY', '$toM', '$toD', '$lastDay', '$date', '$dateY', '$dateM', '$dateD')";
                  if (mysqli_query($linkBD, $sqlUpdateArchive)) {
                    $sqlSave = "UPDATE bookingarchive SET hostbeid='$hostBeId', usrbeid='$guestBeId', plcbeid='$plcBeId', status='$status', paymentStatus='$paymentStatus', source='$source', guestnum='$numOfGuests', currency='$currency', totalprice='$total', fee='$fee', percentagefee='$feeInPerc', plcpricemode='$plcPriceMode', plcworkprice='$plcWorkDayPrice', plcweekprice='$plcWeekDayPrice', fromdate='$fullFrom', fromy='$fromY', fromm='$fromM', fromd='$fromD', firstday='$firstDay', todate='$fullTo', toy='$toY', tom='$toM', tod='$toD', lastday='$lastDay' WHERE beid='$bookingBeId'";
                    if (mysqli_query($linkBD, $sqlSave)) {
                      return "done";
                    } else {
                      return "Archive database error: <br>".mysqli_error($linkBD);
                    }
                  } else {
                    return "Update archive database error: <br>".mysqli_error($linkBD);
                  }
                } else {
                  $sqlReject = "UPDATE bookingarchive SET hostbeid='-', usrbeid='-', plcbeid='-', status='$status', paymentStatus='$paymentStatus', source='-', guestnum='0', currency='-', totalprice='0', fee='0', percentagefee='0', plcpricemode='-', plcworkprice='0', plcweekprice='0', fromdate='0001-01-01', fromy='0', fromm='0', fromd='0', firstday='-', todate='0001-01-01', toy='0', tom='0', tod='0', lastday='-' WHERE beid='$bookingBeId'";
                  if (mysqli_query($linkBD, $sqlReject)) {
                    $sqlUpdateDelete = "DELETE FROM bookingupdatearchive WHERE bookingbeid='$bookingBeId'";
                    mysqli_query($linkBD, $sqlUpdateDelete);
                    $sqlUpdateArchive = "INSERT INTO bookingupdatearchive (beid, bookingbeid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$beIdBookingUpdtArch', '$bookingBeId', '-', '-', '-', '$status', '$paymentStatus', '-', '0', '-', '0', '0', '0', '-', '0', '0', '0001-01-01', '0', '0', '0', '-', '0001-01-01', '0', '0', '0', '-', '$date', '$dateY', '$dateM', '$dateD')";
                    if (mysqli_query($linkBD, $sqlUpdateArchive)) {
                      return "done";
                    } else {
                      return "Archive database rejection info error: <br>".mysqli_error($linkBD);
                    }
                  } else {
                    return "Archive database error: <br>".mysqli_error($linkBD);
                  }
                }
              } else if ($timeliness == "past") {
                $sqlAdditionalUpdatesArchive = "INSERT INTO bookingadditionalupdatesarchive (beid, bookingbeid, hostbeid, usrbeid, plcbeid, status, paymentStatus, source, guestnum, currency, totalprice, fee, percentagefee, plcpricemode, plcworkprice, plcweekprice, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$beIdBookingUpdtArch', '$bookingBeId', '$hostBeId', '$guestBeId', '$plcBeId', '$status', '$paymentStatus', '$source', '$numOfGuests', '$currency', '$total', '$fee', '$feeInPerc', '$plcPriceMode', '$plcWorkDayPrice', '$plcWeekDayPrice', '$fullFrom', '$fromY', '$fromM', '$fromD', '$firstDay', '$fullTo', '$toY', '$toM', '$toD', '$lastDay', '$date', '$dateY', '$dateM', '$dateD')";
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
        } else {
          return "done";
        }
      } else {
        addBookingArchive(
          $bookingBeId,
          $hostBeId,
          $guestBeId,
          $plcBeId,
          $numOfGuests,
          $status,
          $paymentStatus,
          $source,
          $currency,
          $total,
          $feeInPerc,
          $plcPriceMode,
          $plcWorkDayPrice,
          $plcWeekDayPrice,
          $fullFrom,
          $fromD,
          $fromM,
          $fromY,
          $firstDay,
          $fullTo,
          $toD,
          $toM,
          $toY,
          $lastDay
        );
      }
    }
  }
?>
