<?php
  include "../uni/code/php-backend/data.php";
  include "../uni/code/php-backend/getIP.php";
  include "../uni/code/php-backend/get-frontend-id.php";
  include "../uni/dictionary/lang-select.php";
  include "../uni/code/php-backend/get-user.php";
  include "../uni/code/php-backend/account-data-check.php";
  include "uni/code/php-backend/check-sign-in.php";
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    header("Location: dashboard/?section=dashboard");
  } else if ($backDoorCheckSignInSts == "not-signed-in") {
    header("Location: ../home/");
  } else if ($backDoorCheckSignInSts == "backend-locked") {
    header("Location: sign-in/");
  }
?>
