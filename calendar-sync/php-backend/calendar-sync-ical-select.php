<?php
  include "date-verification.php";
  include "calendar-sync-add-booking.php";
  include "calendar-sync-old-import-booking-delete.php";
  function calendarSyncICalSelect() {
    global $link, $linkBD;
    $date = date("Y-m-d H:i:s");
    $sqlSelectCall = $linkBD->query("SELECT * FROM calendarsynccalls WHERE status='ready'");
    if ($sqlSelectCall->num_rows > 0) {
      while($rowSelectedCall = $sqlSelectCall->fetch_assoc()) {
        $callBeID = $rowSelectedCall['beid'];
        $sqlCallInProgressStsUpdate = "UPDATE calendarsynccalls SET status='in-progress' WHERE beid='$callBeID'";
        if (mysqli_query($linkBD, $sqlCallInProgressStsUpdate)) {
          $sqlSelectKey = $linkBD->query("SELECT * FROM calendarsynckey WHERE beid='$callBeID' and status='in-order'");
          if ($sqlSelectKey->num_rows > 0) {
            while($rowSelectedKey = $sqlSelectKey->fetch_assoc()) {
              $plcBeID = $rowSelectedKey['plcbeid'];
              $sourceCode = $rowSelectedKey['sourcecode'];
              $url = $rowSelectedKey['url'];
              $sqlKeyInProgressStsUpdate = "UPDATE calendarsynckey SET status='in-progress' WHERE beid='$callBeID' and plcbeid='$plcBeID' and sourcecode='$sourceCode' and url='$url'";
              if (mysqli_query($linkBD, $sqlKeyInProgressStsUpdate)) {
                calendarSyncICalLoadData($callBeID, $plcBeID, $sourceCode, $url);
              } else {
                errorKey($callBeID, $plcBeID, $sourceCode, $url, "Failed to update key status to 'in-progress' (SQL error: ".mysqli_error($linkBD).")");
              }
            }
            $deleteAllOldCall = allOldDateImportDelete($callBeID);
            if ($deleteAllOldCall == "done") {
              $importStsUpdate = calendarSyncICalUpdateImportSts($callBeID);
              if ($importStsUpdate == "done") {
                doneCall($callBeID);
              } else {
                errorCall($callBeID, "The process of updating statuses of imported bookings failed:<br>".$deleteAllOldCall);
              }
            } else {
              errorCall($callBeID, "The process of deleting data from a previous import failed:<br>".$deleteAllOldCall);
            }
          } else {
            errorCall($callBeID, "No matching data found in calendarsynckey database (SQL error: ".mysqli_error($linkBD).")");
          }
        }
      }
    }
  }

  function calendarSyncICalLoadData($callBeID, $plcBeID, $sourceCode, $url) {
    global $link, $linkBD;
    if (@file($url)) {
      $ical = new ical($url);
      $icalArr = $ical->events();
      for ($c=0; $c < sizeof($icalArr); $c++) {
        $uID = $icalArr[$c]['UID'];
        $dtStart = $icalArr[$c]['DTSTART'];
        $dtEnd = $icalArr[$c]['DTEND'];
        $dSummary = $icalArr[$c]['SUMMARY'];
        calendarSyncICalImportData($callBeID, $plcBeID, $sourceCode, $uID, $dtStart, $dtEnd, $dSummary, $url);
      }
    } else {
      errorKey($callBeID, $plcBeID, $sourceCode, $url, "The link cannot be read");
    }
  }

  function calendarSyncICalImportData($callBeID, $plcBeID, $sourceCode, $uID, $dtStart, $dtEnd, $dSummary, $url) {
    $newDates = calendarSyncICalAvailabilityCheck($sourceCode, $callBeID, $plcBeID, $dtStart, $dtEnd);
    $indexSts = true;
    $iS = 0;
    $dateNotes = "";
    $processDoneSts = true;
    $calSyncICalAddOutput = "";
    $calSyncICalAddErrors = "";
    do {
      if ($iS >= sizeof($newDates) -1) {
        $indexSts = false;
      } else {
        if ($newDates[$iS]["type"] == "note") {
          $dateNotes = $newDates[$iS]["note"];
          $indexSts = false;
        } else {
          ++$iS;
        }
      }
    } while ($indexSts);
    for ($d=0; $d < sizeof($newDates); $d++) {
      if ($newDates[$d]["type"] == "date") {
        $calSyncICalAddOutput = calendarSyncICalAddBooking(
          $plcBeID,
          $callBeID,
          $sourceCode,
          $uID,
          $newDates[$d]["from-y"],
          $newDates[$d]["from-m"],
          $newDates[$d]["from-d"],
          $newDates[$d]["first-day"],
          $newDates[$d]["to-y"],
          $newDates[$d]["to-m"],
          $newDates[$d]["to-d"],
          $newDates[$d]["last-day"],
          $dateNotes
        );
        if ($calSyncICalAddOutput != "done") {
          if ($calSyncICalAddErrors == "") {
            $calSyncICalAddErrors = $calSyncICalAddOutput;
          } else {
            $calSyncICalAddErrors = $calSyncICalAddErrors."<br>".$calSyncICalAddOutput;
          }
        }
      } else if ($newDates[$d]["type"] == "error") {
        $processDoneSts = false;
        errorKey($callBeID, $plcBeID, $sourceCode, $url, $newDates[$d]["msg"]);
      }
    }
    if ($calSyncICalAddErrors != "") {
      $processDoneSts = false;
      errorKey($callBeID, $plcBeID, $sourceCode, $url, $calSyncICalAddErrors);
    }
    if ($processDoneSts) {
      $updateStsToDone = doneKey($callBeID, $plcBeID, $sourceCode, $url);
      if ($updateStsToDone != "done") {
        errorKey($callBeID, $plcBeID, $sourceCode, $url, $updateStsToDone);
      }
    }
  }

  function calendarSyncICalAvailabilityCheck($sourceCode, $callBeID, $plcBeID, $dtStart, $dtEnd) {
    $c_fd = calendarSyncICalTimeStampConvert($dtStart, "d");
    $c_fm = calendarSyncICalTimeStampConvert($dtStart, "m");
    $c_fy = calendarSyncICalTimeStampConvert($dtStart, "y");
    $c_fh = calendarSyncICalTimeStampConvert($dtStart, "h");
    $c_td = calendarSyncICalTimeStampConvert($dtEnd, "d");
    $c_tm = calendarSyncICalTimeStampConvert($dtEnd, "m");
    $c_ty = calendarSyncICalTimeStampConvert($dtEnd, "y");
    $c_th = calendarSyncICalTimeStampConvert($dtEnd, "h");
    if ($c_fh > 12) {
      $c_ffday = "half";
    } else {
      $c_ffday = "whole";
    }
    if ($c_fh > 11) {
      $c_flday = "whole";
    } else {
      $c_flday = "half";
    }
    $availabilityCheck = dateVerification($callBeID, $plcBeID, $c_fy, $c_fm, $c_fd, $c_ffday, $c_ty, $c_tm, $c_td, $c_flday);
    $a = 0;
    $datesNotChanged = false;
    $updateNotes = "";
    while ($a < sizeof($availabilityCheck) && !$datesNotChanged) {
      if ($availabilityCheck[$a]['type'] == "date") {
        if (
          $c_fy == $availabilityCheck[$a]['from-y'] &&
          $c_fm == $availabilityCheck[$a]['from-m'] &&
          $c_fd == $availabilityCheck[$a]['from-d'] &&
          $c_ffday == $availabilityCheck[$a]['first-day'] &&
          $c_ty == $availabilityCheck[$a]['to-y'] &&
          $c_tm == $availabilityCheck[$a]['to-m'] &&
          $c_td == $availabilityCheck[$a]['to-d'] &&
          $c_flday == $availabilityCheck[$a]['last-day']
        ) {
          $datesNotChanged = true;
        } else {
          if ($c_ffday == "whole") {
            $note_ffday = "Whole day";
          } else {
            $note_ffday = "From 14:00";
          }
          if ($c_flday == "whole") {
            $note_flday = "Whole day";
          } else {
            $note_flday = "To 11:00";
          }
          if ($updateNotes != "") {
            $updateNotes = $updateNotes.", ".$c_fd.". ".$c_fm.". ".$c_fy." (".$note_ffday.") - ".$c_td.". ".$c_tm.". ".$c_ty." (".$note_flday.")";
          } else {
            $updateNotes = $c_fd.". ".$c_fm.". ".$c_fy." (".$note_ffday.") - ".$c_td.". ".$c_tm.". ".$c_ty." (".$note_flday.")";
          }
        }
      }
    }
    array_push($availabilityCheck, [
      "type" => "note",
      "note" => $updateNotes
    ]);
    return $availabilityCheck;
  }

  function calendarSyncICalTimeStampConvert($timeStamp, $get) {
    if ($get == "d") {
      return date('d', $timeStamp);
    } else if ($get == "m") {
      return date('m', $timeStamp);
    } else if ($get == "y") {
      return date('Y', $timeStamp);
    } else if ($get == "h") {
      return date('H', $timeStamp);
    } else if ($get == "min") {
      return date('i', $timeStamp);
    } else if ($get == "s") {
      return date('s', $timeStamp);
    }
  }

  function calendarSyncICalUpdateImportSts($callBeID) {
    global $link;
    $importStsUpdateErrors = "";
    $sqlIcalLog = $link->query("SELECT * FROM icalimportlog WHERE callbeid='$callBeID'");
    if ($sqlIcalLog->num_rows > 0) {
      while($rowICalLog = $sqlIcalLog->fetch_assoc()) {
        $bookingBeID = $rowICalLog['bookingbeid'];
        $sqlUpdateBookingSts = "UPDATE booking SET status='booked' WHERE beid='$bookingBeID'";
        if (!mysqli_query($link, $sqlUpdateBookingSts)) {
          if ($importStsUpdateErrors == "") {
            $importStsUpdateErrors = "Failed to update status from import to booked in booking database (SQL error: ".mysqli_error($linkBD).")";
          } else {
            $importStsUpdateErrors = $importStsUpdateErrors."<br>"."Failed to update status from import to booked in booking database (SQL error: ".mysqli_error($linkBD).")";
          }
        }
      }
    }
    if ($importStsUpdateErrors == "") {
      return "done";
    } else {
      return $importStsUpdateErrors;
    }
  }
?>
