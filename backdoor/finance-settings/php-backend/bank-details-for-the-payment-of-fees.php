<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  header('Content-Type: application/json');
  $output = [];
  $iban = mysqli_real_escape_string($link, $_POST['iban']);
  $bankAccount = mysqli_real_escape_string($link, $_POST['bankAccount']);
  $bicSwift = mysqli_real_escape_string($link, $_POST['bicSwift']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $outputError = "";
    $sqlCheckDtbIban = $linkBD->query("SELECT * FROM settings WHERE name='details-for-the-payment-of-fees-iban'");
    if ($sqlCheckDtbIban->num_rows > 0) {
      $sqlUpdateIban = "UPDATE settings SET value='$iban' WHERE name='details-for-the-payment-of-fees-iban'";
      if (!mysqli_query($linkBD, $sqlUpdateIban)) {
        if ($outputError == "") {
          $outputError = "Failed to update IBAN: ".mysqli_error($linkBD);
        } else {
          $outputError = $outputError."<br>Failed to update IBAN: ".mysqli_error($linkBD);
        }
      }
    } else {
      $sqlInsertIban = "INSERT INTO settings (name, value, type) VALUES ('details-for-the-payment-of-fees-iban', '$iban', 'txt')";
      if (!mysqli_query($linkBD, $sqlInsertIban)) {
        if ($outputError == "") {
          $outputError = "Failed to insert IBAN: ".mysqli_error($linkBD);
        } else {
          $outputError = $outputError."<br>Failed to insert IBAN: ".mysqli_error($linkBD);
        }
      }
    }
    $sqlCheckDtbBankAccount = $linkBD->query("SELECT * FROM settings WHERE name='details-for-the-payment-of-fees-bank-account'");
    if ($sqlCheckDtbBankAccount->num_rows > 0) {
      $sqlUpdateBankAccount = "UPDATE settings SET value='$bankAccount' WHERE name='details-for-the-payment-of-fees-bank-account'";
      if (!mysqli_query($linkBD, $sqlUpdateBankAccount)) {
        if ($outputError == "") {
          $outputError = "Failed to update Bank Account: ".mysqli_error($linkBD);
        } else {
          $outputError = $outputError."<br>Failed to update Bank Account: ".mysqli_error($linkBD);
        }
      }
    } else {
      $sqlInsertBankAccount = "INSERT INTO settings (name, value, type) VALUES ('details-for-the-payment-of-fees-bank-account', '$bankAccount', 'txt')";
      if (!mysqli_query($linkBD, $sqlInsertBankAccount)) {
        if ($outputError == "") {
          $outputError = "Failed to insert Bank Account: ".mysqli_error($linkBD);
        } else {
          $outputError = $outputError."<br>Failed to insert Bank Account: ".mysqli_error($linkBD);
        }
      }
    }
    $sqlCheckDtbBicSwift = $linkBD->query("SELECT * FROM settings WHERE name='details-for-the-payment-of-fees-bic-swift'");
    if ($sqlCheckDtbBicSwift->num_rows > 0) {
      $sqlUpdateBicSwift = "UPDATE settings SET value='$bicSwift' WHERE name='details-for-the-payment-of-fees-bic-swift'";
      if (!mysqli_query($linkBD, $sqlUpdateBicSwift)) {
        if ($outputError == "") {
          $outputError = "Failed to update BIC/SWIFT".mysqli_error($linkBD);
        } else {
          $outputError = $outputError."<br>Failed to update BIC/SWIFT".mysqli_error($linkBD);
        }
      }
    } else {
      $sqlInsertBicSwift = "INSERT INTO settings (name, value, type) VALUES ('details-for-the-payment-of-fees-bic-swift', '$bicSwift', 'txt')";
      if (!mysqli_query($linkBD, $sqlInsertBicSwift)) {
        if ($outputError == "") {
          $outputError = "Failed to insert BIC/SWIFT".mysqli_error($linkBD);
        } else {
          $outputError = $outputError."<br>Failed to insert BIC/SWIFT".mysqli_error($linkBD);
        }
      }
    }
    if ($outputError == "") {
      done();
    } else {
      error($outputError);
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
