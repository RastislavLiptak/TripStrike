<?php
  function getPlacesOderedByBookings($limit, $imageSize, $exceptOf) {
    global $link;
    $output = [];
    $bookingsList = [];
    $sqlBookings = $link->query("SELECT plcbeid FROM booking WHERE (status='booked' or status='waiting') and plcbeid NOT IN ('".implode("', '", $exceptOf)."') ORDER BY fullDate DESC LIMIT 1000");
    if ($sqlBookings->num_rows > 0) {
      while($rowB = $sqlBookings->fetch_assoc()) {
        $placeInArray = false;
        for ($bL=0; $bL < sizeof($bookingsList); $bL++) {
          if ($bookingsList[$bL]["beid"] == $rowB['plcbeid']) {
            $placeInArray = true;
            $bookingsList[$bL]["repeat"] = ++$bookingsList[$bL]["repeat"];
          }
        }
        if (!$placeInArray) {
          array_push($bookingsList, [
            "beid" => $rowB['plcbeid'],
            "repeat" => 1
          ]);
        }
      }
      if (sizeof($bookingsList) > $limit) {
        $maxToSort = $limit;
      } else {
        $maxToSort = sizeof($bookingsList);
      }
      $topUsed = [];
      for ($sBL=0; $sBL < $maxToSort; $sBL++) {
        $topNum = 0;
        $topBeID = "";
        for ($checkBL=0; $checkBL < sizeof($bookingsList); $checkBL++) {
          if ($bookingsList[$checkBL]["repeat"] > $topNum && !in_array($bookingsList[$checkBL]["beid"], $topUsed)) {
            $topNum = $bookingsList[$checkBL]["repeat"];
            $topBeID = $bookingsList[$checkBL]["beid"];
          }
        }
        array_push($topUsed, $topBeID);
        $sql = $link->query("SELECT * FROM places WHERE beid='$topBeID' and status='active'");
        if ($sql->num_rows > 0) {
          while($row = $sql->fetch_assoc()) {
            array_push($output, [
              "beid" => $row['beid'],
              "id" => getFrontendId($row['beid']),
              "type" => $row['type'],
              "name" => $row['name'],
              "desc" => $row['description'],
              "img" => getImageForPlace($row['beid'], $imageSize),
              "price_mode" => $row['priceMode'],
              "price_work" => addCurrency($row['currency'], $row['workDayPrice']),
              "price_week" => addCurrency($row['currency'], $row['weekDayPrice']),
              "guestNum" => $row['guestNum'],
              "bedNum" => $row['bedNum'],
              "bathNum" => $row['bathNum'],
              "distanceFromTheWater" => $row['distanceFromTheWater'],
              "usrbeid" => $row['usrbeid']
            ]);
          }
        }
      }
    }
    $outputUsedPlaces = [];
    if (sizeof($output) < $limit) {
      foreach ($output as $outputExOf) {
        array_push($outputUsedPlaces, $outputExOf["beid"]);
      }
      foreach ($exceptOf as $exOf) {
        array_push($outputUsedPlaces, $exOf);
      }
      $outputByDate = getPlacesOderedByDate($limit - sizeof($output), $imageSize, $outputUsedPlaces);
      foreach ($outputByDate as $outputUsedPlcsPush) {
        array_push($output, [
          "beid" => $outputUsedPlcsPush['beid'],
          "id" => $outputUsedPlcsPush['id'],
          "type" => $outputUsedPlcsPush['type'],
          "name" => $outputUsedPlcsPush['name'],
          "desc" => $outputUsedPlcsPush['desc'],
          "img" => $outputUsedPlcsPush['img'],
          "price_mode" => $outputUsedPlcsPush['price_mode'],
          "price_work" => $outputUsedPlcsPush['price_work'],
          "price_week" => $outputUsedPlcsPush['price_week'],
          "guestNum" => $outputUsedPlcsPush['guestNum'],
          "bedNum" => $outputUsedPlcsPush['bedNum'],
          "bathNum" => $outputUsedPlcsPush['bathNum'],
          "distanceFromTheWater" => $outputUsedPlcsPush['distanceFromTheWater'],
          "usrbeid" => $outputUsedPlcsPush['usrbeid']
        ]);
      }
    }
    return $output;
  }

  function getPlacesOderedByDate($limit, $imageSize, $exceptOf) {
    global $link;
    $output = [];
    $sql = $link->query("SELECT * FROM places WHERE status='active' and beid NOT IN ('".implode("', '", $exceptOf)."') ORDER BY fullDate DESC LIMIT $limit");
    if ($sql->num_rows > 0) {
      while($row = $sql->fetch_assoc()) {
        array_push($output, [
          "beid" => $row['beid'],
          "id" => getFrontendId($row['beid']),
          "type" => $row['type'],
          "name" => $row['name'],
          "desc" => $row['description'],
          "img" => getImageForPlace($row['beid'], $imageSize),
          "price_mode" => $row['priceMode'],
          "price_work" => addCurrency($row['currency'], $row['workDayPrice']),
          "price_week" => addCurrency($row['currency'], $row['weekDayPrice']),
          "guestNum" => $row['guestNum'],
          "bedNum" => $row['bedNum'],
          "bathNum" => $row['bathNum'],
          "distanceFromTheWater" => $row['distanceFromTheWater'],
          "usrbeid" => $row['usrbeid']
        ]);
      }
    }
    return $output;
  }

  function getPlacesOderedByRand($limit, $imageSize, $exceptOf) {
    global $link;
    $output = [];
    $sql = $link->query("SELECT * FROM places WHERE status='active' and beid NOT IN ('".implode("', '", $exceptOf)."') ORDER BY RAND() LIMIT $limit");
    if ($sql->num_rows > 0) {
      while($row = $sql->fetch_assoc()) {
        array_push($output, [
          "beid" => $row['beid'],
          "id" => getFrontendId($row['beid']),
          "type" => $row['type'],
          "name" => $row['name'],
          "desc" => $row['description'],
          "img" => getImageForPlace($row['beid'], $imageSize),
          "price_mode" => $row['priceMode'],
          "price_work" => addCurrency($row['currency'], $row['workDayPrice']),
          "price_week" => addCurrency($row['currency'], $row['weekDayPrice']),
          "guestNum" => $row['guestNum'],
          "bedNum" => $row['bedNum'],
          "bathNum" => $row['bathNum'],
          "distanceFromTheWater" => $row['distanceFromTheWater'],
          "usrbeid" => $row['usrbeid']
        ]);
      }
    }
    return $output;
  }

  function getImageForPlace($beId, $size) {
    global $link;
    $sqlPlcImg = $link->query("SELECT imgbeid, sts FROM placeimages WHERE cottbeid='$beId' and type='$size' and sts='main' LIMIT 1");
    if ($sqlPlcImg->num_rows > 0) {
      $imgBeId = $sqlPlcImg->fetch_assoc()["imgbeid"];
      $sqlImgSrc = $link->query("SELECT src FROM images WHERE name='$imgBeId'");
      if ($sqlImgSrc->num_rows > 0) {
        return $sqlImgSrc->fetch_assoc()['src'];
      } else {
        return "uni/icons/home5.svg";
      }
    } else {
      return "uni/icons/home5.svg";
    }
  }
?>
