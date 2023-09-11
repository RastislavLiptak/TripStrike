<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/uni/code/php-backend/add-currency.php';
  function getListOfPaymentReferencesFees($get, $usrBeId, $lastPaymentReference, $searchQuery) {
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
      $sqlAllPaymentReferences = $linkBD->query("SELECT paymentreference FROM feespaymentcalls WHERE hostbeid='$usrBeId' ORDER BY fulldate DESC");
    } else {
      $searchBeIDList = [];
      if (in_array("payment-reference", $searchBy)) {
        $sqlPaymentReferenceFilter = $linkBD->query("SELECT beid FROM feespaymentcalls WHERE paymentreference LIKE '%$searchQuery%' and hostbeid='$usrBeId'");
        if ($sqlPaymentReferenceFilter->num_rows > 0) {
          while($rowPaymentReferenceFilter = $sqlPaymentReferenceFilter->fetch_assoc()) {
            array_push($searchBeIDList, $rowPaymentReferenceFilter["beid"]);
          }
        }
      }
      $sqlAllPaymentReferences = $linkBD->query("SELECT paymentreference FROM feespaymentcalls WHERE beid IN ('".implode("', '", $searchBeIDList)."') and hostbeid='$usrBeId' ORDER BY fulldate DESC");
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
                  array_push($listOfOutputPaymentReferences, [
                    "status" => $status,
                    "payment-reference" => $rowPaymentReferences['paymentreference'],
                    "numOfBookings" => $numOfBookings,
                    "totalFee" => $totalFee,
                    "currency" => $currency
                  ]);
                }
              }
            }
          }
        } else {
          $loadAmount = [
            "all-fees" => count($listOfAllPaymentReferences),
            "loaded" => $stop - $start,
            "remain" => count($listOfAllPaymentReferences) - $stop
          ];
        }
      }
    } else {
      if ($get == "load-amount") {
        $loadAmount = [
          "all-fees" => 0,
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
    return ["payment-reference"];
  }

  function giveStatusValue($status) {
    $stsValueArray = ["", "-", "rejected", "canceled", "waiting", "paid", "booked", "unpaid"];
    return array_search($status, $stsValueArray);
  }
?>
