<?php
  include "../data.php";
  $output = [];
  $errorsOutput = "";
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $payOrYourBookingWillBeCanceled = mysqli_real_escape_string($link, $_POST['payOrYourBookingWillBeCanceled']);
        $cancelUnpaidBookings = mysqli_real_escape_string($link, $_POST['cancelUnpaidBookings']);
        $payFullAmountAlert = mysqli_real_escape_string($link, $_POST['payFullAmountAlert']);
        $unpaidFullAmountCall = mysqli_real_escape_string($link, $_POST['unpaidFullAmountCall']);
        if ($payOrYourBookingWillBeCanceled == "true") {
          $payOrYourBookingWillBeCanceled = 1;
        } else {
          $payOrYourBookingWillBeCanceled = 0;
        }
        if ($cancelUnpaidBookings == "true") {
          $cancelUnpaidBookings = 1;
        } else {
          $cancelUnpaidBookings = 0;
        }
        if ($payFullAmountAlert == "true") {
          $payFullAmountAlert = 1;
        } else {
          $payFullAmountAlert = 0;
        }
        if ($unpaidFullAmountCall == "true") {
          $unpaidFullAmountCall = 1;
        } else {
          $unpaidFullAmountCall = 0;
        }
        $sqlSchSettCheck_payOrYourBookingWillBeCanceled = $link->query("SELECT * FROM userscheduled WHERE beid='$beId' and task='pay-or-your-booking-will-be-canceled' LIMIT 1");
        if ($sqlSchSettCheck_payOrYourBookingWillBeCanceled->num_rows > 0) {
          $sqlSchSettUpdate_payOrYourBookingWillBeCanceled = "UPDATE userscheduled SET status='$payOrYourBookingWillBeCanceled' WHERE beid='$beId' and task='pay-or-your-booking-will-be-canceled'";
          if (!mysqli_query($link, $sqlSchSettUpdate_payOrYourBookingWillBeCanceled)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        } else {
          $sqlSchSettInsert_payOrYourBookingWillBeCanceled = "INSERT INTO userscheduled (beid, task, status) VALUES('$beId', 'pay-or-your-booking-will-be-canceled', '$payOrYourBookingWillBeCanceled')";
          if (!mysqli_query($link, $sqlSchSettInsert_payOrYourBookingWillBeCanceled)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        }
        $sqlSchSettCheck_cancelUnpaidBookings = $link->query("SELECT * FROM userscheduled WHERE beid='$beId' and task='cancel-unpaid-bookings' LIMIT 1");
        if ($sqlSchSettCheck_cancelUnpaidBookings->num_rows > 0) {
          $sqlSchSettUpdate_cancelUnpaidBookings = "UPDATE userscheduled SET status='$cancelUnpaidBookings' WHERE beid='$beId' and task='cancel-unpaid-bookings'";
          if (!mysqli_query($link, $sqlSchSettUpdate_cancelUnpaidBookings)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        } else {
          $sqlSchSettInsert_cancelUnpaidBookings = "INSERT INTO userscheduled (beid, task, status) VALUES('$beId', 'cancel-unpaid-bookings', '$cancelUnpaidBookings')";
          if (!mysqli_query($link, $sqlSchSettInsert_cancelUnpaidBookings)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        }
        $sqlSchSettCheck_payFullAmountAlert = $link->query("SELECT * FROM userscheduled WHERE beid='$beId' and task='pay-full-amount-alert' LIMIT 1");
        if ($sqlSchSettCheck_payFullAmountAlert->num_rows > 0) {
          $sqlSchSettUpdate_payFullAmountAlert = "UPDATE userscheduled SET status='$payFullAmountAlert' WHERE beid='$beId' and task='pay-full-amount-alert'";
          if (!mysqli_query($link, $sqlSchSettUpdate_payFullAmountAlert)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        } else {
          $sqlSchSettInsert_payFullAmountAlert = "INSERT INTO userscheduled (beid, task, status) VALUES('$beId', 'pay-full-amount-alert', '$payFullAmountAlert')";
          if (!mysqli_query($link, $sqlSchSettInsert_payFullAmountAlert)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        }
        $sqlSchSettCheck_unpaidFullAmountCall = $link->query("SELECT * FROM userscheduled WHERE beid='$beId' and task='unpaid-full-amount-call' LIMIT 1");
        if ($sqlSchSettCheck_unpaidFullAmountCall->num_rows > 0) {
          $sqlSchSettUpdate_unpaidFullAmountCall = "UPDATE userscheduled SET status='$unpaidFullAmountCall' WHERE beid='$beId' and task='unpaid-full-amount-call'";
          if (!mysqli_query($link, $sqlSchSettUpdate_unpaidFullAmountCall)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        } else {
          $sqlSchSettInsert_unpaidFullAmountCall = "INSERT INTO userscheduled (beid, task, status) VALUES('$beId', 'unpaid-full-amount-call', '$unpaidFullAmountCall')";
          if (!mysqli_query($link, $sqlSchSettInsert_unpaidFullAmountCall)) {
            $errorsOutput = $errorsOutput."".mysqli_error($link)." ";
          }
        }
        if ($errorsOutput == "") {
          done();
        } else {
          error($errorsOutput);
        }
      } else {
        error("action denied - sign in data not matching with data in database");
      }
    } else {
      error("data from session not maching with data from database");
    }
  } else {
    error("session error - missing data");
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function done() {
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
