<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "place-verification.php";
  include "../../bookings/php-backend/reject-booking.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/host-rejected-the-booking-mail.php";
  include "place-delete.php";
  header('Content-Type: application/json');
  $output = [];
  $bookingRejectError = "";
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
    $beId = $sqlIdToBeId->fetch_assoc()["beid"];
    $today = date("Y-m-d");
    $sqlBookingR = $link->query("SELECT * FROM booking WHERE plcbeid='$beId' and status='waiting'");
    $numOfBookingsToHandle = $sqlBookingR->num_rows;
    if ($numOfBookingsToHandle > 0) {
      while ($bookingR = $sqlBookingR->fetch_assoc()) {
        $bookingBeId = $bookingR['beid'];
        $sqlRejectBookingUpdateRequests = "UPDATE bookingupdaterequest SET status='rejected' WHERE bookingbeid='$bookingBeId'";
        mysqli_query($link, $sqlRejectBookingUpdateRequests);
        $rejectSts = rejectBooking($bookingBeId, "../../");
        if ($rejectSts != "mails") {
          if ($rejectSts == "done") {
            handledBookingsManager();
          } else {
            if ($bookingRejectError == "") {
              $bookingRejectError = "Booking rejection error: ".$rejectSts;
            } else {
              $bookingRejectError = $bookingRejectError."<br>Booking rejection error: ".$rejectSts;
            }
          }
        }
      }
    } else {
      placeDelete($beId);
    }
  } else {
    error($placeVerify);
  }

  function mailDone($sts, $mailType) {
    global $bookingRejectError;
    if ($mailType == "host-rejected-the-booking") {
      if ($sts != "done") {
        if ($bookingRejectError == "") {
          $bookingRejectError = "Booking rejection mail error: ".$sts;
        } else {
          $bookingRejectError = $bookingRejectError."<br>Booking rejection mail error: ".$sts;
        }
      }
    }
    handledBookingsManager();
  }

  $bookingHandledDone = 0;
  function handledBookingsManager() {
    global $beId, $bookingHandledDone, $numOfBookingsToHandle, $bookingRejectError;
    ++$bookingHandledDone;
    if ($numOfBookingsToHandle == $bookingHandledDone) {
      if ($bookingRejectError == "") {
        placeDelete($beId);
      } else {
        error($bookingRejectError);
      }
    }
  }

  function placeDeleteOutput($sts) {
    if ($sts == "done") {
      saved();
    } else {
      error($sts);
    }
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function saved() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
