<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/report-user-mail.php";
  $output = [];
  $reportedBySts = true;
  $userId = mysqli_real_escape_string($link, $_POST['userId']);
  $newReport = mysqli_real_escape_string($link, $_POST['newReport']);
  $checkReport = mysqli_real_escape_string($link, $_POST['checkReport']);
  if (isset($_SESSION["signID"]) && isset($_SESSION["email"])) {
    $myEm = $_SESSION["email"];
    $mySql = $link->query("SELECT beid FROM users WHERE email='$myEm' && status='active'");
    if ($mySql->num_rows == 1) {
      $my = $mySql->fetch_assoc();
      $myId = getFrontendId($my['beid']);
    } else {
      $reportedBySts = false;
      error("Email verification failed");
    }
  } else {
    $myEm = "unknown";
    $myId = "none";
  }
  if ($reportedBySts) {
    if ($newReport != "") {
      $checkString = $newReport;
    } else {
      $checkString = $checkReport;
    }
    $date = date("Y-m-d H:i:s");
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$userId' LIMIT 1");
    if ($sqlIdToBeId->num_rows > 0) {
      $userBeId = $sqlIdToBeId->fetch_assoc()["beid"];
      $userSql = $link->query("SELECT * FROM users WHERE beid='$userBeId'");
      if ($userSql->num_rows == 1) {
        $usr = $userSql->fetch_assoc();
        reportUserMail($myId, $myEm, $userId, $usr['firstname'], $usr['lastname'], $usr['email'], $usr['contactemail'], $usr['phonenum'], $usr['contactphonenum'], $usr['language'], $checkString, $date);
      } else {
        error("No data conected to reported user found");
      }
    } else {
      error("Failed to identify reported user by ID");
    }
  }

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      done();
    } else {
      error("Sending report faild");
    }
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
