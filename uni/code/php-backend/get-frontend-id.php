<?php
  function getFrontendId($beId) {
    global $link;
    $sqlFrontendID = $link->query("SELECT id FROM idlist WHERE beid='$beId' ORDER BY fullDate DESC LIMIT 1");
    if ($sqlFrontendID->num_rows > 0) {
      return $sqlFrontendID->fetch_assoc()['id'];
    } else {
      return "none";
    }
  }
?>
