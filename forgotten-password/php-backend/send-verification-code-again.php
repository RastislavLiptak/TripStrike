<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/forgotten-password-code-mail.php";
  $output = [];
  $date = "";
  $userID = mysqli_real_escape_string($link, $_POST['userID']);
  if ($userID != "") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$userID' LIMIT 1");
    if ($sqlIdToBeId->num_rows > 0) {
      $beId = $sqlIdToBeId->fetch_assoc()["beid"];
      $sql = $link -> query("SELECT * FROM users WHERE beid='$beId'");
      if ($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        $userContactEmail = $row['contactemail'];
        $userName = $row['firstname']." ".$row['lastname'];
        $userLanguage = $row['language'];
        verifyPasswordCodeCreate($beId);
      } else {
        error("No user connected to this ID");
      }
    } else {
      error("ID not connected to backend ID");
    }
  } else {
    error("Failed to get ID of a user");
  }

  function verifyPasswordCodeCreate($beid) {
    global $link, $date, $userID, $userContactEmail, $userName, $userLanguage;
    $date = date("Y-m-d H:i:s");
    $codeReady = false;
    while (!$codeReady) {
      $code = randomHash(6);
      $sqlCH = $link->query("SELECT * FROM forgottenpassword WHERE code='$code' and expired='0'");
      if ($sqlCH->num_rows == 0) {
        $codeReady = true;
      } else {
        $codeReady = false;
      }
    }
    $sqlUpdt = "UPDATE forgottenpassword SET expired='1' WHERE beid='$beid'";
    if (mysqli_query($link, $sqlUpdt)) {
      $sqlCode = "INSERT INTO forgottenpassword (beid, code, fulldate, expired) VALUES ('$beid', '$code', '$date', '0')";
      if (mysqli_query($link, $sqlCode)) {
        forgottenPasswordCodeMail($userID, $userName, $userContactEmail, $userLanguage, $code);
      } else {
        error("Failed add new code to database<br>".mysqli_error($link));
      }
    } else {
      error("Failed to set old codes as expired<br>".mysqli_error($link));
    }
  }

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      done();
    } else {
      error("Failed to send an email with verification code");
    }
  }

  function done() {
    global $output, $date, $userID, $userContactEmail;
    array_push($output, [
      "type" => "done",
      "id" => $userID,
      "email" => $userContactEmail,
      "date" => $date
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
