<?php
  include "data.php";
  include "password-edit.php";
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $signId = $_SESSION["signID"];
    $em = $_SESSION["email"];
    $sqlUser = $link->query("SELECT * FROM users WHERE email='$em' && status='active'");
    if ($sqlUser->num_rows > 0) {
      $usr = $sqlUser->fetch_assoc();
      $beId = $usr['beid'];
      $sqlLog = $link->query("SELECT * FROM signin WHERE beid='$beId' && signinid='$signId' && status='in'");
      if ($sqlLog->num_rows > 0) {
        if ($_POST['pass'] != "") {
          if (password_verify(passEdit($_POST['pass']), passEdit($usr['password']))) {
            outputCreate(1, "done");
          } else {
            outputCreate(2, "wrong-pass");
          }
        } else {
          outputCreate(2, "empty");
        }
      } else {
        outputCreate(2, "data-error-3");
      }
    } else {
      outputCreate(2, "data-error-2");
    }
  } else {
    outputCreate(2, "data-error-1");
  }

  function outputCreate($sts, $msg) {
    $output = [];
    array_push($output, [
      "sts" => $sts,
      "msg" => $msg
    ]);
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
