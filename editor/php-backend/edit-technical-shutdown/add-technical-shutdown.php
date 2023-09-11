<?php
  function addTechnicalShutdown($plcBeId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay) {
    global $link;
    $sqlPlc = $link->query("SELECT usrbeid, name, guestNum, currency FROM places WHERE beid='$plcBeId' and status='active' LIMIT 1");
    if ($sqlPlc->num_rows > 0) {
      $rowPlc = $sqlPlc->fetch_assoc();
      $technicalShutdownIdReady = false;
      while (!$technicalShutdownIdReady) {
        $newTechnicalShutdownId = randomHash(11);
        if ($link->query("SELECT * FROM idlist WHERE id='$newTechnicalShutdownId'")->num_rows == 0) {
          $technicalShutdownIdReady = true;
        } else {
          $technicalShutdownIdReady = false;
        }
      }
      $newTechnicalShutdownBeIdReady = false;
      while (!$newTechnicalShutdownBeIdReady) {
        $newTechnicalShutdownBeId = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$newTechnicalShutdownBeId'")->num_rows == 0) {
          $newTechnicalShutdownBeIdReady = true;
        } else {
          $newTechnicalShutdownBeIdReady = false;
        }
      }
      if ($ts_category != "cleaning" && $ts_category != "maintenance" && $ts_category != "reconstruction") {
        $ts_category = "other";
      }
      $fullFrom = $f_y."-".$f_m."-".$f_d;
      $fullTo = $t_y."-".$t_m."-".$t_d;
      $date = date("Y-m-d H:i:s");
      $dateY = date("Y");
      $dateM = date("m");
      $dateD = date("d");
      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
      $sqlTechnicalShutdownBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$newTechnicalShutdownBeId', '$backendIDNum', 'technical-shutdown')";
      if (mysqli_query($link, $sqlTechnicalShutdownBeID)) {
        $sqlTechnicalShutdownID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$newTechnicalShutdownBeId', '$newTechnicalShutdownId', '$date', '$dateD', '$dateM', '$dateY')";
        if (mysqli_query($link, $sqlTechnicalShutdownID)) {
          $sqlSave = "INSERT INTO technicalshutdown (beid, plcbeid, status, category, notes, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, fulldate, datey, datem, dated) VALUES ('$newTechnicalShutdownBeId', '$plcBeId', 'active', '$ts_category', '$ts_notes', '$fullFrom', '$f_y', '$f_m', '$f_d', '$firstDay', '$fullTo', '$t_y', '$t_m', '$t_d', '$lastDay', '$date', '$dateY', '$dateM', '$dateD')";
          if (mysqli_query($link, $sqlSave)) {
            $allDaysAdded = false;
            $add_y = $f_y;
            $add_m = $f_m;
            $add_d = $f_d;
            $allDaysErrorUnavailable = 0;
            $allDaysErrorSql = 0;
            while (!$allDaysAdded) {
              if ($link->query("SELECT * FROM technicalshutdowndates WHERE beid='$newTechnicalShutdownBeId' and plcbeid='$plcBeId' and status!='canceled' and year='$add_y' and month='$add_m' and day='$add_d'")->num_rows == 0) {
                if (!everyDayToTechnicalShutdownDatabase($newTechnicalShutdownBeId, $plcBeId, $add_y, $add_m, $add_d)) {
                  ++$allDaysErrorSql;
                }
              } else {
                ++$allDaysErrorUnavailable;
              }
              if ($add_y == $t_y && $add_m == $t_m && $add_d == $t_d) {
                $allDaysAdded = true;
              } else {
                ++$add_d;
                if ($add_d > cal_days_in_month(CAL_GREGORIAN, $add_m, $add_y)) {
                  $add_d = 1;
                  ++$add_m;
                  if ($add_m > 12) {
                    $add_m = 1;
                    ++$add_y;
                  }
                }
              }
            }
            if ($allDaysErrorUnavailable == 0 && $allDaysErrorSql == 0) {
              addTechnicalShutdownOutput("done", "good");
            } else {
              $technicalShutdownDatesErrorTxt = "";
              $calcelSts = cancelTechnicalShutdownDates($newTechnicalShutdownBeId);
              if ($allDaysErrorUnavailable > 0) {
                $technicalShutdownDatesErrorTxt = $technicalShutdownDatesErrorTxt."Not all days are available (unavailable dates: ".$allDaysErrorUnavailable.")<br>";
              }
              if ($allDaysErrorSql > 0) {
                $technicalShutdownDatesErrorTxt = $technicalShutdownDatesErrorTxt."Failed to save separate days of technical shutdown (dates: ".$allDaysErrorSql.")<br>";
              }
              if ($calcelSts != "good") {
                $technicalShutdownDatesErrorTxt = $technicalShutdownDatesErrorTxt."".$calcelSts."<br>";
              }
              addTechnicalShutdownOutput("error", "Add technical shutdown failed: <br>".$technicalShutdownDatesErrorTxt);
            }
          } else {
            addTechnicalShutdownOutput("error", "Add technical shutdown failed: failed to save technical shutdown<br>".mysqli_error($link));
          }
        } else {
          addTechnicalShutdownOutput("error", "Add technical shutdown failed: saving ID failed");
        }
      } else {
        addTechnicalShutdownOutput("error", "Add technical shutdown failed: saving backend ID failed");
      }
    } else {
      addTechnicalShutdownOutput("error", "Add technical shutdown failed: place does not exist");
    }
  }

  function everyDayToTechnicalShutdownDatabase($beId, $plcBeId, $y, $m, $d) {
    global $link;
    $fulldate = $y."-".sprintf("%02d", $m)."-".sprintf("%02d", $d);
    $sql = "INSERT INTO technicalshutdowndates (beid, plcbeid, status, year, month, day, fulldate) VALUES ('$beId', '$plcBeId', 'active', '$y', '$m', '$d', '$fulldate')";
    if (mysqli_query($link, $sql)) {
      return true;
    } else {
      return false;
    }
  }

  function cancelTechnicalShutdownDates($beId) {
    global $link;
    $sqlDaysCancel = "UPDATE technicalshutdowndates SET status='canceled' WHERE beid='$beId'";
    if (mysqli_query($link, $sqlDaysCancel)) {
      $sqlTechnicalShutdownCancel = "UPDATE technicalshutdown SET status='canceled' WHERE beid='$beId'";
      if (mysqli_query($link, $sqlTechnicalShutdownCancel)) {
        return "good";
      } else {
        return "cancel-technical-shutdown-failed";
      }
    } else {
      return "cancel-days-list-failed";
    }
  }
?>
