<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/getIP.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "../uni/code/php-backend/check-sign-in.php";
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    if (isset($_SESSION["backdoor-last-url"])) {
      header("Location: ".$_SESSION["backdoor-last-url"]);
    } else {
      header("Location: ../dashboard/?section=dashboard");
    }
  } else if ($backDoorCheckSignInSts == "not-signed-in") {
    header("Location: ../../home/");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/uni.css">
    <link rel="stylesheet" type="text/css" href="css/sign-in.css">
    <script src="../../uni/code/js/test-json.js"></script>
    <script src="../uni/code/js/uni.js" async></script>
    <script src="js/sign-in.js" async></script>
    <link rel="icon" href="../../uni/images/logo/favicon.png">
    <title><?php echo $wrd_signIn." - ".$title." Back Door"; ?></title>
  </head>
  <body>
    <div id="b-d-sign-in-wrp">
      <div id="b-d-sign-in-blck">
        <form id="b-d-sign-in-form" onsubmit="backDoorSignInSubmit(event)">
          <div class="b-d-sign-in-txt-wrp">
            <h1 id="b-d-sign-in-title"><?php echo $title ?></h1>
          </div>
          <div class="b-d-sign-in-txt-wrp">
            <h2 id="b-d-sign-in-subtitle">Back Door</h2>
          </div>
          <div id="b-d-sign-in-input-wrp">
            <input type="password" id="b-d-sign-in-input" placeholder="<?php echo $wrd_password; ?>">
          </div>
          <div id="b-d-sign-in-error-wrp">
            <p id="b-d-sign-in-error"></p>
          </div>
          <div id="b-d-sign-in-btn-wrp">
            <button type="submit" class="btn btn-big btn-prim" id="b-d-sign-in-submit-btn"><?php echo $wrd_submit ?></button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
