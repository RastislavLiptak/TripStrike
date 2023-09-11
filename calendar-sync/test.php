<?php
  include "../uni/code/php-backend/data.php";
  include "../uni/code/php-backend/random-hash-maker.php";
  include "php-backend/calendar-sync-order-maker.php";
  include "php-backend/calendar-sync-ical-select.php";
  include "php-backend/errors-manager.php";
  include "php-backend/done.php";
  require '../libraries/ics-parser-master/class.iCalReader.php';

  calendarSyncOrderMaker();
  calendarSyncICalSelect();
?>
