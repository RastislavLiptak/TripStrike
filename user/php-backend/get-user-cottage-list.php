<?php
  include "../../uni/code/php-backend/data.php";
  include "link-data-render.php";
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
      $sqlBeIdArr = $link->query("SELECT beid FROM places WHERE usrbeid='$usrBeId' and status='active' ORDER BY fullDate DESC");
      if ($sqlBeIdArr->num_rows > 0) {
        while($rowBeIdArr = $sqlBeIdArr->fetch_assoc()) {
          array_push($beIdArr, $rowBeIdArr["beid"]);
        }
        $stopSts = true;
        if ($lastId == "") {
          $start = 0;
        } else {
          if (in_array($lastBeId, $beIdArr)) {
            $start = array_search($lastBeId, $beIdArr) + 1;
          } else {
            error("last-cottage-n-found");
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
            linkDataRender($beId, $i, $stop, "");
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

  function pushLinkToArray($sts, $class, $id, $name, $src, $priceMode, $priceWeek, $priceWork, $currency, $rating) {
    global $output;
    array_push($output, [
      "type" => "link",
      "class" => $class,
      "id" => $id,
      "name" => $name,
      "src" => $src,
      "priceMode" => $priceMode,
      "priceWeek" => $priceWeek,
      "priceWork" => $priceWork,
      "currency" => $currency,
      "rating" => $rating
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
