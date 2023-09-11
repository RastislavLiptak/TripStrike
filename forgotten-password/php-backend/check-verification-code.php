<?php
  include "../../uni/code/php-backend/data.php";
  $output = [];
  $userID = mysqli_real_escape_string($link, $_POST['userID']);
  $code = mysqli_real_escape_string($link, $_POST['code']);
  if ($userID != "") {
    if ($code != "") {
      $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$userID' LIMIT 1");
      if ($sqlIdToBeId->num_rows > 0) {
        $beId = $sqlIdToBeId->fetch_assoc()["beid"];
        $sqlUsr = $link -> query("SELECT * FROM users WHERE beid='$beId'");
        if ($sqlUsr->num_rows > 0) {
          $sqlCode = $link -> query("SELECT * FROM forgottenpassword WHERE beid='$beId' and code='$code'");
          if ($sqlCode->num_rows > 0) {
            $rowCode = $sqlCode->fetch_assoc();
            if ($rowCode['expired'] == 0) {
              $datediff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($rowCode['fulldate']));
              $remainingTime = 300 - $datediff;
              if ($remainingTime > 0) {
                $sqlUpdt = "UPDATE forgottenpassword SET expired='1' WHERE beid='$beId'";
                if (mysqli_query($link, $sqlUpdt)) {
                  done();
                } else {
                  error("Failed to set old codes as expired<br>".mysqli_error($link));
                }
              } else {
                error("The code has expired");
              }
            } else {
              error("Invalid code");
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
      error("Field is empty");
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
