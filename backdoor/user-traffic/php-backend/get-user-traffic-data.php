<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/code/php-backend/get-data-from-date.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../../uni/code/php-backend/calendar/calc-date.php";
  include "../../../uni/code/php-backend/statistics-calc.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  header('Content-Type: application/json');
  $output = [];
  $chartData = [];
  $chartValue = [];
  $listOfValues = [];
  $listOfValuesSumm = [];
  $numOfRecords = 0;
  $type = mysqli_real_escape_string($link, $_POST['type']);
  $period = mysqli_real_escape_string($link, $_POST['period']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $firstY = 0;
    $firstM = 0;
    $firstD = 0;
    $sqlFirstRecord = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' ORDER BY fromdate ASC");
    if ($sqlFirstRecord->num_rows > 0) {
      $rowFirstRecord = $sqlFirstRecord->fetch_assoc();
      $firstY = $rowFirstRecord['fromy'];
      $firstM = $rowFirstRecord['fromm'];
      $firstD = $rowFirstRecord['fromd'];
    }
    $dateY = date("Y");
    $dateM = date("m") *1;
    $dateD = date("d");
    for ($dt=0; $dt < 11; $dt++) {
      $beforeFirstRecord = false;
      if ($period == "months") {
        if (strtotime($firstY."-".$firstM."-01") > strtotime($dateY."-".$dateM."-01")) {
          $beforeFirstRecord = true;
        }
      } else if ($period == "years") {
        if ($firstY > $dateY) {
          $beforeFirstRecord = true;
        }
      } else {
        if (strtotime($firstY."-".$firstM."-".$firstD) > strtotime($dateY."-".$dateM."-".$dateD)) {
          $beforeFirstRecord = true;
        }
      }
      $chartValue = [];
      if ($type == "comparison-(not)-signed-in" || $type == "signed-in" || $type == "not-signed-in") {
        if ($period == "months") {
          $sqlTrafficLogSignedIn = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and fromm='$dateM' and registered='1'");
          $sqlTrafficLogNotSignedIn = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and fromm='$dateM' and registered='0'");
        } else if ($period == "years") {
          $sqlTrafficLogSignedIn = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and registered='1'");
          $sqlTrafficLogNotSignedIn = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and registered='0'");
        } else {
          $sqlTrafficLogSignedIn = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and fromm='$dateM' and fromd='$dateD' and registered='1'");
          $sqlTrafficLogNotSignedIn = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and fromm='$dateM' and fromd='$dateD' and registered='0'");
        }
        if ($type == "comparison-(not)-signed-in") {
          array_push($chartValue, [
            "status" => "signed-in",
            "title" => $wrd_signedIn,
            "value" => $sqlTrafficLogSignedIn->num_rows
          ]);
          array_push($chartValue, [
            "status" => "not-signed-in",
            "title" => $wrd_notSignedIn,
            "value" => $sqlTrafficLogNotSignedIn->num_rows
          ]);
          if ($dt != 0) {
            if (!$beforeFirstRecord) {
              if ($numOfRecords <= 7) {
                array_push($listOfValues, $sqlTrafficLogSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValues, $sqlTrafficLogNotSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValuesSumm, $sqlTrafficLogSignedIn->num_rows + $sqlTrafficLogNotSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
              }
            }
          }
        } else if ($type == "signed-in") {
          array_push($chartValue, [
            "status" => "signed-in",
            "title" => $wrd_signedIn,
            "value" => $sqlTrafficLogSignedIn->num_rows
          ]);
          if ($dt != 0) {
            if (!$beforeFirstRecord) {
              if ($numOfRecords <= 7) {
                array_push($listOfValues, $sqlTrafficLogSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValuesSumm, $sqlTrafficLogSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
              }
            }
          }
        } else if ($type == "not-signed-in") {
          array_push($chartValue, [
            "status" => "not-signed-in",
            "title" => $wrd_notSignedIn,
            "value" => $sqlTrafficLogNotSignedIn->num_rows
          ]);
          if ($dt != 0) {
            if (!$beforeFirstRecord) {
              if ($numOfRecords <= 7) {
                array_push($listOfValues, $sqlTrafficLogNotSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValuesSumm, $sqlTrafficLogNotSignedIn->num_rows + 0.00000000000000000000000000000000000000000000000001);
              }
            }
          }
        }
      } else if ($type == "comparison-(un)-verified" || $type == "verified" || $type == "unverified") {
        if ($period == "months") {
          $sqlTrafficLogVerified = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and fromm='$dateM'");
          $sqlTrafficLogUnverified = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='0' and fromy='$dateY' and fromm='$dateM'");
        } else if ($period == "years") {
          $sqlTrafficLogVerified = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY'");
          $sqlTrafficLogUnverified = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='0' and fromy='$dateY'");
        } else {
          $sqlTrafficLogVerified = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='1' and fromy='$dateY' and fromm='$dateM' and fromd='$dateD'");
          $sqlTrafficLogUnverified = $linkBD->query("SELECT * FROM usertrafficlog WHERE verified='0' and fromy='$dateY' and fromm='$dateM' and fromd='$dateD'");
        }
        if ($type == "comparison-(un)-verified") {
          array_push($chartValue, [
            "status" => "verified",
            "title" => $wrd_verified,
            "value" => $sqlTrafficLogVerified->num_rows
          ]);
          array_push($chartValue, [
            "status" => "unverified",
            "title" => $wrd_unverified,
            "value" => $sqlTrafficLogUnverified->num_rows
          ]);
          if ($dt != 0) {
            if (!$beforeFirstRecord) {
              if ($numOfRecords <= 7) {
                array_push($listOfValues, $sqlTrafficLogVerified->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValues, $sqlTrafficLogUnverified->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValuesSumm, $sqlTrafficLogVerified->num_rows + $sqlTrafficLogUnverified->num_rows + 0.00000000000000000000000000000000000000000000000001);
              }
            }
          }
        } else if ($type == "verified") {
          array_push($chartValue, [
            "status" => "verified",
            "title" => $wrd_verified,
            "value" => $sqlTrafficLogVerified->num_rows
          ]);
          if ($dt != 0) {
            if (!$beforeFirstRecord) {
              if ($numOfRecords <= 7) {
                array_push($listOfValues, $sqlTrafficLogVerified->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValuesSumm, $sqlTrafficLogVerified->num_rows + 0.00000000000000000000000000000000000000000000000001);
              }
            }
          }
        } else if ($type == "unverified") {
          array_push($chartValue, [
            "status" => "unverified",
            "title" => $wrd_unverified,
            "value" => $sqlTrafficLogUnverified->num_rows
          ]);
          if ($dt != 0) {
            if (!$beforeFirstRecord) {
              if ($numOfRecords <= 7) {
                array_push($listOfValues, $sqlTrafficLogUnverified->num_rows + 0.00000000000000000000000000000000000000000000000001);
                array_push($listOfValuesSumm, $sqlTrafficLogUnverified->num_rows + 0.00000000000000000000000000000000000000000000000001);
              }
            }
          }
        }
      } else {
        if ($period == "months") {
          $sqlTrafficLogSummary = $linkBD->query("SELECT * FROM usertrafficlog WHERE fromy='$dateY' and fromm='$dateM'");
        } else if ($period == "years") {
          $sqlTrafficLogSummary = $linkBD->query("SELECT * FROM usertrafficlog WHERE fromy='$dateY'");
        } else {
          $sqlTrafficLogSummary = $linkBD->query("SELECT * FROM usertrafficlog WHERE fromy='$dateY' and fromm='$dateM' and fromd='$dateD'");
        }
        array_push($chartValue, [
          "status" => "summary",
          "title" => $wrd_summary,
          "value" => $sqlTrafficLogSummary->num_rows
        ]);
        if ($dt != 0) {
          if (!$beforeFirstRecord) {
            if ($numOfRecords <= 7) {
              array_push($listOfValues, $sqlTrafficLogSummary->num_rows + 0.00000000000000000000000000000000000000000000000001);
              array_push($listOfValuesSumm, $sqlTrafficLogSummary->num_rows + 0.00000000000000000000000000000000000000000000000001);
            }
          }
        }
      }
      if ($period == "months") {
        $columnName = monthNumToText($dateM)." ".$dateY;
      } else if ($period == "years") {
        $columnName = $dateY;
      } else {
        $columnName = $dateD.".".$dateM.".".$dateY;
      }
      array_push($chartData, [
        "columnName" => $columnName,
        "values" => $chartValue
      ]);
      $tempD = $dateD;
      $tempM = $dateM;
      $tempY = $dateY;
      if ($period == "months") {
        $dateM = calcPreviousMonth($tempM, 1);
        $dateY = calcPreviousYearByMonth($tempM, $tempY, 1);
      } else if ($period == "years") {
        $dateY = $tempY -1;
      } else {
        $dateD = calcPreviousDay($tempD, $tempM, $tempY, 1);
        $dateM = calcPreviousMonthByDays($tempD, $tempM, $tempY, 1);
        $dateY = calcPreviousYearByDays($tempD, $tempM, $tempY, 1);
      }
      ++$numOfRecords;
    }
    array_push($output, [
      "type" => "data",
      "data" => $chartData
    ]);
    $listOfValues = array_reverse($listOfValues);
    $listOfValuesSumm = array_reverse($listOfValuesSumm);
    array_push($output, [
      "type" => "statistics",
      "average" => statisticsCalc("average", $listOfValues),
      "median" => statisticsCalc("median", $listOfValues),
      "coefficient-of-variation" => statisticsCalc("coefficient-of-variation", $listOfValues),
      "growth-rate" => statisticsCalc("growth-rate", $listOfValuesSumm),
      "minimum" => statisticsCalc("minimum", $listOfValues),
      "maximum" => statisticsCalc("maximum", $listOfValues),
      "dispersion" => statisticsCalc("dispersion", $listOfValues),
      "standard-deviation" => statisticsCalc("standard-deviation", $listOfValues)
    ]);
    returnOutput();
  } else {
    error($backDoorCheckSignInSts);
  }

  function monthNumToText($dateM) {
    global $wrd_january, $wrd_february, $wrd_march, $wrd_april, $wrd_may, $wrd_june, $wrd_july, $wrd_august, $wrd_september, $wrd_october, $wrd_november, $wrd_december;
    if ($dateM == "1") {
      return $wrd_january;
    } else if ($dateM == "2") {
      return $wrd_february;
    } else if ($dateM == "3") {
      return $wrd_march;
    } else if ($dateM == "4") {
      return $wrd_april;
    } else if ($dateM == "5") {
      return $wrd_may;
    } else if ($dateM == "6") {
      return $wrd_june;
    } else if ($dateM == "7") {
      return $wrd_july;
    } else if ($dateM == "8") {
      return $wrd_august;
    } else if ($dateM == "9") {
      return $wrd_september;
    } else if ($dateM == "10") {
      return $wrd_october;
    } else if ($dateM == "11") {
      return $wrd_november;
    } else if ($dateM == "12") {
      return $wrd_december;
    }
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
