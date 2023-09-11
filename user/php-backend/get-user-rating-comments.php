<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-account-data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  $userId = $link->escape_string($_POST['user']);
  $lastId = $link->escape_string($_POST['lastId']);
  $output = [];
  $beIdArr = [];
  $max = 16;
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastId' LIMIT 1");
  if ($sqlIdToBeId->num_rows > 0 || $lastId == "") {
    if ($lastId != "") {
      $lastBeId = $sqlIdToBeId->fetch_assoc()["beid"];
    } else {
      $lastBeId = "";
    }
    $sqlIdToBeId_usr = $link->query("SELECT beid FROM idlist WHERE id='$userId' LIMIT 1");
    if ($sqlIdToBeId_usr->num_rows > 0) {
      $usrBeId = $sqlIdToBeId_usr->fetch_assoc()["beid"];
      $sqlBeIdArr = $link->query("SELECT commentbeid FROM comments WHERE beid='$usrBeId' ORDER BY fulldate DESC");
      if ($sqlBeIdArr->num_rows > 0) {
        while($rowBeIdArr = $sqlBeIdArr->fetch_assoc()) {
          array_push($beIdArr, $rowBeIdArr["commentbeid"]);
        }
        $stopSts = true;
        if ($lastId == "") {
          $start = 0;
        } else {
          if (in_array($lastBeId, $beIdArr)) {
            $start = array_search($lastBeId, $beIdArr) + 1;
          } else {
            error("last-comment-n-found");
            $stopSts = false;
          }
        }
        if ($stopSts) {
          $stop = $start + $max;
          if ($stop > count($beIdArr) || $stop +3 >= count($beIdArr)) {
            $stop = count($beIdArr);
          }
          nextDataInfo(count($beIdArr), $stop - $start, count($beIdArr) - $stop);
          for ($i = $start; $i < $stop; $i++) {
            $beId = $beIdArr[$i];
            dataRender($beId, $i, $stop);
          }
        }
      } else {
        nextDataInfo(0, 0, 0);
      }
    } else {
      error("user-id-n-found");
    }
  } else {
    error("id-n-found");
  }

  function dataRender($beId, $current, $last) {
    global $link, $wrd_anonymousUser;
    $commId = getFrontendId($beId);
    $sqlComm = $link->query("SELECT critic, comment, dated, datem, datey FROM comments WHERE commentbeid='$beId' LIMIT 1");
    $comm = $sqlComm->fetch_assoc();
    $date = $comm['dated'].". ".$comm['datem'].". ".$comm['datey'];
    $criticBeId = $comm['critic'];
    $comment = nl2br($comm['comment']);
    $sqlCriticId = $link->query("SELECT * FROM idlist WHERE beid='$criticBeId' LIMIT 1");
    $sqlCriticData = $link->query("SELECT * FROM users WHERE beid='$criticBeId' LIMIT 1");
    if ($sqlCriticId->num_rows > 0 && $sqlCriticData->num_rows > 0) {
      $criticId = getAccountData($criticBeId, "id");
      $criticFirstname = getAccountData($criticBeId, "firstname");
      $criticLastname = getAccountData($criticBeId, "lastname");
      $criticProfImg = getAccountData($criticBeId, "small-profile-image");
      if (isset($_SESSION["email"])) {
        if (getAccountData($criticBeId, "email") == $_SESSION["email"]) {
          $accSts = "my-content";
        } else {
          $accSts = "common";
        }
      } else {
        $accSts = "common";
      }
    } else {
      $criticId = "#";
      $criticFirstname = $wrd_anonymousUser;
      $criticLastname = "";
      $criticProfImg = "uni/images/profile-image-mid-size.png";
      $accSts = "unknown";
    }
    $sqlStars = $link->query("SELECT percentage FROM ratingcriticsummary WHERE critic='$criticBeId' LIMIT 1");
    if ($sqlStars->num_rows > 0) {
      $stars = $sqlStars->fetch_assoc()['percentage'] * 5 / 100;
    } else {
      $stars = 0;
    }
    if ($current +1 == $last) {
      $jsonSts = 1;
    } else {
      $jsonSts = 0;
    }
    pushLinkToArray($jsonSts, $commId, $date, $comment, $criticId, $criticFirstname, $criticLastname, $criticProfImg, $accSts, $stars);
  }

  function pushLinkToArray($sts, $commId, $date, $comment, $criticId, $criticFirstname, $criticLastname, $criticProfImg, $accSts, $stars) {
    global $output;
    array_push($output, [
      "type" => "link",
      "id" => $commId,
      "date" => $date,
      "comment" => $comment,
      "criticId" => $criticId,
      "firstname" => $criticFirstname,
      "lastname" => $criticLastname,
      "img" => $criticProfImg,
      "accSts" => $accSts,
      "stars" => $stars
    ]);
    if ($sts == 1) {
      returnOutput();
    }
  }

  function nextDataInfo($allCott, $cottInProgress, $remainingCott) {
    global $output;
    array_push($output, [
      "type" => "load-next-sts",
      "all" => $allCott,
      "progress" => $cottInProgress,
      "remain" => $remainingCott
    ]);
    if ($cottInProgress == 0) {
      returnOutput();
    }
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
