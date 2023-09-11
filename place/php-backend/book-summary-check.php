<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-user.php";
  include "book-check-dates.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../uni/code/php-backend/calendar/calc-date.php";
  include "../../uni/code/php-backend/calendar/let-me-sleep.php";
  $output = [];
  $plcId = $_POST['id'];
  $numOfGuests = $_POST['guests'];
  $fromY = $_POST['fromY'];
  $fromM = $_POST['fromM'];
  $fromD = $_POST['fromD'];
  $toY = $_POST['toY'];
  $toM = $_POST['toM'];
  $toD = $_POST['toD'];
  if (
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $plcId)) != "" &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $numOfGuests)) != "" &&
    is_numeric($numOfGuests) &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $fromY)) != "" &&
    is_numeric($fromY) &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $fromM)) != "" &&
    is_numeric($fromM) &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $fromD)) != "" &&
    is_numeric($fromD) &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $toY)) != "" &&
    is_numeric($toY) &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $toM)) != "" &&
    is_numeric($toM) &&
    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $toD)) != "" &&
    is_numeric($toD)
  ) {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId' LIMIT 1");
    if ($sqlIdToBeId->num_rows > 0) {
      $beId = $sqlIdToBeId->fetch_assoc()["beid"];
      $datesCheck = checkDates($beId, $fromY, $fromM, $fromD, "half", $toY, $toM, $toD, "half");
      if ($datesCheck == "good") {
        $sqlPlc = $link->query("SELECT guestNum, currency FROM places WHERE beid='$beId' and status='active' LIMIT 1");
        if ($sqlPlc->num_rows > 0) {
          $rowPlc = $sqlPlc->fetch_assoc();
          if ($rowPlc['guestNum'] >= $numOfGuests && $numOfGuests > 0) {
            $total = addCurrency($rowPlc['currency'], totalPriceCalc($beId, $numOfGuests, $fromY, $fromM, $fromD, "half", $toY, $toM, $toD, "half"));
            $sqlPlcCondBeID = $link->query("SELECT beid FROM placeconditionskey WHERE plcbeid='$beId' LIMIT 1");
            if ($sqlPlcCondBeID->num_rows > 0) {
              $condBeID = $sqlPlcCondBeID->fetch_assoc()['beid'];
              $sqlPlcCondTxt = $link->query("SELECT txt FROM conditionsofstayofthehost WHERE beid='$condBeID' LIMIT 1");
              if ($sqlPlcCondTxt->num_rows > 0) {
                if ($sqlPlcCondTxt->fetch_assoc()['txt'] != "") {
                  $condSts = "show";
                } else {
                  $condSts = "hide";
                }
              } else {
                $condSts = "conditions-txt-not-found";
              }
            } else {
              $condSts = "no-conditions-found";
            }
            if (checkDateAvailability($beId, $fromD, $fromM, $fromY) == "not-reserved") {
              if (letMeSleep($fromY, $fromM, $fromD, "available") == "available") {
                $wholeDayAvailability_from = "not-reserved";
              } else {
                $wholeDayAvailability_from = "reserved";
              }
            } else {
              $wholeDayAvailability_from = "reserved";
            }
            if (checkDateAvailability($beId, $toD, $toM, $toY) == "not-reserved") {
              if (letMeSleep($toY, $toM, $toD, "available") == "available") {
                $wholeDayAvailability_to = "not-reserved";
              } else {
                $wholeDayAvailability_to = "reserved";
              }
            } else {
              $wholeDayAvailability_to = "reserved";
            }
            $today_date = date("Y-m-d H:i:s");
            $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 14:00:00";
            $from_diff = abs(strtotime($today_date) - strtotime($fromDate));
            $from_hours = floor($from_diff / 3600);
            if ($today_date > $fromDate) {
              $from_hours = $from_hours * (-1);
            }
            if ($from_hours < 48) {
              $lessThan48h = "yes";
            } else {
              $lessThan48h = "no";
            }
            if ($sign == "yes") {
              pushLinkToArray($setfirstname." ".$setlastname, $setcontactemail, $setcontactphonenum, $numOfGuests, $fromD.". ".$fromM.". ".$fromY, $wholeDayAvailability_from, $toD.". ".$toM.". ".$toY, $wholeDayAvailability_to, $total, "yes", $condSts, $lessThan48h);
            } else {
              $guest_name = "";
              $guest_email = "";
              $guest_phone = "";
              if (isset($_COOKIE["guest-name"])) {
                $guest_name = $_COOKIE["guest-name"];
              }
              if (isset($_COOKIE["guest-email"])) {
                $guest_email = $_COOKIE["guest-email"];
              }
              if (isset($_COOKIE["guest-phone"])) {
                $guest_phone = $_COOKIE["guest-phone"];
              }
              pushLinkToArray($guest_name, $guest_email, $guest_phone, $numOfGuests, $fromD.". ".$fromM.". ".$fromY, $wholeDayAvailability_from, $toD.". ".$toM.". ".$toY, $wholeDayAvailability_to, $total, "no", $condSts, $lessThan48h);
            }
          } else {
            error("wrong-guests-number");
          }
        } else {
          error("place-n-found");
        }
      } else if ($datesCheck == "dates-same") {
        error("dates-are-same");
      } else if ($datesCheck == "dates-order") {
        error("dates-wrong-order");
      } else if ($datesCheck == "unavailable") {
        error("dates-unavailable");
      } else {
        error("Date availability failned: ".$datesCheck);
      }
    } else {
      error("id-n-exist");
    }
  } else {
    error("missing-data");
  }

  function checkDateAvailability($plcBeId, $d, $m, $y) {
    global $link;
    $sqlBookDate = $link->query("SELECT * FROM bookingdates WHERE plcbeid='$plcBeId' and status='booked' and year='$y' and month='$m' and day='$d'");
    if ($sqlBookDate->num_rows > 0) {
      if ($sqlBookDate->num_rows == 1) {
        $bookingBeID = $sqlBookDate->fetch_assoc()['beid'];
        $sqlBookInfo = $link->query("SELECT * FROM booking WHERE beid='$bookingBeID' and status='booked'");
        if ($sqlBookInfo->num_rows > 0) {
          $rowBookInfo = $sqlBookInfo->fetch_assoc();
          if ($d == $rowBookInfo['fromd'] && $m == $rowBookInfo['fromm'] && $y == $rowBookInfo['fromy']) {
            if ($rowBookInfo['firstday'] == "whole") {
              return "reserved";
            } else {
              return "limited-from";
            }
          }
          if ($d == $rowBookInfo['tod'] && $m == $rowBookInfo['tom'] && $y == $rowBookInfo['toy']) {
            if ($rowBookInfo['lastday'] == "whole") {
              return "reserved";
            } else {
              return "limited-to";
            }
          }
        } else {
          return "reserved";
        }
      } else {
        return "reserved";
      }
    } else {
      return "not-reserved";
    }
  }

  function pushLinkToArray($name, $email, $phone, $guests, $from, $availability_from, $to, $availability_to, $price, $checked, $conditionsOfTheHostSts, $lessThan48h) {
    global $output;
    array_push($output, [
      "type" => "data",
      "name" => $name,
      "email" => $email,
      "phone" => $phone,
      "guests" => $guests,
      "from" => $from,
      "availability_from" => $availability_from,
      "to" => $to,
      "availability_to" => $availability_to,
      "price" => $price,
      "checked" => $checked,
      "conditionsOfTheHost" => $conditionsOfTheHostSts,
      "lessThan48h" => $lessThan48h
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
