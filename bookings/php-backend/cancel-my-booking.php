<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../editor/php-backend/edit-booking/cancel-booking.php";
  include "../../email-sender/php-backend/send-mail.php";
  header('Content-Type: application/json');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
  if ($sqlIdToBeId->num_rows > 0) {
    $bookingBeId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
    if ($sqlBooking->num_rows > 0) {
      $bookingRow = $sqlBooking->fetch_assoc();
      if ($usrBeId == $bookingRow['usrbeid']) {
        if ($bookingRow['status'] == "booked" || $bookingRow['status'] == "waiting") {
          if (new DateTime() < new DateTime($bookingRow['fromdate'])) {
            $plcBeId = $bookingRow['plcbeid'];
            $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
            if ($sqlPlc->num_rows > 0) {
              $plcRow = $sqlPlc->fetch_assoc();
              $hostBeId = $plcRow['usrbeid'];
              $sqlHst = $link->query("SELECT * FROM users WHERE beid='$hostBeId'");
              if ($sqlHst->num_rows > 0) {
                $hstRow = $sqlHst->fetch_assoc();
                cancelBooking(
                  $plcBeId,
                  $bookingRow['fromd'],
                  $bookingRow['fromm'],
                  $bookingRow['fromy'],
                  $bookingRow['tod'],
                  $bookingRow['tom'],
                  $bookingRow['toy'],
                  $hstRow['firstname']." ".$hstRow['lastname'],
                  $hstRow['contactemail'],
                  $hstRow['contactphonenum'],
                  "by-the-guest",
                  true
                );
              } else {
                return "Host's profile was not found in the database";
              }
            } else {
              return "The booked place was not found in the database";
            }
          } else {
            error("The booking has already taken place or is still in progress");
          }
        } else {
          error("Booking status is not valid");
        }
      } else {
        error("This booking was not created by this profile");
      }
    } else {
      error("Booking not found in our database");
    }
  } else {
    error("ID not connected to any backend ID");
  }

  $numberOfMailsDone = 0;
  $bookingCancelMailError = "";
  function mailDone($sts, $mailType) {
    global $numberOfMailsDone, $bookingCancelMailError;
    ++$numberOfMailsDone;
    if ($numberOfMailsDone == 2) {
      if ($sts == "done") {
        cancelBookingOutput("done", "good");
      } else {
        if ($bookingCancelMailError != "") {
          cancelBookingOutput("error", $bookingCancelMailError."<br>Cancel booking failed: failed to send an email");
        } else {
          cancelBookingOutput("error", "Cancel booking failed: failed to send an email");
        }
      }
    } else {
      if ($sts != "done") {
        $bookingCancelMailError = "Cancel booking failed: failed to send an email (".$mailType."))";
      }
    }
  }

  function cancelBookingOutput($type, $txt) {
    if ($type == "done") {
      done();
    } else {
      error($txt);
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
