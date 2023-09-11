<?php
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder.'/backdoor/uni/code/php-backend/check-timeliness-of-booking.php';
  function getListOfPaymentReferenceBookings($get, $paymentReferenceCode, $lastBeId) {
    global $link, $linkBD;
    $listOfAllBookings = [];
    $listOfOutputBookings = [];
    $loadAmount = [];
    $limit = 25;
    $sqlPaymentReferencesCalls = $linkBD->query("SELECT * FROM feespaymentcalls WHERE paymentreference='$paymentReferenceCode'");
    if ($sqlPaymentReferencesCalls->num_rows > 0) {
      while($rowPaymentReferencesCalls = $sqlPaymentReferencesCalls->fetch_assoc()) {
        $paymentReferenceBeId = $rowPaymentReferencesCalls['beid'];
        $sqlPaymentReferencesKey = $linkBD->query("SELECT * FROM feespaymentcallskey WHERE beid='$paymentReferenceBeId'");
        if ($sqlPaymentReferencesKey->num_rows > 0) {
          while($rowPaymentReferencesKey = $sqlPaymentReferencesKey->fetch_assoc()) {
            array_push($listOfAllBookings, $rowPaymentReferencesKey['bookingbeid']);
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
                      "fromD" => $rowBookings['fromd'],
                      "fromM" => $rowBookings['fromm'],
                      "fromY" => $rowBookings['fromy'],
                      "toD" => $rowBookings['tod'],
                      "toM" => $rowBookings['tom'],
                      "toY" => $rowBookings['toy'],
                      "currency" => $rowBookings['currency'],
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
?>
