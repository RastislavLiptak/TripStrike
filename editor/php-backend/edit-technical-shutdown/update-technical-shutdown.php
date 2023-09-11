<?php
  $changedList = [];
  function updateTechnicalShutdown($technicalShutdownBeId, $plcBeId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay) {
    global $link;
    if ($firstDay != "whole") {
      $firstDay = "half";
    }
    if ($lastDay != "whole") {
      $lastDay = "half";
    }
    if ($ts_category != "cleaning" && $ts_category != "maintenance" && $ts_category != "reconstruction") {
      $ts_category = "other";
    }
    $whatIsChanged = updateTechnicalShutdownWhatIsChanged($technicalShutdownBeId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay);
    $fullFrom = $f_y."-".$f_m."-".$f_d;
    $fullTo = $t_y."-".$t_m."-".$t_d;
    $sqlUpdt = "UPDATE technicalshutdown SET category='$ts_category', notes='$ts_notes', fromdate='$fullFrom', fromy='$f_y', fromm='$f_m', fromd='$f_d', todate='$fullTo', toy='$t_y', tom='$t_m', tod='$t_d', firstday='$firstDay', lastday='$lastDay' WHERE beid='$technicalShutdownBeId'";
    if (mysqli_query($link, $sqlUpdt)) {
      $sqlCancelTechnicalShutdownDays = "UPDATE technicalshutdowndates SET status='canceled' WHERE beid='$technicalShutdownBeId'";
      mysqli_query($link, $sqlCancelTechnicalShutdownDays);
      $allDaysAdded = false;
      $add_y = $f_y;
      $add_m = $f_m;
      $add_d = $f_d;
      $allDaysErrorSql = 0;
      while (!$allDaysAdded) {
        $technicalShutdownDayFulldate = $add_y."-".sprintf("%02d", $add_m)."-".sprintf("%02d", $add_d);
        if ($link->query("SELECT * FROM technicalshutdowndates WHERE beid='$technicalShutdownBeId' and year='$add_y' and month='$add_m' and day='$add_d'")->num_rows == 0) {
          $sqlAddTechnicalShutdownDay = "INSERT INTO technicalshutdowndates (beid, plcbeid, status, year, month, day, fulldate) VALUES ('$technicalShutdownBeId', '$plcBeId', 'active', '$add_y', '$add_m', '$add_d', '$technicalShutdownDayFulldate')";
          if (!mysqli_query($link, $sqlAddTechnicalShutdownDay)) {
            ++$allDaysErrorSql;
          }
        } else {
          $sqlUpdateTechnicalShutdownDay = "UPDATE technicalshutdowndates SET status='active' WHERE beid='$technicalShutdownBeId' and year='$add_y' and month='$add_m' and day='$add_d' and fulldate='$technicalShutdownDayFulldate'";
          if (!mysqli_query($link, $sqlUpdateTechnicalShutdownDay)) {
            ++$allDaysErrorSql;
            echo mysqli_error($link)."<br>";
          }
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
      if ($allDaysErrorSql == 0) {
        updateTechnicalShutdownOutput("done", "good");
      } else {
        $technicalShutdownDatesErrorTxt = "";
        $calcelSts = emergencyTechnicalShutdownCancel($technicalShutdownBeId);
        if ($allDaysErrorSql > 0) {
          $technicalShutdownDatesErrorTxt = $technicalShutdownDatesErrorTxt."failed to save separate days of technical shutdown (dates: ".$allDaysErrorSql.")<br>";
        }
        if ($calcelSts != "good") {
          $technicalShutdownDatesErrorTxt = $technicalShutdownDatesErrorTxt."".$calcelSts."<br>";
        }
        updateTechnicalShutdownOutput("error", "Update technical shutdown failed: <br>".$technicalShutdownDatesErrorTxt);
      }
    } else {
      updateTechnicalShutdownOutput("error", "Update technical shutdown failed: failed to update 'technicalshutdown' database<br>".mysqli_error($link));
    }
  }

  function emergencyTechnicalShutdownCancel($beId) {
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

  function updateTechnicalShutdownWhatIsChanged($technicalShutdownBeId, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay) {
    global $link, $changedList;
    $sqlTechnicalShutdown = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
    $techShut = $sqlTechnicalShutdown->fetch_assoc();
    if ($techShut['category'] != $ts_category) {
      updateTechnicalShutdownWhatIsChangedList("category");
    }
    if ($techShut['notes'] != $ts_notes) {
      updateTechnicalShutdownWhatIsChangedList("notes");
    }
    if ($techShut['fromd'] != $f_d) {
      updateTechnicalShutdownWhatIsChangedList("fromd");
    }
    if ($techShut['fromm'] != $f_m) {
      updateTechnicalShutdownWhatIsChangedList("fromm");
    }
    if ($techShut['fromy'] != $f_y) {
      updateTechnicalShutdownWhatIsChangedList("fromy");
    }
    if ($techShut['firstday'] != $firstDay) {
      updateTechnicalShutdownWhatIsChangedList("firstday");
    }
    if ($techShut['tod'] != $t_d) {
      updateTechnicalShutdownWhatIsChangedList("tod");
    }
    if ($techShut['tom'] != $t_m) {
      updateTechnicalShutdownWhatIsChangedList("tom");
    }
    if ($techShut['toy'] != $t_y) {
      updateTechnicalShutdownWhatIsChangedList("toy");
    }
    if ($techShut['lastday'] != $lastDay) {
      updateTechnicalShutdownWhatIsChangedList("lastday");
    }
    return $changedList;
  }

  function updateTechnicalShutdownWhatIsChangedList($val) {
    global $changedList;
    array_push($changedList, $val);
  }
?>
