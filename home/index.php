<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "php-backend/home-filters.php";
  $subtitle = $wrd_home;
  $noPlace = false;
  $listOfUsedPlaces = [];
  $sqlNumOfPlaces = $link->query("SELECT beid FROM places WHERE status='active' ORDER BY fullDate DESC LIMIT 2");
  if ($sqlNumOfPlaces->num_rows == 1) {
    header("Location: ../place/?id=".getFrontendId($sqlNumOfPlaces->fetch_assoc()['beid']));
  } else if ($sqlNumOfPlaces->num_rows < 1) {
    $noPlace = true;
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <script src="js/home.js" async></script>
    <script src="js/main-banner.js" async></script>
    <script src="js/about-page.js" async></script>
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/main-banner.css" preload>
    <link rel="stylesheet" type="text/css" href="css/searchbar.css" preload>
    <link rel="stylesheet" type="text/css" href="css/about-page.css" preload>
    <link rel="stylesheet" type="text/css" href="css/big-list-of-places.css" preload>
    <link rel="stylesheet" type="text/css" href="css/small-list-of-places.css" preload>
    <?php
      if ($noPlace) {
    ?>
      <script src="js/no-place.js"></script>
      <link rel="stylesheet" type="text/css" href="css/no-place.css" preload>
    <?php
      }
      include "../uni/code/php-frontend/head.php";
    ?>
    <meta name="keywords" content="<?php echo $wrd_metaKeywords; ?>">
    <meta name="description" content="<?php echo $wrd_metaDescription; ?>">
    <title><?php echo $wrd_home." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if (!$noPlace) {
    ?>
      <div id="home-wrp">
        <?php
          include "php-frontend/main-banner.php";
          include "php-frontend/searchbar.php";
        ?>
        <div id="home-content">
          <?php
            include "php-frontend/about-page.php";
          ?>
            <div class="home-content-border-wrp">
              <div class="home-content-border"></div>
            </div>
          <?php
            include "php-frontend/big-list-of-places.php";
            include "php-frontend/small-list-of-places.php";
          ?>
        </div>
      </div>
    <?php
      } else {
        include "php-frontend/no-place.php";
      }
    ?>
  </body>
</html>
