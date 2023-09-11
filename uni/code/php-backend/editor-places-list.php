<?php
  include "data.php";
  include "get-frontend-id.php";
  include "../../../uni/dictionary/lang-select.php";
  include "get-user.php";
  $lastId = $link->escape_string($_POST['lastId']);
  $output = [];
  $beIdArr = [];
  $max = 9;
  if ($sign == "yes") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$lastId' LIMIT 1");
    if ($sqlIdToBeId->num_rows > 0 || $lastId == "") {
      if ($lastId != "") {
        $lastBeId = $sqlIdToBeId->fetch_assoc()["beid"];
      } else {
        $lastBeId = "";
      }
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
            $sqlPlc = $link->query("SELECT name FROM places WHERE beId='$beId'");
            $plc = $sqlPlc->fetch_assoc();
            $plcId = getFrontendId($beId);
            $plcName = $plc['name'];
            $sqlImgBeID = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$beId' and type='sml' and sts='main' ORDER BY numid DESC LIMIT 1");
            if ($sqlImgBeID->num_rows > 0) {
              $imgName = $sqlImgBeID->fetch_assoc()["imgbeid"];
              $sqlImgSrc = $link->query("SELECT src FROM images WHERE name='$imgName' && status='plc-sml'");
              if ($sqlImgSrc ->num_rows > 0) {
                $imgSrc = $sqlImgSrc->fetch_assoc()['src'];
              } else {
                $imgSrc = "#";
              }
            } else {
              $imgSrc = "#";
            }
            if ($i +1 == $stop) {
              $jsonSts = 1;
            } else {
              $jsonSts = 0;
            }
            pushDataToArray($jsonSts, $plcId, $plcName, $imgSrc);
          }
        }
      } else {
        nextDataInfo(0, 0, 0);
      }
    } else {
      error("id-n-found");
    }
  } else {
    error("not-signed-in");
  }

  function pushDataToArray($sts, $plcId, $plcName, $imgSrc) {
    global $output, $wrd_edit;
    array_push($output, [
      "type" => "link",
      "wrdEdit" => $wrd_edit,
      "id" => $plcId,
      "name" => $plcName,
      "src" => $imgSrc
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
