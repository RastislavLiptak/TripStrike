<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-account-data.php";
  $plcId = $link->escape_string($_POST['plcId']);
  $lastId = $link->escape_string($_POST['lastId']);
  $output = [];
  $commBeIdArr = [];
  // $criticsBeIdArr = [];
  $max = 10;
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastId' LIMIT 1");
  if ($sqlIdToBeId->num_rows > 0 || $lastId == "") {
    if ($lastId != "") {
      $lastBeId = $sqlIdToBeId->fetch_assoc()["beid"];
    } else {
      $lastBeId = "";
    }
    $sqlIdToBeId_plc = $link->query("SELECT beid FROM idlist WHERE id='$plcId' LIMIT 1");
    if ($sqlIdToBeId_plc->num_rows > 0) {
      $plcBeId = $sqlIdToBeId_plc->fetch_assoc()["beid"];
      $sqlCommBeIdArr = $link->query("SELECT * FROM comments WHERE beid='$plcBeId' ORDER BY fulldate DESC");
      if ($sqlCommBeIdArr->num_rows > 0) {
        while ($rowCommBeId = $sqlCommBeIdArr->fetch_assoc()) {
          array_push($commBeIdArr, $rowCommBeId["commentbeid"]);
        }
        $stopSts = true;
        if ($lastId == "") {
          $start = 0;
        } else {
          if (in_array($lastBeId, $commBeIdArr)) {
            $start = array_search($lastBeId, $commBeIdArr) + 1;
          } else {
            error("last-rate-n-found");
            $stopSts = false;
          }
        }
        if ($stopSts) {
          $stop = $start + $max;
          if ($stop > count($commBeIdArr) || $stop +3 >= count($commBeIdArr)) {
            $stop = count($commBeIdArr);
          }
          nextDataInfo(count($commBeIdArr), $stop - $start, count($commBeIdArr) - $stop);
          for ($i = $start; $i < $stop; $i++) {
            dataSelector($commBeIdArr[$i], $i, $stop);
          }
        }
      } else {
        nextDataInfo(0, 0, 0);
      }
    } else {
      error("plc-id-n-found");
    }
  } else {
    error("id-n-found");
  }

  function dataSelector($commBeId, $current, $last) {
    global $link, $plcBeId, $wrd_anonymousUser;
    $frEndID = getFrontendId($commBeId);
    $sqlCommCritic = $link->query("SELECT critic, comment, fulldate FROM comments WHERE commentbeid='$commBeId'");
    $commentData = $sqlCommCritic->fetch_assoc();
    $criticBeId = $commentData["critic"];
    $sqlCriticsSumm = $link->query("SELECT percentage FROM ratingcriticsummary WHERE critic='$criticBeId' and beid='$plcBeId' LIMIT 1");
    $rowCrSumm = $sqlCriticsSumm->fetch_assoc();
    $stars = str_replace('.',',',round($rowCrSumm["percentage"] * 5 / 100, 2));
    $sqlBeIdArr = $link->query("SELECT firstname, lastname FROM users WHERE beid='$criticBeId' and status='active' LIMIT 1");
    if ($sqlBeIdArr->num_rows > 0) {
      $firstname = getAccountData($criticBeId, "firstname");
      $lastname = getAccountData($criticBeId, "lastname");
      $criticId = getAccountData($criticBeId, "id");
      $criticImg = getAccountData($criticBeId, "medium-profile-image");
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
      $firstname = $wrd_anonymousUser;
      $lastname = "";
      $criticId = "#";
      $criticImg = "uni/images/profile-image-mid-size.png";
      $accSts = "unknown";
    }
    $ratingDate = $commentData['fulldate'];
    $ago = agoCalc($ratingDate);
    $comment = nl2br($commentData["comment"]);
    if ($comment != "") {
      $maxCommChars = 250;
      $commentShort = substr($comment, 0, $maxCommChars);
      $maxCommChars = $maxCommChars - (substr_count($commentShort, "\n") * 15);
      $commentShort = substr($comment, 0, $maxCommChars);
      if (trim(preg_replace('/\s\s+/', ' ', $commentShort)) != trim(preg_replace('/\s\s+/', ' ', $comment))) {
        $commentSts = "short/long";
      } else {
        $commentSts = "fix";
      }
      $commentShort = $commentShort."...";
      $commentLong = $comment;
    } else {
      $commentSts = "fix";
      $commentShort = "";
      $commentLong = "";
    }
    if ($current +1 == $last) {
      $jsonSts = 1;
    } else {
      $jsonSts = 0;
    }
    pushLinkToArray($frEndID, $criticId, $criticImg, $firstname, $lastname, $accSts, $ago, $stars, $commentSts, $commentShort, $commentLong, $jsonSts);
  }

  function pushLinkToArray($frEndID, $criticId, $criticImg, $firstname, $lastname, $accSts, $ago, $stars, $commentSts, $commentShort, $commentLong, $sts) {
    global $output;
    array_push($output, [
      "type" => "comm",
      "id" => $frEndID,
      "usr_id" => $criticId,
      "usr_img" => $criticImg,
      "firstname" => $firstname,
      "lastname" => $lastname,
      "acc_sts" => $accSts,
      "ago" => $ago,
      "stars" => $stars,
      "comment_sts" => $commentSts,
      "comment_short" => $commentShort,
      "comment_long" => $commentLong
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

  function agoCalc($ago) {
    global $wrd_ago_before, $wrd_ago_after, $wrd_ago_year, $wrd_ago_years, $wrd_ago_month, $wrd_ago_months, $wrd_ago_day, $wrd_ago_days, $wrd_ago_hour, $wrd_ago_hours, $wrd_ago_minute, $wrd_ago_minutes, $wrd_ago_second, $wrd_ago_seconds, $wrd_now;
    $currentDateTime = date('Y-m-d H:i:s');
    $seconds = strtotime($currentDateTime) - strtotime($ago);
    $years = floor($seconds / (365*60*60*24));
    $months = floor(($seconds - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($seconds - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours = floor($seconds / 3600);
    $minutes = floor($seconds / 60);
    if ($seconds < 60) {
      if ($seconds > 1) {
        return $wrd_ago_before." ".$seconds." ".$wrd_ago_seconds." ".$wrd_ago_after;
      } else if ($seconds == 1) {
        return $wrd_ago_before." ".$seconds." ".$wrd_ago_second." ".$wrd_ago_after;
      } else {
        return $wrd_now;
      }
    } else if ($minutes < 60) {
      if ($minutes > 1) {
        return $wrd_ago_before." ".$minutes." ".$wrd_ago_minutes." ".$wrd_ago_after;
      } else {
        return $wrd_ago_before." ".$minutes." ".$wrd_ago_minute." ".$wrd_ago_after;
      }
    } else if ($hours < 24) {
      if ($hours > 1) {
        return $wrd_ago_before." ".$hours." ".$wrd_ago_hours." ".$wrd_ago_after;
      } else {
        return $wrd_ago_before." ".$hours." ".$wrd_ago_hour." ".$wrd_ago_after;
      }
    } else if ($days < 31) {
      if ($days > 1) {
        return $wrd_ago_before." ".$days." ".$wrd_ago_days." ".$wrd_ago_after;
      } else {
        return $wrd_ago_before." ".$days." ".$wrd_ago_day." ".$wrd_ago_after;
      }
    } else if ($months < 12) {
      if ($months > 1) {
        return $wrd_ago_before." ".$months." ".$wrd_ago_months." ".$wrd_ago_after;
      } else {
        return $wrd_ago_before." ".$months." ".$wrd_ago_month." ".$wrd_ago_after;
      }
    } else {
      if ($years > 1) {
        return $wrd_ago_before." ".$years." ".$wrd_ago_years." ".$wrd_ago_after;
      } else {
        return $wrd_ago_before." ".$years." ".$wrd_ago_year." ".$wrd_ago_after;
      }
    }
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
