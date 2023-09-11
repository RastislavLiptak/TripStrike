<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../editor/php-backend/place-verification.php";
  include "../php-backend/confirm-booking.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/host-confirmed-the-booking-mail.php";
  include "../../email-sender/php-backend/booking-details-mail.php";
  include "../../email-sender/php-backend/booking-payment-details-mail.php";
  header('Content-Type: application/json');
  $output = [];
  $plcID = mysqli_real_escape_string($link, $_POST['plc_id']);
  $f_d = mysqli_real_escape_string($link, $_POST['f_d']);
  $f_m = mysqli_real_escape_string($link, $_POST['f_m']);
  $f_y = mysqli_real_escape_string($link, $_POST['f_y']);
  $t_d = mysqli_real_escape_string($link, $_POST['t_d']);
  $t_m = mysqli_real_escape_string($link, $_POST['t_m']);
  $t_y = mysqli_real_escape_string($link, $_POST['t_y']);
  $placeVerify = placeVerification($plcID);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcID' LIMIT 1");
    if ($sqlIdToBeId->num_rows > 0) {
      $plcBeId = $sqlIdToBeId->fetch_assoc()["beid"];
      $sqlGetBooking = $link->query("SELECT * FROM booking WHERE plcbeid='$plcBeId' and fromy='$f_y' and fromm='$f_m' and fromd='$f_d' and toy='$t_y' and tom='$t_m' and tod='$t_d' and status='waiting' LIMIT 1");
      if ($sqlGetBooking->num_rows > 0) {
        $bookingBeID = $sqlGetBooking->fetch_assoc()["beid"];
        $confirmSts = confirmBooking($bookingBeID, "../../");
        if ($confirmSts != "mails") {
          if ($confirmSts == "done") {
            done();
          } else {
            error($confirmSts);
          }
        }
      } else {
        error("booking-not-found");
      }
    } else {
      error("place-id-not-connected-to-backend");
    }
  } else {
    error($placeVerify);
  }

  $bookingOfferErrorTxt = "";
  $hostConfirmedTheBookingMailDone = false;
  $bookingPaymentDetailsMailDone = false;
  $bookingDetailsMailDone = false;
  function mailDone($msg, $mailType) {
    global $hostConfirmedTheBookingMailDone, $bookingPaymentDetailsMailDone, $bookingDetailsMailDone, $bookingOfferErrorTxt;
    if ($msg != "done") {
      if ($bookingOfferErrorTxt == "") {
        $bookingOfferErrorTxt = $mailType.": ".$msg;
      } else {
        $bookingOfferErrorTxt = $bookingOfferErrorTxt."<br>".$mailType.": ".$msg;
      }
    }
    if ($mailType == "host-confirmed-the-booking") {
      $hostConfirmedTheBookingMailDone = true;
    } else if ($mailType == "booking-payment-details") {
      $bookingPaymentDetailsMailDone = true;
    } else if ($mailType == "booking-details") {
      $bookingDetailsMailDone = true;
    }
    if ($hostConfirmedTheBookingMailDone && $bookingPaymentDetailsMailDone && $bookingDetailsMailDone) {
      if ($bookingOfferErrorTxt == "") {
        done();
      } else {
        error($bookingOfferErrorTxt);
      }
    }
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
