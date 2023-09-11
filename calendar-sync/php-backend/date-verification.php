<?php
  include "../uni/code/php-backend/calendar/calc-date.php";
  function dateVerification($callBeID, $plcBeID, $c_fy, $c_fm, $c_fd, $c_ffday, $c_ty, $c_tm, $c_td, $c_flday) {
    global $link;
    $output = [];
    $firstFreeDay = false;
    $curr_from_y = 0;
    $curr_from_m = 0;
    $curr_from_d = 0;
    $curr_from_first_day = 0;
    $curr_to_y = 0;
    $curr_to_m = 0;
    $curr_to_d = 0;
    $curr_to_last_day = 0;
    $final_to_y = calcNextYearByDays($c_td, $c_tm, $c_ty, 1);
    $final_to_m = calcNextMonthByDays($c_td, $c_tm, $c_ty, 1);
    $final_to_d = calcNextDay($c_td, $c_tm, $c_ty, 1);
    $check_y = $c_fy;
    $check_m = $c_fm;
    $check_d = $c_fd;
    $verificationError = "";
    while ($check_d."/".$check_m."/".$check_y != $final_to_d."/".$final_to_m."/".$final_to_y) {
      $dayDetails = dateVerificationDayDetails($callBeID, $plcBeID, $check_y, $check_m, $check_d);
      if ($dayDetails['errors'] == "") {
        if ($dayDetails['availability'] == "available") {
          // available
          if ($c_td."/".$c_tm."/".$c_ty != $check_d."/".$check_m."/".$check_y) {
            if (!$firstFreeDay) {
              $firstFreeDay = true;
              $curr_from_y = $check_y;
              $curr_from_m = $check_m;
              $curr_from_d = $check_d;
              if ($c_fd."/".$c_fm."/".$c_fy == $check_d."/".$check_m."/".$check_y) {
                $curr_from_first_day = $c_ffday;
              } else {
                $curr_from_first_day = "whole";
              }
            }
          } else {
            if ($firstFreeDay) {
              $firstFreeDay = false;
              $curr_to_y = $check_y;
              $curr_to_m = $check_m;
              $curr_to_d = $check_d;
              $curr_to_last_day = $c_flday;
            }
          }
        } else {
          if (!$firstFreeDay) {
            if ($dayDetails['half-whole'] == "half") {
              if ($dayDetails['day-of-booking'] == "lastday") {
                // available
                if ($c_td."/".$c_tm."/".$c_ty != $check_d."/".$check_m."/".$check_y) {
                  if (!$firstFreeDay) {
                    $firstFreeDay = true;
                    $curr_from_y = $check_y;
                    $curr_from_m = $check_m;
                    $curr_from_d = $check_d;
                    $curr_from_first_day = "half";
                  }
                }
              } else {
                // unavailable
              }
            }
          } else {
            // unavailable
            if ($dayDetails['half-whole'] == "half") {
              $firstFreeDay = false;
              $curr_to_y = $check_y;
              $curr_to_m = $check_m;
              $curr_to_d = $check_d;
              $curr_to_last_day = "half";
            } else {
              $firstFreeDay = false;
              $curr_to_d = calcPreviousDay($check_d, $check_m, $check_y, 1);
              $curr_to_m = calcPreviousMonthByDays($check_d, $check_m, $check_y, 1);
              $curr_to_y = calcPreviousYearByDays($check_d, $check_m, $check_y, 1);
              $curr_to_last_day = "whole";
              if ($curr_to_y."/".$curr_to_m."/".$curr_to_d == $curr_from_y."/".$curr_from_m."/".$curr_from_d) {
                $curr_from_y = 0;
                $curr_from_m = 0;
                $curr_from_d = 0;
                $curr_from_first_day = 0;
                $curr_to_y = 0;
                $curr_to_m = 0;
                $curr_to_d = 0;
                $curr_to_last_day = 0;
              }
            }
          }
        }
      } else {
        $check_d = $final_to_d;
        $check_m = $final_to_m;
        $check_y = $final_to_y;
        if ($verificationError != "") {
          $verificationError = $verificationError."<br>".$dayDetails['errors'];
        } else {
          $verificationError = $dayDetails['errors'];
        }
      }
      if (
        $curr_from_y != 0 &&
        $curr_from_m != 0 &&
        $curr_from_d != 0 &&
        $curr_from_first_day != 0 &&
        $curr_to_y != 0 &&
        $curr_to_m != 0 &&
        $curr_to_d != 0 &&
        $curr_to_last_day != 0
      ) {
        $output = dateVerificationOutputDate(
          $output,
          $curr_from_y,
          $curr_from_m,
          $curr_from_d,
          $curr_from_first_day,
          $curr_to_y,
          $curr_to_m,
          $curr_to_d,
          $curr_to_last_day
        );
        $curr_from_y = 0;
        $curr_from_m = 0;
        $curr_from_d = 0;
        $curr_from_first_day = 0;
        $curr_to_y = 0;
        $curr_to_m = 0;
        $curr_to_d = 0;
        $curr_to_last_day = 0;
      }
      $temp_d = calcNextDay($check_d, $check_m, $check_y, 1);
      $temp_m = calcNextMonthByDays($check_d, $check_m, $check_y, 1);
      $temp_y = calcNextYearByDays($check_d, $check_m, $check_y, 1);
      $check_d = $temp_d;
      $check_m = $temp_m;
      $check_y = $temp_y;
    }
    if ($verificationError != "") {
      $output = [];
      $output = dateVerificationOutputError($output, $verificationError);
    }
    return $output;
  }

  function dateVerificationDayDetails($callBeID, $plcBeID, $check_y, $check_m, $check_d) {
    global $link;
    $dayDetailsOutput = [];
    $halfWholeDaySts = "";
    $checkError = "";
    $dayOfBooking = "middle";
    $dateUnavailableSts = false;
    $sqlbook = $link->query("SELECT * FROM bookingdates WHERE plcbeid='$plcBeID' and (status='booked' or status='waiting') and year='$check_y' and month='$check_m' and day='$check_d'");
    if ($sqlbook->num_rows > 0) {
      $temp_booking_sts = "available";
      $temp_booking_dayOfBooking = "";
      $temp_booking_halfWholeDaySts = "";
      $temp_booking_checkError = "";
      while($sqlBookingRow = $sqlbook->fetch_assoc()) {
        $bookingBeID = $sqlBookingRow['beid'];
        $sqlBookAbout = $link->query("SELECT * FROM booking WHERE beid='$bookingBeID' and (status='booked' or status='waiting')");
        if ($sqlBookAbout->num_rows > 0) {
          $rowBookAbout = $sqlBookAbout->fetch_assoc();
          if (getImportKey($bookingBeID) != "" && getImportKey($bookingBeID) != $callBeID) {
            $oldDateImportDeleteSts = oldDateImportDelete($bookingBeID);
            if ($oldDateImportDeleteSts != "done") {
              $temp_booking_sts = "unavailable";
              $temp_booking_dayOfBooking = "middle";
              $temp_booking_halfWholeDaySts = "whole";
              if ($temp_booking_checkError != "") {
                $temp_booking_checkError = $temp_booking_checkError."<br>Date (".$check_d.". ".$check_m.". ".$check_y.") - ".$oldDateImportDeleteSts;
              } else {
                $temp_booking_checkError = "Date (".$check_d.". ".$check_m.". ".$check_y.") - ".$oldDateImportDeleteSts;
              }
            }
          } else {
            if ($temp_booking_sts == "available") {
              $temp_booking_sts = "unavailable";
              if ($rowBookAbout['fromd'] == $check_d && $rowBookAbout['fromm'] == $check_m && $rowBookAbout['fromy'] == $check_y) {
                $temp_booking_dayOfBooking = "firstday";
                if ($rowBookAbout['firstday'] == "half") {
                  $temp_booking_halfWholeDaySts = "half";
                } else {
                  $temp_booking_halfWholeDaySts = "whole";
                }
              } else if ($rowBookAbout['tod'] == $check_d && $rowBookAbout['tom'] == $check_m && $rowBookAbout['toy'] == $check_y) {
                $temp_booking_dayOfBooking = "lastday";
                if ($rowBookAbout['lastday'] == "half") {
                  $temp_booking_halfWholeDaySts = "half";
                } else {
                  $temp_booking_halfWholeDaySts = "whole";
                }
              } else {
                $temp_booking_dayOfBooking = "middle";
                $temp_booking_halfWholeDaySts = "whole";
              }
            } else {
              $temp_booking_dayOfBooking = "middle";
              $temp_booking_halfWholeDaySts = "whole";
            }
          }
        } else {
          $temp_booking_sts = "unavailable";
          $temp_booking_dayOfBooking = "middle";
          $temp_booking_halfWholeDaySts = "whole";
          if ($temp_booking_checkError != "") {
            $temp_booking_checkError = $temp_booking_checkError."<br>Date (".$check_d.". ".$check_m.". ".$check_y.") is unavailable in 'bookingdates' database, but in 'booking' nothing has been found (SQL error: ".mysqli_error($link).")";
          } else {
            $temp_booking_checkError = "Date (".$check_d.". ".$check_m.". ".$check_y.") is unavailable in 'bookingdates' database, but in 'booking' nothing has been found (SQL error: ".mysqli_error($link).")";
          }
        }
      }
      if ($temp_booking_sts != "available") {
        $dateUnavailableSts = true;
        $halfWholeDaySts = $temp_booking_halfWholeDaySts;
        $checkError = $temp_booking_checkError;
        $dayOfBooking = $temp_booking_dayOfBooking;
      }
    }
    if ($halfWholeDaySts != "whole") {
      $sqltechnicalshutdown = $link->query("SELECT * FROM technicalshutdowndates WHERE plcbeid='$plcBeID' and status='active' and year='$check_y' and month='$check_m' and day='$check_d'");
      if ($sqltechnicalshutdown->num_rows > 0) {
        if ($dateUnavailableSts) {
          $temp_technical_shutdown_sts = "unavailable";
        } else {
          $temp_technical_shutdown_sts = "available";
        }
        $temp_technical_shutdown_dayOfBooking = $dayOfBooking;
        $temp_technical_shutdown_halfWholeDaySts = $halfWholeDaySts;
        $temp_technical_shutdown_checkError = $checkError;
        while($sqlTechnicalShutdownRow = $sqltechnicalshutdown->fetch_assoc()) {
          $technicalShutdownBeID = $sqlTechnicalShutdownRow['beid'];
          $sqltechnicalshutdownAbout = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeID' and status='active'");
          if ($sqltechnicalshutdownAbout->num_rows > 0) {
            $rowTechnicalShutdownAbout = $sqltechnicalshutdownAbout->fetch_assoc();
            if ($temp_technical_shutdown_sts == "available") {
              $temp_technical_shutdown_sts = "unavailable";
              if ($rowTechnicalShutdownAbout['fromd'] == $check_d && $rowTechnicalShutdownAbout['fromm'] == $check_m && $rowTechnicalShutdownAbout['fromy'] == $check_y) {
                $temp_technical_shutdown_dayOfBooking = "firstday";
                if ($rowTechnicalShutdownAbout['firstday'] == "half") {
                  $temp_technical_shutdown_halfWholeDaySts = "half";
                } else {
                  $temp_technical_shutdown_halfWholeDaySts = "whole";
                }
              } else if ($rowTechnicalShutdownAbout['tod'] == $check_d && $rowTechnicalShutdownAbout['tom'] == $check_m && $rowTechnicalShutdownAbout['toy'] == $check_y) {
                $temp_technical_shutdown_dayOfBooking = "lastday";
                if ($rowTechnicalShutdownAbout['lastday'] == "half") {
                  $temp_technical_shutdown_halfWholeDaySts = "half";
                } else {
                  $temp_technical_shutdown_halfWholeDaySts = "whole";
                }
              } else {
                $temp_technical_shutdown_dayOfBooking = "middle";
                $temp_technical_shutdown_halfWholeDaySts = "whole";
              }
            } else {
              $temp_technical_shutdown_dayOfBooking = "middle";
              $temp_technical_shutdown_halfWholeDaySts = "whole";
            }
          } else {
            $temp_technical_shutdown_sts = "unavailable";
            $temp_technical_shutdown_dayOfBooking = "middle";
            $temp_technical_shutdown_halfWholeDaySts = "whole";
            if ($temp_technical_shutdown_checkError != "") {
              $temp_technical_shutdown_checkError = $temp_technical_shutdown_checkError."<br>Date (".$check_d.". ".$check_m.". ".$check_y.") is unavailable in 'technicalshutdowndates' database, but in 'technicalshutdown' nothing has been found (SQL error: ".mysqli_error($link).")";
            } else {
              $temp_technical_shutdown_checkError = "Date (".$check_d.". ".$check_m.". ".$check_y.") is unavailable in 'technicalshutdowndates' database, but in 'technicalshutdown' nothing has been found (SQL error: ".mysqli_error($link).")";
            }
          }
        }
        if ($temp_technical_shutdown_sts != "available") {
          $dateUnavailableSts = true;
          $halfWholeDaySts = $temp_technical_shutdown_halfWholeDaySts;
          $checkError = $temp_technical_shutdown_checkError;
          $dayOfBooking = $temp_technical_shutdown_dayOfBooking;
        }
      }
    }
    if (!$dateUnavailableSts) {
      $availabilitySts = "available";
    } else {
      $availabilitySts = "unavailable";
    }
    $dayDetailsOutput = [
      "availability" => $availabilitySts,
      "day-of-booking" => $dayOfBooking,
      "half-whole" => $halfWholeDaySts,
      "errors" => $checkError
    ];
    return $dayDetailsOutput;
  }

  function getImportKey($bookingBeID) {
    global $linkBD;
    $sqlSyncKey = $linkBD->query("SELECT beid FROM calendarsynckey WHERE plcbeid='$bookingBeID' and status='done' ORDER BY fulldate DESC LIMIT 1");
    if ($sqlSyncKey->num_rows > 0) {
      return $sqlSyncKey->fetch_assoc()['beid'];
    } else {
      return "";
    }
  }

  function dateVerificationOutputDate($output, $curr_from_y, $curr_from_m, $curr_from_d, $curr_from_first_day, $curr_to_y, $curr_to_m, $curr_to_d, $curr_to_last_day) {
    array_push($output, [
      "type" => "date",
      "from-y" => $curr_from_y,
      "from-m" => $curr_from_m,
      "from-d" => $curr_from_d,
      "first-day" => $curr_from_first_day,
      "to-y" => $curr_to_y,
      "to-m" => $curr_to_m,
      "to-d" => $curr_to_d,
      "last-day" => $curr_to_last_day
    ]);
    return $output;
  }

  function dateVerificationOutputError($output, $msg) {
    array_push($output, [
      "type" => "error",
      "msg" => $msg
    ]);
    return $output;
  }
?>
