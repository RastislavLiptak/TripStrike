<?php
  $listOfContentToChange = [];
  $listOfBookingsToConnect = [];
  $listOfTechnicalShutdownsToConnect = [];
  $e_fD = "";
  $e_fM = "";
  $e_fY = "";
  $e_tD = "";
  $e_tM = "";
  $e_tY = "";
  $e_firstDay = "";
  $e_lastDay = "";
  $totalNumOfChanges = 0;
  $changesDone = 0;
  $changesfailed = 0;
  $secondaryErrorsString = "";
  $contentChangesCheckReady = true;
  function calendarChangesManager($editTasksOutputArray, $f_d, $f_m, $f_y, $t_d, $t_m, $t_y, $firstDay, $lastDay) {
    global $link, $listOfContentToChange, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay;
    $e_fD = $f_d;
    $e_fM = $f_m;
    $e_fY = $f_y;
    $e_tD = $t_d;
    $e_tM = $t_m;
    $e_tY = $t_y;
    $e_firstDay = $firstDay;
    $e_lastDay = $lastDay;
    for ($dC = 0;$dC < sizeof($editTasksOutputArray);$dC++) {
      if ($editTasksOutputArray[$dC]['type'] == "booking") {
        $bookingBeId = $editTasksOutputArray[$dC]['bookingBeID'];
        $sqlAboutBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
        $aboutBooking = $sqlAboutBooking->fetch_assoc();
        $bookingPlcBeID = $aboutBooking['plcbeid'];
        $sqlPlaceHostBeId = $link->query("SELECT usrbeid FROM places WHERE beid='$bookingPlcBeID' LIMIT 1");
        $plcHostBeID = $sqlPlaceHostBeId->fetch_assoc()['usrbeid'];
        $sqlHostData = $link->query("SELECT * FROM users WHERE beid='$plcHostBeID' LIMIT 1");
        $aboutHost = $sqlHostData->fetch_assoc();
        array_push($listOfContentToChange, [
          "beID" => $bookingBeId,
          "type" => $editTasksOutputArray[$dC]['type'],
          "task" => $editTasksOutputArray[$dC]['task'],
          "plcBeID" => $bookingPlcBeID,
          "from_d" => $aboutBooking['fromd'],
          "from_m" => $aboutBooking['fromm'],
          "from_y" => $aboutBooking['fromy'],
          "to_d" => $aboutBooking['tod'],
          "to_m" => $aboutBooking['tom'],
          "to_y" => $aboutBooking['toy'],
          "firstday" => $aboutBooking['firstday'],
          "lastday" => $aboutBooking['lastday'],
          "host_name" => $aboutHost['firstname']." ".$aboutHost['lastname'],
          "host_email" => $aboutHost['contactemail'],
          "host_phone" => $aboutHost['contactphonenum'],
        ]);
      } else {
        $technicalShutdownBeId = $editTasksOutputArray[$dC]['technicalShutdownBeID'];
        $sqlAboutTechnicalShutdown = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
        $aboutTechnicalShutdown = $sqlAboutTechnicalShutdown->fetch_assoc();
        array_push($listOfContentToChange, [
          "beID" => $technicalShutdownBeId,
          "type" => $editTasksOutputArray[$dC]['type'],
          "task" => $editTasksOutputArray[$dC]['task'],
          "plcBeID" => $aboutTechnicalShutdown['plcbeid'],
          "from_d" => $aboutTechnicalShutdown['fromd'],
          "from_m" => $aboutTechnicalShutdown['fromm'],
          "from_y" => $aboutTechnicalShutdown['fromy'],
          "to_d" => $aboutTechnicalShutdown['tod'],
          "to_m" => $aboutTechnicalShutdown['tom'],
          "to_y" => $aboutTechnicalShutdown['toy'],
          "firstday" => $aboutTechnicalShutdown['firstday'],
          "lastday" => $aboutTechnicalShutdown['lastday']
        ]);
      }
    }
    changesHandler();
  }

  function changesHandler() {
    global $listOfContentToChange, $listOfBookingsToConnect, $listOfTechnicalShutdownsToConnect, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay, $totalNumOfChanges;
    $totalNumOfChanges = sizeof($listOfContentToChange);
    for ($lC = 0;$lC < sizeof($listOfContentToChange);$lC++) {
      if ($listOfContentToChange[$lC]["task"] == "delete") {
        if ($listOfContentToChange[$lC]["type"] == "booking") {
          cancelBooking($listOfContentToChange[$lC]['plcBeID'], $listOfContentToChange[$lC]['from_d'], $listOfContentToChange[$lC]['from_m'], $listOfContentToChange[$lC]['from_y'], $listOfContentToChange[$lC]['to_d'], $listOfContentToChange[$lC]['to_m'], $listOfContentToChange[$lC]['to_y'], $listOfContentToChange[$lC]['host_name'], $listOfContentToChange[$lC]['host_email'], $listOfContentToChange[$lC]['host_phone'], "booking", true);
        } else {
          cancelTechnicalShutdown($listOfContentToChange[$lC]['plcBeID'], $listOfContentToChange[$lC]['from_d'], $listOfContentToChange[$lC]['from_m'], $listOfContentToChange[$lC]['from_y'], $listOfContentToChange[$lC]['to_d'], $listOfContentToChange[$lC]['to_m'], $listOfContentToChange[$lC]['to_y']);
        }
      } else if ($listOfContentToChange[$lC]["task"] == "shorten") {
        shortenContent($listOfContentToChange[$lC]["type"], $listOfContentToChange[$lC]['beID'], $listOfContentToChange[$lC]['plcBeID'], $listOfContentToChange[$lC]['from_d'], $listOfContentToChange[$lC]['from_m'], $listOfContentToChange[$lC]['from_y'], $listOfContentToChange[$lC]['to_d'], $listOfContentToChange[$lC]['to_m'], $listOfContentToChange[$lC]['to_y'], $listOfContentToChange[$lC]['firstday'], $listOfContentToChange[$lC]['lastday'], true);
      } else if ($listOfContentToChange[$lC]["task"] == "connect") {
        if ($listOfContentToChange[$lC]["type"] == "booking") {
          array_push($listOfBookingsToConnect, $listOfContentToChange[$lC]['beID']);
        } else {
          array_push($listOfTechnicalShutdownsToConnect, $listOfContentToChange[$lC]['beID']);
        }
        if ($e_fY."-".$e_fM."-".$e_fD > $listOfContentToChange[$lC]['from_y']."-".$listOfContentToChange[$lC]['from_m']."-".$listOfContentToChange[$lC]['from_d']) {
          $e_fD = $listOfContentToChange[$lC]['from_d'];
          $e_fM = $listOfContentToChange[$lC]['from_m'];
          $e_fY = $listOfContentToChange[$lC]['from_y'];
          $e_firstDay = $listOfContentToChange[$lC]['firstday'];
        } else if ($e_fY."-".$e_fM."-".$e_fD == $listOfContentToChange[$lC]['from_y']."-".$listOfContentToChange[$lC]['from_m']."-".$listOfContentToChange[$lC]['from_d']) {
          if ($listOfContentToChange[$lC]['firstday'] == "whole") {
            $e_firstDay = $listOfContentToChange[$lC]['firstday'];
          }
        } else if ($e_tY."-".$e_tM."-".$e_tD < $listOfContentToChange[$lC]['to_y']."-".$listOfContentToChange[$lC]['to_m']."-".$listOfContentToChange[$lC]['to_d']) {
          $e_tD = $listOfContentToChange[$lC]['to_d'];
          $e_tM = $listOfContentToChange[$lC]['to_m'];
          $e_tY = $listOfContentToChange[$lC]['to_y'];
          $e_lastDay = $listOfContentToChange[$lC]['lastday'];
        } else if ($e_tY."-".$e_tM."-".$e_tD < $listOfContentToChange[$lC]['to_y']."-".$listOfContentToChange[$lC]['to_m']."-".$listOfContentToChange[$lC]['to_d']) {
          if ($listOfContentToChange[$lC]['lastday'] == "whole") {
            $e_lastDay = $listOfContentToChange[$lC]['lastday'];
          }
        }
        anotherChangeDone();
      } else if ($listOfContentToChange[$lC]["task"] == "split") {
        ++$totalNumOfChanges;
        prepareSecondPartOfSplitedDate($listOfContentToChange[$lC]['to_d'], $listOfContentToChange[$lC]['to_m'], $listOfContentToChange[$lC]['to_y'], $listOfContentToChange[$lC]['lastday']);
        shortenContent($listOfContentToChange[$lC]["type"], $listOfContentToChange[$lC]['beID'], $listOfContentToChange[$lC]['plcBeID'], $listOfContentToChange[$lC]['from_d'], $listOfContentToChange[$lC]['from_m'], $listOfContentToChange[$lC]['from_y'], $listOfContentToChange[$lC]['to_d'], $listOfContentToChange[$lC]['to_m'], $listOfContentToChange[$lC]['to_y'], $listOfContentToChange[$lC]['firstday'], $listOfContentToChange[$lC]['lastday'], false);
        addSecondPartOfSplitedDate($listOfContentToChange[$lC]["type"], $listOfContentToChange[$lC]['beID']);
      } else if ($listOfContentToChange[$lC]["task"] == "reject") {
        rejectBooking($listOfContentToChange[$lC]['beID'], "../../");
      }
      contentChangesHandler();
    }
  }

  function shortenContent($type, $beID, $plcBeID, $fromD, $fromM, $fromY, $toD, $toM, $toY, $firstD, $lastD, $sendEmail) {
    global $link, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay;
    $termReady = false;
    $affectedDayReady = false;
    $affectedDayFound = false;
    $unaffectedDayReady = false;
    $unaffectedDayFound = false;
    $changeD = $fromD;
    $changeM = $fromM;
    $changeY = $fromY;
    while (!$termReady) {
      if ($changeD."-".$changeM."-".$changeY != calcNextDay($toD, $toM, $toY, 1)."-".calcNextMonthByDays($toD, $toM, $toY, 1)."-".calcNextYearByDays($toD, $toM, $toY, 1)) {
        if (
          $changeY."-".$changeM."-".$changeD > $e_fY."-".$e_fM."-".$e_fD &&
          $changeY."-".$changeM."-".$changeD < $e_fY."-".$e_fM."-".$e_fD
        ) {
          $affectedDayReady = false;
          $affectedDayFound = true;
          $unaffectedDayReady = false;
        } else if ($changeY == $e_fY && $changeM == $e_fM && $changeD == $e_fD) {
          if ($e_firstDay == "half") {
            if ($affectedDayReady) {
              $affectedDayReady = false;
              $affectedDayFound = true;
              $unaffectedDayReady = false;
              $toD = calcPreviousDay($changeD, $changeM, $changeY, 1);
              $toM = calcPreviousMonthByDays($changeD, $changeM, $changeY, 1);
              $toY = calcPreviousYearByDays($changeD, $changeM, $changeY, 1);
              $lastD = "whole";
            } else {
              $affectedDayReady = true;
              if ($unaffectedDayReady) {
                $unaffectedDayFound = true;
                $toD = $changeD;
                $toM = $changeM;
                $toY = $changeY;
                $lastD = "half";
              } else {
                $unaffectedDayReady = true;
              }
            }
          } else {
            $affectedDayReady = false;
            $affectedDayFound = true;
            $unaffectedDayReady = false;
            $toD = calcPreviousDay($changeD, $changeM, $changeY, 1);
            $toM = calcPreviousMonthByDays($changeD, $changeM, $changeY, 1);
            $toY = calcPreviousYearByDays($changeD, $changeM, $changeY, 1);
            $lastD = "whole";
          }
          $termReady = true;
        } else if ($changeY == $e_tY && $changeM == $e_tM && $changeD == $e_tD) {
          if ($e_lastDay == "half") {
            if ($affectedDayReady) {
              $affectedDayReady = false;
              $affectedDayFound = true;
              $unaffectedDayReady = false;
              $fromD = calcNextDay($changeD, $changeM, $changeY, 1);
              $fromM = calcNextMonthByDays($changeD, $changeM, $changeY, 1);
              $fromY = calcNextYearByDays($changeD, $changeM, $changeY, 1);
              $firstD = "whole";
            } else {
              $affectedDayReady = true;
              $unaffectedDayFound = true;
              $fromD = $changeD;
              $fromM = $changeM;
              $fromY = $changeY;
              $firstD = "half";
            }
          } else {
            $affectedDayReady = false;
            $affectedDayFound = true;
            $unaffectedDayReady = false;
            $fromD = calcNextDay($changeD, $changeM, $changeY, 1);
            $fromM = calcNextMonthByDays($changeD, $changeM, $changeY, 1);
            $fromY = calcNextYearByDays($changeD, $changeM, $changeY, 1);
            $firstD = "whole";
          }
          $termReady = true;
        } else {
          $affectedDayReady = false;
          if ($unaffectedDayReady) {
            $unaffectedDayFound = true;
          } else {
            $unaffectedDayReady = true;
          }
        }
      } else {
        $termReady = true;
      }
      ++$changeD;
      if ($changeD > cal_days_in_month(CAL_GREGORIAN, $changeM, $changeY)) {
        $changeD = 1;
        ++$changeM;
        if ($changeM > 12) {
          $changeM = 1;
          ++$changeY;
        }
      }
    }
    if ($type == "booking") {
      $sqlBookingData = $link->query("SELECT * FROM booking WHERE beid='$beID' LIMIT 1");
      $bookingData = $sqlBookingData->fetch_assoc();
      updateBooking($beID, $plcBeID, $bookingData['name'], $bookingData['email'], $bookingData['phonenum'], $bookingData['guestnum'], $bookingData['notes'], $fromD, $fromM, $fromY, $firstD, $toD, $toM, $toY, $lastD, $bookingData['deposit'], $bookingData['fullAmount'], $sendEmail);
    } else {
      $sqlTechnicalShutdownData = $link->query("SELECT * FROM technicalshutdown WHERE beid='$beID' LIMIT 1");
      $technicalShutdownData = $sqlTechnicalShutdownData->fetch_assoc();
      updateTechnicalShutdown($beID, $plcBeID, $technicalShutdownData['category'], $technicalShutdownData['notes'], $fromD, $fromM, $fromY, $firstD, $toD, $toM, $toY, $lastD);
    }
  }

  $sec_split_f_y = "";
  $sec_split_f_m = "";
  $sec_split_f_d = "";
  $sec_split_firstDay = "";
  $sec_split_t_y = "";
  $sec_split_t_m = "";
  $sec_split_t_d = "";
  $sec_split_lastDay = "";
  function prepareSecondPartOfSplitedDate($toD, $toM, $toY, $lastD) {
    global $link, $e_tD, $e_tM, $e_tY, $e_lastDay, $sec_split_f_y, $sec_split_f_m, $sec_split_f_d, $sec_split_firstDay, $sec_split_t_y, $sec_split_t_m, $sec_split_t_d, $sec_split_lastDay;
    if ($e_lastDay == "half") {
      $sec_split_f_y = $e_tY;
      $sec_split_f_m = $e_tM;
      $sec_split_f_d = $e_tD;
    } else {
      $sec_split_f_y = calcNextYearByDays($e_tD, $e_tM, $e_tY, 1);
      $sec_split_f_m = calcNextMonthByDays($e_tD, $e_tM, $e_tY, 1);
      $sec_split_f_d = calcNextDay($e_tD, $e_tM, $e_tY, 1);
    }
    $sec_split_firstDay = $e_lastDay;
    $sec_split_t_y = $toY;
    $sec_split_t_m = $toM;
    $sec_split_t_d = $toD;
    $sec_split_lastDay = $lastD;
  }

  function addSecondPartOfSplitedDate($type, $beID) {
    global $link, $linkBD, $sec_split_f_y, $sec_split_f_m, $sec_split_f_d, $sec_split_firstDay, $sec_split_t_y, $sec_split_t_m, $sec_split_t_d, $sec_split_lastDay;
    if ($type == "booking") {
      $sqlBookingData = $link->query("SELECT * FROM booking WHERE beid='$beID' LIMIT 1");
      $bookingData = $sqlBookingData->fetch_assoc();
      $sqlBookingArchive = $linkBD->query("SELECT source, percentagefee, paymentStatus FROM bookingarchive WHERE beid='$beID' LIMIT 1");
      if ($sqlBookingArchive->num_rows > 0) {
        $splitedBookingRow = $sqlBookingArchive->fetch_assoc();
        $splitedBookingFeePaymentStatus = $splitedBookingRow['paymentStatus'];
        $splitedBookingSource = $splitedBookingRow['source'];
        $splitedBookingFeeInPerc = $splitedBookingRow['percentagefee'];
      } else {
        $splitedBookingFeePaymentStatus = 1;
        $splitedBookingSource = "editor";
        $splitedBookingFeeInPerc = 0;
      }
      addBooking($bookingData['plcbeid'], $bookingData['name'], $bookingData['email'], $bookingData['phonenum'], $bookingData['guestnum'], $bookingData['notes'], $sec_split_f_d, $sec_split_f_m, $sec_split_f_y, $sec_split_firstDay, $sec_split_t_d, $sec_split_t_m, $sec_split_t_y, $sec_split_lastDay, $bookingData['deposit'], $bookingData['fullAmount'], $splitedBookingFeePaymentStatus, $splitedBookingSource, $splitedBookingFeeInPerc, true);
    } else {
      $sqlTechnicalShutdownData = $link->query("SELECT * FROM technicalshutdown WHERE beid='$beID' LIMIT 1");
      $technicalShutdownData = $sqlTechnicalShutdownData->fetch_assoc();
      addTechnicalShutdown($technicalShutdownData['plcbeid'], $technicalShutdownData['category'], $technicalShutdownData['notes'], $sec_split_f_d, $sec_split_f_m, $sec_split_f_y, $sec_split_firstDay, $sec_split_t_d, $sec_split_t_m, $sec_split_t_y, $sec_split_lastDay);
    }
  }

  function anotherChangeDone() {
    global $changesDone;
    ++$changesDone;
  }

  function anotherChangefailed($txt) {
    global $changesfailed, $secondaryErrorsString;
    $secondaryErrorsString = $secondaryErrorsString."".$txt."<br>";
    ++$changesfailed;
  }

  function contentChangesHandler() {
    global $listOfBookingsToConnect, $listOfTechnicalShutdownsToConnect, $secondaryErrorsString, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay, $contentChangesCheckReady;
    if (contentChangesCheck() && $contentChangesCheckReady) {
      $contentChangesCheckReady = false;
      if ($secondaryErrorsString == "") {
        if (sizeof($listOfBookingsToConnect) > 0) {
          connectBooking($listOfBookingsToConnect, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay);
        } else if (sizeof($listOfTechnicalShutdownsToConnect) > 0) {
          connectTechnicalShutdown($listOfTechnicalShutdownsToConnect, $e_fD, $e_fM, $e_fY, $e_tD, $e_tM, $e_tY, $e_firstDay, $e_lastDay);
        } else {
          performMainTask();
        }
      } else {
        error($secondaryErrorsString);
      }
    }
  }

  function contentChangesCheck() {
    global $changesDone, $changesfailed, $totalNumOfChanges;
    if ($changesDone + $changesfailed >= $totalNumOfChanges) {
      return true;
    } else {
      return false;
    }
  }
?>
