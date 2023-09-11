<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "place-verification.php";
  include "edit-booking/cancel-booking.php";
  header('Content-Type: application/json');
  $output = [];
  $url_id = mysqli_real_escape_string($link, $_POST['plc_id']);
  $f_d = mysqli_real_escape_string($link, $_POST['f_d']);
  $f_m = mysqli_real_escape_string($link, $_POST['f_m']);
  $f_y = mysqli_real_escape_string($link, $_POST['f_y']);
  $t_d = mysqli_real_escape_string($link, $_POST['t_d']);
  $t_m = mysqli_real_escape_string($link, $_POST['t_m']);
  $t_y = mysqli_real_escape_string($link, $_POST['t_y']);
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
  $beId = $sqlIdToBeId->fetch_assoc()["beid"];
  $sqlPlaceHostBeId = $link->query("SELECT usrbeid FROM places WHERE beid='$beId' LIMIT 1");
  $plcHostBeID = $sqlPlaceHostBeId->fetch_assoc()['usrbeid'];
  $sqlHostData = $link->query("SELECT * FROM users WHERE beid='$plcHostBeID' LIMIT 1");
  $aboutHost = $sqlHostData->fetch_assoc();
  cancelBooking($beId, $f_d, $f_m, $f_y, $t_d, $t_m, $t_y, $aboutHost['firstname']." ".$aboutHost['lastname'], $aboutHost['contactemail'], $aboutHost['contactphonenum'], "booking", true);

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      cancelBookingOutput("done", "good");
    } else {
      cancelBookingOutput("error", "Cancel booking failed: failed to send an email");
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
