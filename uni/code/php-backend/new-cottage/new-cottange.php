<?php
  include "../data.php";
  include "../random-hash-maker.php";
  include "../get-frontend-id.php";
  include "../../../dictionary/lang-select.php";
  include "../get-user.php";
  include "../img-crop/place-img-convert.php";
  header('Content-Type: application/json');
  ini_set('max_execution_time', '-1');
  ini_set('memory_limit', '-1');
  $output = [];
  $equiArrMax = 0;
  $photoArrMax = 0;
  $videosArrMax = 0;
  $calendarSyncArrMax = 0;
  $equipDoneNum = 0;
  $photoDoneNum = 0;
  $videosDoneNum = 0;
  $calendarSyncDoneNum = 0;
  $multipleErrorTxt = "";
  $checkIfDoneReady = true;
  $currency = $default_currency_arr[0];
  $operationFromToReadyErrorTxt = "";
  $summerSeason = [3, 4, 5, 6, 7, 8, 9, 10];
  $winterSeason = [1, 2, 3, 4, 9, 10, 11, 12];
  if ($sign == "yes") {
    if ($bnft_add_cottage == "good") {
      if ($iban != "" && $iban != "-") {
        $name = mysqli_real_escape_string($link, $_POST['name']);
        $cottId = mysqli_real_escape_string($link, $_POST['id']);
        $desc = mysqli_real_escape_string($link, $_POST['desc']);
        $placeType = mysqli_real_escape_string($link, $_POST['type']);
        $bedNum = mysqli_real_escape_string($link, $_POST['bedNum']);
        $guestNum = mysqli_real_escape_string($link, $_POST['guestNum']);
        $bathNum = mysqli_real_escape_string($link, $_POST['bathNum']);
        $distanceFromTheWater = mysqli_real_escape_string($link, $_POST['distanceFromTheWater']);
        $conditionsLang = mysqli_real_escape_string($link, $_POST['conditionsLang']);
        $conditions = mysqli_real_escape_string($link, $_POST['conditions']);
        $priceMode = mysqli_real_escape_string($link, $_POST['priceMode']);
        $priceWork = mysqli_real_escape_string($link, $_POST['priceWork']);
        $priceWeek = mysqli_real_escape_string($link, $_POST['priceWeek']);
        $lat = mysqli_real_escape_string($link, $_POST['lat']);
        $lng = mysqli_real_escape_string($link, $_POST['lng']);
        $operation = mysqli_real_escape_string($link, $_POST['operation']);
        $operationSummerFrom = mysqli_real_escape_string($link, $_POST['operationSummerFrom']);
        $operationSummerTo = mysqli_real_escape_string($link, $_POST['operationSummerTo']);
        $operationWinterFrom = mysqli_real_escape_string($link, $_POST['operationWinterFrom']);
        $operationWinterTo = mysqli_real_escape_string($link, $_POST['operationWinterTo']);
        $equipmentArr = json_decode($_POST['equipment']);
        $imgsArr = json_decode($_POST['imgs']);
        $videosArr = json_decode($_POST['videos']);
        $calendarSyncArr = json_decode($_POST['calendarSync']);
        $priceWork = number_format((float)$priceWork, 2, '.', '') + 0;
        $priceWeek = number_format((float)$priceWeek, 2, '.', '') + 0;
        if (trim(preg_replace('/\s+/', '', $distanceFromTheWater)) == "") {
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
        if (
          $operation != "year-round" &&
          $operation != "summer" &&
          $operation != "winter"
        ) {
          $operation = "year-round";
        }
        if ($priceMode != "guests") {
          $priceMode = "nights";
        }
        if (trim(preg_replace('/\s+/', '', $name)) != "") {
          if (trim(preg_replace('/\s+/', '', $cottId)) != "") {
            if (ctype_alnum($cottId)) {
              $sqlIDCh = $link->query("SELECT * FROM idlist WHERE id='$cottId'");
              if ($sqlIDCh->num_rows == 0) {
                if (trim(preg_replace('/\s+/', '', $bedNum)) != "") {
                  if (is_numeric($bedNum)) {
                    if ($bedNum > 0) {
                      if (trim(preg_replace('/\s+/', '', $guestNum)) != "") {
                        if (is_numeric($guestNum)) {
                          if ($guestNum > 0) {
                            if (trim(preg_replace('/\s+/', '', $bathNum)) != "") {
                              if (is_numeric($bathNum)) {
                                if ($bathNum > 0) {
                                  if (is_numeric($distanceFromTheWater)) {
                                    $calendarSyncEmptyURLSts = true;
                                    foreach ($calendarSyncArr as $emptyCS => $emptyObjCalendarSync) {
                                      if (preg_replace('/\s+/', '', $emptyObjCalendarSync->url) == "") {
                                        $calendarSyncEmptyURLSts = false;
                                      }
                                    }
                                    if ($calendarSyncEmptyURLSts) {
                                      if (trim(preg_replace('/\s+/', '', $priceWork)) != "") {
                                        if (trim(preg_replace('/\s+/', '', $priceWeek)) != "") {
                                          if (is_numeric($priceWork)) {
                                            $priceWorkValid = true;
                                          } else {
                                            $priceWorkValid = false;
                                          }
                                          if (is_numeric($priceWeek)) {
                                            $priceWeekValid = true;
                                          } else {
                                            $priceWeekValid = false;
                                          }
                                          if ($priceWorkValid && $priceWeekValid) {
                                            if ($priceWork > 0) {
                                              if ($priceWeek > 0) {
                                                if ($priceWork < 100000) {
                                                  if ($priceWeek < 100000) {
                                                    if (!is_numeric($lat) || !is_numeric($lng)) {
                                                      multipleErrors("Failed to set places coordinates. Latitude or longitude is not in the correct format");
                                                      $lat = 0;
                                                      $lng = 0;
                                                    }
                                                    if ($operation != "year-round") {
                                                      if ($operation == "summer") {
                                                        if (in_array($operationSummerFrom, $summerSeason)) {
                                                          if (in_array($operationSummerTo, $summerSeason)) {
                                                            if ($operationSummerFrom < $operationSummerTo) {
                                                              $operationFrom = $operationSummerFrom;
                                                              $operationTo = $operationSummerTo;
                                                            } else {
                                                              $operationFromToReadyErrorTxt = "Wrong order of months (summer)";
                                                            }
                                                          } else {
                                                            $operationFromToReadyErrorTxt = "Invalid value of summer-to select";
                                                          }
                                                        } else {
                                                          $operationFromToReadyErrorTxt = "Invalid value of summer-from select";
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
                                                            } else {
                                                              $operationFromToReadyErrorTxt = "Wrong order of months (winter)";
                                                            }
                                                          } else {
                                                            $operationFromToReadyErrorTxt = "Invalid value of winter-to select";
                                                          }
                                                        } else {
                                                          $operationFromToReadyErrorTxt = "Invalid value of winter-from select";
                                                        }
                                                      }
                                                    } else {
                                                      $operationFrom = 0;
                                                      $operationTo = 0;
                                                    }
                                                    if ($operationFromToReadyErrorTxt == "") {
                                                      $date = date("Y-m-d H:i:s");
                                                      $dateY = date("Y");
                                                      $dateM = date("m");
                                                      $dateD = date("d");
                                                      $beIdReady = false;
                                                      while (!$beIdReady) {
                                                        $beId = randomHash(64);
                                                        $sqlCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$beId'");
                                                        if ($sqlCH->num_rows == 0) {
                                                          $beIdReady = true;
                                                        } else {
                                                          $beIdReady = false;
                                                        }
                                                      }
                                                      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
                                                      $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$beId', '$backendIDNum', 'place')";
                                                      if (mysqli_query($link, $sqlBeID)) {
                                                        $sql = "INSERT INTO places(beid, usrbeid, status, type, name, description, bedNum, guestNum, bathNum, distanceFromTheWater, operation, operationFrom, operationTo, workDayPrice, weekDayPrice, priceMode, currency, lat, lng, fullDate, dateD, dateM, dateY) VALUES('$beId', '$usrBeId', 'ready', '$placeType', '$name', '$desc', '$bedNum', '$guestNum', '$bathNum', '$distanceFromTheWater', '$operation', '$operationFrom', '$operationTo', '$priceWork', '$priceWeek', '$priceMode', '$currency', '$lat', '$lng', '$date', '$dateD', '$dateM', '$dateY')";
                                                        if (mysqli_query($link, $sql)) {
                                                          $sqlID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$beId', '$cottId', '$date', '$dateD', '$dateM', '$dateY')";
                                                          if (mysqli_query($link, $sqlID)) {
                                                            $conditionsLang = conditionsManager($conditionsLang, $conditions);
                                                            $conditions = str_replace("'", "&apos;", $conditions);
                                                            $conditionsBeIdReady = false;
                                                            while (!$conditionsBeIdReady) {
                                                              $ruleBeId = randomHash(64);
                                                              $sqlRuleCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$ruleBeId'");
                                                              if ($sqlRuleCH->num_rows == 0) {
                                                                $conditionsBeIdReady = true;
                                                              } else {
                                                                $conditionsBeIdReady = false;
                                                              }
                                                            }
                                                            $conditionsIdReady = false;
                                                            while (!$conditionsIdReady) {
                                                              $ruleId = randomHash(11);
                                                              $sqlRuleIdCH = $link -> query("SELECT * FROM idlist WHERE id='$ruleId'");
                                                              if ($sqlRuleIdCH->num_rows == 0) {
                                                                $conditionsIdReady = true;
                                                              } else {
                                                                $conditionsIdReady = false;
                                                              }
                                                            }
                                                            ++$backendIDNum;
                                                            $sqlRuleBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$ruleBeId', '$backendIDNum', 'conditions')";
                                                            if (mysqli_query($link, $sqlRuleBeID)) {
                                                              $sqlRuleID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$ruleBeId', '$ruleId', '$date', '$dateD', '$dateM', '$dateY')";
                                                              if (mysqli_query($link, $sqlRuleID)) {
                                                                $sqlRuleTxt = "INSERT INTO conditionsofstayofthehost(beid, language, txt, fullDate) VALUES('$ruleBeId', '$conditionsLang', '$conditions', '$date')";
                                                                if (mysqli_query($link, $sqlRuleTxt)) {
                                                                  $sqlRuleKey = "INSERT INTO placeconditionskey(beid, plcbeid, usrbeid) VALUES('$ruleBeId', '$beId', '$usrBeId')";
                                                                  if (mysqli_query($link, $sqlRuleKey)) {
                                                                    $equiArrMax = sizeof($equipmentArr);
                                                                    foreach ($equipmentArr as $iE => $objEqui) {
                                                                      $sqlE = "INSERT INTO placeequipment(beid, name, src) VALUES('$beId', '$objEqui->title', '$objEqui->src')";
                                                                      if (!mysqli_query($link, $sqlE)) {
                                                                        multipleErrors("Equipment (".$objEqui->title.") saving has failed<br>".mysqli_error($link));
                                                                      }
                                                                      equipDone();
                                                                    }
                                                                    $photoArrMax = sizeof($imgsArr);
                                                                    foreach ($imgsArr as $iI => $objImg) {
                                                                      $imgBeIdReady = false;
                                                                      while (!$imgBeIdReady) {
                                                                        $imgBeId = randomHash(64);
                                                                        $sqlImgBeIdCH = $link -> query("SELECT * FROM backendidlist WHERE beid='$imgBeId'");
                                                                        if ($sqlImgBeIdCH->num_rows == 0) {
                                                                          $imgBeIdReady = true;
                                                                        } else {
                                                                          $imgBeIdReady = false;
                                                                        }
                                                                      }
                                                                      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
                                                                      $sqlImgBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$imgBeId', '$backendIDNum', 'img-group')";
                                                                      mysqli_query($link, $sqlImgBeID);
                                                                      if (file_exists("../../../../media/temp/".$objImg->src)) {
                                                                        $smlImgName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $objImg->src);
                                                                        $newSmlImgPath = "media/places/".$objImg->src;
                                                                        $sqlSmlImg = "UPDATE images SET successful='0', status='plc-sml', src='$newSmlImgPath' WHERE name='$smlImgName'";
                                                                        if (mysqli_query($link, $sqlSmlImg)) {
                                                                          if (rename("../../../../media/temp/".$objImg->src, "../../../../".$newSmlImgPath)) {
                                                                            $sqlSmlImgUpdt = "UPDATE images SET successful='1' WHERE name='$smlImgName'";
                                                                            if (mysqli_query($link, $sqlSmlImgUpdt)) {
                                                                              $smlSts = "sml";
                                                                              $sqlImgNum = $link->query("SELECT * FROM placeimages");
                                                                              $sqlImgId = $sqlImgNum->num_rows;
                                                                              $sqlSmlPlc = "INSERT INTO placeimages (cottbeid, imgbeid, type, sts, numid) VALUES ('$beId', '$smlImgName', '$smlSts', '$objImg->sts', '$sqlImgId')";
                                                                              if (mysqli_query($link, $sqlSmlPlc)) {
                                                                                $sqlSmlKey = "INSERT INTO placeimageskey (beid, convertname, type, sts) VALUES ('$imgBeId', '$smlImgName', '$smlSts', '$objImg->sts')";
                                                                                if (mysqli_query($link, $sqlSmlKey)) {
                                                                                  imgDone($smlSts, "done", $imgBeId, $smlImgName, $objImg->sts);
                                                                                } else {
                                                                                  photoDone();
                                                                                  multipleErrors("Failed to save an image (sml) to placeimageskey database<br>".mysqli_error($link));
                                                                                }
                                                                              } else {
                                                                                photoDone();
                                                                                multipleErrors("Failed to save an image (sml) to placeimages database<br>".mysqli_error($link));
                                                                              }
                                                                            } else {
                                                                              photoDone();
                                                                              multipleErrors("Update image (sml) status failed<br>".mysqli_error($link));
                                                                            }
                                                                          } else {
                                                                            photoDone();
                                                                            multipleErrors("Move an image (sml) failed");
                                                                          }
                                                                        } else {
                                                                          photoDone();
                                                                          multipleErrors("Failed to save an image (sml) to images database<br>".mysqli_error($link));
                                                                        }
                                                                      } else {
                                                                        photoDone();
                                                                        multipleErrors("Image (sml) not found on our server");
                                                                      }
                                                                      placeImageConvert("", "", $imgBeId, $objImg->org, $usrBeId, "org", $objImg->sts, "../../../../", "media/places/");
                                                                      placeImageConvert(1920, 1080, $imgBeId, $objImg->org, $usrBeId, "huge", $objImg->sts, "../../../../", "media/places/");
                                                                      placeImageConvert(1024, 576, $imgBeId, $objImg->org, $usrBeId, "big", $objImg->sts, "../../../../", "media/places/");
                                                                      placeImageConvert(544, 306, $imgBeId, $objImg->org, $usrBeId, "mid", $objImg->sts, "../../../../", "media/places/");
                                                                      placeImageConvert(144, 81, $imgBeId, $objImg->org, $usrBeId, "mini", $objImg->sts, "../../../../", "media/places/");
                                                                    }
                                                                    $videosArrMax = sizeof($videosArr);
                                                                    foreach ($videosArr as $iV => $objVid) {
                                                                      $sqlV = "INSERT INTO placevideos(beid, videoid) VALUES('$beId', '$objVid->id')";
                                                                      if (!mysqli_query($link, $sqlV)) {
                                                                        multipleErrors("Videos (".$objVid->id.") saving has failed<br>".mysqli_error($link));
                                                                      }
                                                                      videosDone();
                                                                    }
                                                                    $calendarSyncArrMax = sizeof($calendarSyncArr);
                                                                    foreach ($calendarSyncArr as $iCS => $objCalendarSync) {
                                                                      $sqlCS = "INSERT INTO placecalendarsync (beid, code, url, error) VALUES('$beId', '$objCalendarSync->code', '$objCalendarSync->url', '')";
                                                                      if (!mysqli_query($link, $sqlCS)) {
                                                                        multipleErrors("Calendar sync (".$objCalendarSync->code.") saving has failed<br>".mysqli_error($link));
                                                                      }
                                                                      calendarSyncDone();
                                                                    }
                                                                    checkIfDone();
                                                                  } else {
                                                                    placeFailed();
                                                                    error("Saving key of conditions to the database has failed<br>".mysqli_error($link), "conditions");
                                                                  }
                                                                } else {
                                                                  placeFailed();
                                                                  error("Saving conditions to the database has failed<br>".mysqli_error($link), "conditions");
                                                                }
                                                              } else {
                                                                error("Saving ID of conditions has failed<br>".mysqli_error($link), "conditions");
                                                              }
                                                            } else {
                                                              error("Saving backend ID of conditions has failed<br>".mysqli_error($link), "conditions");
                                                            }
                                                          } else {
                                                            placeFailed();
                                                            error("Saving ID has failed<br>".mysqli_error($link), "uni");
                                                          }
                                                        } else {
                                                          error("Saving data to the place database has failed<br>".mysqli_error($link), "uni");
                                                        }
                                                      } else {
                                                        error("Saving backend ID has failed<br>".mysqli_error($link), "uni");
                                                      }
                                                    } else {
                                                      error($operationFromToReadyErrorTxt, "details");
                                                    }
                                                  } else {
                                                    error("Value of Price per night over the weekend field is too low high (maximum is 99 999 €)", "price");
                                                  }
                                                } else {
                                                  error("Value of Price per night during the working week field is too low high (maximum is 99 999 €)", "price");
                                                }
                                              } else {
                                                error("Value of Price per night over the weekend field is too low", "price");
                                              }
                                            } else {
                                              error("Value of Price per night during the working week field is too low", "price");
                                            }
                                          } else {
                                            if (!$priceWorkValid) {
                                              error("Value of Price per night during the working week field is not a number", "price");
                                            } else if (!$priceWorkValid) {
                                              error("Value of Price per night over the weekend field is not a number", "price");
                                            }
                                          }
                                        } else {
                                          error("Price per night over the weekend field is empty", "price");
                                        }
                                      } else {
                                        error("Price per night during the working week field is empty", "price");
                                      }
                                    } else {
                                      error("Calendar sync - empty URL field", "calendar-sync");
                                    }
                                  } else {
                                    error("Distance from the water field contains invalid characters", "details");
                                  }
                                } else {
                                  error("Value of Number of bathrooms field is too low", "details");
                                }
                              } else {
                                error("Value of Number of bathrooms field is not a number", "details");
                              }
                            } else {
                              error("Number of bathrooms field is empty", "details");
                            }
                          } else {
                            error("Value of Maximum number of guests field is too low", "details");
                          }
                        } else {
                          error("Value of Maximum number of guests field is not a number", "details");
                        }
                      } else {
                        error("Maximum number of guests field is empty", "details");
                      }
                    } else {
                      error("Value of Number of bedrooms field is too low", "details");
                    }
                  } else {
                    error("Value of Number of bedrooms field is not a number", "details");
                  }
                } else {
                  error("Number of bedrooms field is empty", "details");
                }
              } else {
                error("This ID is already taken", "details");
              }
            } else {
              error("ID contains not allowed characters", "details");
            }
          } else {
            error("ID field is empty", "details");
          }
        } else {
          error("Title field is empty", "basics");
        }
      } else {
        error("Bank account data not filled in", "bank-account");
      }
    } else {
      if ($bnft_add_cottage == "none") {
        error("Feature is unavailable", "uni");
      } else if ($bnft_add_cottage == "ban") {
        error("Feature is banned", "uni");
      } else {
        error("Failed to get availability status of this feature", "uni");
      }
    }
  } else {
    error("You are not signed in", "uni");
  }

  function conditionsManager($langSelected, $text) {
    global $link, $default_langs_arr, $usrBeId, $wrd_shrt;
    if ($text != "Unable to open file!") {
      foreach ($default_langs_arr as &$lngShort) {
        $file = "../../../../conditions/texts/conditions_of_stay_of_the_host/".$lngShort."_conditions_of_stay_of_the_host.txt";
        $file_text = fopen($file, "r") or die("Unable to open file!");
        if ($text == mysqli_real_escape_string($link, fread($file_text, filesize($file)))) {
          $langSelected = $lngShort;
        }
        fclose($file_text);
      }
    }
    if (!in_array($langSelected, $default_langs_arr)) {
      $usersConditionsBeIdArr = [];
      $sqlUsersConditionsBeId = $link->query("SELECT beid FROM placeconditionskey WHERE usrbeid='$usrBeId'");
      if ($sqlUsersConditionsBeId->num_rows > 0) {
        while($usersConditionsBeId = $sqlUsersConditionsBeId->fetch_assoc()) {
          array_push($usersConditionsBeIdArr, $usersConditionsBeId['beid']);
        }
        $langFound = false;
        while (!$langFound) {
          if (!in_array($langSelected, $default_langs_arr)) {
            $sqlGetLangSelectedCondition = "";
            foreach ($usersConditionsBeIdArr as &$usrConditionsBeId) {
              if ($sqlGetLangSelectedCondition == "") {
                $sqlGetLangSelectedCondition = "beid='".$usrConditionsBeId."'";
              } else {
                $sqlGetLangSelectedCondition = $sqlGetLangSelectedCondition." || beid='".$usrConditionsBeId."'";
              }
            }
            $text = str_replace("'", "&apos;", $text);
            $sqlGetLangSelectedCondition = "(".$sqlGetLangSelectedCondition.") && txt='".$text."'";
            $sqlGetLangSelected = $link->query("SELECT beid, language FROM conditionsofstayofthehost WHERE ".$sqlGetLangSelectedCondition." LIMIT 1");
            if ($sqlGetLangSelected->num_rows > 0) {
              $getLangSelected = $sqlGetLangSelected->fetch_assoc();
              $langSelected = $getLangSelected['language'];
              array_splice($usersConditionsBeIdArr, array_search($getLangSelected['beid'], $usersConditionsBeIdArr), 1);
            } else {
              $sqlLangSelectedIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$langSelected' LIMIT 1");
              if ($sqlLangSelectedIdToBeId->num_rows > 0) {
                $langSelectedBeId = $sqlLangSelectedIdToBeId->fetch_assoc()["beid"];
                $sqlLangSelectedOrgLang = $link->query("SELECT language FROM conditionsofstayofthehost WHERE beid='$langSelectedBeId' LIMIT 1");
                if ($sqlLangSelectedOrgLang->num_rows > 0) {
                  $langSelected = $sqlLangSelectedOrgLang->fetch_assoc()['language'];
                } else {
                  $langSelected = $wrd_shrt;
                }
              } else {
                $langSelected = $wrd_shrt;
              }
              $langFound = true;
            }
          } else {
            $langFound = true;
          }
        }
      } else {
        $langSelected = $wrd_shrt;
      }
    }
    return $langSelected;
  }

  function imgDone($type, $msg, $imgBeId, $name, $sts) {
    global $link, $beId;
    if ($msg == "done") {
      $dtbImgPlcSuccess = false;
      if ($type != "sml") {
        $dtbSts = $type;
        $sqlImgNum = $link->query("SELECT * FROM placeimages");
        $sqlImgId = $sqlImgNum->num_rows;
        $sqlImgPlc = "INSERT INTO placeimages (cottbeid, imgbeid, type, sts, numid) VALUES ('$beId', '$name', '$dtbSts', '$sts', '$sqlImgId')";
        if (mysqli_query($link, $sqlImgPlc)) {
          $sqlImgKey = "INSERT INTO placeimageskey (beid, convertname, type, sts) VALUES ('$imgBeId', '$name', '$dtbSts', '$sts')";
          if (mysqli_query($link, $sqlImgKey)) {
            $dtbImgPlcSuccess = true;
          } else {
            multipleErrors("Failed to save an image (".$type.") to placeimageskey database<br>".mysqli_error($link));
            $dtbImgPlcSuccess = false;
          }
        } else {
          multipleErrors("Failed to save an image (".$type.") to placeimages database<br>".mysqli_error($link));
          $dtbImgPlcSuccess = false;
        }
      } else {
        $dtbImgPlcSuccess = true;
      }
    } else {
      multipleErrors("Image (".$type.") failed (error: ".$msg.")");
    }
    photoDone();
    checkIfDone();
  }

  function placeFailed() {
    global $link, $beId;
    $sqlFailUpdt = "UPDATE places SET status='fail' WHERE beid='$beId'";
    mysqli_query($link, $sqlFailUpdt);
  }

  function equipDone() {
    global $equipDoneNum;
    ++$equipDoneNum;
  }

  function photoDone() {
    global $photoDoneNum;
    ++$photoDoneNum;
  }

  function videosDone() {
    global $videosDoneNum;
    ++$videosDoneNum;
  }

  function calendarSyncDone() {
    global $calendarSyncDoneNum;
    ++$calendarSyncDoneNum;
  }

  function checkIfDone() {
    global $link, $beId, $equipDoneNum, $photoDoneNum, $videosDoneNum, $equiArrMax, $photoArrMax, $videosArrMax, $checkIfDoneReady, $calendarSyncDoneNum, $calendarSyncArrMax;
    $photoMax = $photoArrMax * 6;
    if ($equipDoneNum >= $equiArrMax && $photoDoneNum >= $photoMax && $videosDoneNum >= $videosArrMax && $calendarSyncDoneNum >= $calendarSyncArrMax && $checkIfDoneReady) {
      $checkIfDoneReady = false;
      done();
    }
  }

  function multipleErrors($msg) {
    global $multipleErrorTxt;
    if ($multipleErrorTxt == "") {
      $multipleErrorTxt = $msg;
    } else {
      $multipleErrorTxt = $multipleErrorTxt."<br>".$msg;
    }
  }

  function error($msg, $sect) {
    global $output, $multipleErrorTxt;
    if ($multipleErrorTxt != "") {
      $msg = $multipleErrorTxt."<br>".$msg;
      $sect = "uni";
    }
    array_push($output, [
      "type" => "error",
      "section" => $sect,
      "error" => $msg
    ]);
    returnOutput();
  }

  function done() {
    global $output, $multipleErrorTxt;
    if ($multipleErrorTxt != "") {
      array_push($output, [
        "type" => "error",
        "section" => "uni",
        "error" => $multipleErrorTxt
      ]);
    }
    array_push($output, [
      "type" => "done"
    ]);
    returnOutput();
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
