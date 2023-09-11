<?php
  if (isset($_SERVER['HTTPS'])) {
    $_SESSION['backdoor-last-url'] = "https://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
  } else {
    $_SESSION['backdoor-last-url'] = "http://".$_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
  }
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts != "good") {
    if ($backDoorCheckSignInSts == "not-signed-in") {
      header("Location: ../../home/");
    } else if ($backDoorCheckSignInSts == "backend-locked") {
      header("Location: ../sign-in/");
    }
  }
?>
