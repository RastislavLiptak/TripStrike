<?php
  if (!function_exists('langShortcut')) {
    function langShortcut($beId) {
      global $link;
      if ($beId != "") {
        $sqlLng = $link->query("SELECT language FROM users WHERE beid='$beId' LIMIT 1");
        if ($sqlLng->num_rows > 0) {
          $output = $sqlLng->fetch_assoc()['language'];
        } else {
          $output = "";
        }
      } else {
        $output = "";
      }
      if ($output == "") {
        if (isset($_COOKIE["language"])) {
          $output = $_COOKIE["language"];
        } else {
          $cntrCode = ip_info();
          if ($cntrCode == "SK") {
            $output = "sk";
          } else if ($cntrCode == "CZ") {
            $output = "cz";
          } else {
            $output = "eng";
          }
        }
      }
      return $output;
    }
  }
?>
