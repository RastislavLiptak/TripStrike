<?php
  include "header.php";
  include "settings/settings.php";
  include "modals.php";
  include "floating-banners.php";
  include "password-alert.php";
  include dirname(__DIR__)."/php-backend/random-hash-maker.php";
  if ($bnft_add_cottage == "good") {
    include "new-cottage/new-cottage.php";
  }
  if ($bnft_edit_cottage == "good") {
    include "editor-modal.php";
  }
  if ($sign != "yes") {
    include "sign-in/sign-in/sign-in.php";
    include "sign-in/sign-up/sign-up.php";
  }
?>
