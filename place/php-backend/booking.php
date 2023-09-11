<?php
  include "../../uni/code/php-backend/data.php";
  include "../../uni/dictionary/lang-select.php";
  include "../../uni/code/php-backend/get-frontend-id.php";
  include "../../uni/code/php-backend/get-user.php";
  include "../../uni/code/php-backend/account-data-check.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../uni/code/php-backend/calendar/calc-date.php";
  include "../../uni/code/php-backend/calendar/let-me-sleep.php";
  include "book-check-dates.php";
  include "../../uni/code/php-backend/random-hash-maker.php";
  include "../../uni/code/php-backend/password-edit.php";
  include "../../email-sender/php-backend/send-mail.php";
  include "../../email-sender/php-backend/booking-offer-mail.php";
  include "../../email-sender/php-backend/confirmation-of-booking-mail.php";
  include "../../backdoor/uni/code/php-backend/edit-booking-archive/add-booking-archive.php";
  $output = [];
  $plcId = $_POST['id'];
  $guestName = mysqli_real_escape_string($link, $_POST['name']);
  $guestEmail = mysqli_real_escape_string($link, $_POST['email']);
  $guestPhone = mysqli_real_escape_string($link, str_replace("plus", "+", $_POST['phone']));
  $pass = $_POST['pass'];
  $accept = $_POST['accept'];
  $hostConditions = $_POST['hostConditions'];
  $numOfGuests = $_POST['guests'];
  $fromY = $_POST['fromY'];
  $fromM = $_POST['fromM'];
  $fromD = $_POST['fromD'];
  $firstDay = $_POST['fromAvailability'];
  $toY = $_POST['toY'];
  $toM = $_POST['toM'];
  $toD = $_POST['toD'];
  $lastDay = $_POST['toAvailability'];
  if ($firstDay != "whole") {
    $firstDay = "half";
  }
  if ($lastDay != "whole") {
    $lastDay = "half";
  }
  if (check($guestName, "empty")) {
    if (check($guestName, "numIn")) {
      if (check($guestEmail, "empty")) {
        if (check($guestEmail, "email")) {
          if (check(str_replace("+", "", $guestPhone), "empty")) {
            if (check($guestPhone, "tel")) {
              if (preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $numOfGuests)) != "") {
                if (is_numeric($numOfGuests)) {
                  $numOfGuests = intval($numOfGuests);
                  if (
                    preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $plcId)) != "" &&
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
                    if ($accept == "true") {
                      if ($hostConditions == "true") {
                        $sqlEmail = $link->query("SELECT * FROM users WHERE (email='$guestEmail' or contactemail='$guestEmail') and status='active'");
                        if ($sqlEmail->num_rows > 0) {
                          if ($pass != "") {
                            $goodPassword = "no";
                            if ($guestEmail != $setemail && $guestEmail != $setcontactemail) {
                              $sqlPass = $link->query("SELECT password FROM users WHERE (email='$guestEmail' or contactemail='$guestEmail') and status='active'");
                              while($rowPass = $sqlPass->fetch_assoc()) {
                                if ($goodPassword == "no") {
                                  if (password_verify(passEdit($pass), passEdit($rowPass['password']))) {
                                    $goodPassword = "yes";
                                  } else {
                                    $goodPassword = "no";
                                  }
                                }
                              }
                            } else {
                              $goodPassword = "yes";
                            }
                          } else {
                            $goodPassword = "empty";
                          }
                          if ($goodPassword == "empty") {
                            if ($sign == "yes") {
                              if ($setemail == $guestEmail || $setcontactemail == $guestEmail) {
                                $passRequired = false;
                              } else {
                                $passRequired = true;
                              }
                            } else {
                              $passRequired = true;
                            }
                          } else if ($goodPassword == "yes") {
                            $passRequired = false;
                          } else {
                            $passRequired = true;
                          }
                        } else {
                          $goodPassword = "empty";
                          $passRequired = false;
                        }
                        if ($goodPassword != "no") {
                          if (!$passRequired) {
                            $sqlIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcId' LIMIT 1");
                            if ($sqlIdToBeId->num_rows > 0) {
                              $plcBeId = $sqlIdToBeId->fetch_assoc()["beid"];
                              $datesCheck = checkDates($plcBeId, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay);
                              if ($datesCheck == "good") {
                                $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId' and status='active' LIMIT 1");
                                if ($sqlPlc->num_rows > 0) {
                                  $rowPlc = $sqlPlc->fetch_assoc();
                                  if ($rowPlc['type'] == "cottage") {
                                    if (intval($rowPlc['guestNum']) >= $numOfGuests && $numOfGuests > 0) {
                                      $beIdReady = false;
                                      while (!$beIdReady) {
                                        $beId = randomHash(64);
                                        if ($link->query("SELECT * FROM backendidlist WHERE beid='$beId'")->num_rows == 0) {
                                          $beIdReady = true;
                                        } else {
                                          $beIdReady = false;
                                        }
                                      }
                                      $idReady = false;
                                      while (!$idReady) {
                                        $id = randomHash(11);
                                        if ($link->query("SELECT * FROM idlist WHERE id='$id'")->num_rows == 0) {
                                          $idReady = true;
                                        } else {
                                          $idReady = false;
                                        }
                                      }
                                      if ($sign == "yes") {
                                        $guestBeId = $usrBeId;
                                      } else {
                                        $sqlGuestContactEm = $link->query("SELECT * FROM users WHERE contactemail='$guestEmail' && status='active'");
                                        if ($sqlGuestContactEm->num_rows > 0) {
                                          $gs = $sqlGuestContactEm->fetch_assoc();
                                          $guestBeId = $gs['beid'];
                                        } else {
                                          $sqlGuestMainEm = $link->query("SELECT * FROM users WHERE email='$guestEmail' && status='active'");
                                          if ($sqlGuestMainEm->num_rows > 0) {
                                            $gs = $sqlGuestMainEm->fetch_assoc();
                                            $guestBeId = $gs['beid'];
                                          } else {
                                            $guestBeId = "-";
                                          }
                                        }
                                      }
                                      $total = totalPriceCalc($plcBeId, $numOfGuests, $fromY, $fromM, $fromD, $firstDay, $toY, $toM, $toD, $lastDay);
                                      $currency = $rowPlc['currency'];
                                      $fullFrom = $fromY."-".$fromM."-".$fromD;
                                      $fullTo = $toY."-".$toM."-".$toD;
                                      $date = date("Y-m-d H:i:s");
                                      $dateY = date("Y");
                                      $dateM = date("m");
                                      $dateD = date("d");
                                      if ($firstDay != "whole") {
                                        $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 14:00:00";
                                      } else {
                                        $fromDate = $fromY."-".sprintf("%02d", $fromM)."-".sprintf("%02d", $fromD)." 00:00:00";
                                      }
                                      $from_diff = abs(strtotime($date) - strtotime($fromDate));
                                      $from_hours = floor($from_diff / 3600);
                                      if ($date > $fromDate) {
                                        $from_hours = $from_hours * (-1);
                                      }
                                      if ($from_hours < 48) {
                                        $lessThan48h = 1;
                                      } else {
                                        $lessThan48h = 0;
                                      }
                                      $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
                                      $sqlBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$beId', '$backendIDNum', 'booking')";
                                      if (mysqli_query($link, $sqlBeID)) {
                                        $sqlID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$beId', '$id', '$date', '$dateD', '$dateM', '$dateY')";
                                        if (mysqli_query($link, $sqlID)) {
                                          $sqlSave = "INSERT INTO booking (beid, usrbeid, plcbeid, status, name, email, phonenum, notes, language, guestnum, totalprice, totalcurrency, deposit, fullAmount, lessthan48h, ratingMail, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, source, fulldate, datey, datem, dated) VALUES ('$beId', '$guestBeId', '$plcBeId', 'waiting', '$guestName', '$guestEmail', '$guestPhone', '', '$wrd_shrt', '$numOfGuests', '$total', '$currency', '0', '0', '$lessThan48h', '0', '$fullFrom', '$fromY', '$fromM', '$fromD', '$firstDay', '$fullTo', '$toY', '$toM', '$toD', '$lastDay', 'none', '$date', '$dateY', '$dateM', '$dateD')";
                                          if (mysqli_query($link, $sqlSave)) {
                                            $allDaysAdded = false;
                                            $add_y = $fromY;
                                            $add_m = $fromM;
                                            $add_d = $fromD;
                                            $allDaysErrorUnavailable = 0;
                                            $allDaysErrorSql = 0;
                                            while (!$allDaysAdded) {
                                              if ($link->query("SELECT * FROM bookingdates WHERE beid='$beId' and plcbeid='$plcBeId' and status!='canceled' and year='$add_y' and month='$add_m' and day='$add_d'")->num_rows == 0) {
                                                if (!everyDayToDatabase($beId, $plcBeId, $add_y, $add_m, $add_d)) {
                                                  ++$allDaysErrorSql;
                                                }
                                              } else {
                                                ++$allDaysErrorUnavailable;
                                              }
                                              if ($add_y == $toY && $add_m == $toM && $add_d == $toD) {
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
                                            if ($allDaysErrorUnavailable == 0 && $allDaysErrorSql == 0) {
                                              $addBookingArchiveSts = addBookingArchive(
                                                $beId,
                                                $rowPlc['usrbeid'],
                                                $guestBeId,
                                                $plcBeId,
                                                $numOfGuests,
                                                "waiting",
                                                0,
                                                "booking-form",
                                                $currency,
                                                $total,
                                                $bds_percAmountOfTheFees,
                                                $rowPlc['priceMode'],
                                                $rowPlc['workDayPrice'],
                                                $rowPlc['weekDayPrice'],
                                                $fullFrom,
                                                $fromD,
                                                $fromM,
                                                $fromY,
                                                $firstDay,
                                                $fullTo,
                                                $toD,
                                                $toM,
                                                $toY,
                                                $lastDay
                                              );
                                              if ($addBookingArchiveSts != "done") {
                                                error("Booking archive error: ".$addBookingArchiveSts, 0);
                                              }
                                              $plcName = $rowPlc['name'];
                                              $plcCurrency = $rowPlc['currency'];
                                              $hostBeId = $rowPlc['usrbeid'];
                                              if (getAccountData($hostBeId, "feature-no-fees") == "good") {
                                                $bds_percAmountOfTheFees = 0;
                                              }
                                              $sqlUsr = $link->query("SELECT contactemail, language FROM users WHERE beid='$hostBeId' and status='active'");
                                              if ($sqlUsr->num_rows == 1) {
                                                $usrRow = $sqlUsr->fetch_assoc();
                                                bookingOfferMail(
                                                  $usrRow['language'],
                                                  $usrRow['contactemail'],
                                                  $numOfGuests,
                                                  getFrontendId($plcBeId),
                                                  $plcName,
                                                  $id,
                                                  $firstDay,
                                                  $lastDay,
                                                  $fromD,
                                                  $fromM,
                                                  $fromY,
                                                  $toD,
                                                  $toM,
                                                  $toY,
                                                  $total,
                                                  $bds_percAmountOfTheFees,
                                                  $plcCurrency
                                                );
                                              } else {
                                                $calcelSts = cancelDates($beId);
                                                if ($calcelSts != "good") {
                                                  error($calcelSts, 0);
                                                }
                                                if ($sqlUsr->num_rows > 1) {
                                                  error("too-many-accounts-w-id", 1);
                                                } else {
                                                  error("no-account-w-id", 1);
                                                }
                                              }
                                            } else {
                                              $calcelSts = cancelDates($beId);
                                              if ($calcelSts != "good") {
                                                error($calcelSts, 0);
                                              }
                                              if ($allDaysErrorUnavailable > 0) {
                                                valueError("day-unavailable", $allDaysErrorUnavailable, 1);
                                              }
                                              if ($allDaysErrorSql > 0) {
                                                valueError("day-sql-faild", $allDaysErrorSql, 1);
                                              }
                                            }
                                          } else {
                                            error("booking-sql-faild", 1);
                                          }
                                        } else {
                                          error("saving-id-faild", 1);
                                        }
                                      } else {
                                        error("saving-backend-id-faild", 1);
                                      }
                                    } else {
                                      error("wrong-guests-number", 1);
                                    }
                                  } else {
                                    error("unsupported-type-of-place-for-booking", 1);
                                  }
                                } else {
                                  error("place-n-found", 1);
                                }
                              } else if ($datesCheck == "dates-same") {
                                error("dates-are-same", 1);
                              } else if ($datesCheck == "dates-order") {
                                error("wrong-dates-order", 1);
                              } else if ($datesCheck == "unavailable") {
                                error("dates-unavailable", 1);
                              } else {
                                error("Date availability failned: ".$datesCheck, 1);
                              }
                            } else {
                              error("id-n-found", 1);
                            }
                          } else {
                            error("ask-f-pass", 1);
                          }
                        } else {
                          error("wrong-pass", 1);
                        }
                      } else {
                        error("accept-host-conditions", 1);
                      }
                    } else {
                      error("accept-terms", 1);
                    }
                  } else {
                    error("missing-data", 1);
                  }
                } else {
                  error("guests-n-number", 1);
                }
              } else {
                error("guests-empty", 1);
              }
            } else {
              error("not-phone", 1);
            }
          } else {
            error("phone-empty", 1);
          }
        } else {
          error("not-email", 1);
        }
      } else {
        error("email-empty", 1);
      }
    } else {
      error("name-w-num", 1);
    }
  } else {
    error("name-empty", 1);
  }

  function everyDayToDatabase($beId, $plcBeId, $y, $m, $d) {
    global $link;
    $fulldate = $y."-".sprintf("%02d", $m)."-".sprintf("%02d", $d);
    $sql = "INSERT INTO bookingdates (beid, plcbeid, status, year, month, day, fulldate) VALUES ('$beId', '$plcBeId', 'waiting', '$y', '$m', '$d', '$fulldate')";
    if (mysqli_query($link, $sql)) {
      return true;
    } else {
      return false;
    }
  }

  $hostMailSended = false;
  function mailDone($msg, $mailType) {
    global $beId, $id, $plcBeId, $hostMailSended, $plcName, $fromD, $fromM, $fromY, $firstDay, $toD, $toM, $toY, $lastDay, $plcCurrency, $total, $sign, $guestName, $guestEmail, $hostBeId, $guestPhone, $wrd_shrt, $lessThan48h, $numOfGuests;
    if (!$hostMailSended) {
      $hostMailSended = true;
      if ($msg == "done") {
        confirmationOfBookingMail(
          $id,
          $wrd_shrt,
          $guestEmail,
          $numOfGuests,
          getFrontendId($plcBeId),
          $hostBeId,
          getFrontendId($hostBeId),
          $plcName,
          $firstDay,
          $lastDay,
          $fromD,
          $fromM,
          $fromY,
          $toD,
          $toM,
          $toY,
          $total,
          $plcCurrency,
          $lessThan48h
        );
      } else {
        $calcelSts = cancelDates($beId);
        if ($calcelSts != "good") {
          error($calcelSts, 0);
        }
        error("email-to-host-faild", 1);
      }
    } else {
      if ($sign != "yes") {
        setcookie("guest-name", $guestName, time() + (86400 * 30 * 365), "/");
        setcookie("guest-email", $guestEmail, time() + (86400 * 30 * 365), "/");
        setcookie("guest-phone", $guestPhone, time() + (86400 * 30 * 365), "/");
      }
      if ($msg == "done") {
        done();
      } else {
        error("email-to-guest-faild", 1);
      }
    }
  }

  function cancelDates($beId) {
    global $link, $linkBD;
    $sqlDaysDeleteArchive = "DELETE FROM bookingarchive WHERE beid='$beId'";
    if (mysqli_query($linkBD, $sqlDaysDeleteArchive)) {
      $sqlBookingDeleteArchive = "DELETE FROM bookingupdatearchive WHERE bookingbeid='$beId'";
      if (mysqli_query($linkBD, $sqlBookingDeleteArchive)) {
        $sqlDaysDelete = "DELETE FROM bookingdates WHERE beid='$beId'";
        if (mysqli_query($link, $sqlDaysDelete)) {
          $sqlBookingDelete = "DELETE FROM booking WHERE beid='$beId'";
          if (mysqli_query($link, $sqlBookingDelete)) {
            return "good";
          } else {
            return "delete-booking-faild";
          }
        } else {
          return "delete-days-list-faild";
        }
      } else {
        return "delete-booking-updates-archive-faild";
      }
    } else {
      return "delete-booking-archive-faild";
    }
  }

  function done() {
    global $output;
    array_push($output, [
      "type" => "done",
      "msg" => "good"
    ]);
    returnOutput();
  }

  function error($msg, $sts) {
    global $output;
    array_push($output, [
      "type" => "error",
      "error" => $msg
    ]);
    if ($sts == 1) {
      returnOutput();
    }
  }

  function valueError($msg, $val, $sts) {
    global $output;
    array_push($output, [
      "type" => "value-error",
      "error" => $msg,
      "value" => $val
    ]);
    if ($sts == 1) {
      returnOutput();
    }
  }

  function returnOutput() {
    global $output;
    $JSON = json_encode($output);
    echo $JSON;
  }
?>
