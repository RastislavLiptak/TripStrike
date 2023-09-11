<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/password-edit.php";
  include "../../uni/code/php-backend/account-data-check.php";
  $output = [];
  $userID = mysqli_real_escape_string($link, $_POST['userID']);
  $code = mysqli_real_escape_string($link, $_POST['code']);
  $pass = mysqli_real_escape_string($link, $_POST['pass']);
  $passVerify = mysqli_real_escape_string($link, $_POST['passVerify']);
  if ($userID != "") {
    if ($code != "") {
      if (check($pass, "empty")) {
        if (check($pass, "length")) {
          if (check($passVerify, "empty")) {
            if ($pass == $passVerify) {
              $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$userID' LIMIT 1");
              if ($sqlIdToBeId->num_rows > 0) {
                $beId = $sqlIdToBeId->fetch_assoc()["beid"];
                $sqlUsr = $link -> query("SELECT * FROM users WHERE beid='$beId'");
                if ($sqlUsr->num_rows > 0) {
                  $sqlCode = $link -> query("SELECT * FROM forgottenpassword WHERE beid='$beId' and code='$code'");
                  if ($sqlCode->num_rows > 0) {
                    $rowCode = $sqlCode->fetch_assoc();
                    if ($rowCode['expired'] == 1) {
                      $datediff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($rowCode['fulldate']));
                      $remainingTime = 900 - $datediff;
                      if ($remainingTime > 0) {
                          $password_hash = password_hash(passEdit($pass), PASSWORD_DEFAULT);
                          $sqlUpdt = "UPDATE users SET password='$password_hash' WHERE beid='$beId'";
                          if (mysqli_query($link, $sqlUpdt)) {
                            done();
                          } else {
                            error("Saving new password to the database failed");
                          }
                      } else {
                        error("Verification code is too old. Refresh this page and start again.");
                      }
                    } else {
                      error("Verification code is not ready to be used. Refresh this page and start again.");
                    }
                  } else {
                    error("Code does not exist");
                  }
                } else {
                  error("No user connected to this ID");
                }
              } else {
                error("ID not connected to backend ID");
              }
            } else {
              error("New password and password verification are not same");
            }
          } else {
            error("Password verification is empty");
          }
        } else {
          error("New password is too short (minimal length is 4 characters)");
        }
      } else {
         error("New password field is empty");
      }
    } else {
      error("Failed to get virification code");
    }
  } else {
    error("Failed to get ID of a user");
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
