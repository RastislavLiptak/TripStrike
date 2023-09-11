<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/calendar/calc-date.php";
  include "../../uni/code/php-backend/calendar/let-me-sleep.php";
  $output = [];
  $datesArr = [];
  $plcId = $_POST['plcId'];
  if (preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $plcId)) != "") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId' LIMIT 1");
    if ($sqlIdToBeId->num_rows > 0) {
      $beId = $sqlIdToBeId->fetch_assoc()["beid"];
      $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$beId' and status='active' LIMIT 1");
      if ($sqlPlace->num_rows > 0) {
        $plcRow = $sqlPlace->fetch_assoc();
        $today_date_d = date("d");
        $today_date_m = date("m");
        $today_date_y = date("Y");
        if (letMeSleep(calcNextYearByDays($today_date_d, $today_date_m, $today_date_y, 1), calcNextMonthByDays($today_date_d, $today_date_m, $today_date_y, 1), calcNextDay($today_date_d, $today_date_m, $today_date_y, 1), "available") != "unavailable") {
          $date_d = calcNextDay($today_date_d, $today_date_m, $today_date_y, 1);
          $date_m = calcNextMonthByDays($today_date_d, $today_date_m, $today_date_y, 1);
          $date_y = calcNextYearByDays($today_date_d, $today_date_m, $today_date_y, 1);
        } else {
          $date_d = calcNextDay($today_date_d, $today_date_m, $today_date_y, 2);
          $date_m = calcNextMonthByDays($today_date_d, $today_date_m, $today_date_y, 2);
          $date_y = calcNextYearByDays($today_date_d, $today_date_m, $today_date_y, 2);
        }
        $first_d = $date_d;
        $first_m = $date_m;
        $first_y = $date_y;
        $start_term_d = "";
        $start_term_m = "";
        $start_term_y = "";
        $end_term_d = "";
        $end_term_m = "";
        $end_term_y = "";
        $day_counter = 0;
        $term_counter = 0;
        $oneNightBookingSts = false;
        $oneNightBookingReady = false;
        $oneNightTechnicalShutdownReady = false;
        while ($day_counter < 60 && $term_counter < 2) {
          if ($plcRow['operation'] == "summer") {
            if ($date_m < $plcRow['operationFrom'] || $date_m > $plcRow['operationTo']) {
              $operationBlocking = true;
            } else {
              $operationBlocking = false;
            }
          } else if ($plcRow['operation'] == "winter") {
            $winterOperationMonthsList = [];
            $winterUnavailableReady = false;
            $winterOperationFrom = $plcRow['operationFrom'];
            while (!$winterUnavailableReady) {
              array_push($winterOperationMonthsList, $winterOperationFrom);
              if ($winterOperationFrom == 4) {
                $winterOperationFrom = 9;
              } else if ($winterOperationFrom == 12) {
                $winterOperationFrom = 1;
              } else {
                ++$winterOperationFrom;
              }
              if ($winterOperationFrom == $plcRow['operationTo']) {
                $winterUnavailableReady = true;
              }
            }
            if (!in_array($date_m, $winterOperationMonthsList)) {
              $operationBlocking = true;
            } else {
              $operationBlocking = false;
            }
          } else {
            $operationBlocking = false;
          }
          if (!$operationBlocking) {
            $sqlbook = $link->query("SELECT * FROM bookingdates WHERE plcbeid='$beId' and (status='booked' or status='waiting') and year='$date_y' and month='$date_m' and day='$date_d'");
            if ($sqlbook->num_rows > 0) {
              $bookingBeID = $sqlbook->fetch_assoc()['beid'];
              $sqlBookAbout = $link->query("SELECT * FROM booking WHERE beid='$bookingBeID' and (status='booked' or status='waiting')");
              if ($sqlBookAbout->num_rows > 0) {
                $rowBookAbout = $sqlBookAbout->fetch_assoc();
                if ($rowBookAbout['fromd'] == $date_d && $rowBookAbout['fromm'] == $date_m && $rowBookAbout['fromy'] == $date_y && $rowBookAbout['firstday'] == "half") {
                  $oneNightBookingReady = true;
                  $dateBooked = false;
                } else if ($rowBookAbout['tod'] == $date_d && $rowBookAbout['tom'] == $date_m && $rowBookAbout['toy'] == $date_y && $rowBookAbout['lastday'] == "half") {
                  if ($oneNightBookingReady) {
                    $oneNightBookingSts = true;
                  }
                  $dateBooked = false;
                } else {
                  $oneNightBookingSts = false;
                  $oneNightBookingReady = false;
                  $dateBooked = true;
                }
              } else {
                $oneNightBookingSts = false;
                $oneNightBookingReady = false;
                $dateBooked = false;
              }
            } else {
              $oneNightBookingSts = false;
              $oneNightBookingReady = false;
              $dateBooked = false;
            }
            if (!$oneNightBookingReady && !$oneNightBookingSts && !$dateBooked) {
              $sqltechnicalshutdown = $link->query("SELECT * FROM technicalshutdowndates WHERE plcbeid='$beId' and status='active' and year='$date_y' and month='$date_m' and day='$date_d'");
              if ($sqltechnicalshutdown->num_rows > 0) {
                $technicalShutdownBeID = $sqltechnicalshutdown->fetch_assoc()['beid'];
                $sqltechnicalshutdownAbout = $link->query("SELECT * FROM technicalshutdown WHERE beid='$technicalShutdownBeID' and status='active'");
                if ($sqltechnicalshutdownAbout->num_rows > 0) {
                  $rowTechnicalShutdownAbout = $sqltechnicalshutdownAbout->fetch_assoc();
                  if ($rowTechnicalShutdownAbout['fromd'] == $date_d && $rowTechnicalShutdownAbout['fromm'] == $date_m && $rowTechnicalShutdownAbout['fromy'] == $date_y && $rowTechnicalShutdownAbout['firstday'] == "half") {
                    $oneNightTechnicalShutdownReady = true;
                    $dateBooked = false;
                  } else if ($rowTechnicalShutdownAbout['tod'] == $date_d && $rowTechnicalShutdownAbout['tom'] == $date_m && $rowTechnicalShutdownAbout['toy'] == $date_y && $rowTechnicalShutdownAbout['lastday'] == "half") {
                    if ($oneNightTechnicalShutdownReady) {
                      $oneNightBookingSts = true;
                    }
                    $dateBooked = false;
                  } else {
                    $oneNightBookingSts = false;
                    $oneNightTechnicalShutdownReady = false;
                    $dateBooked = true;
                  }
                } else {
                  $oneNightBookingSts = false;
                  $oneNightTechnicalShutdownReady = false;
                  $dateBooked = false;
                }
              } else {
                $oneNightBookingSts = false;
                $oneNightTechnicalShutdownReady = false;
                $dateBooked = false;
              }
            }
          } else {
            $oneNightBookingSts = false;
            $oneNightTechnicalShutdownReady = false;
            $dateBooked = true;
          }
          if ($dateBooked) {
            if ($start_term_d != "" && $start_term_m != "" && $start_term_y != "" && $end_term_d != "" && $end_term_m != "" && $end_term_y != "") {
              $oneTerm = termFormat($start_term_d, $start_term_m, $start_term_y, $end_term_d, $end_term_m, $end_term_y);
              array_push($datesArr, $oneTerm);
              ++$term_counter;
            }
            $start_term_d = "";
            $start_term_m = "";
            $start_term_y = "";
            $end_term_d = "";
            $end_term_m = "";
            $end_term_y = "";
          } else {
            if (!$oneNightBookingSts) {
              if ($start_term_d != "" && $start_term_m != "" && $start_term_y != "") {
                $end_term_d = $date_d;
                $end_term_m = $date_m;
                $end_term_y = $date_y;
              } else {
                $start_term_d = $date_d;
                $start_term_m = $date_m;
                $start_term_y = $date_y;
              }
            } else {
              $oneNightBookingSts = false;
              $oneNightBookingReady = false;
              $oneNightTechnicalShutdownReady = false;
              if ($end_term_d != "" && $end_term_m != "" && $end_term_y != "" && $start_term_d."-".$start_term_m."-".$start_term_y != $end_term_d."-".$end_term_m."-".$end_term_y) {
                $oneTerm = termFormat($start_term_d, $start_term_m, $start_term_y, $end_term_d, $end_term_m, $end_term_y);
                array_push($datesArr, $oneTerm);
                ++$term_counter;
              }
              $start_term_d = $date_d;
              $start_term_m = $date_m;
              $start_term_y = $date_y;
              $end_term_d = "";
              $end_term_m = "";
              $end_term_y = "";
            }
          }
          ++$date_d;
          if ($date_d > cal_days_in_month(CAL_GREGORIAN, $date_m, $date_y)) {
            $date_d = 1;
            ++$date_m;
            if ($date_m > 12) {
              $date_m = 1;
              ++$date_y;
            }
          }
          ++$day_counter;
        }
        $finalNumOfTerms = 0;
        if (sizeof($datesArr) > 0) {
          $allAvailableTerms = "";
          foreach($datesArr as $fromToTerm) {
            if ($allAvailableTerms != "") {
              $allAvailableTerms = $allAvailableTerms." ".$wrd_and." ".$fromToTerm;
            } else {
              $allAvailableTerms = $fromToTerm;
            }
            ++$finalNumOfTerms;
          }
          if ($start_term_d."".$start_term_m."".$start_term_y != $first_d."".$first_m."".$first_y && ($start_term_d != "" && $start_term_m != "" && $start_term_y != "")) {
            $allAvailableTerms = $allAvailableTerms." ".$wrd_and." ".termFormat($start_term_d, $start_term_m, $start_term_y, $date_d, $date_m, $date_y);
            ++$finalNumOfTerms;
          }
          found($finalNumOfTerms, $allAvailableTerms);
        } else if ($start_term_d != "" && $start_term_m != "" && $start_term_y != "") {
          if ($start_term_d == $first_d && $start_term_m == $first_m && $start_term_y == $first_y) {
            allDaysAvailable();
          } else {
            if ($start_term_y != date("Y")) {
              $sinceDate = $start_term_d.". ".$start_term_m.". ".$start_term_y;
            } else {
              $sinceDate = $start_term_d.". ".$start_term_m;
            }
            allDaysAvailableSince($sinceDate);
          }
        } else {
          notFound();
        }
      } else {
        error("place-not-found");
      }
    } else {
      error("id-n-exist");
    }
  } else {
    error("missing-data");
  }

  function termFormat($start_d, $start_m, $start_y, $end_d, $end_m, $end_y) {
    $this_y = date("Y");
    $start_d = (int)$start_d;
    $start_m = (int)$start_m;
    $end_d = (int)$end_d;
    $end_m = (int)$end_m;
    if ($start_y != $end_y) {
      return $start_d.". ".$start_m.". ".$start_y." - ".$end_d.". ".$end_m.". ".$end_y;
    } else {
      if ($end_y != $this_y) {
        return $start_d.". ".$start_m." - ".$end_d.". ".$end_m.". ".$end_y;
      } else {
        return $start_d.". ".$start_m." - ".$end_d.". ".$end_m;
      }
    }
  }

  function found($termsNum, $term) {
    global $output;
    array_push($output, [
      "type" => "found",
      "num" => $termsNum,
      "date" => $term
    ]);
    returnOutput();
  }

  function allDaysAvailable() {
    global $output;
    array_push($output, [
      "type" => "all-available"
    ]);
    returnOutput();
  }

  function allDaysAvailableSince($since) {
    global $output;
    array_push($output, [
      "type" => "all-available-since",
      "since" => $since
    ]);
    returnOutput();
  }

  function notFound() {
    global $output;
    array_push($output, [
      "type" => "not-found"
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
