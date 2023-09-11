<?php
  include "../../email-sender/php-backend/update-booking-mail-to-guest.php";
  include "../../email-sender/php-backend/unpaid-full-amount-call-mail.php";
  include "../../email-sender/php-backend/pay-full-amount-mail-alert.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../backdoor/uni/code/php-backend/edit-booking-archive/add-booking-archive.php";
  include "../../backdoor/uni/code/php-backend/edit-booking-archive/update-booking-archive.php";
  $changedList = [];
  function updateBooking($bookingBeId, $plcBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, $sendEmail) {
    global $link, $linkBD;
    $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId' LIMIT 1");
    if ($sqlPlc->num_rows > 0) {
      $rowPlc = $sqlPlc->fetch_assoc();
      $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
      if ($sqlBooking->num_rows > 0) {
        $rowBooking = $sqlBooking->fetch_assoc();
        if ($firstDay != "whole") {
          $firstDay = "half";
        }
        if ($lastDay != "whole") {
          $lastDay = "half";
        }
        if ($g_name == "") {
          $g_name = "-";
        }
        if ($g_email == "") {
          $g_email = "-";
        }
        if ($g_phone == "") {
          $g_phone = "-";
        }
        $bookingSts = $rowBooking['status'];
        $g_guest = intval($g_guest);
        $whatIsChanged = updateBookingWhatIsChanged($bookingBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount);
        if ($g_guest > 0) {
          if ($g_guest <= intval($rowPlc["guestNum"])) {
            $guestBeId = $rowBooking['usrbeid'];
            $guestLanguage = $rowBooking['language'];
            $guestSignedUp = false;
            if ($g_email != "-" && $guestBeId == "-") {
              $sqlGuest = $link->query("SELECT * FROM users WHERE email='$g_email' && status='active'");
              if ($sqlGuest->num_rows > 0) {
                $gs = $sqlGuest->fetch_assoc();
                $guestBeId = $gs['beid'];
                $guestLanguage = $gs['language'];
                $guestSignedUp = true;
              }
            }
            $fullFrom = $f_y."-".$f_m."-".$f_d;
            $fullTo = $t_y."-".$t_m."-".$t_d;
            $totalprice = totalPriceCalc($plcBeId, $g_guest, $f_y, $f_m, $f_d, $firstDay, $t_y, $t_m, $t_d, $lastDay);
            $sqlUpdt = "UPDATE booking SET usrbeid='$guestBeId', name='$g_name', email='$g_email', phonenum='$g_phone', notes='$g_notes', language='$guestLanguage', guestnum='$g_guest', totalprice='$totalprice', deposit='$deposit', fullAmount='$fullAmount', fromdate='$fullFrom', fromy='$f_y', fromm='$f_m', fromd='$f_d', todate='$fullTo', toy='$t_y', tom='$t_m', tod='$t_d', firstday='$firstDay', lastday='$lastDay' WHERE beid='$bookingBeId'";
            if (mysqli_query($link, $sqlUpdt)) {
              $sqlCancelBookingDays = "UPDATE bookingdates SET status='canceled' WHERE beid='$bookingBeId'";
              mysqli_query($link, $sqlCancelBookingDays);
              $allDaysAdded = false;
              $add_y = $f_y;
              $add_m = $f_m;
              $add_d = $f_d;
              $allDaysErrorSql = 0;
              while (!$allDaysAdded) {
                $bookingDayFulldate = $add_y."-".sprintf("%02d", $add_m)."-".sprintf("%02d", $add_d);
                if ($link->query("SELECT * FROM bookingdates WHERE beid='$bookingBeId' and year='$add_y' and month='$add_m' and day='$add_d'")->num_rows == 0) {
                  $sqlAddBookingDay = "INSERT INTO bookingdates (beid, plcbeid, status, year, month, day, fulldate) VALUES ('$bookingBeId', '$plcBeId', '$bookingSts', '$add_y', '$add_m', '$add_d', '$bookingDayFulldate')";
                  if (!mysqli_query($link, $sqlAddBookingDay)) {
                    ++$allDaysErrorSql;
                  }
                } else {
                  $sqlUpdateBookingDay = "UPDATE bookingdates SET status='$bookingSts' WHERE beid='$bookingBeId' and year='$add_y' and month='$add_m' and day='$add_d' and fulldate='$bookingDayFulldate'";
                  if (!mysqli_query($link, $sqlUpdateBookingDay)) {
                    ++$allDaysErrorSql;
                    echo mysqli_error($link)."<br>";
                  }
                }
                if ($add_y == $t_y && $add_m == $t_m && $add_d == $t_d) {
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
              if ($allDaysErrorSql == 0) {
                $sqlArchiveCheck = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
                if ($sqlArchiveCheck->num_rows > 0) {
                  $rowArBooking = $sqlArchiveCheck->fetch_assoc();
                  $editBookingArchiveSts = updateBookingArchive(
                    $bookingBeId,
                    $rowPlc['usrbeid'],
                    $guestBeId,
                    $plcBeId,
                    $g_guest,
                    $bookingSts,
                    $rowArBooking['paymentStatus'],
                    $rowArBooking['source'],
                    $rowPlc['currency'],
                    $totalprice,
                    $rowArBooking['percentagefee'],
                    $rowPlc['priceMode'],
                    $rowPlc['workDayPrice'],
                    $rowPlc['weekDayPrice'],
                    $fullFrom,
                    $f_d,
                    $f_m,
                    $f_y,
                    $firstDay,
                    $fullTo,
                    $t_d,
                    $t_m,
                    $t_y,
                    $lastDay
                  );
                } else {
                  $editBookingArchiveSts = addBookingArchive(
                    $bookingBeId,
                    $rowPlc['usrbeid'],
                    $guestBeId,
                    $plcBeId,
                    $g_guest,
                    $bookingSts,
                    1,
                    "editor",
                    $rowPlc['currency'],
                    $totalprice,
                    0,
                    $rowPlc['priceMode'],
                    $rowPlc['workDayPrice'],
                    $rowPlc['weekDayPrice'],
                    $fullFrom,
                    $f_d,
                    $f_m,
                    $f_y,
                    $firstDay,
                    $fullTo,
                    $t_d,
                    $t_m,
                    $t_y,
                    $lastDay
                  );
                }
                if ($editBookingArchiveSts != "done") {
                  addBookingOutput("error", "Booking archive error: ".$editBookingArchiveSts);
                }
                if (sizeof($whatIsChanged) > 0) {
                  $plcHostBeID = $rowPlc['usrbeid'];
                  $sqlHostData = $link->query("SELECT * FROM users WHERE beid='$plcHostBeID' LIMIT 1");
                  $aboutHost = $sqlHostData->fetch_assoc();
                  if (in_array("deposit", $whatIsChanged)) {
                    if ($deposit == 1 && $fullAmount == 0) {
                      if ($g_email != "-") {
                        if ($firstDay != "whole") {
                          $fullFrom = $fullFrom." 14:00";
                        } else {
                          $fullFrom = $fullFrom." 00:00";
                        }
                        $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($fullFrom));
                        $hours = floor($diff / 3600);
                        if (date("Y-m-d H:i:s") > $fullFrom) {
                          $hours = $hours * -1;
                        }
                        if ($hours <= 48) {
                          unpaidFullAmountCallMail(
                            getFrontendId($bookingBeId),
                            $g_email,
                            $guestLanguage,
                            $f_d,
                            $f_m,
                            $f_y,
                            $firstDay,
                            $t_d,
                            $t_m,
                            $t_y,
                            $lastDay,
                            $totalprice,
                            $rowPlc['currency'],
                            getFrontendId($plcBeId),
                            $rowPlc['name'],
                            getFrontendId($plcHostBeID),
                            $aboutHost['firstname'],
                            $aboutHost['lastname'],
                            $aboutHost['contactemail'],
                            $aboutHost['contactphonenum']
                          );
                        } else if ($hours <= 54) {
                          payFullAmountMailAlert(
                            getFrontendId($bookingBeId),
                            $g_email,
                            $g_name,
                            $guestLanguage,
                            $f_d,
                            $f_m,
                            $f_y,
                            $firstDay,
                            $t_d,
                            $t_m,
                            $t_y,
                            $lastDay,
                            $totalprice,
                            $rowPlc['currency'],
                            getFrontendId($plcBeId),
                            $rowPlc['name'],
                            $aboutHost['bankaccount'],
                            $aboutHost['iban'],
                            $aboutHost['bicswift'],
                            getFrontendId($plcHostBeID),
                            $aboutHost['firstname'],
                            $aboutHost['lastname'],
                            $aboutHost['contactemail'],
                            $aboutHost['contactphonenum']
                          );
                        }
                      }
                    }
                  }
                  if (sizeof($whatIsChanged) > 1 || (sizeof($whatIsChanged) == 1 && $whatIsChanged[0] != "notes")) {
                    if ($g_email != "-" && $sendEmail) {
                      if (date("Y-m-d") < $t_y."-".$t_m."-".$t_d) {
                        updateBookingMailToGuest(
                          getFrontendId($bookingBeId),
                          getFrontendId($plcHostBeID),
                          $aboutHost['firstname']." ".$aboutHost['lastname'],
                          $aboutHost['contactemail'],
                          $aboutHost['contactphonenum'],
                          $g_name,
                          $g_email,
                          $g_phone,
                          $g_guest,
                          getFrontendId($plcBeId),
                          $rowPlc['name'],
                          $guestLanguage,
                          $f_d.". ".$f_m.". ".$f_y,
                          $firstDay,
                          $t_d.". ".$t_m.". ".$t_y,
                          $lastDay,
                          addCurrency("eur", $totalprice),
                          $deposit,
                          $fullAmount
                        );
                      } else {
                        updateBookingOutput("done", "good");
                      }
                    } else {
                      updateBookingOutput("done", "good");
                    }
                  } else {
                    updateBookingOutput("done", "good");
                  }
                } else {
                  updateBookingOutput("done", "good");
                }
              } else {
                $bookingDatesErrorTxt = "";
                $calcelSts = emergencyBookingCancel($bookingBeId);
                if ($allDaysErrorSql > 0) {
                  $bookingDatesErrorTxt = $bookingDatesErrorTxt."failed to save separate days of booking (dates: ".$allDaysErrorSql.")<br>";
                }
                if ($calcelSts != "good") {
                  $bookingDatesErrorTxt = $bookingDatesErrorTxt."".$calcelSts."<br>";
                }
                updateBookingOutput("error", "Update booking failed: <br>".$bookingDatesErrorTxt);
              }
            } else {
              updateBookingOutput("error", "Update booking failed: failed to update 'booking' database<br>".mysqli_error($link));
            }
          } else {
            updateBookingOutput("error", "Update booking failed: number of guests is too high");
          }
        } else {
          updateBookingOutput("error", "Update booking failed: number of guests is too low");
        }
      } else {
        updateBookingOutput("error", "Update booking failed: booking does not exist");
      }
    } else {
      updateBookingOutput("error", "Update booking failed: place does not exist");
    }
  }

  function emergencyBookingCancel($beId) {
    global $link;
    $sqlDaysCancel = "UPDATE bookingdates SET status='canceled' WHERE beid='$beId'";
    if (mysqli_query($link, $sqlDaysCancel)) {
      $sqlBookingCancel = "UPDATE booking SET status='canceled' WHERE beid='$beId'";
      if (mysqli_query($link, $sqlBookingCancel)) {
        return "good";
      } else {
        return "cancel-booking-failed";
      }
    } else {
      return "cancel-days-list-failed";
    }
  }

  function updateBookingWhatIsChanged($bookingBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount) {
    global $link, $changedList;
    $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
    $book = $sqlBooking->fetch_assoc();
    if ($book['name'] != $g_name) {
      updateBookingWhatIsChangedList("name");
    }
    if ($book['email'] != $g_email) {
      updateBookingWhatIsChangedList("email");
    }
    if ($book['phonenum'] != $g_phone) {
      updateBookingWhatIsChangedList("phonenum");
    }
    if ($book['guestnum'] != $g_guest) {
      updateBookingWhatIsChangedList("guestnum");
    }
    if ($book['notes'] != $g_notes) {
      updateBookingWhatIsChangedList("notes");
    }
    if ($book['fromd'] != $f_d) {
      updateBookingWhatIsChangedList("fromd");
    }
    if ($book['fromm'] != $f_m) {
      updateBookingWhatIsChangedList("fromm");
    }
    if ($book['fromy'] != $f_y) {
      updateBookingWhatIsChangedList("fromy");
    }
    if ($book['firstday'] != $firstDay) {
      updateBookingWhatIsChangedList("firstday");
    }
    if ($book['tod'] != $t_d) {
      updateBookingWhatIsChangedList("tod");
    }
    if ($book['tom'] != $t_m) {
      updateBookingWhatIsChangedList("tom");
    }
    if ($book['toy'] != $t_y) {
      updateBookingWhatIsChangedList("toy");
    }
    if ($book['lastday'] != $lastDay) {
      updateBookingWhatIsChangedList("lastday");
    }
    if ($book['deposit'] != $deposit) {
      updateBookingWhatIsChangedList("deposit");
    }
    if ($book['fullAmount'] != $fullAmount) {
      updateBookingWhatIsChangedList("fullAmount");
    }
    return $changedList;
  }

  function updateBookingWhatIsChangedList($val) {
    global $changedList;
    array_push($changedList, $val);
  }
?>
