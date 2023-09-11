<?php
  include realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/uni/code/php-backend/random-hash-maker.php';
  if (
    isset($_SESSION["user-traffic-log-id"]) && isset($_SESSION["user-traffic-log-date"])
  ) {
    $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($_SESSION["user-traffic-log-date"]));
    $minutes = floor($diff / 60);
    if ($minutes >= 15) {
      insertUserTrafficLog();
    } else {
      updateUserTrafficLog();
    }
  } else {
    insertUserTrafficLog();
  }

  function updateUserTrafficLog() {
    global $linkBD, $sign;
    $toDate = userTrafficLogAddSeveralMinutes("full");
    $toY = userTrafficLogAddSeveralMinutes("y");
    $toM = userTrafficLogAddSeveralMinutes("m");
    $toD = userTrafficLogAddSeveralMinutes("d");
    $id = $_SESSION["user-traffic-log-id"];
    if ($sign == "yes") {
      $registered = 1;
    } else {
      $registered = 0;
    }
    $sqlUpdate = "UPDATE usertrafficlog SET todate='$toDate', toy='$toY', tom='$toM', tod='$toD', registered='$registered', verified='1' WHERE id='$id'";
    if (mysqli_query($linkBD, $sqlUpdate)) {
      $_SESSION["user-traffic-log-date"] = date("Y-m-d H:i:s");
    }
  }

  function insertUserTrafficLog() {
    global $linkBD, $sign;
    $idReady = false;
    while (!$idReady) {
      $id = randomHash(2048);
      $sqlCH = $linkBD->query("SELECT * FROM usertrafficlog WHERE id='$id'");
      if ($sqlCH->num_rows == 0) {
        $idReady = true;
      } else {
        $idReady = false;
      }
    }
    $date = date("Y-m-d H:i:s");
    $dateY = date("Y");
    $dateM = date("m");
    $dateD = date("d");
    if ($sign == "yes") {
      $registered = 1;
    } else {
      $registered = 0;
    }
    $toDate = userTrafficLogAddSeveralMinutes("full");
    $toY = userTrafficLogAddSeveralMinutes("y");
    $toM = userTrafficLogAddSeveralMinutes("m");
    $toD = userTrafficLogAddSeveralMinutes("d");
    $countryCode = ip_info();
    $browser = getBrowser();
    $sqlInsert = "INSERT INTO usertrafficlog (id, fromdate, fromy, fromm, fromd, todate, toy, tom, tod, registered, countrycode, browser, verified) VALUES('$id', '$date', '$dateY', '$dateM', '$dateD', '$toDate', '$toY', '$toM', '$toD', '$registered', '$countryCode', '$browser', '0')";
    if (mysqli_query($linkBD, $sqlInsert)) {
      $_SESSION["user-traffic-log-id"] = $id;
      $_SESSION["user-traffic-log-date"] = date("Y-m-d H:i:s");
    }
  }

  function userTrafficLogAddSeveralMinutes($get) {
    $date = date("Y-m-d H:i:s");
    $currentDate = strtotime($date);
    $futureDate = $currentDate+(60*1);
    if ($get == "full") {
      return date("Y-m-d H:i:s", $futureDate);
    } else if ($get == "y") {
      return date("Y", $futureDate);
    } else if ($get == "m") {
      return date("m", $futureDate);
    } else if ($get == "d") {
      return date("d", $futureDate);
    }
  }
?>
