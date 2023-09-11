<?php
  include "../data.php";
  include "../password-edit.php";
  include "../account-data-check.php";
  $output = [];
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        $oldPass = mysqli_real_escape_string($link, $_POST['oldPass']);
        $newPass = mysqli_real_escape_string($link, $_POST['newPass']);
        $newPassVerification = mysqli_real_escape_string($link, $_POST['newPassVerification']);
        if (check($oldPass, "empty")) {
          if (password_verify(passEdit($oldPass), passEdit($usr['password']))) {
            if (check($newPass, "empty")) {
              if (check($newPass, "length")) {
                if (check($newPassVerification, "empty")) {
                  if ($newPass == $newPassVerification) {
                    $password_hash = password_hash(passEdit($newPass), PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password='$password_hash' WHERE beid='$beId'";
                    if (mysqli_query($link, $sql)) {
                      done();
                    } else {
                      error("Saving new password to the database failed");
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
              error("New password is empty");
            }
          } else {
            error("Old password is wrong");
          }
        } else {
          error("Old password is empty");
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
