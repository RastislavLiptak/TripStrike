<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "place-verification.php";
  include "notify-about-change-in-price.php";
  header('Content-Type: application/json');
  $output = [];
  $equiDoneArr = [];
  $date = date("Y-m-d H:i:s");
  $dateY = date("Y");
  $dateM = date("m");
  $dateD = date("d");
  $readyToReturn = false;
  $summerSeason = [3, 4, 5, 6, 7, 8, 9, 10];
  $winterSeason = [1, 2, 3, 4, 9, 10, 11, 12];
  $operationFromToReadySts = false;
  $url_id = mysqli_real_escape_string($link, $_POST['urlId']);
  $name = mysqli_real_escape_string($link, $_POST['name']);
  $cottId = mysqli_real_escape_string($link, $_POST['id']);
  $desc = mysqli_real_escape_string($link, $_POST['desc']);
  $placeType = mysqli_real_escape_string($link, $_POST['type']);
  $bedNum = mysqli_real_escape_string($link, $_POST['bedNum']);
  $guestNum = mysqli_real_escape_string($link, $_POST['guestNum']);
  $bathNum = mysqli_real_escape_string($link, $_POST['bathNum']);
  $distanceFromTheWater = mysqli_real_escape_string($link, $_POST['distanceFromTheWater']);
  $operation = mysqli_real_escape_string($link, $_POST['operation']);
  $operationSummerFrom = mysqli_real_escape_string($link, $_POST['operationSummerFrom']);
  $operationSummerTo = mysqli_real_escape_string($link, $_POST['operationSummerTo']);
  $operationWinterFrom = mysqli_real_escape_string($link, $_POST['operationWinterFrom']);
  $operationWinterTo = mysqli_real_escape_string($link, $_POST['operationWinterTo']);
  $priceMode = mysqli_real_escape_string($link, $_POST['priceMode']);
  $priceWork = mysqli_real_escape_string($link, $_POST['priceWork']);
  $priceWeek = mysqli_real_escape_string($link, $_POST['priceWeek']);
  $lat = mysqli_real_escape_string($link, $_POST['lat']);
  $lng = mysqli_real_escape_string($link, $_POST['lng']);
  $equipmentArr = json_decode($_POST['equipment']);
  $videosArr = json_decode($_POST['videos']);
  $calendarSyncArr = json_decode($_POST['calendarSync']);
  $conditionsLangShrt = mysqli_real_escape_string($link, $_POST['conditionsLangShrt']);
  $conditionsTxt = str_replace("'", "&apos;", mysqli_real_escape_string($link, $_POST['conditionsTxt']));
  $conditionsTxt = str_replace("\n\\", "\n", $conditionsTxt);
  if ($distanceFromTheWater == "") {
    $distanceFromTheWater = 0;
  }
  if (
    $placeType != "camp" &&
    $placeType != "guesthouse" &&
    $placeType != "hotel" &&
    $placeType != "apartment"
  ) {
    $placeType = "cottage";
  }
  if ($operation != "year-round" && $operation != "summer" && $operation != "winter") {
    $operation = "year-round";
  }
  if ($priceMode != "guests") {
    $priceMode = "nights";
  }
  $placeVerify = placeVerification($url_id);
  if ($placeVerify == "good") {
    $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$url_id' LIMIT 1");
    $beId = $sqlIdToBeId->fetch_assoc()["beid"];
    $sqlOldPlc = $link->query("SELECT * FROM places WHERE beid='$beId' LIMIT 1");
    $oldPlc = $sqlOldPlc->fetch_assoc();
    if (preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $name)) != "") {
      if ($name != $oldPlc['name']) {
        if (save("name", $name)) {
          saveDone("name");
        } else {
          error("Title - sql-failed");
        }
      }
    } else {
      error("Title - empty");
    }
    if ($desc != $oldPlc['description']) {
      if (save("desc", $desc)) {
        saveDone("desc");
      } else {
        error("Description - sql-failed");
      }
    }
    if ($placeType != $oldPlc['type']) {
      if (save("type", $placeType)) {
        saveDone("type");
      } else {
        error("Type of accommodation - sql-failed");
      }
    }
    if (is_numeric($bedNum)) {
      if ($bedNum > 0) {
        if ($bedNum != $oldPlc['bedNum']) {
          if (save("bedNum", $bedNum)) {
            saveDone("beds");
          } else {
            error("Number of bedrooms - sql-failed");
          }
        }
      } else {
        error("Number of bedrooms - number-is-too-low");
      }
    } else {
      error("Number of bedrooms - not-number");
    }
    if (is_numeric($guestNum)) {
      if ($guestNum > 0) {
        if ($guestNum != $oldPlc['guestNum']) {
          if (save("guestNum", $guestNum)) {
            saveDone("guests");
          } else {
            error("Number of guests - sql-failed");
          }
        }
      } else {
        error("Number of guests - number-is-too-low");
      }
    } else {
      error("Number of guests - not-number");
    }
    if (is_numeric($bathNum)) {
      if ($bathNum > 0) {
        if ($bathNum != $oldPlc['bathNum']) {
          if (save("bathNum", $bathNum)) {
            saveDone("bathrooms");
          } else {
            error("Number of bathrooms - sql-failed");
          }
        }
      } else {
        error("Number of bathrooms - number-is-too-low");
      }
    } else {
      error("Number of bathrooms - not-number");
    }
    if (is_numeric($distanceFromTheWater)) {
      if ($distanceFromTheWater != $oldPlc['distanceFromTheWater']) {
        if (save("distanceFromTheWater", $distanceFromTheWater)) {
          saveDone("distanceFromTheWater");
        } else {
          error("Distance from the water - sql-failed");
        }
      }
    } else {
      error("Distance from the water - not-number");
    }
    if ($operation == "summer") {
      $operationFrom = $operationSummerFrom;
      $operationTo = $operationSummerTo;
    } else if ($operation == "winter") {
      $operationFrom = $operationWinterFrom;
      $operationTo = $operationWinterTo;
    } else {
      $operationFrom = 0;
      $operationTo = 0;
    }
    if ($operation != $oldPlc['operation'] || $operationFrom != $oldPlc['operationFrom'] || $operationTo != $oldPlc['operationTo']) {
      $operationSaveDone = false;
      $operationFromSaveDone = false;
      $operationToSaveDone = false;
      if ($operation != $oldPlc['operation']) {
        if (save("operation", $operation)) {
          $operationSaveDone = true;
        } else {
          error("Operation - sql-operation-failed");
        }
      } else {
        $operationSaveDone = true;
      }
      if ($operationSaveDone) {
        if ($operation != "year-round") {
          if ($operation == "summer") {
            if (in_array($operationSummerFrom, $summerSeason)) {
              if (in_array($operationSummerTo, $summerSeason)) {
                if ($operationSummerFrom < $operationSummerTo) {
                  $operationFrom = $operationSummerFrom;
                  $operationTo = $operationSummerTo;
                  $operationFromToReadySts = true;
                } else {
                  error("Operation - wrong-order-summer");
                }
              } else {
                error("Operation - invalid-value-of-summer-to");
              }
            } else {
              error("Operation - invalid-value-of-summer-from");
            }
          } else if ($operation == "winter") {
            if (in_array($operationWinterFrom, $winterSeason)) {
              if (in_array($operationWinterTo, $winterSeason)) {
                if ($operationWinterFrom < 5) {
                  $operationWinterFromTemp = $operationWinterFrom * 100;
                } else {
                  $operationWinterFromTemp = $operationWinterFrom;
                }
                if ($operationWinterTo < 5) {
                  $operationWinterToTemp = $operationWinterTo * 100;
                } else {
                  $operationWinterToTemp = $operationWinterTo;
                }
                if ($operationWinterFromTemp < $operationWinterToTemp) {
                  $operationFrom = $operationWinterFrom;
                  $operationTo = $operationWinterTo;
                  $operationFromToReadySts = true;
                } else {
                  error("Operation - wrong-order-winter");
                }
              } else {
                error("Operation - invalid-value-of-winter-to");
              }
            } else {
              error("Operation - invalid-value-of-winter-from");
            }
          }
        } else {
          $operationFrom = 0;
          $operationTo = 0;
          $operationFromToReadySts = true;
        }
        if ($operationFromToReadySts) {
          if ($operationFrom != $oldPlc['operationFrom']) {
            if (save("operation-season-from", $operationFrom)) {
              $operationFromSaveDone = true;
            } else {
              error("Operation - sql-operation-season-from-failed");
            }
          } else {
            $operationFromSaveDone = true;
          }
          if ($operationFromSaveDone) {
            if ($operationTo != $oldPlc['operationTo']) {
              if (save("operation-season-to", $operationTo)) {
                $operationToSaveDone = true;
              } else {
                error("Operation - sql-operation-season-to-failed");
              }
            } else {
              $operationToSaveDone = true;
            }
          }
        }
      }
      if ($operationSaveDone && $operationFromSaveDone && $operationToSaveDone) {
        saveDone("operation");
      }
    }
    if (is_numeric($lat) || is_numeric($lng)) {
      if ($lat != $oldPlc['lat'] || $lng != $oldPlc['lng']) {
        if (save("lat", $lat) && save("lng", $lng)) {
          saveDone("map");
        } else {
          error("Map - sql-failed");
        }
      }
    } else {
      error("Map - not-number");
    }
    if ($priceMode != $oldPlc['priceMode']) {
      if (save("prcMode", $priceMode)) {
        if ($priceWork == $oldPlc['workDayPrice'] && $priceWeek == $oldPlc['weekDayPrice']) {
          notifyAboutChangeInPrice($beId, "price-mode");
        } else {
          saveDone("price-mode");
        }
      } else {
        error("Price mode - sql-failed");
      }
    }
    if (is_numeric($priceWork)) {
      if ($priceWork > 0) {
        if ($priceWork != $oldPlc['workDayPrice']) {
          if (save("work", $priceWork)) {
            if ($priceWeek == $oldPlc['weekDayPrice']) {
              notifyAboutChangeInPrice($beId, "work-price");
            } else {
              saveDone("work-price");
            }
          } else {
            error("Work price - sql-failed");
          }
        }
      } else {
        error("Work price - number-is-too-low");
      }
    } else {
      error("Work price - not-number");
    }
    if (is_numeric($priceWeek)) {
      if ($priceWeek > 0) {
        if ($priceWeek != $oldPlc['weekDayPrice']) {
          if (save("week", $priceWeek)) {
            notifyAboutChangeInPrice($beId, "week-price");
          } else {
            error("Week price - sql-failed");
          }
        }
      } else {
        error("Week price - number-is-too-low");
      }
    } else {
      error("Week price - not-number");
    }
    if (preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $cottId)) != "") {
      if (ctype_alnum($cottId)) {
        if ($cottId != getFrontendId($beId)) {
          $sqlIDCh = $link->query("SELECT * FROM idlist WHERE id='$cottId' and beid!='$beId'");
          if ($sqlIDCh->num_rows == 0) {
            $sqlID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$beId', '$cottId', '$date', '$dateD', '$dateM', '$dateY')";
            if (mysqli_query($link, $sqlID)) {
              saveDone("id");
            } else {
              error("ID - sql-failed");
            }
          } else {
            error("ID - id-is-taken");
          }
        }
      } else {
        error("ID - not-allowed-characters");
      }
    } else {
      error("ID - empty");
    }
    $equipAddSts = false;
    $sqlOldEquip = $link->query("SELECT name, src FROM placeequipment WHERE beid='$beId'");
    if ($sqlOldEquip->num_rows == sizeof($equipmentArr)) {
      if ($sqlOldEquip->num_rows > 0) {
        while($oldEquip = $sqlOldEquip->fetch_assoc()) {
          $equiCompareDtbAndArrSts = false;
          foreach ($equipmentArr as $checkIE => $checkObjEqui) {
            if ($checkObjEqui->title == $oldEquip['name'] && $checkObjEqui->src == $oldEquip['src']) {
              $equiCompareDtbAndArrSts = true;
            }
          }
          if (!$equiCompareDtbAndArrSts) {
            $equipAddSts = true;
          }
        }
      }
    } else {
      $equipAddSts = true;
    }
    if ($equipAddSts) {
      $sqlDeleteEquip = "DELETE FROM placeequipment WHERE beid='$beId'";
      if (mysqli_query($link, $sqlDeleteEquip)) {
        $equipAddFailedNum = 0;
        foreach ($equipmentArr as $iE => $objEqui) {
          $sqlAddEquip = "INSERT INTO placeequipment (beid, name, src) VALUES('$beId', '$objEqui->title', '$objEqui->src')";
          if (!mysqli_query($link, $sqlAddEquip)) {
            ++$equipAddFailedNum;
          }
        }
        if ($equipAddFailedNum == 0) {
          saveDone("equip");
        } else {
          error("Equipment - sql-failed (Num of failed: ".$equipAddFailedNum.")");
        }
      } else {
        error("Equipment - remove-failed");
      }
    }
    $vidAddSts = false;
    $sqlOldVideos = $link->query("SELECT videoid FROM placevideos WHERE beid='$beId'");
    if ($sqlOldVideos->num_rows == sizeof($videosArr)) {
      if ($sqlOldVideos->num_rows > 0) {
        while($oldVid = $sqlOldVideos->fetch_assoc()) {
          $idCompareDtbAndArrSts = false;
          foreach ($videosArr as $checkIV => $checkObjVid) {
            if ($checkObjVid->id == $oldVid['videoid']) {
              $idCompareDtbAndArrSts = true;
            }
          }
          if (!$idCompareDtbAndArrSts) {
            $vidAddSts = true;
          }
        }
      }
    } else {
      $vidAddSts = true;
    }
    if ($vidAddSts) {
      $sqlDeleteVid = "DELETE FROM placevideos WHERE beid='$beId'";
      if (mysqli_query($link, $sqlDeleteVid)) {
        $vidAddFailedNum = 0;
        foreach ($videosArr as $iV => $objVid) {
          $sqlAddVid = "INSERT INTO placevideos (beid, videoid) VALUES('$beId', '$objVid->id')";
          if (!mysqli_query($link, $sqlAddVid)) {
            ++$vidAddFailedNum;
          }
        }
        if ($vidAddFailedNum == 0) {
          saveDone("videos");
        } else {
          error("Videos - sql-failed (Num of failed: ".$vidAddFailedNum.")");
        }
      } else {
        error("Videos - remove-failed");
      }
    }

    $calendarSyncEmptyURLSts = true;
    foreach ($calendarSyncArr as $emptyCS => $emptyObjCalendarSync) {
      if (preg_replace('/\s+/', '', $emptyObjCalendarSync->url) == "") {
        $calendarSyncEmptyURLSts = false;
      }
    }
    if ($calendarSyncEmptyURLSts) {
      $calendarSyncAddSts = false;
      $sqlOldCalendarSync = $link->query("SELECT code, url FROM placecalendarsync WHERE beid='$beId'");
      if ($sqlOldCalendarSync->num_rows == sizeof($calendarSyncArr)) {
        if ($sqlOldCalendarSync->num_rows > 0) {
          while($oldCalendarSync = $sqlOldCalendarSync->fetch_assoc()) {
            $calendarSyncCompareDtbAndArrSts = false;
            foreach ($calendarSyncArr as $checkCS => $checkObjCalendarSync) {
              if ($checkObjCalendarSync->code == $oldCalendarSync['code'] && $checkObjCalendarSync->url == $oldCalendarSync['url']) {
                $calendarSyncCompareDtbAndArrSts = true;
              }
            }
            if (!$calendarSyncCompareDtbAndArrSts) {
              $calendarSyncAddSts = true;
            }
          }
        }
      } else {
         $calendarSyncAddSts = true;
       }
      if ($calendarSyncAddSts) {
        $sqlDeleteCalendarSync = "DELETE FROM placecalendarsync WHERE beid='$beId'";
        if (mysqli_query($link, $sqlDeleteCalendarSync)) {
          $calendarSyncAddFailedNum = 0;
          foreach ($calendarSyncArr as $cS => $objCalendarSync) {
            $sqlAddCalendarSync = "INSERT INTO placecalendarsync (beid, code, url, error) VALUES('$beId', '$objCalendarSync->code', '$objCalendarSync->url', '')";
            if (!mysqli_query($link, $sqlAddCalendarSync)) {
              ++$calendarSyncAddFailedNum;
            }
          }
          if ($calendarSyncAddFailedNum == 0) {
            saveDone("calendarSync");
          } else {
            error("Calendar sync - sql-failed (Num of failed: ".$calendarSyncAddFailedNum.")");
          }
        } else {
          error("Calendar sync - remove-failed");
        }
      }
    } else {
      error("Calendar sync - empty URL field");
    }
    $conditionsIdReady = false;
    while (!$conditionsIdReady) {
      $newConditionsId = randomHash(11);
      $sqlCondIdCH = $link -> query("SELECT * FROM idlist WHERE beid='$newConditionsId'");
      if ($sqlCondIdCH->num_rows == 0) {
        $conditionsIdReady = true;
      } else {
        $conditionsIdReady = false;
      }
    }
    $conditionsBeIdReady = false;
    while (!$conditionsBeIdReady) {
      $newConditionsBeId = randomHash(64);
      $sqlCondBeIdCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$newConditionsBeId'");
      if ($sqlCondBeIdCH->num_rows == 0) {
        $conditionsBeIdReady = true;
      } else {
        $conditionsBeIdReady = false;
      }
    }
    $plcDate = $oldPlc['fullDate'];
    if (in_array($conditionsLangShrt, $default_langs_arr)) {
      $finalConditionsLangShrt = $conditionsLangShrt;
    } else {
      $sqlCondLangBeId = $link->query("SELECT beid FROM idlist WHERE id='$conditionsLangShrt' LIMIT 1");
      if ($sqlCondLangBeId->num_rows > 0) {
        $condLangBeId = $sqlCondLangBeId->fetch_assoc()['beid'];
        $sqlCondLangShrt = $link->query("SELECT language FROM conditionsofstayofthehost WHERE beid='$condLangBeId' LIMIT 1");
        if ($sqlCondLangShrt->num_rows > 0) {
          $finalConditionsLangShrt = $sqlCondLangShrt->fetch_assoc()['language'];
        } else {
          $finalConditionsLangShrt = "no-data-connected-to-conditions-backend-id";
        }
      } else {
        $finalConditionsLangShrt = "no-language-connected-to-conditions-id";
      }
    }
    $sqlCondBeId = $link->query("SELECT beid FROM placeconditionskey WHERE plcbeid='$beId' LIMIT 1");
    if ($sqlCondBeId->num_rows > 0) {
      $condBeId = $sqlCondBeId->fetch_assoc()['beid'];
      $sqlCondData = $link->query("SELECT language, txt FROM conditionsofstayofthehost WHERE beid='$condBeId' LIMIT 1");
      if ($sqlCondData->num_rows > 0) {
        $condData = $sqlCondData->fetch_assoc();
        if (in_array($finalConditionsLangShrt, $default_langs_arr)) {
          if ($finalConditionsLangShrt != $condData['language'] || $conditionsTxt != mysqli_real_escape_string($link, $condData['txt'])) {
            $sqlConditionsUpdate = "UPDATE conditionsofstayofthehost SET language='$finalConditionsLangShrt', txt='$conditionsTxt' WHERE beid='$condBeId'";
            if (mysqli_query($link, $sqlConditionsUpdate)) {
              saveDone("rules");
            } else {
              error("Conditions - sql-failed");
            }
          }
        } else {
          error("Conditions - ".$finalConditionsLangShrt);
        }
      } else {
        $oldIDExist = true;
        $sqlCheckCondID = $link->query("SELECT * FROM idlist WHERE beid='$condBeId' LIMIT 1");
        if ($sqlCheckCondID->num_rows == 0) {
          $sqlConditionsID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$condBeId', '$newConditionsId', '$date', '$dateD', '$dateM', '$dateY')";
          if (!mysqli_query($link, $sqlConditionsID)) {
            $oldIDExist = false;
          }
        }
        if ($oldIDExist) {
          $sqlConditionsInsert = "INSERT INTO conditionsofstayofthehost(beid, language, txt, fullDate) VALUES('$condBeId', '$finalConditionsLangShrt', '$conditionsTxt', '$plcDate')";
          if (mysqli_query($link, $sqlConditionsInsert)) {
            saveDone("rules");
          } else {
            error("Conditions - insert-conditionsofstayofthehost-insert-failed-2");
          }
        } else {
          error("Conditions - new-condition-id-save-failed-2");
        }
      }
    } else {
      if (in_array($finalConditionsLangShrt, $default_langs_arr)) {
        $conditionsBackendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
        $sqlConditionsBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$newConditionsBeId', '$conditionsBackendIDNum', 'conditions')";
        if (mysqli_query($link, $sqlConditionsBeID)) {
          $sqlConditionsID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$newConditionsBeId', '$newConditionsId', '$date', '$dateD', '$dateM', '$dateY')";
          if (mysqli_query($link, $sqlConditionsID)) {
            $sqlConditionsInsert = "INSERT INTO conditionsofstayofthehost(beid, language, txt, fullDate) VALUES('$newConditionsBeId', '$finalConditionsLangShrt', '$conditionsTxt', '$plcDate')";
            if (mysqli_query($link, $sqlConditionsInsert)) {
              $sqlConditionsKey = "INSERT INTO placeconditionskey(beid, plcbeid, usrbeid) VALUES('$newConditionsBeId', '$beId', '$usrBeId')";
              if (mysqli_query($link, $sqlConditionsKey)) {
                saveDone("rules");
              } else {
                error("Conditions - insert-placeconditionskey-insert-failed");
              }
            } else {
              error("Conditions - insert-conditionsofstayofthehost-insert-failed-1");
            }
          } else {
            error("Conditions - new-condition-id-save-failed-1");
          }
        } else {
          error("Conditions - new-condition-backend-id-save-failed");
        }
      } else {
        error("Conditions - ".$finalConditionsLangShrt);
      }
    }
    if (notifyAboutChangeInPriceCheck()) {
      returnOutput();
    } else {
      $readyToReturn = true;
    }
  } else {
    error($placeVerify);
  }

  function mailDone($sts, $mailType) {
    if ($mailType == "pricing-of-the-cottage-changed-price-mode" || $mailType == "pricing-of-the-cottage-changed-work-price" || $mailType == "pricing-of-the-cottage-changed-week-price") {
      if ($sts == "done") {
        if ($mailType == "pricing-of-the-cottage-changed-price-mode") {
          notifyAboutChangeInPriceDone("price-mode");
        } else if ($mailType == "pricing-of-the-cottage-changed-work-price") {
          notifyAboutChangeInPriceDone("work-price");
        } else if ($mailType == "pricing-of-the-cottage-changed-week-price") {
          notifyAboutChangeInPriceDone("week-price");
        }
      } else {
        if ($mailType == "pricing-of-the-cottage-changed-price-mode") {
          notifyAboutChangeInPriceError("notify about change in price - mail error (".$sts.")", "price-mode");
        } else if ($mailType == "pricing-of-the-cottage-changed-work-price") {
          notifyAboutChangeInPriceError("notify about change in price - mail error (".$sts.")", "work-price");
        } else if ($mailType == "pricing-of-the-cottage-changed-week-price") {
          notifyAboutChangeInPriceError("notify about change in price - mail error (".$sts.")", "week-price");
        }
      }
    }
  }

  function readyToReturnCheck() {
    global $readyToReturn;
    if ($readyToReturn) {
      returnOutput();
    }
  }

  function save($tsk, $save) {
    global $link, $beId;
    if ($tsk == "name") {
      $sql = "UPDATE places SET name='$save' WHERE beid='$beId'";
    } else if ($tsk == "desc") {
      $sql = "UPDATE places SET description='$save' WHERE beid='$beId'";
    } else if ($tsk == "type") {
      $sql = "UPDATE places SET type='$save' WHERE beid='$beId'";
    } else if ($tsk == "bedNum") {
      $sql = "UPDATE places SET bedNum='$save' WHERE beid='$beId'";
    } else if ($tsk == "guestNum") {
      $sql = "UPDATE places SET guestNum='$save' WHERE beid='$beId'";
    } else if ($tsk == "bathNum") {
      $sql = "UPDATE places SET bathNum='$save' WHERE beid='$beId'";
    } else if ($tsk == "distanceFromTheWater") {
      $sql = "UPDATE places SET distanceFromTheWater='$save' WHERE beid='$beId'";
    } else if ($tsk == "operation") {
      $sql = "UPDATE places SET operation='$save' WHERE beid='$beId'";
    } else if ($tsk == "operation-season-from") {
      $sql = "UPDATE places SET operationFrom='$save' WHERE beid='$beId'";
    } else if ($tsk == "operation-season-to") {
      $sql = "UPDATE places SET operationTo='$save' WHERE beid='$beId'";
    } else if ($tsk == "lat") {
      $sql = "UPDATE places SET lat='$save' WHERE beid='$beId'";
    } else if ($tsk == "lng") {
      $sql = "UPDATE places SET lng='$save' WHERE beid='$beId'";
    } else if ($tsk == "prcMode") {
      $sql = "UPDATE places SET priceMode='$save' WHERE beid='$beId'";
    } else if ($tsk == "work") {
      $sql = "UPDATE places SET workDayPrice='$save' WHERE beid='$beId'";
    } else if ($tsk == "week") {
      $sql = "UPDATE places SET weekDayPrice='$save' WHERE beid='$beId'";
    }
    if (mysqli_query($link, $sql)) {
      return true;
    } else {
      return false;
    }
  }

  function error($msg) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
  }

  function saveDone($sect) {
    global $output;
    array_push($output, [
      "type" => "done",
      "section" => $sect
    ]);
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
