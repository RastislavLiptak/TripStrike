<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/forgotten-password-code-mail.php";
  $output = [];
  $date = "";
  $signInData = mysqli_real_escape_string($link, $_POST['data']);
  if ($signInData != "") {
    $isEmail = false;
    $isPhone = false;
    $unknown = false;
    if (!filter_var($signInData, FILTER_VALIDATE_EMAIL)) {
      $signInData = str_replace("plus", "+", $signInData);
      if (!preg_match("/[^0-9\s+-\/]/", $signInData)) {
        $isPhone = true;
      } else {
        $isPhone = false;
      }
    } else {
      $isEmail = true;
    }
    if ($isEmail) {
      $sql = $link -> query("SELECT * FROM users WHERE email='$signInData' && status='active'");
    } else if ($isPhone) {
      $sql = $link -> query("SELECT * FROM users WHERE phonenum='$signInData' && status='active'");
    } else {
      $unknown = true;
    }
    if (!$unknown) {
      if ($sql->num_rows > 0) {
        $row = $sql->fetch_assoc();
        $userID = getFrontendId($row['beid']);
        $userContactEmail = $row['contactemail'];
        $userName = $row['firstname']." ".$row['lastname'];
        $userLanguage = $row['language'];
        verifyPasswordCodeCreate($row['beid']);
      } else {
        if ($isEmail) {
          error("User with this email does not exist");
        } else {
          error("User with this phone number does not exist");
        }
      }
    } else {
      error("Unknown input data type");
    }
  } else {
    error("Field is empty");
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
