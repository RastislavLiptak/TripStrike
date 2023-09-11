<?php
  function getBookingHistory($get, $bookingBeId, $lastBeId) {
    global $link, $linkBD;
    $listOfAllUpdates = [];
    $listOfOutputUpdates = [];
    $loadAmount = [];
    $limit = 25;
    $sqlAllUpdates = $linkBD->query("SELECT beid FROM bookingupdatearchive WHERE bookingbeid='$bookingBeId' ORDER BY fulldate DESC");
    if ($sqlAllUpdates->num_rows > 0) {
      while($rowAllUpdates = $sqlAllUpdates->fetch_assoc()) {
        array_push($listOfAllUpdates, $rowAllUpdates["beid"]);
      }
      $stopSts = true;
      if ($lastBeId == "") {
        $start = 0;
      } else {
        if (in_array($lastBeId, $listOfAllUpdates)) {
          $start = array_search($lastBeId, $listOfAllUpdates) + 1;
        } else {
          $start = 0;
        }
      }
      if ($stopSts) {
        $stop = $start + $limit;
        if ($stop > count($listOfAllUpdates) || $stop +3 >= count($listOfAllUpdates)) {
          $stop = count($listOfAllUpdates);
        }
        if ($get == "list") {
          for ($u = $start; $u < $stop; $u++) {
            $updateBeId = $listOfAllUpdates[$u];
            $sqlUpdates = $linkBD->query("SELECT * FROM bookingupdatearchive WHERE beid='$updateBeId'");
            if ($sqlUpdates->num_rows > 0) {
              while($rowUpdates = $sqlUpdates->fetch_assoc()) {
                $plcBeID = $rowUpdates['plcbeid'];
                $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID' LIMIT 1");
                if ($sqlPlc->num_rows > 0) {
                  $rowPlc = $sqlPlc->fetch_assoc();
                  $plcID = getFrontendId($plcBeID);
                  $plcName = $rowPlc['name'];
                } else {
                  $plcID = "-";
                  $plcName = "-";
                }
                $hostBeId = $rowUpdates['hostbeid'];
                $sqlAboutHost = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$hostBeId'");
                if ($sqlAboutHost->num_rows > 0) {
                  while ($rowAboutHst = $sqlAboutHost->fetch_assoc()) {
                    $hostName = $rowAboutHst['firstname']." ".$rowAboutHst['lastname'];
                  }
                } else {
                  $hostName = "-";
                }
                $guestBeId = $rowUpdates['usrbeid'];
                $sqlAboutguest = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$guestBeId'");
                if ($sqlAboutguest->num_rows > 0) {
                  while ($rowAboutGst = $sqlAboutguest->fetch_assoc()) {
                    $guestName = $rowAboutGst['firstname']." ".$rowAboutGst['lastname'];
                  }
                } else {
                  $guestName = "-";
                }
                array_push($listOfOutputUpdates, [
                  "updateID" => getFrontendId($updateBeId),
                  "status" => $rowUpdates['status'],
                  "paymentStatus" => $rowUpdates['paymentStatus'],
                  "plcID" => $plcID,
                  "plcName" => $plcName,
                  "hostID" => getFrontendId($hostBeId),
                  "hostName" => $hostName,
                  "guestID" => getFrontendId($guestBeId),
                  "guestName" => $guestName,
                  "source" => $rowUpdates['source'],
                  "numOfGuests" => $rowUpdates['guestnum'],
                  "fromD" => $rowUpdates['fromd'],
                  "fromM" => $rowUpdates['fromm'],
                  "fromY" => $rowUpdates['fromy'],
                  "firstday" => $rowUpdates['firstday'],
                  "toD" => $rowUpdates['tod'],
                  "toM" => $rowUpdates['tom'],
                  "toY" => $rowUpdates['toy'],
                  "lastday" => $rowUpdates['lastday'],
                  "currency" => $rowUpdates['currency'],
                  "priceMode" => $rowUpdates['plcpricemode'],
                  "workPrice" => $rowUpdates['plcworkprice'],
                  "weekPrice" => $rowUpdates['plcweekprice'],
                  "totalPrice" => $rowUpdates['totalprice'],
                  "fee" => $rowUpdates['fee'],
                  "percentageFee" => $rowUpdates['percentagefee'],
                  "validFrom" => $rowUpdates['fulldate']
                ]);
              }
            }
          }
        } else {
          $loadAmount = [
            "all-bookings" => count($listOfAllUpdates),
            "loaded" => $stop - $start,
            "remain" => count($listOfAllUpdates) - $stop
          ];
        }
      }
    } else {
      if ($get == "load-amount") {
        $loadAmount = [
          "all-bookings" => 0,
          "loaded" => 0,
          "remain" => 0
        ];
      }
    }
    if ($get == "list") {
      return $listOfOutputUpdates;
    } else {
      return $loadAmount;
    }
  }
?>
