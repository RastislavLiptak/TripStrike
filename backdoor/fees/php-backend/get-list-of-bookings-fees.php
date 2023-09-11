<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  function getListOfBookingsFees($get, $lastBeId, $searchQuery) {
    global $link, $linkBD;
    $listOfAllBookings = [];
    $listOfOutputBookings = [];
    $loadAmount = [];
    $limit = 25;
    if ($searchQuery != "") {
      $searchBy = getListOfBookingsFeesSearchType($searchQuery);
    } else {
      $searchBy = [];
    }
    if (sizeof($searchBy) == 0) {
      $sqlAllBookings = $linkBD->query("SELECT beid FROM bookingarchive WHERE status='waiting' or status='booked'  ORDER BY fulldate DESC");
    } else {
      $searchBeIDList = [];
      if (in_array("places", $searchBy)) {
        $listOfSearchPlace = [];
        $sqlSearchPlace = $link->query("SELECT beid FROM places WHERE name LIKE '%$searchQuery%'");
        if ($sqlSearchPlace->num_rows > 0) {
          while($rowSearchPlace = $sqlSearchPlace->fetch_assoc()) {
            array_push($listOfSearchPlace, $rowSearchPlace["beid"]);
          }
        }
        $sqlBookingPlaceFilter = $linkBD->query("SELECT beid FROM bookingarchive WHERE plcbeid IN ('".implode("', '", $listOfSearchPlace)."')");
        if ($sqlBookingPlaceFilter->num_rows > 0) {
          while($rowBookingPlaceFilter = $sqlBookingPlaceFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowBookingPlaceFilter["beid"]);
          }
        }
      }
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
      $sqlAllBookings = $linkBD->query("SELECT beid FROM bookingarchive WHERE (status='waiting' or status='booked') and beid IN ('".implode("', '", $searchBeIDList)."') ORDER BY fulldate DESC");
    }
    if ($sqlAllBookings->num_rows > 0) {
      while($rowAllBookings = $sqlAllBookings->fetch_assoc()) {
        array_push($listOfAllBookings, $rowAllBookings["beid"]);
      }
      $stopSts = true;
      if ($lastBeId == "") {
        $start = 0;
      } else {
        if (in_array($lastBeId, $listOfAllBookings)) {
          $start = array_search($lastBeId, $listOfAllBookings) + 1;
        } else {
          $start = 0;
        }
      }
      if ($stopSts) {
        $stop = $start + $limit;
        if ($stop > count($listOfAllBookings) || $stop +3 >= count($listOfAllBookings)) {
          $stop = count($listOfAllBookings);
        }
        if ($get == "list") {
          for ($b = $start; $b < $stop; $b++) {
            $bookingBeId = $listOfAllBookings[$b];
            $sqlBookings = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId'");
            if ($sqlBookings->num_rows > 0) {
              while($rowBookings = $sqlBookings->fetch_assoc()) {
                $plcBeID = $rowBookings['plcbeid'];
                $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID' LIMIT 1");
                if ($sqlPlc->num_rows > 0) {
                  $rowPlc = $sqlPlc->fetch_assoc();
                  $plcSts = $rowPlc['status'];
                  $plcID = getFrontendId($plcBeID);
                  $plcName = $rowPlc['name'];
                } else {
                  $plcSts = "delete";
                  $plcID = "-";
                  $plcName = "-";
                }
                $hostBeId = $rowBookings['hostbeid'];
                $sqlAboutHost = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$hostBeId'");
                if ($sqlAboutHost->num_rows > 0) {
                  while ($rowAboutHst = $sqlAboutHost->fetch_assoc()) {
                    $hostName = $rowAboutHst['firstname']." ".$rowAboutHst['lastname'];
                  }
                } else {
                  $hostName = "-";
                }
                $bookingSts = $rowBookings['status'];
                if ($rowBookings['paymentStatus'] == 1) {
                  $bookingSts = "paid";
                } else {
                  if (checkTimelinessOfBooking($rowBookings['beid']) == "past" && $rowBookings['tom']."-".$rowBookings['toy'] != (int)date("m")."-".date("Y")) {
                    if ($bookingSts == "booked") {
                      if ($rowBookings['paymentStatus'] == 1) {
                        $bookingSts = "paid";
                      } else {
                        $bookingSts = "unpaid";
                      }
                    }
                  }
                }
                array_push($listOfOutputBookings, [
                  "status" => $bookingSts,
                  "bookingID" => getFrontendId($bookingBeId),
                  "plcID" => $plcID,
                  "plcSts" => $plcSts,
                  "plcName" => $plcName,
                  "hostID" => getFrontendId($hostBeId),
                  "hostName" => $hostName,
                  "fromD" => $rowBookings['fromd'],
                  "fromM" => $rowBookings['fromm'],
                  "fromY" => $rowBookings['fromy'],
                  "toD" => $rowBookings['tod'],
                  "toM" => $rowBookings['tom'],
                  "toY" => $rowBookings['toy'],
                  "currency" => $rowBookings['currency'],
                  "totalPrice" => $rowBookings['totalprice'],
                  "fee" => $rowBookings['fee'],
                  "percentageFee" => $rowBookings['percentagefee']
                ]);
              }
            }
          }
        } else {
          $loadAmount = [
            "all-bookings" => count($listOfAllBookings),
            "loaded" => $stop - $start,
            "remain" => count($listOfAllBookings) - $stop
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
      return $listOfOutputBookings;
    } else {
      return $loadAmount;
    }
  }

  function getListOfBookingsFeesSearchType($searchQuery) {
    if (preg_match("/[a-z]/i", $searchQuery)){
      return ["hosts", "places"];
    } else {
      if (date_parse($searchQuery)) {
        return ["dates"];
      } else {
        return ["hosts", "places", "dates"];
      }
    }
  }
?>
