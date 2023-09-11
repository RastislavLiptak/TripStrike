<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "booking-verification.php";
  include "../../editor/php-backend/edit-booking/update-booking.php";
  include "../../email-sender/php-backend/send-mail.php";
  header('Content-Type: application/json');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $g_name = mysqli_real_escape_string($link, $_POST['name']);
  $g_email = mysqli_real_escape_string($link, $_POST['email']);
  $g_phone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['phone']));
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
  if ($sqlIdToBeId->num_rows > 0) {
    $bookingBeId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
    $booking = $sqlBooking->fetch_assoc();
    if ($g_name != "") {
      if ($g_email != "") {
        if (check($g_email, "email")) {
          if ($g_phone != "") {
            if (check($g_phone, "tel")) {
              updateBooking(
                $bookingBeId,
                $booking['plcbeid'],
                $g_name,
                $g_email,
                $g_phone,
                $booking['guestnum'],
                $booking['notes'],
                $booking['fromd'],
                $booking['fromm'],
                $booking['fromy'],
                $booking['firstday'],
                $booking['tod'],
                $booking['tom'],
                $booking['toy'],
                $booking['lastday'],
                $booking['deposit'],
                $booking['fullAmount'],
                true
              );
            } else {
              error("This has to be a phone number", "phone");
            }
          } else {
            error("This field cannot be left blank", "phone");
          }
        } else {
          error("This has to be an email", "email");
        }
      } else {
        error("This field cannot be left blank", "email");
      }
    } else {
      error("This field cannot be left blank", "name");
    }
  } else {
    error("ID not connected to any backend ID", "uni");
  }

  function updateBookingOutput($type, $txt) {
    if ($type == "done") {
      done();
    } else {
      error("Booking update error: ".$txt, "uni");
    }
  }

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      done();
    } else {
      error("Failed to send an email width confirmation", "uni");
    }
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function error($msg, $sect) {
    global $output;
    array_push($output, [
      "type" => "error",
      "section" => $sect,
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
