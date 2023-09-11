<?php
  function passEdit($pass) {
    return str_replace(array("\'", '\"', "'", '"'), array(".one.", "..dbl..", ".one.", "..dbl.."), $pass);
  }
?>
