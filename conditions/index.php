<?php
  include "../uni/code/php-head.php";
  if (isset($_GET['file'])) {
    $file = $_GET['file'];
  }
  $subtitle = $wrd_conditions;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/conditions.css">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <title><?php echo $wrd_conditions." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="conditions-wrp">
      <p id="conditions-txt">
        <?php
          if ($file == "terms-and-condition") {
            $terms_and_conditions_file = fopen("texts/terms-and-conditions/".$wrd_shrt."_terms-and-conditions.txt", "r") or die("Unable to open file! (terms-and-conditions.txt)");
            echo nl2br(fread($terms_and_conditions_file, filesize("texts/terms-and-conditions/".$wrd_shrt."_terms-and-conditions.txt")));
            fclose($terms_and_conditions_file);
          } else if ($file == "booking-conditions") {
            $booking_conditions_file = fopen("texts/booking-conditions/".$wrd_shrt."_booking-conditions.txt", "r") or die("Unable to open file! (booking-conditions.txt)");
            echo nl2br(fread($booking_conditions_file, filesize("texts/booking-conditions/".$wrd_shrt."_booking-conditions.txt")));
            fclose($booking_conditions_file);
          } else {
            $terms_and_conditions_file = fopen("texts/terms-and-conditions/".$wrd_shrt."_terms-and-conditions.txt", "r") or die("Unable to open file! (terms-and-conditions.txt)");
            echo nl2br(fread($terms_and_conditions_file, filesize("texts/terms-and-conditions/".$wrd_shrt."_terms-and-conditions.txt")));
            fclose($terms_and_conditions_file);
            $booking_conditions_file = fopen("texts/booking-conditions/".$wrd_shrt."_booking-conditions.txt", "r") or die("Unable to open file! (booking-conditions.txt)");
            echo nl2br(fread($booking_conditions_file, filesize("texts/booking-conditions/".$wrd_shrt."_booking-conditions.txt")));
            fclose($booking_conditions_file);
          }
        ?>
      </p>
    </div>
  </body>
</html>
