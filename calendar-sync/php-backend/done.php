<?php
  function doneCall($callBeID) {
    global $linkBD;
    $date = date("Y-m-d H:i:s");
    $finalSts = getCallFinalSts($callBeID);
    $sqlCallDone = "UPDATE calendarsynccalls SET status='$finalSts', enddate='$date', errors='' WHERE beid='$callBeID'";
    if (!mysqli_query($linkBD, $sqlCallDone)) {
      errorCall($callBeID, "Failed to update status to DONE (SQL error: ".mysqli_error($linkBD).")");
    }
  }

  function getCallFinalSts($callBeID) {
    global $linkBD;
    $finalSts = "failed";
    $sqlCheckSts = $linkBD->query("SELECT * FROM calendarsynckey WHERE beid='$callBeID'");
    if ($sqlCheckSts->num_rows > 0) {
      while($rowCheckSts = $sqlCheckSts->fetch_assoc()) {
        if ($rowCheckSts['status'] == "done") {
          $finalSts = "done";
        }
      }
    } else {
      $finalSts = "done";
    }
    return $finalSts;
  }

  function doneKey($callBeID, $plcBeID, $sourceCode, $url) {
    global $linkBD;
    $sqlKeyDone = "UPDATE calendarsynckey SET status='done' WHERE beid='$callBeID' and plcbeid='$plcBeID' and sourcecode='$sourceCode' and url='$url'";
    if (mysqli_query($linkBD, $sqlKeyDone)) {
      return "done";
    } else {
      return "Failed to update status to DONE (SQL error: ".mysqli_error($linkBD).")";
    }
  }
?>
