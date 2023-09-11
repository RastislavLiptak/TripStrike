<?php
  include "../../backdoor/uni/code/php-backend/edit-booking-archive/add-booking-archive.php";
  include "../../email-sender/php-backend/booking-payment-details-mail.php";
  function addBooking($plcBeId, $g_name, $g_email, $g_phone, $g_guest, $g_notes, $f_d, $f_m, $f_y, $firstDay, $t_d, $t_m, $t_y, $lastDay, $deposit, $fullAmount, $bookingFeePaymentStatus, $source, $feeInPerc, $sendEmail) {
    global $link, $wrd_shrt;
    $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$plcBeId' and status='active' LIMIT 1");
    if ($sqlPlc->num_rows > 0) {
      $rowPlc = $sqlPlc->fetch_assoc();
      $bookingIdReady = false;
      while (!$bookingIdReady) {
        $newBookingId = randomHash(11);
        if ($link->query("SELECT * FROM idlist WHERE id='$newBookingId'")->num_rows == 0) {
          $bookingIdReady = true;
        } else {
          $bookingIdReady = false;
        }
      }
      $newBookingBeIdReady = false;
      while (!$newBookingBeIdReady) {
        $newBookingBeId = randomHash(64);
        if ($link->query("SELECT * FROM backendidlist WHERE beid='$newBookingBeId'")->num_rows == 0) {
          $newBookingBeIdReady = true;
        } else {
          $newBookingBeIdReady = false;
        }
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
      $g_guest = intval($g_guest);
      if ($g_guest > 0) {
        if ($g_guest <= intval($rowPlc["guestNum"])) {
          $guestBeId = "-";
          $guestLanguage = $wrd_shrt;
          if ($g_email != "-") {
            $sqlGuestContactEm = $link->query("SELECT * FROM users WHERE contactemail='$g_email' && status='active'");
            if ($sqlGuestContactEm->num_rows > 0) {
              $gs = $sqlGuestContactEm->fetch_assoc();
              $guestBeId = $gs['beid'];
              $guestLanguage = $gs['language'];
            } else {
              $sqlGuestMainEm = $link->query("SELECT * FROM users WHERE email='$g_email' && status='active'");
              if ($sqlGuestMainEm->num_rows > 0) {
                $gs = $sqlGuestMainEm->fetch_assoc();
                $guestBeId = $gs['beid'];
                $guestLanguage = $gs['language'];
              }
            }
          }
          $totalPrice = totalPriceCalc($plcBeId, $g_guest, $f_y, $f_m, $f_d, $firstDay, $t_y, $t_m, $t_d, $lastDay);
          $plcCurrency = $rowPlc["currency"];
          $fullFrom = $f_y."-".$f_m."-".$f_d;
          $fullTo = $t_y."-".$t_m."-".$t_d;
          $date = date("Y-m-d H:i:s");
          $dateY = date("Y");
          $dateM = date("m");
          $dateD = date("d");
          if ($firstDay != "whole") {
            $f_date = $f_y."-".sprintf("%02d", $f_m)."-".sprintf("%02d", $f_d)." 14:00:00";
          } else {
            $f_date = $f_y."-".sprintf("%02d", $f_m)."-".sprintf("%02d", $f_d)." 00:00:00";
          }
          $from_diff = abs(strtotime($date) - strtotime($f_date));
          $from_hours = floor($from_diff / 3600);
          if ($date > $f_date) {
            $lessThan48h = 0;
          } else {
            if ($from_hours < 48) {
              $lessThan48h = 1;
            } else {
              $lessThan48h = 0;
            }
          }
          $backendIDNum = $link->query("SELECT * FROM backendidlist")->num_rows;
          $sqlNewBookingBeID = "INSERT INTO backendidlist (beid, numid, type) VALUES('$newBookingBeId', '$backendIDNum', 'booking')";
          if (mysqli_query($link, $sqlNewBookingBeID)) {
            $sqlNewBookingID = "INSERT INTO idlist(beid, id, fullDate, dateD, dateM, dateY) VALUES('$newBookingBeId', '$newBookingId', '$date', '$dateD', '$dateM', '$dateY')";
            if (mysqli_query($link, $sqlNewBookingID)) {
              $sqlSave = "INSERT INTO booking (beid, usrbeid, plcbeid, status, name, email, phonenum, notes, language, guestnum, totalprice, totalcurrency, deposit, fullAmount, lessthan48h, ratingMail, fromdate, fromy, fromm, fromd, firstday, todate, toy, tom, tod, lastday, source, fulldate, datey, datem, dated) VALUES ('$newBookingBeId', '$guestBeId', '$plcBeId', 'booked', '$g_name', '$g_email', '$g_phone', '$g_notes', '$guestLanguage', '$g_guest', '$totalPrice', '$plcCurrency', '$deposit', '$fullAmount', '$lessThan48h', '0', '$fullFrom', '$f_y', '$f_m', '$f_d', '$firstDay', '$fullTo', '$t_y', '$t_m', '$t_d', '$lastDay', 'none', '$date', '$dateY', '$dateM', '$dateD')";
              if (mysqli_query($link, $sqlSave)) {
                $allDaysAdded = false;
                $add_y = $f_y;
                $add_m = $f_m;
                $add_d = $f_d;
                $allDaysErrorUnavailable = 0;
                $allDaysErrorSql = 0;
                while (!$allDaysAdded) {
                  if ($link->query("SELECT * FROM bookingdates WHERE beid='$newBookingBeId' and plcbeid='$plcBeId' and status!='canceled' and year='$add_y' and month='$add_m' and day='$add_d'")->num_rows == 0) {
                    if (!everyDayToBookingDatabase($newBookingBeId, $plcBeId, $add_y, $add_m, $add_d)) {
                      ++$allDaysErrorSql;
                    }
                  } else {
                    ++$allDaysErrorUnavailable;
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
                if ($allDaysErrorUnavailable == 0 && $allDaysErrorSql == 0) {
                  $addBookingArchiveSts = addBookingArchive(
                    $newBookingBeId,
                    $rowPlc['usrbeid'],
                    $guestBeId,
                    $plcBeId,
                    $g_guest,
                    "booked",
                    $bookingFeePaymentStatus,
                    $source,
                    $plcCurrency,
                    $totalPrice,
                    $feeInPerc,
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
                  if ($addBookingArchiveSts != "done") {
                    addBookingOutput("error", "Booking archive error: ".$addBookingArchiveSts);
                  }
                  if ($g_email != "-" && $sendEmail) {
                    if (date("Y-m-d") < $t_y."-".$t_m."-".$t_d) {
                      bookingPaymentDetailsMail(
                        "../../",
                        $newBookingId,
                        $guestLanguage,
                        $g_name,
                        $g_email,
                        getFrontendId($plcBeId),
                        $rowPlc['usrbeid'],
                        getFrontendId($rowPlc['usrbeid']),
                        $rowPlc['name'],
                        $firstDay,
                        $lastDay,
                        $f_d,
                        $f_m,
                        $f_y,
                        $t_d,
                        $t_m,
                        $t_y,
                        $date,
                        $totalPrice,
                        $plcCurrency,
                        $lessThan48h
                      );
                    } else {
                      addBookingOutput("done", "good");
                    }
                  } else {
                    addBookingOutput("done", "good");
                  }
                } else {
                  $bookingDatesErrorTxt = "";
                  $calcelSts = cancelBookingDates($newBookingBeId);
                  if ($allDaysErrorUnavailable > 0) {
                    $bookingDatesErrorTxt = $bookingDatesErrorTxt."Not all days are available (unavailable dates: ".$allDaysErrorUnavailable.")<br>";
                  }
                  if ($allDaysErrorSql > 0) {
                    $bookingDatesErrorTxt = $bookingDatesErrorTxt."Failed to save separate days of booking (dates: ".$allDaysErrorSql.")<br>";
                  }
                  if ($calcelSts != "good") {
                    $bookingDatesErrorTxt = $bookingDatesErrorTxt."".$calcelSts."<br>";
                  }
                  addBookingOutput("error", "Add booking failed: <br>".$bookingDatesErrorTxt);
                }
              } else {
                addBookingOutput("error", "Add booking failed: failed to save booking<br>".mysqli_error($link));
              }
            } else {
              addBookingOutput("error", "Add booking failed: saving ID failed");
            }
          } else {
            addBookingOutput("error", "Add booking failed: saving backend ID failed");
          }
        } else {
          addBookingOutput("error", "Add booking failed: number of guests is too high");
        }
      } else {
        addBookingOutput("error", "Add booking failed: number of guests is too low");
      }
    } else {
      addBookingOutput("error", "Add booking failed: place does not exist");
    }
  }

  function everyDayToBookingDatabase($beId, $plcBeId, $y, $m, $d) {
    global $link;
    $fulldate = $y."-".sprintf("%02d", $m)."-".sprintf("%02d", $d);
    $sql = "INSERT INTO bookingdates (beid, plcbeid, status, year, month, day, fulldate) VALUES ('$beId', '$plcBeId', 'booked', '$y', '$m', '$d', '$fulldate')";
    if (mysqli_query($link, $sql)) {
      return true;
    } else {
      return false;
    }
  }

  function cancelBookingDates($beId) {
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
?>
