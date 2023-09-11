<?php
  function forgottenPasswordCodeExpiration() {
    global $link;
    $date = date("Y-m-d H:i:s");
    $sqlAllCodes = $link->query("SELECT beid, fulldate FROM forgottenpassword WHERE expired='0'");
    if ($sqlAllCodes->num_rows > 0) {
      while($allCodes = $sqlAllCodes->fetch_assoc()) {
        $datediff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($allCodes['fulldate']));
        $remainingTime = 300 - $datediff;
        if ($remainingTime <= 0) {
          $codeBeId = $allCodes['beid'];
          $sqlUpdt = "UPDATE forgottenpassword SET expired='1' WHERE beid='$codeBeId'";
          mysqli_query($link, $sqlUpdt);
        }
      }
    }
  }
?>
