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
  $bookingID = mysqli_real_escape_string($link, $_POST['bookingID']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $sqlBookingIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingID' LIMIT 1");
    if ($sqlBookingIdToBeId->num_rows > 0) {
      $bookingBeId = $sqlBookingIdToBeId->fetch_assoc()["beid"];
      array_push($listOfBookingsBeIDs, $bookingBeId);
      bookingFeePayment($listOfBookingsBeIDs);
    } else {
      error("Booking ID not exist");
    }
  } else {
    error($backDoorCheckSignInSts);
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
    global $output, $wrd_paid, $wrd_unpaid;
    array_push($output, [
      "type" => "done",
      "task" => $task,
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
