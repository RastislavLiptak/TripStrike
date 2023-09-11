<?php
  include "../uni/code/php-head.php";
  $subtitle = $wrd_forgot;
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="../uni/code/js/set-up-step-by-step.js"></script>
    <script src="js/forgotten-password-uni.js"></script>
    <script src="js/type-sign-in-email.js"></script>
    <script src="js/code-verification.js"></script>
    <script src="js/password-update.js"></script>
    <link rel="stylesheet" type="text/css" href="../uni/code/css/set-up-step-by-step.css">
    <link rel="stylesheet" type="text/css" href="css/forgotten-password-uni.css">
    <link rel="stylesheet" type="text/css" href="css/code-verification.css">
    <link rel="stylesheet" type="text/css" href="css/password-update.css">
    <link rel="stylesheet" type="text/css" href="css/success.css">
    <meta name="description" content="<?php echo $wrd_metaDescriptionForgottenPassword; ?>">
    <title><?php echo $wrd_forgot." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div class="set-up-step-by-step-wrp">
      <div class="set-up-step-by-step-blck">
        <?php
          include "php-frontend/type-sign-in-email.php";
          include "php-frontend/code-verification.php";
          include "php-frontend/password-update.php";
          include "php-frontend/success.php";
        ?>
      </div>
    </div>
  </body>
</html>
