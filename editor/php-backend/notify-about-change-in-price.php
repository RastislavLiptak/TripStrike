<?php
  include "../../uni/code/php-backend/total-price-calculator.php";
  include "../../email-sender/php-backend/pricing-of-the-booking-has-been-changed-mail.php";
  require realpath($_SERVER["DOCUMENT_ROOT"]).$projectFolder."/backdoor/uni/code/php-backend/edit-booking-archive/update-booking-archive.php";
  $bookingNotifiedAboutChangeTotal = 0;
  $bookingNotifiedAboutChangeCount = 0;
  $bookingNotifiedAboutChangeErrorCount = 0;
  $bookingNotifiedAboutChangeError = "";
  function notifyAboutChangeInPrice($beId, $sts) {
    global $link, $linkBD, $bookingNotifiedAboutChangeTotal;
    $date = date("Y-m-d");
    $sqlPlc = $link->query("SELECT * FROM places WHERE beid='$beId' and status='active'");
    if ($sqlPlc->num_rows > 0) {
      $rowPlc = $sqlPlc->fetch_assoc();
      $hostBeID = $rowPlc['usrbeid'];
      $sqlHost = $link->query("SELECT firstname, lastname, contactemail, contactphonenum, bankaccount, iban, bicswift FROM users WHERE beid='$hostBeID' and status='active'");
      if ($sqlHost->num_rows > 0) {
        $rowHost = $sqlHost->fetch_assoc();
        $sqlBookings = $link->query("SELECT * FROM booking WHERE plcbeid='$beId' and (status='booked' or status='waiting') and fromdate>'$date'");
        $bookingNotifiedAboutChangeTotal = $sqlBookings->num_rows;
        if ($bookingNotifiedAboutChangeTotal > 0) {
          while($rowBookings = $sqlBookings->fetch_assoc()) {
            $oldTotalPrice = $rowBookings['totalprice'];
            $oldTotalCurrency = $rowBookings['totalcurrency'];
            $bookingBeId = $rowBookings['beid'];
            $newTotalPrice = totalPriceCalc($beId, $rowBookings['guestnum'], $rowBookings['fromy'], $rowBookings['fromm'], $rowBookings['fromd'], $rowBookings['firstday'], $rowBookings['toy'], $rowBookings['tom'], $rowBookings['tod'], $rowBookings['lastday']);
            $sqlArchiveCheck = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
            if ($sqlArchiveCheck->num_rows > 0) {
              $rowArBooking = $sqlArchiveCheck->fetch_assoc();
              $editBookingArchiveSts = updateBookingArchive(
                $bookingBeId,
                $rowPlc['usrbeid'],
                $rowBookings['usrbeid'],
                $beId,
                $rowBookings['guestnum'],
                $rowArBooking['status'],
                $rowArBooking['paymentStatus'],
                $rowArBooking['source'],
                $rowPlc['currency'],
                $newTotalPrice,
                $rowArBooking['percentagefee'],
                $rowPlc['priceMode'],
                $rowPlc['workDayPrice'],
                $rowPlc['weekDayPrice'],
                $rowBookings['fromdate'],
                $rowBookings['fromd'],
                $rowBookings['fromm'],
                $rowBookings['fromy'],
                $rowBookings['firstday'],
                $rowBookings['todate'],
                $rowBookings['tod'],
                $rowBookings['tom'],
                $rowBookings['toy'],
                $rowBookings['lastday']
              );
            }
            if ($editBookingArchiveSts == "done") {
              if ($oldTotalPrice != $newTotalPrice) {
                $sqlNewTotalPrice = "UPDATE booking SET totalprice='$newTotalPrice' WHERE beid='$bookingBeId'";
                if (mysqli_query($link, $sqlNewTotalPrice)) {
                  if ($rowBookings['email'] != "-") {
                    pricingOfTheBookingHasBeenChangedMail(
                      getFrontendId($bookingBeId),
                      $sts,
                      $rowBookings['language'],
                      getFrontendId($beId),
                      $rowPlc['name'],
                      $rowBookings['status'],
                      $rowBookings['name'],
                      $rowBookings['email'],
                      getFrontendId($hostBeID),
                      $rowHost['firstname']." ".$rowHost['lastname'],
                      $rowHost['contactemail'],
                      $rowHost['contactphonenum'],
                      $rowHost['bankaccount'],
                      $rowHost['iban'],
                      $rowHost['bicswift'],
                      $rowBookings['fromy'],
                      $rowBookings['fromm'],
                      $rowBookings['fromd'],
                      $rowBookings['firstday'],
                      $rowBookings['toy'],
                      $rowBookings['tom'],
                      $rowBookings['tod'],
                      $rowBookings['lastday'],
                      $oldTotalPrice,
                      $oldTotalCurrency,
                      $newTotalPrice,
                      $oldTotalCurrency
                    );
                  } else {
                    notifyAboutChangeInPriceDone($sts);
                  }
                } else {
                  notifyAboutChangeInPriceError("notify about change in price - failed to update data <br>".mysqli_error($link), $sts);
                }
              } else {
                notifyAboutChangeInPriceDone($sts);
              }
            } else {
              error($sts, "Booking archive error: ".$editBookingArchiveSts);
            }
          }
        } else {
          if ($sts == "price-mode") {
            updatePlacePriceModeDone();
          } else if ($sts == "work-price") {
            updatePlacePriceWorkDone();
          } else {
            updatePlacePriceWeekDone();
          }
        }
      } else {
        error($sts, "notify about change in price - failed to get data about a host <br>".mysqli_error($link));
      }
    } else {
      error($sts, "notify about change in price - failed to get data about a place <br>".mysqli_error($link));
    }
  }

  function notifyAboutChangeInPriceError($msg, $sts) {
    global $bookingNotifiedAboutChangeErrorCount, $bookingNotifiedAboutChangeError;
    if ($bookingNotifiedAboutChangeError == "") {
      $bookingNotifiedAboutChangeError = $msg;
    } else {
      $bookingNotifiedAboutChangeError = $bookingNotifiedAboutChangeError."<br>".$msg;
    }
    ++$bookingNotifiedAboutChangeErrorCount;
    notifyAboutChangeInPriceDone($sts);
  }

  function notifyAboutChangeInPriceDone($sts) {
    global $bookingNotifiedAboutChangeCount;
    ++$bookingNotifiedAboutChangeCount;
    notifyAboutChangeInPriceOutput($sts);
  }

  function notifyAboutChangeInPriceOutput($sts) {
    global $bookingNotifiedAboutChangeTotal, $bookingNotifiedAboutChangeErrorCount, $bookingNotifiedAboutChangeError;
    if (notifyAboutChangeInPriceCheck()) {
      if ($bookingNotifiedAboutChangeError == "") {
        if ($sts == "price-mode") {
          updatePlacePriceModeDone();
        } else if ($sts == "work-price") {
          updatePlacePriceWorkDone();
        } else {
          updatePlacePriceWeekDone();
        }
        readyToReturnCheck();
      } else {
        if ($bookingNotifiedAboutChangeErrorCount != $bookingNotifiedAboutChangeTotal) {
          $bookingNotifiedAboutChangeError = "NACIP - failed ".$bookingNotifiedAboutChangeErrorCount."/".$bookingNotifiedAboutChangeTotal."<br>".$bookingNotifiedAboutChangeError;
        }
        error($sts, $bookingNotifiedAboutChangeError);
      }
    }
  }

  function notifyAboutChangeInPriceCheck() {
    global $bookingNotifiedAboutChangeTotal, $bookingNotifiedAboutChangeCount;
    if ($bookingNotifiedAboutChangeTotal <= $bookingNotifiedAboutChangeCount) {
      return true;
    } else {
      return false;
    }
  }

  function updatePlacePriceModeDone() {
    saveDone("price-mode");
  }

  function updatePlacePriceWorkDone() {
    saveDone("work-price");
  }

  function updatePlacePriceWeekDone() {
    saveDone("week-price");
  }
?>
