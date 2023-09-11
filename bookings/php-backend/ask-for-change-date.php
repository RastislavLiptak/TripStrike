<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../uni/code/php-backend/convert-month-num-to-text.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../uni/code/php-backend/calendar/calc-date.php";
  include "booking-verification.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../editor/php-backend/check-booking-term-availability.php";
  include "../../editor/php-backend/get-booking-edit-task.php";
  include "../../editor/php-backend/get-technical-shutdown-edit-task.php";
  include "../../email-sender/php-backend/ask-for-booking-update-dates-mail.php";
  header('Content-Type: application/json');
  $output = [];
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  $new_total = 0;
  $price_diff = 0;
  $editTasksOutputArray = [];
  $unavailableBecauseOfBookingSchedule = [];
  $unavailableBecauseOfTechnicalShutdownSchedule = [];
  $numOfUnavailable = 0;
  $doneOfUnavailable = 0;
  $getBookingEditTaskErrorTxt = "";
  $getTechnicalShutdownEditTaskErrorTxt = "";
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $fromD = mysqli_real_escape_string($link, $_POST['fromD']);
  $fromM = mysqli_real_escape_string($link, $_POST['fromM']);
  $fromY = mysqli_real_escape_string($link, $_POST['fromY']);
  $toD = mysqli_real_escape_string($link, $_POST['toD']);
  $toM = mysqli_real_escape_string($link, $_POST['toM']);
  $toY = mysqli_real_escape_string($link, $_POST['toY']);
  $firstDay = mysqli_real_escape_string($link, $_POST['firstDay']);
  $lastDay = mysqli_real_escape_string($link, $_POST['lastDay']);
  if ($firstDay != "whole") {
    $firstDay = "half";
  }
  if ($lastDay != "whole") {
    $lastDay = "half";
  }
  $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
  if ($sqlIdToBeId->num_rows > 0) {
    $bookingBeId = $sqlIdToBeId->fetch_assoc()["beid"];
    $bookingVerify = bookingVerification($bookingBeId);
    if ($bookingVerify == "good") {
      $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId'");
      $booking = $sqlBooking->fetch_assoc();
      $plcBeId = $booking['plcbeid'];
      $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId'");
      $plc = $sqlPlc->fetch_assoc();
      $hostBeID = $plc['usrbeid'];
      $sqlHst = $link->query("SELECT * FROM users WHERE beid='$hostBeID' and status='active'");
      $hst = $sqlHst->fetch_assoc();
      $g_name = $booking['name'];
      $g_email = $booking['email'];
      $g_phone = $booking['phonenum'];
      $g_guest = $booking['guestnum'];
      $g_notes = $booking['notes'];
      $deposit = $booking['deposit'];
      $fullAmount = $booking['fullAmount'];
      $operation = $plc['operation'];
      $operationFrom = $plc['operationFrom'];
      $operationTo = $plc['operationTo'];
      if (
        $fromD != "" &&
        $fromM != "" &&
        $fromY != "" &&
        $fromD != null &&
        $fromM != null &&
        $fromY != null
      ) {
        if (
          $toD != "" &&
          $toM != "" &&
          $toY != "" &&
          $toD != null &&
          $toM != null &&
          $toY != null
        ) {
          if (
            is_numeric($fromD) &&
            is_numeric($fromM) &&
            is_numeric($fromY)
          ) {
            if (
              is_numeric($toD) &&
              is_numeric($toM) &&
              is_numeric($toY)
            ) {
              if (new DateTime($fromY."-".$fromM."-".$fromD) < new DateTime($toY."-".$toM."-".$toD)) {
                if (
                  $fromD != $booking['fromd'] ||
                  $fromM != $booking['fromm'] ||
                  $fromY != $booking['fromy'] ||
                  $toD != $booking['tod'] ||
                  $toM != $booking['tom'] ||
                  $toY != $booking['toy'] ||
                  $firstDay != $booking['firstday'] ||
                  $lastDay != $booking['lastday']
                ) {
                  checkBookingTermAvailability($booking['plcbeid'], $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay);
                } else {
                  $sqlBookUpdtReqDelete = "UPDATE bookingupdaterequest SET status='canceled' WHERE bookingbeid='$bookingBeId' and type='date'";
                  if (mysqli_query($link, $sqlBookUpdtReqDelete)) {
                    $sqlBookGuestsReqSelect = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='guests'");
                    if ($sqlBookGuestsReqSelect->num_rows > 0) {
                      $gnReqRow = $sqlBookGuestsReqSelect->fetch_assoc();
                      $new_total = totalPriceCalc(
                        $plcBeId,
                        $gnReqRow['guestNum'],
                        $fromY,
                        $fromM,
                        $fromD,
                        $firstDay,
                        $toY,
                        $toM,
                        $toD,
                        $lastDay
                      );
                    } else {
                      $new_total = $booking['totalprice'];;
                    }
                    if ($new_total < $booking['totalprice']) {
                      $price_diff = $booking['totalprice'] - $new_total;
                      $price_diff = "-".$price_diff;
                    } else if ($new_total > $booking['totalprice']) {
                      $price_diff = $new_total - $booking['totalprice'];
                      $price_diff = "+".$price_diff;
                    } else {
                      $price_diff = 0;
                    }
                    $fromY = "org";
                    $fromM = "org";
                    $fromD = "org";
                    $firstDay = "org";
                    $toY = "org";
                    $toM = "org";
                    $toD = "org";
                    $lastDay = "org";
                    done();
                  } else {
                    error("Delete original request in the database failed<br>".mysqli_error($link));
                  }
                }
              } else {
                error("The dates are not in the correct order");
              }
            } else {
              error("To date has not a right format");
            }
          } else {
            error("From date has not a right format");
          }
        } else {
          error("'To' date is not filled in");
        }
      } else {
        error("'From' date is not filled in");
      }
    } else {
      error("Booking verification error: ".$bookingVerify);
    }
  } else {
    error("ID not connected to any backend ID");
  }

  function checkBookingTermAvailabilityOutput($sts, $txt, $type) {
    global $bookingBeId, $unavailableBecauseOfBookingSchedule, $unavailableBecauseOfTechnicalShutdownSchedule, $numOfUnavailable, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $deposit, $fullAmount;
    if ($sts == "available") {
      requestForChangeOfDate();
    } elseif ($sts == "unavailable") {
      if ($type == "booking") {
        if ($bookingBeId != $txt && !in_array($txt, $unavailableBecauseOfBookingSchedule)) {
          array_push($unavailableBecauseOfBookingSchedule, $txt);
        }
      } elseif ($type == "technical-shutdown") {
        if (!in_array($txt, $unavailableBecauseOfTechnicalShutdownSchedule)) {
          array_push($unavailableBecauseOfTechnicalShutdownSchedule, $txt);
        }
      }
    } elseif ($sts == "loop-unavailable") {
      $numOfUnavailable = sizeof($unavailableBecauseOfBookingSchedule) + sizeof($unavailableBecauseOfTechnicalShutdownSchedule);
      if ($numOfUnavailable > 0) {
        foreach ($unavailableBecauseOfBookingSchedule as $blockingBooking) {
          getBookingEditTask($blockingBooking, "booking", $g_name, $g_email, $g_phone, $g_guest, $g_notes, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $deposit, $fullAmount);
        }
        foreach ($unavailableBecauseOfTechnicalShutdownSchedule as $blockingTechnicalShutdown) {
          getTechnicalShutdownEditTask($blockingTechnicalShutdown, "booking", "", "", $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay);
        }
      } else {
        checkBookingTermAvailabilityOutput("available", "good", $type);
      }
    } else {
      error($txt);
    }
  }

  function getBookingEditTaskOutput($type, $txt, $tsk) {
    global $link, $getBookingEditTaskErrorTxt, $getTechnicalShutdownEditTaskErrorTxt, $numOfUnavailable, $doneOfUnavailable, $editTasksOutputArray;
    ++$doneOfUnavailable;
    if ($type == "task") {
      $sqlBlockBooking = $link->query("SELECT * FROM booking WHERE beid='$txt'");
      $bBookingRow = $sqlBlockBooking->fetch_assoc();
      array_push($editTasksOutputArray, [
        "type" => "booking",
        "bookingBeID" => $txt,
        "task" => $tsk,
        "fromD" => $bBookingRow['fromd'],
        "fromM" => $bBookingRow['fromm'],
        "fromY" => $bBookingRow['fromy'],
        "firstDay" => $bBookingRow['firstday'],
        "toD" => $bBookingRow['tod'],
        "toM" => $bBookingRow['tom'],
        "toY" => $bBookingRow['toy'],
        "lastDay" => $bBookingRow['lastday']
      ]);
    } else {
      $getBookingEditTaskErrorTxt = $txt."<br>";
    }
    if ($doneOfUnavailable == $numOfUnavailable) {
      if ($getBookingEditTaskErrorTxt == "" && $getTechnicalShutdownEditTaskErrorTxt == "") {
        requestForChangeOfDate();
      } else {
        if ($getBookingEditTaskErrorTxt != "" && $getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt."<br>".$getTechnicalShutdownEditTaskErrorTxt);
        } else if ($getBookingEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt);
        } else if ($getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getTechnicalShutdownEditTaskErrorTxt);
        }
      }
    }
  }

  function getTechnicalShutdownEditTaskOutput($type, $txt, $tsk) {
    global $link, $getBookingEditTaskErrorTxt, $getTechnicalShutdownEditTaskErrorTxt, $numOfUnavailable, $doneOfUnavailable, $editTasksOutputArray;
    ++$doneOfUnavailable;
    if ($type == "task") {
      $sqlBlockTechShutdown = $link->query("SELECT * FROM technicalshutdown WHERE beid='$txt'");
      $bTechShutdownRow = $sqlBlockTechShutdown->fetch_assoc();
      array_push($editTasksOutputArray, [
        "type" => "technical-shutdown",
        "technicalShutdownBeID" => $txt,
        "task" => $tsk,
        "fromD" => $bTechShutdownRow['fromd'],
        "fromM" => $bTechShutdownRow['fromm'],
        "fromY" => $bTechShutdownRow['fromy'],
        "firstDay" => $bTechShutdownRow['firstday'],
        "toD" => $bTechShutdownRow['tod'],
        "toM" => $bTechShutdownRow['tom'],
        "toY" => $bTechShutdownRow['toy'],
        "lastDay" => $bTechShutdownRow['lastday']
      ]);
    } else {
      $getTechnicalShutdownEditTaskErrorTxt = $txt."<br>";
    }
    if ($doneOfUnavailable == $numOfUnavailable) {
      if ($getBookingEditTaskErrorTxt == "" && $getTechnicalShutdownEditTaskErrorTxt == "") {
        requestForChangeOfDate();
      } else {
        if ($getBookingEditTaskErrorTxt != "" && $getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt."<br>".$getTechnicalShutdownEditTaskErrorTxt);
        } else if ($getBookingEditTaskErrorTxt != "") {
          error($getBookingEditTaskErrorTxt);
        } else if ($getTechnicalShutdownEditTaskErrorTxt != "") {
          error($getTechnicalShutdownEditTaskErrorTxt);
        }
      }
    }
  }

  function requestForChangeOfDate() {
    global $link, $url_id, $bookingBeId, $editTasksOutputArray, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $date, $dateY, $dateM, $dateD, $operation, $operationFrom, $operationTo, $booking, $plc, $plcBeId, $hst, $new_total, $price_diff;
    $fromDate = $fromY."-".$fromM."-".$fromD;
    $toDate = $toY."-".$toM."-".$toD;
    $saveDone = false;
    $sendMail = false;
    $sqlBookUpdtReqSelect = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='date'");
    if ($sqlBookUpdtReqSelect->num_rows > 0) {
      $reqRow = $sqlBookUpdtReqSelect->fetch_assoc();
      if (
        $fromD != $reqRow['fromd'] ||
        $fromM != $reqRow['fromm'] ||
        $fromY != $reqRow['fromy'] ||
        $toD != $reqRow['tod'] ||
        $toM != $reqRow['tom'] ||
        $toY != $reqRow['toy'] ||
        $firstDay != $reqRow['firstday'] ||
        $lastDay != $reqRow['lastday']
      ) {
        $reqBeId = $reqRow['beid'];
        $sqlBookUpdtReqUpdate = "UPDATE bookingupdaterequest SET fromdate='$fromDate', fromy='$fromY', fromm='$fromM', fromd='$fromD', firstday='$firstDay', todate='$toDate', toy='$toY', tom='$toM', tod='$toD', lastday='$lastDay', fulldate='$date', datey='$dateY', datem='$dateM', dated='$dateD' WHERE beid='$reqBeId'";
        if (mysqli_query($link, $sqlBookUpdtReqUpdate)) {
          $saveDone = true;
          $sendMail = true;
        } else {
          error("Update request in the database failed<br>".mysqli_error($link));
        }
      } else {
        $saveDone = true;
      }
    } else {
      $beIdReady = false;
      while (!$beIdReady) {
        $reqBeId = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$reqBeId'")->num_rows == 0) {
          $beIdReady = true;
        } else {
          $beIdReady = false;
        }
      }
      $idReady = false;
      while (!$idReady) {
        $reqId = randomHash(11);
        if ($link->query("SELECT * FROM idlist WHERE id='$reqId'")->num_rows == 0) {
          $idReady = true;
        } else {
          $idReady = false;
        }
      }
      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
      $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$reqBeId', '$backendIDNum', 'change-request')";
      if (mysqli_query($link, $sqlBeID)) {
        $sqlID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$reqBeId', '$reqId', '$date', '$dateD', '$dateM', '$dateY')";
        if (mysqli_query($link, $sqlID)) {
          $sqlBookUpdtReqInsert = "INSERT INTO bookingupdaterequest (beid, bookingbeid, status, type, guestNum, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES
          ('$reqBeId', '$bookingBeId', 'ready', 'date', '0', '$fromDate', '$fromY', '$fromM', '$fromD', '$firstDay', '$toDate', '$toY', '$toM', '$toD', '$lastDay', '$date', '$dateY', '$dateM', '$dateD')";
          if (mysqli_query($link, $sqlBookUpdtReqInsert)) {
            $saveDone = true;
            $sendMail = true;
          } else {
            error("Insert request to the database failed<br>".mysqli_error($link));
          }
        } else {
          error("Saving request ID failed<br>".mysqli_error($link));
        }
      } else {
        error("Saving request backend ID failed<br>".mysqli_error($link));
      }
    }
    if ($saveDone) {
      $inOperation = true;
      $sqlBookGuestsReqSelect = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='guests'");
      if ($sqlBookGuestsReqSelect->num_rows > 0) {
        $gnReqRow = $sqlBookGuestsReqSelect->fetch_assoc();
        $new_total = totalPriceCalc(
          $plcBeId,
          $gnReqRow['guestNum'],
          $fromY,
          $fromM,
          $fromD,
          $firstDay,
          $toY,
          $toM,
          $toD,
          $lastDay
        );
      } else {
        $new_total = totalPriceCalc(
          $plcBeId,
          $booking['guestnum'],
          $fromY,
          $fromM,
          $fromD,
          $firstDay,
          $toY,
          $toM,
          $toD,
          $lastDay
        );
      }
      if ($new_total < $booking['totalprice']) {
        $price_diff = $booking['totalprice'] - $new_total;
        $price_diff = "-".$price_diff;
      } else if ($new_total > $booking['totalprice']) {
        $price_diff = $new_total - $booking['totalprice'];
        $price_diff = "+".$price_diff;
      } else {
        $price_diff = 0;
      }
      if ($sendMail) {
        if ($operation == "summer") {
          if ($fromM < $operationFrom || $fromM > $operationTo || $toM < $operationFrom || $toM > $operationTo) {
            $inOperation = false;
          }
        } else if ($operation == "winter") {
          $winterOperationMonthsList = [];
          $winterUnavailableReady = false;
          while (!$winterUnavailableReady) {
            array_push($winterOperationMonthsList, $operationFrom);
            if ($operationFrom == 4) {
              $operationFrom = 9;
            } else if ($operationFrom == 12) {
              $operationFrom = 1;
            } else {
              ++$operationFrom;
            }
            if ($operationFrom == $operationTo) {
              $winterUnavailableReady = true;
            }
          }
          if (!in_array($fromM, $winterOperationMonthsList)) {
            $inOperation = false;
          }
        }
        askForBookingUpdateDatesMail(
          $hst['language'],
          $hst['contactemail'],
          getFrontendId($plcBeId),
          $plc['name'],
          $booking['fromd'],
          $booking['fromm'],
          $booking['fromy'],
          $booking['firstday'],
          $booking['tod'],
          $booking['tom'],
          $booking['toy'],
          $booking['lastday'],
          $fromD,
          $fromM,
          $fromY,
          $firstDay,
          $toD,
          $toM,
          $toY,
          $lastDay,
          $booking['guestnum'],
          $new_total,
          $price_diff,
          $booking['totalcurrency'],
          $url_id,
          $booking['name'],
          $booking['email'],
          $booking['phonenum'],
          $inOperation,
          $operationFrom,
          $operationTo,
          $editTasksOutputArray
        );
      } else {
        done();
      }
    }
  }

  function mailDone($sts, $mailType) {
    if ($sts == "done") {
      done();
    } else {
      error("New data was saved, but contacting the host failed. Please inform him/her about the changes in person via email or contact phone");
    }
  }

  function done() {
    global $output, $booking, $new_total, $price_diff, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay;
    $bookingLangShrt = $booking['language'];
    include "../../uni/dictionary/langs/".$bookingLangShrt.".php";
    if ($firstDay == "whole") {
      $firstDayTxt = $wrd_theWholeDay;
    } else {
      $firstDayTxt = $wrd_from." 14:00";
    }
    if ($lastDay == "whole") {
      $lastDayTxt = $wrd_theWholeDay;
    } else {
      $lastDayTxt = $wrd_to." 11:00";
    }
    array_push($output, [
      "type" => "done",
      "fromD" => $fromD,
      "fromM" => $fromM,
      "fromY" => $fromY,
      "firstDaySts" => $firstDay,
      "firstDayTxt" => $firstDayTxt,
      "toD" => $toD,
      "toM" => $toM,
      "toY" => $toY,
      "lastDaySts" => $lastDay,
      "lastDayTxt" => $lastDayTxt,
      "currency" => $booking['totalcurrency'],
      "newTotal" => $new_total,
      "priceDiff" => $price_diff
    ]);
    returnOutput();
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
