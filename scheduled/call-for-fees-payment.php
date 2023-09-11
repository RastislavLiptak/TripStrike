<?php
  // localhost:8888/projects/Cottages/scheduled/call-for-fees-payment.php
  include "../uni/code/php-backend/data.php";
  include "../uni/code/php-backend/get-frontend-id.php";
  include "../uni/code/php-backend/random-hash-maker.php";
  include "../uni/code/php-backend/add-currency.php";
  include "../backdoor/uni/code/php-backend/check-timeliness-of-booking.php";
  include "../email-sender/php-backend/send-mail.php";
  include "../email-sender/php-backend/call-for-fees-payment-mail.php";
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  if (getBackDoorSettingsValue("date-of-call-for-fees-day") == $dateD) {
    if (
      (
        getBackDoorSettingsValue("date-of-call-for-fees-time-hours") == date("H") &&
        getBackDoorSettingsValue("date-of-call-for-fees-time-minutes") <= date("i")
      ) ||
      getBackDoorSettingsValue("date-of-call-for-fees-time-hours") <= date("H")
    ) {
      $sqlCheckLog = $linkBD->query("SELECT * FROM callforfeespaymentchecklog WHERE day='$dateD' and month='$dateM' and year='$dateY' and status='done'");
      if ($sqlCheckLog->num_rows == 0) {
        $logBeIdReady = false;
        while (!$logBeIdReady) {
          $logBeId = randomHash(64);
          $sqlLogCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$logBeId'");
          if ($sqlLogCH->num_rows == 0) {
            $logBeIdReady = true;
          } else {
            $logBeIdReady = false;
          }
        }
        $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
        $sqlLogBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$logBeId', '$backendIDNum', 'fee-payment-call-log')";
        if (mysqli_query($link, $sqlLogBeID)) {
          $sqlInsertCheckLogInProgress = "INSERT INTO callforfeespaymentchecklog (beid, status, day, month, year, fulldate) VALUES('$logBeId', 'in-progress', '$dateD', '$dateM', '$dateY', '$date')";
          if (mysqli_query($linkBD, $sqlInsertCheckLogInProgress)) {
            $allHostsDone = false;
            $selectedHosts = [];
            while (!$allHostsDone) {
              $totalFee = 0;
              $totalCurrency = "";
              $bookingsList = [];
              $callIDReady = false;
              while (!$callIDReady) {
                $callID = randomHash(11);
                $sqlCallIDCH = $link -> query("SELECT * FROM idlist WHERE id='$callID'");
                if ($sqlCallIDCH->num_rows == 0) {
                  $callIDReady = true;
                } else {
                  $callIDReady = false;
                }
              }
              $callBeIdReady = false;
              while (!$callBeIdReady) {
                $callBeId = randomHash(64);
                $sqlCallBeIDCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$callBeId'");
                if ($sqlCallBeIDCH->num_rows == 0) {
                  $callBeIdReady = true;
                } else {
                  $callBeIdReady = false;
                }
              }
              $paymentReferenceReady = false;
              while (!$paymentReferenceReady) {
                $paymentReference = rand(100,9999999999);
                $sqlPaymentRefCH = $linkBD -> query("SELECT * FROM feespaymentcalls WHERE paymentreference='$paymentReference'");
                if ($sqlPaymentRefCH->num_rows == 0) {
                  $paymentReferenceReady = true;
                } else {
                  $paymentReferenceReady = false;
                }
              }
              $sqlSelectHost = $linkBD->query("SELECT hostbeid FROM bookingarchive WHERE status='booked' and paymentStatus='0' and fee!='0' and hostbeid NOT IN ('".implode("', '", $selectedHosts)."') ORDER BY fulldate DESC LIMIT 1");
              if ($sqlSelectHost->num_rows > 0) {
                while ($rowSelectedHosts = $sqlSelectHost->fetch_assoc()) {
                  $hostBeID = $rowSelectedHosts["hostbeid"];
                  $sqlHostData = $linkBD->query("SELECT * FROM usersarchive WHERE beid='$hostBeID' LIMIT 1");
                  if ($sqlSelectHost->num_rows > 0) {
                    $aboutHost = $sqlHostData->fetch_assoc();
                    $hostLang = $aboutHost['language'];
                    $hostEmail = $aboutHost['contactemail'];
                  } else {
                    $hostLang = "-";
                    $hostEmail = "-";
                  }
                  array_push($selectedHosts, $hostBeID);
                  $sqlAboutBooking = $linkBD->query("SELECT * FROM bookingarchive WHERE status='booked' and hostbeid='$hostBeID' and paymentStatus='0' ORDER BY todate DESC");
                  if ($sqlAboutBooking->num_rows > 0) {
                    while ($rowBook = $sqlAboutBooking->fetch_assoc()) {
                      if (checkTimelinessOfBooking($rowBook['beid']) == "past" && $rowBook['tom']."-".$rowBook['toy'] != (int)$dateM."-".$dateY) {
                        $plcBeID = $rowBook['plcbeid'];
                        $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeID' LIMIT 1");
                        if ($sqlPlc->num_rows > 0) {
                          $rowPlc = $sqlPlc->fetch_assoc();
                          $plcID = getFrontendId($plcBeID);
                          $plcName = $rowPlc['name'];
                        } else {
                          $plcID = "-";
                          $plcName = "-";
                        }
                        array_push($bookingsList, [
                          "beId" => $rowBook['beid'],
                          "placeID" => $plcID,
                          "placeName" => $plcName,
                          "paymentStatus" => $rowBook['paymentStatus'],
                          "currency" => $rowBook['currency'],
                          "fee" => $rowBook['fee'],
                          "feeInPercent" => $rowBook['percentagefee'],
                          "fromY" => $rowBook['fromy'],
                          "fromM" => $rowBook['fromm'],
                          "fromD" => $rowBook['fromd'],
                          "toY" => $rowBook['toy'],
                          "toM" => $rowBook['tom'],
                          "toD" => $rowBook['tod']
                        ]);
                      }
                    }
                  }
                  if (sizeof($bookingsList) > 0) {
                    $insertCallSts = false;
                    $sqlCheckCall = $linkBD->query("SELECT * FROM feespaymentcalls WHERE beid='$callBeId'");
                    if ($sqlCheckCall->num_rows == 0) {
                      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
                      $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$callBeId', '$backendIDNum', 'payment-call')";
                      if (mysqli_query($link, $sqlBeID)) {
                        $sqlID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$callBeId', '$callID', '$date', '$dateD', '$dateM', '$dateY')";
                        if (mysqli_query($link, $sqlID)) {
                          $sqlInsertCall = "INSERT INTO feespaymentcalls (beid, hostbeid, calllogbeid, paymentreference, fulldate, datey, datem, dated) VALUES ('$callBeId' ,'$hostBeID', '$logBeId', '$paymentReference', '$date', '$dateY', '$dateM', '$dateD')";
                          if (mysqli_query($linkBD, $sqlInsertCall)) {
                            $insertCallSts = true;
                          } else {
                            $insertCallSts = false;
                          }
                        } else {
                          $insertCallSts = false;
                        }
                      } else {
                        $insertCallSts = false;
                      }
                    } else {
                      $insertCallSts = true;
                    }
                    if ($insertCallSts) {
                      $mailListOfBookings = [];
                      for ($bL=0; $bL < sizeof($bookingsList); $bL++) {
                        $bookingBeID = $bookingsList[$bL]['beId'];
                        $sqlInsertCallKey = "INSERT INTO feespaymentcallskey (beid, bookingbeid) VALUES ('$callBeId' ,'$bookingBeID')";
                        if (mysqli_query($linkBD, $sqlInsertCallKey)) {
                          array_push($mailListOfBookings, [
                            "beId" => $bookingsList[$bL]['beId'],
                            "placeID" => $bookingsList[$bL]['placeID'],
                            "placeName" => $bookingsList[$bL]['placeName'],
                            "paymentStatus" => $bookingsList[$bL]['paymentStatus'],
                            "currency" => $bookingsList[$bL]['currency'],
                            "fee" => $bookingsList[$bL]['fee'],
                            "feeInPercent" => $bookingsList[$bL]['feeInPercent'],
                            "fromY" => $bookingsList[$bL]['fromY'],
                            "fromM" => $bookingsList[$bL]['fromM'],
                            "fromD" => $bookingsList[$bL]['fromD'],
                            "toY" => $bookingsList[$bL]['toY'],
                            "toM" => $bookingsList[$bL]['toM'],
                            "toD" => $bookingsList[$bL]['toD']
                          ]);
                          $totalFee = $totalFee + $bookingsList[$bL]['fee'];
                          $totalCurrency = $bookingsList[$bL]['currency'];
                        }
                      }
                      if ($hostEmail != "-" && $totalFee > 0) {
                        callForFeesPaymentMail(
                          $hostLang,
                          $hostEmail,
                          $mailListOfBookings,
                          $totalFee,
                          $totalCurrency,
                          $paymentReference,
                          getBackDoorSettingsValue("details-for-the-payment-of-fees-iban"),
                          getBackDoorSettingsValue("details-for-the-payment-of-fees-bank-account"),
                          getBackDoorSettingsValue("details-for-the-payment-of-fees-bic-swift")
                        );
                        setPaymentCallSts($logBeId, "done");
                      } else {
                        setPaymentCallSts($logBeId, "failed");
                      }
                    } else {
                      setPaymentCallSts($logBeId, "failed");
                    }
                  }
                }
              } else {
                $allHostsDone = true;
              }
            }
          }
        }
      }
    }
  }

  function mailDone($sts, $mailType) {

  }

  function setPaymentCallSts($logBeId, $sts) {
    global $linkBD;
    $sqlUpdt = "UPDATE callforfeespaymentchecklog SET status='$sts' WHERE beid='$logBeId'";
    mysqli_query($linkBD, $sqlUpdt);
  }
?>
