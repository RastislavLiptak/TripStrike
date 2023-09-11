<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/edit-booking-archive/update-booking-archive.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/email-sender/php-backend/send-mail.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/email-sender/php-backend/confirmation-of-fees-payment-mail.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/email-sender/php-backend/change-in-the-status-of-fees-payment-mail.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/uni/code/php-backend/add-currency.php';
  $bookingPaymentEmailHostsList = [];
  $updateBookingArchiveErrors = "";
  $task = "none";
  function bookingFeePayment($listOfBookingsBeIDs) {
    global $linkBD, $task, $link, $bookingPaymentEmailHostsList, $updateBookingArchiveErrors;
    for ($b=0; $b < sizeof($listOfBookingsBeIDs); $b++) {
      $bookingBeId = $listOfBookingsBeIDs[$b];
      $sqlBookingArch = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId'");
      if ($sqlBookingArch->num_rows > 0) {
        while($rowBookingArch = $sqlBookingArch->fetch_assoc()) {
          if ($rowBookingArch['paymentStatus'] == "0") {
            if ($task == "none" || $task == "paid") {
              $task = "paid";
            } else {
              $task = "multiple";
            }
          } else {
            if ($task == "none" || $task == "unpaid") {
              $task = "unpaid";
            } else {
              $task = "multiple";
            }
          }
        }
      }
    }
    if ($task != "none" && $task != "multiple") {
      if ($task == "paid") {
        $paymentStatus = 1;
      } else {
        $paymentStatus = 0;
      }
      $updateBookingArchiveDoneList = [];
      for ($bU=0; $bU < sizeof($listOfBookingsBeIDs); $bU++) {
        $bookingBeId = $listOfBookingsBeIDs[$bU];
        $sqlBookingArch = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId'");
        if ($sqlBookingArch->num_rows > 0) {
          while($rowBookingArch = $sqlBookingArch->fetch_assoc()) {
            $updateBookingArchiveOutput = updateBookingArchive(
              $bookingBeId,
              $rowBookingArch['hostbeid'],
              $rowBookingArch['usrbeid'],
              $rowBookingArch['plcbeid'],
              $rowBookingArch['guestnum'],
              $rowBookingArch['status'],
              $paymentStatus,
              $rowBookingArch['source'],
              $rowBookingArch['currency'],
              $rowBookingArch['totalprice'],
              $rowBookingArch['percentagefee'],
              $rowBookingArch['plcpricemode'],
              $rowBookingArch['plcworkprice'],
              $rowBookingArch['plcweekprice'],
              $rowBookingArch['fromdate'],
              $rowBookingArch['fromd'],
              $rowBookingArch['fromm'],
              $rowBookingArch['fromy'],
              $rowBookingArch['firstday'],
              $rowBookingArch['todate'],
              $rowBookingArch['tod'],
              $rowBookingArch['tom'],
              $rowBookingArch['toy'],
              $rowBookingArch['lastday']
            );
            if ($updateBookingArchiveOutput != "done") {
              if ($updateBookingArchiveErrors == "") {
                $updateBookingArchiveErrors = $updateBookingArchiveOutput;
              } else {
                $updateBookingArchiveErrors = $updateBookingArchiveErrors."<br>".$updateBookingArchiveOutput;
              }
            } else {
              array_push($updateBookingArchiveDoneList, $bookingBeId);
            }
          }
        }
      }
      for ($bE=0; $bE < sizeof($updateBookingArchiveDoneList); $bE++) {
        $bookingBeId = $updateBookingArchiveDoneList[$bE];
        $sqlBookingArch = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId'");
        if ($sqlBookingArch->num_rows > 0) {
          while($rowBookingArch = $sqlBookingArch->fetch_assoc()) {
            if (!in_array($rowBookingArch['hostbeid'], $bookingPaymentEmailHostsList)) {
              array_push($bookingPaymentEmailHostsList, $rowBookingArch['hostbeid']);
            }
          }
        }
      }
      for ($h=0; $h < sizeof($bookingPaymentEmailHostsList); $h++) {
        $updatedBookingsData = [];
        $updatedBookingsTotalFees = 0;
        $bookingPaymentStatus = 0;
        $bookingCurrency = "eur";
        $hostBeId = $bookingPaymentEmailHostsList[$h];
        $sqlAllBookings = $linkBD->query("SELECT * FROM bookingarchive WHERE hostbeid='$hostBeId' and beid IN ('".implode("', '", $updateBookingArchiveDoneList)."')");
        if ($sqlAllBookings->num_rows > 0) {
          while ($rowAllBookings = $sqlAllBookings->fetch_assoc()) {
            $plcBeId = $rowAllBookings['plcbeid'];
            $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
            if ($sqlPlc->num_rows > 0) {
              $plc = $sqlPlc->fetch_assoc();
              $placeName = $plc['name'];
            } else {
              $placeName = "-";
            }
            array_push($updatedBookingsData, [
              "fromD" => $rowAllBookings['fromd'],
              "fromM" => $rowAllBookings['fromm'],
              "fromY" => $rowAllBookings['fromy'],
              "toD" => $rowAllBookings['tod'],
              "toM" => $rowAllBookings['tom'],
              "toY" => $rowAllBookings['toy'],
              "placeName" => $placeName,
              "placeID" => getFrontendId($plcBeId),
              "paymentStatus" => $rowAllBookings['paymentStatus'],
              "fee" => $rowAllBookings['fee']
            ]);
            $bookingPaymentStatus = $rowAllBookings['paymentStatus'];
            $updatedBookingsTotalFees = $updatedBookingsTotalFees + $rowAllBookings['fee'];
            $bookingCurrency = $rowAllBookings['currency'];
          }
        }
        $hostContactEmail = "-";
        $hostLanguage = "-";
        $sqlHostData = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$hostBeId' and status='active'");
        if ($sqlHostData->num_rows > 0) {
          $hst = $sqlHostData->fetch_assoc();
          $hostContactEmail = $hst['contactemail'];
          $hostLanguage = $hst['language'];
        }
        if ($hostContactEmail != "-") {
          if ($bookingPaymentStatus == "1") {
            confirmationOfFeesPaymentMail(
              $hostLanguage,
              $hostContactEmail,
              $updatedBookingsData,
              $updatedBookingsTotalFees,
              $bookingCurrency
            );
          } else {
            changeInTheStatusOfFeesPaymentMail(
              $hostLanguage,
              $hostContactEmail,
              $updatedBookingsData,
              $updatedBookingsTotalFees,
              $bookingCurrency
            );
          }
        } else {
          if ($bookingPaymentStatus == "1") {
            mailDone("done", "confirmation-of-fees-payment");
          } else {
            mailDone("done", "change-in-the-status-of-fees-payment");
          }
        }
      }
    } else {
      if ($task == "none") {
        bookingFeePaymentOutput("error", "", "No booking has been found");
      } else if ($task == "multiple") {
        bookingFeePaymentOutput("error", "", "Multiple payment tasks");
      }
    }
  }

  $numOfDoneMails = 0;
  $mailFailedErrors = "";
  function mailDone($sts, $mailType) {
    global $numOfDoneMails, $mailFailedErrors, $bookingPaymentEmailHostsList, $updateBookingArchiveErrors, $task;
    if ($sts != "done") {
      if ($mailFailedErrors == "") {
        $mailFailedErrors = "Email sending failed";
      } else {
        $mailFailedErrors = $mailFailedErrors."<br>Email sending failed";
      }
    }
    ++$numOfDoneMails;
    if ($numOfDoneMails >= sizeof($bookingPaymentEmailHostsList)) {
      if ($updateBookingArchiveErrors != "") {
        $mailFailedErrors = $updateBookingArchiveErrors."<br>".$mailFailedErrors;
      }
      if ($mailFailedErrors == "") {
        bookingFeePaymentOutput("done", $task, "");
      } else {
        bookingFeePaymentOutput("done", $task, $mailFailedErrors);
      }
    }
  }
?>
