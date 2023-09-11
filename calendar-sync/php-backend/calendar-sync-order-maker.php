<?php
  include "../uni/code/php-backend/random-hash-maker.php";
  function calendarSyncOrderMaker() {
    global $link, $linkBD;
    $sqlCalendarSyncList = $link->query("SELECT * FROM placecalendarsync");
    if ($sqlCalendarSyncList->num_rows > 0) {
      $date = date("Y-m-d H:i:s");
      $beIdReady = false;
      while (!$beIdReady) {
        $beId = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$beId'")->num_rows == 0) {
          $beIdReady = true;
        } else {
          $beIdReady = false;
        }
      }
      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
      $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$beId', '$backendIDNum', 'calendar-sync-call')";
      if (mysqli_query($link, $sqlBeID)) {
        $sqlCreateCall = "INSERT INTO calendarsynccalls (beid, status, numofsources, startdate, enddate, errors) VALUES('$beId', 'created', '0', '$date', '0001-01-01', '')";
        if (mysqli_query($linkBD, $sqlCreateCall)) {
          $orderCreationError = "";
          $numOfSources = 0;
          while($rowCalendarSyncList = $sqlCalendarSyncList->fetch_assoc()) {
            $plcBeId = $rowCalendarSyncList['beid'];
            $sourceCode = $rowCalendarSyncList['code'];
            $url = $rowCalendarSyncList['url'];
            $sqlAddKey = "INSERT INTO calendarsynckey (beid, status, plcbeid, sourcecode, url, error, fulldate) VALUES('$beId', 'in-order', '$plcBeId', '$sourceCode', '$url', '', '$date')";
            if (!mysqli_query($linkBD, $sqlAddKey)) {
              if ($orderCreationError == "") {
                $orderCreationError = "Failed to add link to list (Place BeID: ".$plcBeId."; Source code: ".$sourceCode."; URL: ".$url.")";
              } else {
                $orderCreationError = $orderCreationError."\n"."Failed to add link to list (Place BeID: ".$plcBeId."; Source code: ".$sourceCode."; URL: ".$url.")";
              }
            }
            ++$numOfSources;
          }
          $sqlReadyStsUpdate = "UPDATE calendarsynccalls SET status='ready', numofsources='$numOfSources', errors='$orderCreationError' WHERE beid='$beId'";
          if (mysqli_query($linkBD, $sqlReadyStsUpdate)) {
            return "done";
          }
        }
      }
    }
  }
?>
