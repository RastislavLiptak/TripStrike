<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  function getListOfPaymentReferencesFees($get, $lastPaymentReference, $searchQuery) {
    global $link, $linkBD;
    $listOfAllPaymentReferences = [];
    $listOfOutputPaymentReferences = [];
    $loadAmount = [];
    $limit = 25;
    if ($searchQuery != "") {
      $searchBy = getListOfPaymentReferencesFeesSearchType($searchQuery);
    } else {
      $searchBy = [];
    }
    if (sizeof($searchBy) == 0) {
      $sqlAllPaymentReferences = $linkBD->query("SELECT paymentreference FROM feespaymentcalls ORDER BY fulldate DESC");
    } else {
      $searchBeIDList = [];
      if (in_array("payment-reference", $searchBy)) {
        $sqlPaymentReferenceFilter = $linkBD->query("SELECT beid FROM feespaymentcalls WHERE paymentreference LIKE '%$searchQuery%'");
        if ($sqlPaymentReferenceFilter->num_rows > 0) {
          while($rowPaymentReferenceFilter = $sqlPaymentReferenceFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowPaymentReferenceFilter["beid"]);
          }
        }
      }
      if (in_array("hosts", $searchBy)) {
        $listOfSearchHost = [];
        $listOfSearchBookings = [];
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
            array_push($listOfSearchBookings, $rowBookingHostFilter["beid"]);
          }
        }
        $sqlPayRefHostFilter = $linkBD->query("SELECT beid FROM feespaymentcallskey WHERE bookingbeid IN ('".implode("', '", $listOfSearchBookings)."')");
        if ($sqlPayRefHostFilter->num_rows > 0) {
          while($rowPayRefHostFilter = $sqlPayRefHostFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowPayRefHostFilter["beid"]);
          }
        }
      }
      $sqlAllPaymentReferences = $linkBD->query("SELECT paymentreference FROM feespaymentcalls WHERE beid IN ('".implode("', '", $searchBeIDList)."') ORDER BY fulldate DESC");
    }
    if ($sqlAllPaymentReferences->num_rows > 0) {
      while($rowAllPaymentReferences = $sqlAllPaymentReferences->fetch_assoc()) {
        array_push($listOfAllPaymentReferences, $rowAllPaymentReferences["paymentreference"]);
      }
      $stopSts = true;
      if ($lastPaymentReference == "") {
        $start = 0;
      } else {
        if (in_array($lastPaymentReference, $listOfAllPaymentReferences)) {
          $start = array_search($lastPaymentReference, $listOfAllPaymentReferences) + 1;
        } else {
          $start = 0;
        }
      }
      if ($stopSts) {
        $stop = $start + $limit;
        if ($stop > count($listOfAllPaymentReferences) || $stop +3 >= count($listOfAllPaymentReferences)) {
          $stop = count($listOfAllPaymentReferences);
        }
        if ($get == "list") {
          for ($pR = $start; $pR < $stop; $pR++) {
            $numOfBookings = 0;
            $paymentReferenceCode = $listOfAllPaymentReferences[$pR];
            $sqlPaymentReferences = $linkBD->query("SELECT * FROM feespaymentcalls WHERE paymentreference='$paymentReferenceCode'");
            if ($sqlPaymentReferences->num_rows > 0) {
              while($rowPaymentReferences = $sqlPaymentReferences->fetch_assoc()) {
                $paymentReferenceBeId = $rowPaymentReferences['beid'];
                $sqlPaymentReferencesKey = $linkBD->query("SELECT * FROM feespaymentcallskey WHERE beid='$paymentReferenceBeId'");
                $numOfBookings = $sqlPaymentReferencesKey->num_rows;
                if ($numOfBookings > 0) {
                  $status = "";
                  $currency = "";
                  $totalFee = 0;
                  $paymentStatus = "none";
                  $hostBeId = $rowPaymentReferences['hostbeid'];
                  while($rowPaymentReferencesKey = $sqlPaymentReferencesKey->fetch_assoc()) {
                    $bookingBeId = $rowPaymentReferencesKey['bookingbeid'];
                    $sqlBooking = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
                    if ($sqlBooking->num_rows > 0) {
                      while ($rowBooking = $sqlBooking->fetch_assoc()) {
                        $bookingSts = $rowBooking['status'];
                        $currency = $rowBooking['currency'];
                        if ($rowBooking['paymentStatus'] == 1) {
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
                        if ($paymentStatus == "paid") {
                          $bookingSts = "paid";
                        } else {
                          if (checkTimelinessOfBooking($rowBooking['beid']) == "past" && $rowBooking['tom']."-".$rowBooking['toy'] != (int)date("m")."-".date("Y")) {
                            if ($bookingSts == "waiting" || $bookingSts == "booked") {
                              if ($rowBooking['paymentStatus'] == 1) {
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
                        $totalFee = $totalFee * 1 + $rowBooking['fee'];
                      }
                    }
                  }
                  $sqlAboutHost = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$hostBeId'");
                  if ($sqlAboutHost->num_rows > 0) {
                    while ($rowAboutHst = $sqlAboutHost->fetch_assoc()) {
                      $hostName = $rowAboutHst['firstname']." ".$rowAboutHst['lastname'];
                    }
                  } else {
                    $hostName = "-";
                  }
                  array_push($listOfOutputPaymentReferences, [
                    "status" => $status,
                    "hostID" => getFrontendId($hostBeId),
                    "hostName" => $hostName,
                    "payment-reference" => $rowPaymentReferences['paymentreference'],
                    "numOfBookings" => $numOfBookings,
                    "totalFee" => $totalFee,
                    "currency" => $currency,
                    "paymentStatus" => $paymentStatus
                  ]);
                }
              }
            }
          }
        } else {
          $loadAmount = [
            "all-bookings" => count($listOfAllPaymentReferences),
            "loaded" => $stop - $start,
            "remain" => count($listOfAllPaymentReferences) - $stop
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
      return $listOfOutputPaymentReferences;
    } else {
      return $loadAmount;
    }
  }

  function getListOfPaymentReferencesFeesSearchType($searchQuery) {
    if (preg_match("/[a-z]/i", $searchQuery)){
      return ["hosts"];
    } else {
      if (date_parse($searchQuery)) {
        return ["payment-reference"];
      } else {
        return ["hosts", "payment-reference"];
      }
    }
  }

  function giveStatusValue($status) {
    $stsValueArray = ["", "-", "rejected", "canceled", "waiting", "paid", "booked", "unpaid"];
    return array_search($status, $stsValueArray);
  }
?>
