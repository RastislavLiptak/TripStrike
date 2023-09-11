<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../../uni/code/php-backend/account-data-check.php";
  include "../../../uni/code/php-backend/password-edit.php";
  include "../../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  $output = [];
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  if (backDoorCheckSignIn() == "backend-locked") {
    $pass = $_POST['password'];
    if ($pass != "") {
      $sqlCheckNumOfAdmins = $linkBD->query("SELECT * FROM admins WHERE status='active'");
      if ($sqlCheckNumOfAdmins->num_rows > 0) {
        $sqlAdminVerify = $linkBD->query("SELECT * FROM admins WHERE beid='$usrBeId' and status='active'");
        if ($sqlAdminVerify->num_rows > 0) {
          $rowAdmVerify = $sqlAdminVerify->fetch_assoc();
          if (password_verify(passEdit($pass), passEdit($rowAdmVerify['password']))) {
            setcookie("backdoor", "ready", time() + 900, "/");
            $_SESSION['backdoor'] = "ready";
            done();
          } else {
            error("Wrong password");
          }
        } else {
          error("Admin profile not exist");
        }
      } else {
        if (!file_exists("../../uni/files/extracted/root_key/root_key.txt")) {
          $keyFolder = new ZipArchive();
          $unlockStatus = $keyFolder->open("../../uni/files/root_key.zip");
          if ($unlockStatus === true) {
            if ($keyFolder->setPassword($pass)) {
              if ($keyFolder->extractTo("../../uni/files/extracted/")) {
                $keyFolder->close();
                file_put_contents("../../uni/files/extracted/root_key/root_key.txt", "At the day ".$dateD.".".$dateM.".".$dateY." is ".$setfirstname." ".$setlastname." becoming your fucking BOSS. Thank you");
                $password_hash = password_hash(passEdit($pass), PASSWORD_DEFAULT);
                $sqlInsert = "INSERT INTO admins (beid, password, status, title, fulldate, datey, datem, dated) VALUES ('$usrBeId', '$password_hash', 'active', 'root', '$date', '$dateY', '$dateM', '$dateD')";
                if (mysqli_query($linkBD, $sqlInsert)) {
                  setcookie("backdoor", "ready", time() + 900, "/");
                  $_SESSION['backdoor'] = "ready";
                  done();
                } else {
                  error("Failed to insert into admins database<br>".mysqli_error($link));
                }
              } else {
                $keyFolder->close();
                error("Wrong password");
              }
            }
          } else {
            error("Failed opening archive: ". @$keyFolder->getStatusString() . " (code: ". $unlockStatus .")");
          }
        } else {
          error("Root user's file already exists");
        }
      }
    } else {
      error("Password field is empty");
    }
  } else {
    error("Invalid signed in status");
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
