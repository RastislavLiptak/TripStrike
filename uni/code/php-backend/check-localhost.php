<?php
  $whitelist = array("127.0.0.1", "::1");
  if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
    //header("Location: ../../../index.php");
  }
?>
