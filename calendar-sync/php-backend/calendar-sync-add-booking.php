<?php
  function calendarSyncICalAddBooking($plcBeID, $callBeID, $sourceCode, $uID, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay, $note) {
    global $link;
    $sqlPlc = $link->query("SELECT usrbeid FROM places WHERE beid='$plcBeID' and status='active' LIMIT 1");
    if ($sqlPlc->num_rows > 0) {
      $usrBeID = $sqlPlc->fetch_assoc()["usrbeid"];
      $date = date("Y-m-d H:i:s");
      $dateY = date("Y");
      $dateM = date("m");
      $dateD = date("d");
      $idReady = false;
      while (!$idReady) {
        $iD = randomHash(11);
        if ($link->query("SELECT * FROM idlist WHERE id='$iD'")->num_rows == 0) {
          $idReady = true;
        } else {
          $idReady = false;
        }
      }
      $beIDReady = false;
      while (!$beIDReady) {
        $bookingBeId = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$bookingBeId'")->num_rows == 0) {
          $beIDReady = true;
        } else {
          $beIDReady = false;
        }
      }
      $fullFrom = $fromY."-".$fromM."-".$fromD;
      $fullTo = $toY."-".$toM."-".$toD;
      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
      $sqlBookingBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$bookingBeId', '$backendIDNum', 'booking-import')";
      if (mysqli_query($link, $sqlBookingBeID)) {
        $sqlNewBookingID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$bookingBeId', '$iD', '$date', '$dateD', '$dateM', '$dateY')";
        if (mysqli_query($link, $sqlNewBookingID)) {
          $sqlImportLog = "INSERT INTO icalimportlog(callbeid, plcbeid, bookingbeid, uid) VALUES ('$callBeID', '$plcBeID', '$bookingBeId', '$uID')";
          if (mysqli_query($link, $sqlImportLog)) {
            $sqlSave = "INSERT INTO booking (beid, usrbeid, plcbeid, status, name, email, phonenum, notes, language, guestnum, totalprice, totalcurrency, deposit, fullAmount, lessthan48h, ratingMail, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, source, fulldate, datey, datem, dated) VALUES ('$bookingBeId', '-', '$plcBeID', 'imported', '-', '-', '-', '$note', 'eng', '1', '0', 'eur', '0', '0', '0', '0', '$fullFrom', '$fromY', '$fromM', '$fromD', '$firstDay', '$fullTo', '$toY', '$toM', '$toD', '$lastDay', '$sourceCode', '$date', '$dateY', '$dateM', '$dateD')";
            if (mysqli_query($link, $sqlSave)) {
              return "done";
            } else {
              return "Add booking (".$fromD.". ".$fromM.". ".$fromY." - ".$toD.". ".$toM.". ".$toY.") failed: failed to save to booking database<br>".mysqli_error($link);
            }
          } else {
            return "Add booking (".$fromD.". ".$fromM.". ".$fromY." - ".$toD.". ".$toM.". ".$toY.") failed: failed to save to icalimportlog database<br>".mysqli_error($link);
          }
        } else {
          return "Add booking (".$fromD.". ".$fromM.". ".$fromY." - ".$toD.". ".$toM.". ".$toY.") failed: failed to save to idlist database<br>".mysqli_error($link);
        }
      } else {
        return "Add booking (".$fromD.". ".$fromM.". ".$fromY." - ".$toD.". ".$toM.". ".$toY.") failed: failed to save to backendidlist database<br>".mysqli_error($link);
      }
    } else {
      return "Add booking (".$fromD.". ".$fromM.". ".$fromY." - ".$toD.". ".$toM.". ".$toY.") failed: place not found";
    }
  }
?>
