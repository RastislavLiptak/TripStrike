<?php
  if (!function_exists('randomHash')) {
    function randomHash($n) {
      $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $charLength = strlen($characters);
      $hash = '';
      for ($i = 0; $i < $n; $i++) {
        $hash .= $characters[rand(0, $charLength - 1)];
      }
      return $hash;
    }
  }
?>
