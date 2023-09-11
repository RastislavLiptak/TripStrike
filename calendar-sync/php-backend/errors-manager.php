<?php
  function errorCall($callBeID, $msg) {
    global $linkBD;
    $date = date("Y-m-d H:i:s");
    $sqlCallFailed = "UPDATE calendarsynccalls SET status='failed', enddate='$date' WHERE beid='$callBeID'";
    mysqli_query($linkBD, $sqlCallFailed);
    $sqlGetCallError = $linkBD->query("SELECT * FROM calendarsynccalls WHERE beid='$callBeID'");
    if ($sqlGetCallError->num_rows > 0) {
      while($rowGetCallError = $sqlGetCallError->fetch_assoc()) {
        if ($rowGetCallError['errors'] != "") {
          $msg = $rowGetCallError['errors']."\n".$msg;
        }
      }
    }
    $sqlCallErrorMsg = "UPDATE calendarsynccalls SET errors='$msg' WHERE beid='$callBeID'";
    mysqli_query($linkBD, $sqlCallErrorMsg);
  }

  function errorKey($callBeID, $plcBeID, $sourceCode, $url, $msg) {
    global $linkBD, $link;
    $date = date("Y-m-d H:i:s");
    $sqlKeyFailed = "UPDATE calendarsynckey SET status='failed', error='$msg' WHERE beid='$callBeID' and plcbeid='$plcBeID' and sourcecode='$sourceCode' and url='$url'";
    mysqli_query($linkBD, $sqlKeyFailed);
    $sqlGetKeyError = $linkBD->query("SELECT * FROM calendarsynckey WHERE beid='$callBeID' and plcbeid='$plcBeID'");
    if ($sqlGetKeyError->num_rows > 0) {
      while($rowGetKeyError = $sqlGetKeyError->fetch_assoc()) {
        if ($rowGetKeyError['error'] != "") {
          $msg = $rowGetKeyError['error']."\n".$msg;
        }
      }
    }
    $sqlKeyErrorMsg = "UPDATE placecalendarsync SET error='$msg' WHERE beid='$plcBeID' and code='$sourceCode' and url='$url'";
    mysqli_query($link, $sqlKeyErrorMsg);
  }
?>
