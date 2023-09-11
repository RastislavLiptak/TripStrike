<?php
  include "../uni/code/php-head.php";
  if (!isset($_GET['section'])) {
    header("Location: ../finance-settings/?section=finance-settings");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/amount-of-the-fees.js"></script>
    <script src="js/date-of-call-for-fees.js"></script>
    <script src="js/bank-details-for-the-payment-of-fees.js"></script>
    <link rel="stylesheet" type="text/css" href="css/date-of-call-for-fees.css">
    <title><?php echo $wrd_financeSettings." - ".$title." Back Door"; ?></title>
  </head>
  <body>
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="page-layout">
      <?php
        include "../uni/code/php-frontend/main-menu.php";
      ?>
      <div id="content-wrp">
        <div id="content">
          <div id="content-title-wrp">
            <h1 id="content-title"><?php echo $wrd_financeSettings; ?></h1>
          </div>
          <div id="content-layout">
            <?php
              include "php-frontend/amount-of-the-fees.php";
              include "php-frontend/date-of-call-for-fees.php";
              include "php-frontend/bank-details-for-the-payment-of-fees.php";
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
