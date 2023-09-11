<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "../editor/php-backend/place-verification.php";
  include "php-backend/confirm-booking.php";
  include "php-backend/reject-booking.php";
  include "../email-sender/php-backend/send-mail.php";
  include "../email-sender/php-backend/host-confirmed-the-booking-mail.php";
  include "../email-sender/php-backend/host-rejected-the-booking-mail.php";
  include "../email-sender/php-backend/booking-details-mail.php";
  include "../email-sender/php-backend/booking-payment-details-mail.php";
  if (isset($_GET['task'])) {
    $tsk = $_GET['task'];
  } else {
    $tsk = "none";
  }
  if ($tsk == "confirm") {
    $subtitle = $wrd_confirmBooking;
  } else if ($tsk = "reject") {
    $subtitle = $wrd_rejectBooking;
  } else {
    $subtitle = $wrd_unknown;
  }
  $bookingOfferTaskDoneSts = false;
  $bookingOfferErrorTxt = "";
  if (isset($_GET['plc'])) {
    $placeVerify = placeVerification($_GET['plc']);
    if ($placeVerify == "good") {
      $plcID = $_GET['plc'];
      $sqlPlcIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcID' LIMIT 1");
      $plcBeId = $sqlPlcIdToBeId->fetch_assoc()["beid"];
      if (isset($_GET['booking'])) {
        $bookingId = $_GET['booking'];
        $sqlBookingIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingId' LIMIT 1");
        if ($sqlBookingIdToBeId->num_rows > 0) {
          $bookingBeId = $sqlBookingIdToBeId->fetch_assoc()["beid"];
          $sqlCheckBookingDtb = $link->query("SELECT status FROM booking WHERE beid='$bookingBeId' and plcbeid='$plcBeId' LIMIT 1");
          if ($sqlCheckBookingDtb->num_rows > 0) {
            $bookingSts = $sqlCheckBookingDtb->fetch_assoc()["status"];
            if ($bookingSts == "waiting") {
              if ($tsk == "confirm") {
                $taskFunc = confirmBooking($bookingBeId, "../");
              } else if ($tsk = "reject") {
                $taskFunc = rejectBooking($bookingBeId, "../");
              } else {
                $taskFunc = "unknown task";
              }
              if ($taskFunc != "done" && $taskFunc != "mails") {
                $bookingOfferErrorTxt = "confirm/reject func. error: ".$taskFunc;
                $bookingOfferTaskDoneSts = true;
              }
            } else if ($bookingSts == "booked") {
              $bookingOfferErrorTxt = $wrd_thisBookingHasAlreadyBeenConfirmed;
              $bookingOfferTaskDoneSts = true;
            } else if ($bookingSts == "canceled") {
              $bookingOfferErrorTxt = $wrd_thisBookingHasAlreadyBeenCanceled;
              $bookingOfferTaskDoneSts = true;
            } else {
              $bookingOfferErrorTxt = "unknown-booking-status";
              $bookingOfferTaskDoneSts = true;
            }
          } else {
           $bookingOfferErrorTxt = "booking-does-not-match-with-place";
           $bookingOfferTaskDoneSts = true;
          }
        } else {
          $bookingOfferErrorTxt = "booking-ID-from-url-not-exist";
          $bookingOfferTaskDoneSts = true;
        }
      } else {
        $bookingOfferErrorTxt = "undefined-url-booking-ID";
        $bookingOfferTaskDoneSts = true;
      }
    } else {
      $bookingOfferErrorTxt = "Place verification: ".$placeVerify;
      $bookingOfferTaskDoneSts = true;
    }
  } else {
    $bookingOfferErrorTxt = "undefined-url-place-ID";
    $bookingOfferTaskDoneSts = true;
  }

  $hostConfirmedTheBookingMailDone = false;
  $bookingPaymentDetailsMailDone = false;
  $bookingDetailsMailDone = false;
  $hostRejectedTheBookingMailDone = false;
  function mailDone($msg, $mailType) {
    global $tsk, $hostConfirmedTheBookingMailDone, $bookingPaymentDetailsMailDone, $bookingDetailsMailDone, $hostRejectedTheBookingMailDone, $bookingOfferTaskDoneSts, $bookingOfferErrorTxt;
    if ($msg != "done") {
      if ($bookingOfferErrorTxt == "") {
        $bookingOfferErrorTxt = $mailType.": ".$msg;
      } else {
        $bookingOfferErrorTxt = $bookingOfferErrorTxt."<br>".$mailType.": ".$msg;
      }
    }
    if ($tsk == "confirm") {
      if ($mailType == "host-confirmed-the-booking") {
        $hostConfirmedTheBookingMailDone = true;
      } else if ($mailType == "booking-payment-details") {
        $bookingPaymentDetailsMailDone = true;
      } else if ($mailType == "booking-details") {
        $bookingDetailsMailDone = true;
      }
      if ($hostConfirmedTheBookingMailDone && $bookingPaymentDetailsMailDone && $bookingDetailsMailDone) {
        $bookingOfferTaskDoneSts = true;
      }
    } else if ($tsk = "reject") {
      if ($mailType == "host-rejected-the-booking") {
        $hostRejectedTheBookingMailDone = true;
      }
      if ($hostRejectedTheBookingMailDone) {
        $bookingOfferTaskDoneSts = true;
      }
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/booking-offer.css">
    <?php
      include "../uni/code/php-frontend/head.php";
      if ($tsk == "confirm") {
    ?>
        <title><?php echo $wrd_confirmBooking." - ".$title; ?></title>
    <?php
      } else if ($tsk = "reject") {
    ?>
        <title><?php echo $wrd_rejectBooking." - ".$title; ?></title>
    <?php
      } else {
    ?>
        <title><?php echo $wrd_unknown." - ".$title; ?></title>
    <?php
      }
    ?>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($bookingOfferTaskDoneSts) {
    ?>
        <div id="booking-offer-wrp">
          <div id="booking-offer-blck">
            <div id="booking-offer-img-wrp">
              <?php
                if ($bookingOfferErrorTxt == "") {
              ?>
                  <img src="../uni/icons/success.svg" alt="success icon" id="booking-offer-img">
              <?php
                } else {
              ?>
                  <img src="../uni/icons/error2.svg" alt="error icon" id="booking-offer-img">
              <?php
                }
              ?>
            </div>
            <div id="booking-offer-txt">
              <?php
                if ($bookingOfferErrorTxt == "") {
                  if ($tsk == "confirm") {
              ?>
                    <p id="booking-offer-txt-done"><?php echo $wrd_bookingWasConfirmed; ?></p>
              <?php
                  } else if ($tsk = "reject") {
              ?>
                    <p id="booking-offer-txt-done"><?php echo $wrd_bookingWasRejected; ?></p>
              <?php
                  }
                } else {
              ?>
                  <p id="booking-offer-txt-error"><?php echo $bookingOfferErrorTxt; ?></p>
              <?php
                }
              ?>
            </div>
          </div>
        </div>
    <?php
      }
    ?>
  </body>
</html>
