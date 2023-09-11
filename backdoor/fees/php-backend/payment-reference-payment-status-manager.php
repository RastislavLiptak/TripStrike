<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  include "../../uni/code/php-backend/booking-fee-payment.php";
  header('Content-Type: application/json');
  $output = [];
  $listOfBookingsBeIDs = [];
  $paymentReferenceCode = mysqli_real_escape_string($link, $_POST['paymentReference']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $sqlPaymentReferenceBeId = $linkBD->query("SELECT beid FROM feespaymentcalls WHERE paymentreference='$paymentReferenceCode'");
    if ($sqlPaymentReferenceBeId->num_rows > 0) {
      $paymentReferenceBeId = $sqlPaymentReferenceBeId->fetch_assoc()["beid"];
      $sqlAllBookings = $linkBD->query("SELECT bookingbeid FROM feespaymentcallskey WHERE beid='$paymentReferenceBeId'");
      if ($sqlAllBookings->num_rows > 0) {
        while ($rowAllBookings = $sqlAllBookings->fetch_assoc()) {
          array_push($listOfBookingsBeIDs, $rowAllBookings['bookingbeid']);
        }
        bookingFeePayment($listOfBookingsBeIDs);
      } else {
        error("No bookings has been found");
      }
    } else {
      error("Payment reference code does not mach with data in database");
    }
  } else {
    error($backDoorCheckSignInSts);
  }

  function getPaymentReferenceStatus($paymentReferenceBeId) {
    global $linkBD, $wrd_unknown, $wrd_rejected, $wrd_canceled, $wrd_waitingForConfirmation, $wrd_booked, $wrd_paid, $wrd_unpaid;
    $status = "";
    $paymentStatus = "none";
    $sqlPaymentReferencesKey = $linkBD->query("SELECT * FROM feespaymentcallskey WHERE beid='$paymentReferenceBeId'");
    if ($sqlPaymentReferencesKey->num_rows > 0) {
      while($rowPaymentReferencesKey = $sqlPaymentReferencesKey->fetch_assoc()) {
        $bookingBeId = $rowPaymentReferencesKey['bookingbeid'];
        $sqlBooking = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
        if ($sqlBooking->num_rows > 0) {
          while ($rowBooking = $sqlBooking->fetch_assoc()) {
            $bookingSts = $rowBooking['status'];
            if ($rowBooking['paymentStatus'] == 1) {
              if ($paymentStatus == "none" || $paymentStatus == "paid") {
                $paymentStatus = "paid";
              } else {
                $paymentStatus = "multiple";
              }
            } else {
              if ($paymentStatus == "none" || $paymentStatus == "unpaid") {
                $paymentStatus = "unpaid";
              } else {
                $paymentStatus = "multiple";
              }
            }
            if ($paymentStatus == "paid") {
              $bookingSts = "paid";
            } else {
              if (checkTimelinessOfBooking($rowBooking['beid']) == "past" && $rowBooking['tom']."-".$rowBooking['toy'] != (int)date("m")."-".date("Y")) {
                if ($bookingSts == "waiting" || $bookingSts == "booked") {
                  if ($rowBooking['paymentStatus'] == 1) {
                    $bookingSts = "paid";
                  } else {
                    $bookingSts = "unpaid";
                  }
                }
              }
            }
            if (giveStatusValue($status) < giveStatusValue($bookingSts)) {
              $status = $bookingSts;
            }
          }
        }
      }
    }
    if ($status == "") {
      return "<i>".$wrd_unknown." (empty)</i>";
    } else if ($status == "-") {
      return "<i>".$wrd_unknown." (-)</i>";
    } else if ($status == "rejected") {
      return "<i>".$wrd_rejected."</i>";
    } else if ($status == "canceled") {
      return "<i>".$wrd_canceled."</i>";
    } else if ($status == "waiting") {
      return $wrd_waitingForConfirmation;
    } else if ($status == "booked") {
      return $wrd_booked;
    } else if ($status == "paid") {
      return "<span class='table-data-green'>".$wrd_paid."</span>";
    } else if ($status == "unpaid") {
      return "<span class='table-data-red'>".$wrd_unpaid."</span>";
    }
  }

  function giveStatusValue($status) {
    $stsValueArray = ["", "-", "rejected", "canceled", "waiting", "paid", "booked", "unpaid"];
    return array_search($status, $stsValueArray);
  }

  function bookingFeePaymentOutput($sts, $task, $msg) {
    if ($sts == "done") {
      if ($msg == "") {
        done($task);
      } else {
        error("Done, BUT <br>".$msg);
      }
    } else {
      error($msg);
    }
  }

  function done($task) {
    global $output, $wrd_paid, $wrd_unpaid, $paymentReferenceBeId;
    array_push($output, [
      "type" => "done",
      "task" => $task,
      "status" => getPaymentReferenceStatus($paymentReferenceBeId),
      "paid" => $wrd_paid,
      "unpaid" => $wrd_unpaid
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
