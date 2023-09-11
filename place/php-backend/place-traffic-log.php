<?php
  if (
    isset($_SESSION["user-traffic-log-id"]) && isset($_SESSION["user-traffic-log-date"])
  ) {
    $userTrafficLogID = $_SESSION["user-traffic-log-id"];
    $plcId = $_GET['id'];
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId'");
    if ($sqlBeId->num_rows > 0) {
      $plcBeId = $sqlBeId->fetch_assoc()['beid'];
      $sqlCheckPlcTrafficLog = $linkBD->query("SELECT * FROM placetrafficlog WHERE usertrafficid='$userTrafficLogID' and placebeid='$plcBeId'");
      if ($sqlCheckPlcTrafficLog->num_rows == 0) {
        $date = date("Y-m-d H:i:s");
        $dateY = date("Y");
        $dateM = date("m");
        $dateD = date("d");
        $sqlPlcLogInsert = "INSERT INTO placetrafficlog (usertrafficid, placebeid, fulldate, datey, datem, dated) VALUES('$userTrafficLogID', '$plcBeId', '$date', '$dateY', '$dateM', '$dateD')";
        mysqli_query($linkBD, $sqlPlcLogInsert);
      }
    }
  }
?>
