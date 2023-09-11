<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  header('Content-Type: application/json');
  $output = [];
  $feesPerc = mysqli_real_escape_string($link, $_POST['feesPerc']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $feesPerc = str_replace(",", ".", $feesPerc);
    if (is_numeric($feesPerc)) {
      $sqlCheckDtb = $linkBD->query("SELECT * FROM settings WHERE name='amount-of-the-fees'");
      if ($sqlCheckDtb->num_rows > 0) {
        $sqlUpdate = "UPDATE settings SET value='$feesPerc' WHERE name='amount-of-the-fees'";
        if (mysqli_query($linkBD, $sqlUpdate)) {
          done();
        } else {
          error("Failed to update <br>".mysqli_error($linkBD));
        }
      } else {
        $sqlInsert = "INSERT INTO settings (name, value, type) VALUES ('amount-of-the-fees', '$feesPerc', 'num')";
        if (mysqli_query($linkBD, $sqlInsert)) {
          done();
        } else {
          error("Failed to insert <br>".mysqli_error($linkBD));
        }
      }
    } else {
      error("Invalid format (only numbers allowed)");
    }
  } else {
    error($backDoorCheckSignInSts);
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
