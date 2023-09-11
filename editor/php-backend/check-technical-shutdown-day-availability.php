<?php
  function checkTechnicalShutdownDayAvailability($plcBeID, $check_d, $check_m, $check_y, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay) {
    global $link;
    $output = [];
    $sqlTechnicalShutdownDates = $link->query("SELECT beid FROM technicalshutdowndates WHERE plcbeid='$plcBeID' and status='active' and year='$check_y' and month='$check_m' and day='$check_d'");
    if ($sqlTechnicalShutdownDates->num_rows > 0) {
      while($technicalShutdownDates = $sqlTechnicalShutdownDates->fetch_assoc()) {
        $technicalShutdownBeId = $technicalShutdownDates["beid"];
        $sqlTechnicalShutdown = $link->query("SELECT fromy, fromm, fromd, firstday, toy, tom, tod, lastday FROM technicalshutdown WHERE beid='$technicalShutdownBeId' LIMIT 1");
        if ($sqlTechnicalShutdown->num_rows > 0) {
          $technicalShutdownRow = $sqlTechnicalShutdown->fetch_assoc();
          if ($check_d == $f_d && $check_m == $f_m && $check_y == $f_y) {
            if ($firstDay == "half") {
              if ($check_y == $technicalShutdownRow['fromy'] && $check_m == $technicalShutdownRow['fromm'] && $check_d == $technicalShutdownRow['fromd']) {
                array_push($output, $technicalShutdownBeId);
              } else if ($check_y == $technicalShutdownRow['toy'] && $check_m == $technicalShutdownRow['tom'] && $check_d == $technicalShutdownRow['tod']) {
                if ($technicalShutdownRow['lastday'] != "half") {
                  array_push($output, $technicalShutdownBeId);
                }
              } else {
                array_push($output, $technicalShutdownBeId);
              }
            } else {
              array_push($output, $technicalShutdownBeId);
            }
          } else if ($check_d == $t_d && $check_m == $t_m && $check_y == $t_y) {
            if ($lastDay == "half") {
              if ($check_y == $technicalShutdownRow['fromy'] && $check_m == $technicalShutdownRow['fromm'] && $check_d == $technicalShutdownRow['fromd']) {
                if ($technicalShutdownRow['firstday'] != "half") {
                  array_push($output, $technicalShutdownBeId);
                }
              } else if ($check_y == $technicalShutdownRow['toy'] && $check_m == $technicalShutdownRow['tom'] && $check_d == $technicalShutdownRow['tod']) {
                array_push($output, $technicalShutdownBeId);
              } else {
                array_push($output, $technicalShutdownBeId);
              }
            } else {
              array_push($output, $technicalShutdownBeId);
            }
          } else {
            array_push($output, $technicalShutdownBeId);
          }
        }
      }
    }
    return $output;
  }
?>
