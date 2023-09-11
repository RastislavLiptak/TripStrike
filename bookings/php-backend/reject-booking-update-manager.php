<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../editor/php-backend/place-verification.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/request-to-update-booking-dates-rejected.php";
  include "../../email-sender/php-backend/request-to-update-number-of-guests-booking-rejected.php";
  header('Content-Type: application/json');
  $output = [];
  $plcID = mysqli_real_escape_string($link, $_POST['plc_id']);
  $reqID = mysqli_real_escape_string($link, $_POST['req_id']);
  $sqlPlcIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcID' LIMIT 1");
  if ($sqlPlcIdToBeId->num_rows > 0) {
    $plcBeId = $sqlPlcIdToBeId->fetch_assoc()["beid"];
    $sqlPlaces = $link->query("SELECT * FROM places WHERE beid='$plcBeId' LIMIT 1");
    $plc = $sqlPlaces->fetch_assoc();
    $hostBeId = $plc['usrbeid'];
    $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeId' and status='active' LIMIT 1");
    $hst = $sqlHost->fetch_assoc();
    $sqlReqIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$reqID' LIMIT 1");
    if ($sqlReqIdToBeId->num_rows > 0) {
      $reqBeId = $sqlReqIdToBeId->fetch_assoc()["beid"];
      $sqlGetRequest = $link->query("SELECT * FROM bookingupdaterequest WHERE beid='$reqBeId'");
      if ($sqlGetRequest->num_rows > 0) {
        $requestRow = $sqlGetRequest->fetch_assoc();
        if ($requestRow['status'] == "ready") {
          $bookingBeId = $requestRow['bookingbeid'];
          $sqlGetBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
          if ($sqlGetBooking->num_rows > 0) {
            $bookingRow = $sqlGetBooking->fetch_assoc();
            if ($bookingRow['status'] == "booked" || $bookingRow['status'] == "waiting") {
              if ($bookingRow['plcbeid'] == $plcBeId) {
                $sqlRejectRequest = "UPDATE bookingupdaterequest SET status='rejected' WHERE beid='$reqBeId'";
                if (mysqli_query($link, $sqlRejectRequest)) {
                  if ($bookingRow['email'] != "-") {
                    if ($requestRow['type'] == "date") {
                      requestToUpdateBookingDatesRejected(
                        getFrontendId($bookingBeId),
                        $plc['name'],
                        $plcID,
                        $requestRow['fromd'],
                        $requestRow['fromm'],
                        $requestRow['fromy'],
                        $requestRow['firstday'],
                        $requestRow['tod'],
                        $requestRow['tom'],
                        $requestRow['toy'],
                        $requestRow['lastday'],
                        $bookingRow['fromd'],
                        $bookingRow['fromm'],
                        $bookingRow['fromy'],
                        $bookingRow['firstday'],
                        $bookingRow['tod'],
                        $bookingRow['tom'],
                        $bookingRow['toy'],
                        $bookingRow['lastday'],
                        $bookingRow['guestnum'],
                        $bookingRow['language'],
                        $bookingRow['email'],
                        getFrontendId($hostBeId),
                        $hst['firstname']." ".$hst['lastname'],
                        $hst['contactemail'],
                        $hst['contactphonenum']
                      );
                    } else if ($requestRow['type'] == "guests") {
                      requestToUpdateNumberOfGuestsBookingRejected(
                        getFrontendId($bookingBeId),
                        $plc['name'],
                        $plcID,
                        $bookingRow['fromd'],
                        $bookingRow['fromm'],
                        $bookingRow['fromy'],
                        $bookingRow['firstday'],
                        $bookingRow['tod'],
                        $bookingRow['tom'],
                        $bookingRow['toy'],
                        $bookingRow['lastday'],
                        $requestRow['guestNum'],
                        $bookingRow['guestnum'],
                        $bookingRow['language'],
                        $bookingRow['email'],
                        getFrontendId($hostBeId),
                        $hst['firstname']." ".$hst['lastname'],
                        $hst['contactemail'],
                        $hst['contactphonenum']
                      );
                    }
                  }
                } else {
                  error("request-rejection-failed: <br>".mysqli_error($link));
                }
              } else {
                error("place-of-the-booking-not-matches-with-place-id-from-url");
              }
            } else {
              error("booking-not-active");
            }
          } else {
            error("booking-not-found");
          }
        } else {
          if ($requestRow['status'] == "canceled") {
            error("request-already-canceled");
          } else if ($requestRow['status'] == "confirmed") {
            error("request-already-confirmed");
          } elseif ($requestRow['status'] == "rejected") {
            error("request-already-rejected");
          } else {
            error("request-status: ".$requestRow['status']);
          }
        }
      } else {
        error("request-details-not-found");
      }
    } else {
      error("request-id-not-connected-to-backend");
    }
  } else {
    error("place-id-not-connected-to-backend");
  }

  function mailDone($msg, $mailType) {
    if ($msg == "done") {
      done();
    } else {
      error("the-request-was-rejected-but-the-email-to-the-guest-failed-to-be-sent");
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
