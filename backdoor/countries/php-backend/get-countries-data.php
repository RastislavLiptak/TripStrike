<?php
  include "../../../uni/code/php-backend/data.php";
  include "../../../uni/code/php-backend/get-frontend-id.php";
  include "../../../uni/dictionary/lang-select.php";
  include "../../../uni/code/php-backend/get-user.php";
  include "../../../uni/code/php-backend/calendar/calc-date.php";
  include "../../../uni/code/php-backend/statistics-calc.php";
  include "../../uni/code/php-backend/check-sign-in.php";
  header('Content-Type: application/json');
  $output = [];
  $chartData = [];
  $listOfValues = [];
  $listForStatistics = [];
  $type = mysqli_real_escape_string($link, $_POST['type']);
  $period = mysqli_real_escape_string($link, $_POST['period']);
  $backDoorCheckSignInSts = backDoorCheckSignIn();
  if ($backDoorCheckSignInSts == "good") {
    $dateY = date("Y");
    $dateM = date("m") *1;
    $dateD = date("d");
    if ($period == "today") {
      $periodFilter = "fromy='".$dateY."' and fromm='".$dateM."' and fromd='".$dateD."'";
    } else if ($period == "yesterday") {
      $newD = calcPreviousDay($dateD, $dateM, $dateY, 1);
      $newM = calcPreviousMonthByDays($dateD, $dateM, $dateY, 1);
      $newY = calcPreviousYearByDays($dateD, $dateM, $dateY, 1);
      $periodFilter = "fromy='".$newY."' and fromm='".$newM."' and fromd='".$newD."'";
    } else if ($period == "week") {
      $fromD = calcPreviousDay($dateD, $dateM, $dateY, 7);
      $fromM = calcPreviousMonthByDays($dateD, $dateM, $dateY, 7);
      $fromY = calcPreviousYearByDays($dateD, $dateM, $dateY, 7);
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-".$dateM."-".$dateD." 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "two-weeks") {
      $fromD = calcPreviousDay($dateD, $dateM, $dateY, 14);
      $fromM = calcPreviousMonthByDays($dateD, $dateM, $dateY, 14);
      $fromY = calcPreviousYearByDays($dateD, $dateM, $dateY, 14);
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-".$dateM."-".$dateD." 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "month") {
      $fromD = 1;
      $fromM = $dateM;
      $fromY = $dateY;
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-".$dateM."-".cal_days_in_month(CAL_GREGORIAN, $dateM, $dateY)." 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "three-months") {
      $fromD = 1;
      $fromM = calcPreviousMonth($dateM, 3);
      $fromY = calcPreviousYearByMonth($dateM, $dateY, 3);
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-".$dateM."-".cal_days_in_month(CAL_GREGORIAN, $dateM, $dateY)." 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "six-months") {
      $fromD = 1;
      $fromM = calcPreviousMonth($dateM, 6);
      $fromY = calcPreviousYearByMonth($dateM, $dateY, 6);
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-".$dateM."-".cal_days_in_month(CAL_GREGORIAN, $dateM, $dateY)." 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "year") {
      $fromD = 1;
      $fromM = 1;
      $fromY = $dateY;
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-12-31 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "two-years") {
      $fromD = 1;
      $fromM = 1;
      $fromY = $dateY -2;
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-12-31 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "five-years") {
      $fromD = 1;
      $fromM = 1;
      $fromY = $dateY -5;
      $fromDate = $fromY."-".$fromM."-".$fromD." 00:00:00";
      $toDate = $dateY."-12-31 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    } else if ($period == "all") {
      $fromDate = "00-00-00 00:00:00";
      $toDate = $dateY."-12-31 23:59:59";
      $periodFilter = "(fromdate BETWEEN '".$fromDate."' AND '".$toDate."')";
    }
    $allTrafficDone = false;
    while (!$allTrafficDone) {
      $sqlTrafficLog = $linkBD->query("SELECT * FROM usertrafficlog WHERE $periodFilter and countrycode NOT IN ('".implode("', '", $listOfValues)."') LIMIT 1");
      if ($sqlTrafficLog->num_rows > 0) {
        array_push($listOfValues, $sqlTrafficLog->fetch_assoc()['countrycode']);
      } else {
        $allTrafficDone = true;
      }
    }
    $totalNum = 0;
    foreach ($listOfValues as $countryCode) {
      $verifiedNum = 0;
      $unverifiedNum = 0;
      $sqlCountryCode = $linkBD->query("SELECT * FROM usertrafficlog WHERE $periodFilter and countrycode='$countryCode'");
      if ($sqlCountryCode->num_rows > 0) {
        while($rowCC = $sqlCountryCode->fetch_assoc()) {
          if ($rowCC['verified'] == "1") {
            ++$verifiedNum;
          } else {
            ++$unverifiedNum;
          }
        }
      }
      if ($type == "verified") {
        $primValue = $verifiedNum;
        $secValue = 0;
      } else if ($type == "unverified") {
        $primValue = $unverifiedNum;
        $secValue = 0;
      } else {
        $primValue = $verifiedNum + $unverifiedNum;
        $secValue = $unverifiedNum;
      }
      if ($countryCode != "unknown") {
        $countryName = locale_get_display_region('sl-Latn-'.$countryCode.'-nedis', 'en');
      } else {
        $countryName = "<i>(".$countryCode.")</i>";
      }
      if ($primValue > 0) {
        array_push($chartData, [
          "type" => "data",
          "title" => $countryName,
          "link" => "#",
          "perc" => "0",
          "prim-value" => $primValue,
          "sec-value" => $secValue
        ]);
        $totalNum = $totalNum + $primValue;
        array_push($listForStatistics, $primValue);
      }
    }
    for ($p=0; $p < sizeof($chartData); $p++) {
      $chartData[$p]['perc'] = round($chartData[$p]['prim-value'] * 100 / $totalNum, 1);
    }
    array_push($output, [
      "type" => "data",
      "data" => sortDataInArray($chartData)
    ]);
    array_push($output, [
      "type" => "statistics",
      "average" => statisticsCalc("average", $listForStatistics),
      "median" => statisticsCalc("median", $listForStatistics),
      "minimum" => statisticsCalc("minimum", $listForStatistics),
      "maximum" => statisticsCalc("maximum", $listForStatistics)
    ]);
    if ($period == "today") {
      $outputDate = $dateD.".".$dateM.".".$dateY;
    } else if ($period == "yesterday") {
      $outputDate = $newD.".".$newM.".".$newY;
    } else if ($period == "all") {
      $outputDate = $wrd_all;
    } else {
      $outputDate = $fromD.".".$fromM.".".$fromY." - ".$dateD.".".$dateM.".".$dateY;
    }
    array_push($output, [
      "type" => "dates",
      "data" => $outputDate
    ]);
    returnOutput();
  } else {
    error($backDoorCheckSignInSts);
  }

  function sortDataInArray($arr) {
    $sortDone = false;
    $tempValue = "";
    while (!$sortDone) {
      $changeDone = false;
      if (sizeof($arr) > 1) {
        for ($s=1; $s < sizeof($arr); $s++) {
          if ($arr[$s]['prim-value'] > $arr[$s -1]['prim-value']) {
            $tempValue = $arr[$s];
            $arr[$s] = $arr[$s -1];
            $arr[$s -1] = $tempValue;
            $changeDone = true;
          }
        }
      }
      if (!$changeDone) {
        $sortDone = true;
      }
    }
    return $arr;
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
