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
        $bankAccount = mysqli_real_escape_string($link, $_POST['bankAccount']);
        $iban = mysqli_real_escape_string($link, $_POST['iban']);
        $bicswift = mysqli_real_escape_string($link, $_POST['bicswift']);
        if ($bankAccount == "") {
          $bankAccount = "-";
        }
        if ($bicswift == "") {
          $bicswift = "-";
        }
        if ($iban != "") {
          if (strlen($iban) <= 34) {
            if (strlen($bicswift) == 8 || strlen($bicswift) == 11 || $bicswift == "-") {
              $sql = "UPDATE users SET bankaccount='$bankAccount', iban='$iban', bicswift='$bicswift' WHERE beid='$beId'";
              if (mysqli_query($link, $sql)) {
                done();
              } else {
                error("Failed to save to database<br>".mysqli_error($link));
              }
            } else {
              error("BIC/SWIFT has invalid number of characters");
            }
          } else {
            error("IBAN has invalid number of characters");
          }
        } else {
          error("At least the IBAN field has to be filled");
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
