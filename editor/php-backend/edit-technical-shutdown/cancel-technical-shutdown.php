<?php
  function cancelTechnicalShutdown($plcBeID, $fromD, $fromM, $fromY, $toD, $toM, $toY) {
    global $link;
    $sqlTechnicalShutdown = $link->query("SELECT * FROM technicalshutdown WHERE plcbeid='$plcBeID' and fromd='$fromD' and fromm='$fromM' and fromy='$fromY' and tod='$toD' and tom='$toM' and toy='$toY' and status='active'");
    if ($sqlTechnicalShutdown->num_rows > 0) {
      $technicalShutdown = $sqlTechnicalShutdown->fetch_assoc();
      $technicalShutdownBeId = $technicalShutdown['beid'];
      $sqlCancelTechnicalShutdownDays = "UPDATE technicalshutdowndates SET status='canceled' WHERE beid='$technicalShutdownBeId'";
      mysqli_query($link, $sqlCancelTechnicalShutdownDays);
      $sqlCancelTechnicalShutdown = "UPDATE technicalshutdown SET status='canceled' WHERE beid='$technicalShutdownBeId'";
      mysqli_query($link, $sqlCancelTechnicalShutdown);
      cancelTechnicalShutdownOutput("done", "good");
    } else {
      cancelTechnicalShutdownOutput("error", "Cancel technical shutdown failed: technical shutdown not found in database");
    }
  }
?>
