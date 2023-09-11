<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  function getListOfHostsFees($get, $lastBeId, $lastPeriodY, $lastPeriodM, $searchQuery) {
    global $link, $linkBD;
    $listOfUsedData = [];
    $listOfOutputs = [];
    $loadAmount = [];
    $limit = 25;
    $done = 0;
    $loaded = 0;
    $readyToStart = false;
    if ($lastBeId == "" || $lastPeriodY == "0" || $lastPeriodM == "0") {
      $readyToStart = true;
    }
    if ($searchQuery != "") {
      $searchBy = getListOfHostsFeesSearchType($searchQuery);
    } else {
      $searchBy = [];
    }
    if (sizeof($searchBy) == 0) {
      $sqlAllBookings = $linkBD->query("SELECT hostbeid, currency, tom, toy FROM bookingarchive WHERE status='waiting' or status='booked'  ORDER BY todate DESC");
    } else {
      $searchBeIDList = [];
      if (in_array("hosts", $searchBy)) {
        $listOfSearchHost = [];
        $explodeName = explode(' ', $searchQuery, 2);
        if (sizeof($explodeName) > 1) {
          if ($explodeName[1] != "") {
            $fstName = $explodeName[0];
            $secName = $explodeName[1];
            $sqlSearchHost = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$fstName%' or lastname LIKE '%$secName%' or firstname LIKE '%$secName%' or lastname LIKE '%$fstName%'");
          } else {
            $searchQuery = str_replace(" ", "", $searchQuery);
            $sqlSearchHost = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
          }
        } else {
          $sqlSearchHost = $linkBD->query("SELECT beid FROM usersarchive WHERE firstname LIKE '%$searchQuery%' or lastname LIKE '%$searchQuery%'");
        }
        if ($sqlSearchHost->num_rows > 0) {
          while($rowSearchHost = $sqlSearchHost->fetch_assoc()) {
            array_push($listOfSearchHost, $rowSearchHost["beid"]);
          }
        }
        $sqlBookingHostFilter = $linkBD->query("SELECT beid FROM bookingarchive WHERE hostbeid IN ('".implode("', '", $listOfSearchHost)."')");
        if ($sqlBookingHostFilter->num_rows > 0) {
          while($rowBookingHostFilter = $sqlBookingHostFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowBookingHostFilter["beid"]);
          }
        }
      }
      if (in_array("dates", $searchBy)) {
        $searchByDateWhere = "";
        if (sizeof(explode(" - ", $searchQuery)) == 2) {
          $searchFromD = getDataFromDate("d", explode(" - ", $searchQuery)[0]);
          $searchFromM = getDataFromDate("m", explode(" - ", $searchQuery)[0]);
          $searchFromY = getDataFromDate("y", explode(" - ", $searchQuery)[0]);
          $searchToD = getDataFromDate("d", explode(" - ", $searchQuery)[1]);
          $searchToM = getDataFromDate("m", explode(" - ", $searchQuery)[1]);
          $searchToY = getDataFromDate("y", explode(" - ", $searchQuery)[1]);
          if ($searchFromD != "-") {
            $searchByDateWhere = "fromd = '".$searchFromD."'";
          }
          if ($searchFromM != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and fromm = '".$searchFromM."'";
            } else {
              $searchByDateWhere = "fromm = '".$searchFromM."'";
            }
          }
          if ($searchFromY != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and fromy = '".$searchFromY."'";
            } else {
              $searchByDateWhere = "fromy = '".$searchFromY."'";
            }
          }
          if ($searchToD != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and tod = '".$searchToD."'";
            } else {
              $searchByDateWhere = "tod = '".$searchToD."'";
            }
          }
          if ($searchToM != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and tom = '".$searchToM."'";
            } else {
              $searchByDateWhere = "tom = '".$searchToM."'";
            }
          }
          if ($searchToY != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and toy = '".$searchToY."'";
            } else {
              $searchByDateWhere = "toy = '".$searchToY."'";
            }
          }
        } else if (sizeof(explode(" - ", $searchQuery)) == 1) {
          $searchD = getDataFromDate("d", $searchQuery);
          $searchM = getDataFromDate("m", $searchQuery);
          $searchY = getDataFromDate("y", $searchQuery);
          if ($searchD != "-") {
            $searchByDateWhere = "(fromd = '".$searchD."' or tod = '".$searchD."')";
          }
          if ($searchM != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and (fromm = '".$searchM."' or tom = '".$searchM."')";
            } else {
              $searchByDateWhere = "(fromm = '".$searchM."' or tom = '".$searchM."')";
            }
          }
          if ($searchY != "-") {
            if ($searchByDateWhere != "") {
              $searchByDateWhere = $searchByDateWhere." and (fromy = '".$searchY."' or toy = '".$searchY."')";
            } else {
              $searchByDateWhere = "(fromy = '".$searchY."' or toy = '".$searchY."')";
            }
          }
        }
        $sqlBookingDatesFilter = $linkBD->query("SELECT beid FROM bookingarchive WHERE (".$searchByDateWhere.")");
        if ($sqlBookingDatesFilter->num_rows > 0) {
          while($rowBookingDatesFilter = $sqlBookingDatesFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowBookingDatesFilter["beid"]);
          }
        }
      }
      $sqlAllBookings = $linkBD->query("SELECT hostbeid, currency, tom, toy FROM bookingarchive WHERE (status='waiting' or status='booked' ) and beid IN ('".implode("', '", $searchBeIDList)."') ORDER BY fulldate DESC");
    }
    $numOfAllBookings = $sqlAllBookings->num_rows;
    if ($numOfAllBookings > 0) {
      while ($rowAllBookings = $sqlAllBookings->fetch_assoc()) {
        if ($done < $limit) {
          $archHstBeID = $rowAllBookings['hostbeid'];
          $archToY = $rowAllBookings['toy'];
          $archToM = $rowAllBookings['tom'];
          if ($readyToStart) {
            $inArray = false;
            for ($lOUD=0; $lOUD < sizeOf($listOfUsedData); $lOUD++) {
              if (
                $listOfUsedData[$lOUD]["hostbeid"] == $archHstBeID &&
                $listOfUsedData[$lOUD]["toY"] == $archToY &&
                $listOfUsedData[$lOUD]["toM"] == $archToM
              ) {
                $inArray = true;
              }
            }
            if (!$inArray) {
              array_push($listOfUsedData, [
                "hostbeid" => $archHstBeID,
                "toY" => $archToY,
                "toM" => $archToM
              ]);
              $status = "";
              $periodM = $archToM;
              $periodY = $archToY;
              $numOfBookings = 0;
              $currency = $rowAllBookings['currency'];
              $totalPrice = 0;
              $totalFee = 0;
              $paymentStatus = "none";
              $sqlHostDataCalc = $linkBD->query("SELECT * FROM bookingarchive WHERE hostbeid='$archHstBeID' and tom='$archToM' and toy='$archToY'");
              $numOfBookings = $sqlHostDataCalc->num_rows;
              if ($numOfBookings > 0) {
                while ($rowHstData = $sqlHostDataCalc->fetch_assoc()) {
                  $bookingSts = $rowHstData['status'];
                  if ($bookingSts != "waiting") {
                    if ($rowHstData['paymentStatus'] == 1) {
                      if ($paymentStatus == "none" || $paymentStatus == "paid") {
                        $paymentStatus = "paid";
                      } else {
                        $paymentStatus = "multiple";
                      }
                    } else {
                      if ($paymentStatus == "none" || $paymentStatus == "unpaid") {
                        $paymentStatus = "unpaid";
                      } else {
                        $paymentStatus = "multiple";
                      }
                    }
                  }
                  if ($paymentStatus == "paid") {
                    $bookingSts = "paid";
                  } else {
                    if (checkTimelinessOfBooking($rowHstData['beid']) == "past" && $archToM."-".$archToY != (int)date("m")."-".date("Y")) {
                      if ($bookingSts == "waiting" || $bookingSts == "booked") {
                        if ($rowHstData['paymentStatus'] == 1) {
                          $bookingSts = "paid";
                        } else {
                          $bookingSts = "unpaid";
                        }
                      }
                    }
                  }
                  if (giveStatusValue($status) < giveStatusValue($bookingSts)) {
                    $status = $bookingSts;
                  }
                  if ($bookingSts != "waiting") {
                    $totalPrice = $totalPrice * 1 + $rowHstData['totalprice'];
                    $totalFee = $totalFee * 1 + $rowHstData['fee'];
                  }
                  ++$loaded;
                }
              }
              $sqlAboutHost = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$archHstBeID'");
              if ($sqlAboutHost->num_rows > 0) {
                while ($rowAboutHst = $sqlAboutHost->fetch_assoc()) {
                  $hostName = $rowAboutHst['firstname']." ".$rowAboutHst['lastname'];
                }
              } else {
                $hostName = "-";
              }
              array_push($listOfOutputs, [
                "type" => "host",
                "id" => getFrontendId($archHstBeID),
                "username" => $hostName,
                "status" => $status,
                "periodM" => $periodM,
                "periodY" => $periodY,
                "numOfBookings" => $numOfBookings,
                "currency" => $currency,
                "totalPrice" => $totalPrice,
                "totalFee" => $totalFee,
                "paymentStatus" => $paymentStatus
              ]);
              ++$done;
            }
          } else {
            $inArray = false;
            for ($lOUD=0; $lOUD < sizeOf($listOfUsedData); $lOUD++) {
              if (
                $listOfUsedData[$lOUD]["hostbeid"] == $archHstBeID &&
                $listOfUsedData[$lOUD]["toY"] == $archToY &&
                $listOfUsedData[$lOUD]["toM"] == $archToM
              ) {
                $inArray = true;
              }
            }
            if (!$inArray) {
              array_push($listOfUsedData, [
                "hostbeid" => $archHstBeID,
                "toY" => $archToY,
                "toM" => $archToM
              ]);
            }
            $sqlHostDataCalc = $linkBD->query("SELECT * FROM bookingarchive WHERE hostbeid='$archHstBeID' and tom='$archToM' and toy='$archToY'");
            if ($sqlHostDataCalc->num_rows > 0) {
              while ($rowHstData = $sqlHostDataCalc->fetch_assoc()) {
                ++$loaded;
              }
            }
            if ($archHstBeID == $lastBeId && $archToY == $lastPeriodY && $archToM == $lastPeriodM) {
              $readyToStart = true;
            }
          }
        }
      }
      $loadAmount = [
        "all-bookings" => $numOfAllBookings,
        "loaded" => $loaded,
        "remain" => $numOfAllBookings - $loaded
      ];
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
      return $listOfOutputs;
    } else {
      return $loadAmount;
    }
  }

  function getListOfHostsFeesSearchType($searchQuery) {
    if (preg_match("/[a-z]/i", $searchQuery)){
      return ["hosts"];
    } else {
      if (date_parse($searchQuery)) {
        return ["dates"];
      } else {
        return ["hosts", "dates"];
      }
    }
  }

  function giveStatusValue($status) {
    $stsValueArray = ["", "-", "rejected", "canceled", "waiting", "paid", "booked", "unpaid"];
    return array_search($status, $stsValueArray);
  }
?>
