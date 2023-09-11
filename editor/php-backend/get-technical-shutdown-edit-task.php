<?php
  $unaffectedTechnicalShutdownTermReady = false;
  $unaffectedTechnicalShutdownTermStatus = false;
  $affectedTechnicalShutdownTermReady = false;
  $affectedTechnicalShutdownTermStatus = false;
  $unaffectedTechnicalShutdownSplitReady = false;
  $unaffectedTechnicalShutdownSplitStatus = false;
  function getTechnicalShutdownEditTask($blockingTechnicalShutdown, $type, $ts_category, $ts_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay) {
    global $link, $unaffectedTechnicalShutdownTermReady, $unaffectedTechnicalShutdownTermStatus, $affectedTechnicalShutdownTermStatus, $affectedTechnicalShutdownTermReady, $unaffectedTechnicalShutdownSplitReady, $unaffectedTechnicalShutdownSplitStatus;
    $sqlBLockingData = $link->query("SELECT * FROM technicalshutdown WHERE beid='$blockingTechnicalShutdown' and status='active' LIMIT 1");
    if ($sqlBLockingData->num_rows > 0) {
      $blockingRow = $sqlBLockingData->fetch_assoc();
      $f_d = sprintf("%02d", $f_d);
      $f_m = sprintf("%02d", $f_m);
      $f_y = sprintf("%02d", $f_y);
      $t_d = sprintf("%02d", $t_d);
      $t_m = sprintf("%02d", $t_m);
      $t_y = sprintf("%02d", $t_y);
      $unaffectedTechnicalShutdownTermReady = false;
      $unaffectedTechnicalShutdownTermStatus = false;
      $affectedTechnicalShutdownTermReady = false;
      $affectedTechnicalShutdownTermStatus = false;
      $unaffectedTechnicalShutdownSplitReady = false;
      $unaffectedTechnicalShutdownSplitStatus = false;
      $sqlBlockingDays = $link->query("SELECT * FROM technicalshutdowndates WHERE beid='$blockingTechnicalShutdown' and status='active' ORDER BY fulldate ASC");
      if ($sqlBlockingDays->num_rows > 0) {
        while($blockingDays = $sqlBlockingDays->fetch_assoc()) {
          $blocking_day_y = $blockingDays['year'];
          $blocking_day_m = sprintf("%02d", $blockingDays['month']);
          $blocking_day_d = sprintf("%02d", $blockingDays['day']);
          if (
            $blocking_day_y."-".$blocking_day_m."-".$blocking_day_d > $f_y."-".$f_m."-".$f_d &&
            $blocking_day_y."-".$blocking_day_m."-".$blocking_day_d < $t_y."-".$t_m."-".$t_d
          ) {
            getTechnicalShutdownEditTaskAffectedDay();
          } else if ($blocking_day_y == $f_y && $blocking_day_m == $f_m && $blocking_day_d == $f_d) {
            if ($blocking_day_y."-".$blocking_day_m."-".$blocking_day_d != $blockingRow['fromy']."-".sprintf("%02d", $blockingRow['fromm'])."-".sprintf("%02d", $blockingRow['fromd'])) {
              if ($firstDay == "half") {
                if ($affectedTechnicalShutdownTermReady) {
                  getTechnicalShutdownEditTaskAffectedDay();
                } else {
                  $affectedTechnicalShutdownTermReady = true;
                }
                getTechnicalShutdownEditTaskUnaffectedDay();
              } else {
                getTechnicalShutdownEditTaskAffectedDay();
              }
            } else {
              getTechnicalShutdownEditTaskAffectedDay();
            }
          } else if ($blocking_day_y == $t_y && $blocking_day_m == $t_m && $blocking_day_d == $t_d) {
            if ($blocking_day_y."-".$blocking_day_m."-".$blocking_day_d != $blockingRow['toy']."-".sprintf("%02d", $blockingRow['tom'])."-".sprintf("%02d", $blockingRow['tod'])) {
              if ($lastDay == "half") {
                if ($affectedTechnicalShutdownTermReady) {
                  getTechnicalShutdownEditTaskAffectedDay();
                } else {
                  $affectedTechnicalShutdownTermReady = true;
                }
                getTechnicalShutdownEditTaskUnaffectedDay();
              } else {
                getTechnicalShutdownEditTaskAffectedDay();
              }
            } else {
              getTechnicalShutdownEditTaskAffectedDay();
            }
          } else {
            getTechnicalShutdownEditTaskUnaffectedDay();
          }
        }
        if ($unaffectedTechnicalShutdownSplitStatus || $unaffectedTechnicalShutdownTermStatus) {
          if ($type == "technical-shutdown" && $blockingRow['category'] == $ts_category && $blockingRow['notes'] == $ts_notes) {
            getTechnicalShutdownEditTaskOutput("task", $blockingTechnicalShutdown, "connect");
          } else {
            if ($unaffectedTechnicalShutdownSplitStatus) {
              getTechnicalShutdownEditTaskOutput("task", $blockingTechnicalShutdown, "split");
            } else {
              getTechnicalShutdownEditTaskOutput("task", $blockingTechnicalShutdown, "shorten");
            }
          }
        } else {
          getTechnicalShutdownEditTaskOutput("task", $blockingTechnicalShutdown, "delete");
        }
      } else {
        getTechnicalShutdownEditTaskOutput("error", "Get task for edit: Days of a technical shutdown not found in database", "");
      }
    } else {
      getTechnicalShutdownEditTaskOutput("error", "Get task for edit: Technical shutdown not found in database", "");
    }
  }

  function getTechnicalShutdownEditTaskAffectedDay() {
    global $affectedTechnicalShutdownTermReady, $affectedTechnicalShutdownTermStatus, $unaffectedTechnicalShutdownTermReady, $unaffectedTechnicalShutdownTermStatus, $unaffectedTechnicalShutdownSplitReady;
    $affectedTechnicalShutdownTermReady = false;
    $affectedTechnicalShutdownTermStatus = true;
    $unaffectedTechnicalShutdownTermReady = false;
    if ($unaffectedTechnicalShutdownTermStatus) {
      $unaffectedTechnicalShutdownSplitReady = true;
    }
  }

  function getTechnicalShutdownEditTaskUnaffectedDay() {
    global $unaffectedTechnicalShutdownTermReady, $unaffectedTechnicalShutdownTermStatus, $affectedTechnicalShutdownTermStatus, $unaffectedTechnicalShutdownSplitReady, $unaffectedTechnicalShutdownSplitStatus;
    if ($unaffectedTechnicalShutdownTermReady) {
      if ($unaffectedTechnicalShutdownTermStatus) {
        if ($affectedTechnicalShutdownTermStatus && $unaffectedTechnicalShutdownSplitReady) {
          $unaffectedTechnicalShutdownSplitStatus = true;
        }
      } else {
        $unaffectedTechnicalShutdownTermStatus = true;
      }
    } else {
      $unaffectedTechnicalShutdownTermReady = true;
    }
  }
?>
