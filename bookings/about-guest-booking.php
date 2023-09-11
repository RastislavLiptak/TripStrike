<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "../uni/code/php-backend/total-price-calculator.php";
  $subtitle = $wrp_reservation;
  $idSts = "unset";
  $plcSts = "unset";
  if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];
    $idSts = "good";
  }
  $date = date("Y-m-d H:i:s");
  $guestName = "-";
  $guestEmail = "-";
  $guestPhone = "-";
  $guestID = "-";
  $bookingNotes = "";
  $lessthan48h = "0";
  $depositSts = "0";
  $fullAmountSts = "0";
  $priceDeposit = 0;
  if ($idSts == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingId'");
    if ($sqlBeId->num_rows > 0) {
      $bookingBeId = $sqlBeId->fetch_assoc()['beid'];
      if ($sign == "yes") {
        if ($bnft_add_cottage == "good") {
          $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' LIMIT 1");
          if ($sqlBooking->num_rows > 0) {
            $idSts = "good";
            $bookingRow = $sqlBooking->fetch_assoc();
            $priceDeposit = 10 * $bookingRow['totalprice'] / 100;
            $guestName = $bookingRow['name'];
            $guestEmail = $bookingRow['email'];
            $guestPhone = $bookingRow['phonenum'];
            $bookingNotes = $bookingRow['notes'];
            $lessthan48h = $bookingRow['lessthan48h'];
            $depositSts = $bookingRow['deposit'];
            $fullAmountSts = $bookingRow['fullAmount'];
            $arch_fee = 0;
            $arch_prcFee = 0;
            $arch_plcPriceMode = "nights";
            $arch_plcWorkPrice = 0;
            $arch_plcWeekPrice = 0;
            $sqlBookingArch = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
            if ($sqlBookingArch->num_rows > 0) {
              $bookingArchRow = $sqlBookingArch->fetch_assoc();
              $arch_fee = $bookingArchRow['fee'];
              $arch_prcFee = $bookingArchRow['percentagefee'];
              $arch_plcPriceMode = $bookingArchRow['plcpricemode'];
              $arch_plcWorkPrice = $bookingArchRow['plcworkprice'];
              $arch_plcWeekPrice = $bookingArchRow['plcweekprice'];
              if ($bookingArchRow['status'] == "rejected") {
                $idSts = "rejected";
              }
            }
            $guestBlockSts = false;
            $guestBlockName = "-";
            $guestBeID = $bookingRow['usrbeid'];
            if ($guestBeID != "-") {
              $sqlGuest = $link->query("SELECT * FROM users WHERE beid='$guestBeID' LIMIT 1");
              if ($sqlGuest->num_rows > 0) {
                $rowGuest = $sqlGuest->fetch_assoc();
                $guestBlockSts = true;
                $guestID = getFrontendId($guestBeID);
                $guestBlockName = $rowGuest['firstname']." ".$rowGuest['lastname'];
              }
            }
            $plcMaxGuestNum = "";
            $plcBeId = $bookingRow['plcbeid'];
            $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeId' LIMIT 1");
            if ($sqlPlace->num_rows > 0) {
              $plcRow = $sqlPlace->fetch_assoc();
              if ($plcRow['usrbeid'] == $usrBeId) {
                if ($plcRow['status'] == "active") {
                  $plcSts = "good";
                  $plcMaxGuestNum = $plcRow['guestNum'];
                  $sqlPlcMiniImgBeID = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$plcBeId' and type='mini' and sts='main' ORDER BY numid DESC LIMIT 1");
                  if ($sqlPlcMiniImgBeID->num_rows > 0) {
                    $plcMiniImgBeID = $sqlPlcMiniImgBeID->fetch_assoc()["imgbeid"];
                    $sqlPlcMiniImgSrc = $link->query("SELECT src FROM images WHERE name='$plcMiniImgBeID'");
                    if ($sqlPlcMiniImgSrc ->num_rows > 0) {
                      $plcMiniImgSrc = "../".$sqlPlcMiniImgSrc->fetch_assoc()['src'];
                    } else {
                      $plcMiniImgSrc = "#";
                    }
                  } else {
                    $plcMiniImgSrc = "#";
                  }
                } else {
                  $plcSts = "place-deleted";
                }
              } else {
                $idSts = "not-belong-to-you";
              }
            } else {
              $plcSts = "place-not-exist";
            }
          } else {
            $idSts = "beId-not-in-database";
          }
        } else {
          $idSts = "feature-not-available";
        }
      } else {
        $idSts = "not-signed-in";
      }
    } else {
      $idSts = "not-exist";
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/about-booking.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/permission-needed-modal.css">
    <script src="js/about-booking.js" async></script>
    <script src="js/about-guest-booking.js" async></script>
    <script src="../uni/code/js/add-currency.js" async></script>
    <script src="../uni/code/js/permission-needed-modal.js" async></script>
    <?php
      include "../uni/code/php-frontend/head.php";
      if ($idSts == "good") {
    ?>
        <title><?php echo $wrd_bookingDetails." - ".$bookingId." - ".$title; ?></title>
    <?php
      } else {
    ?>
        <title><?php echo $wrd_bookingDetails." - ".$title; ?></title>
    <?php
      }
    ?>
  </head>
  <body onload="<?php echo $onload; ?>aboutBookingsProgressSetScroll();aboutBookingsProgressFormTextareaAdjust();aboutGuestBookingsPlcID('<?php echo getFrontendId($plcBeId); ?>');">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($idSts == "good") {
    ?>
      <div id="about-booking-size">
        <div id="about-booking-layout">
          <div id="about-this-booking-layout">
            <div id="about-this-booking-btn-wrp">
              <?php
                if ($bookingRow['status'] == "waiting") {
              ?>
              <button type="button" class="btn btn-mid btn-prim about-this-booking-btn" id="about-this-booking-btn-confirm" onclick="aboutConfirmBooking('<?php echo $bookingRow['fromd'] ?>', '<?php echo $bookingRow['fromm'] ?>', '<?php echo $bookingRow['fromy'] ?>', '<?php echo $bookingRow['tod'] ?>', '<?php echo $bookingRow['tom'] ?>', '<?php echo $bookingRow['toy'] ?>');"><?php echo $wrd_confirm; ?></button>
              <button type="button" class="btn btn-mid btn-fth about-this-booking-btn" id="about-this-booking-btn-reject" onclick="aboutRejectBooking('<?php echo $bookingRow['fromd'] ?>', '<?php echo $bookingRow['fromm'] ?>', '<?php echo $bookingRow['fromy'] ?>', '<?php echo $bookingRow['tod'] ?>', '<?php echo $bookingRow['tom'] ?>', '<?php echo $bookingRow['toy'] ?>');"><?php echo $wrd_reject; ?></button>
              <?php
                } else if ($bookingRow['status'] == "booked") {
              ?>
                  <button type="button" class="btn btn-mid btn-prim about-this-booking-btn" id="about-this-booking-btn-save" onclick="aboutSaveBooking();"><?php echo $wrd_save; ?></button>
                  <button type="button" class="btn btn-mid btn-fth about-this-booking-btn" id="about-this-booking-btn-cancel-booking" onclick="modCover('show', 'modal-cover-cancel-booking');"><?php echo $wrd_cancelBooking; ?></button>
              <?php
                }
              ?>
            </div>
            <div id="about-this-booking-error-wrp">
              <p id="about-this-booking-error-txt"></p>
            </div>
            <div id="about-this-booking-form-wrp">
              <?php
                if ($bookingRow['status'] == "waiting") {
                  $disabledAttribute = "disabled";
                } else if ($bookingRow['status'] == "booked") {
                  $disabledAttribute = "";
                } else if ($bookingRow['status'] == "canceled") {
                  $disabledAttribute = "disabled";
                }
                if ($bookingRow['status'] != "waiting") {
              ?>
                  <div class="about-this-booking-form-line about-this-booking-form-first-line">
                    <div class="about-this-booking-form-about">
                      <p class="about-this-booking-form-title"><?php echo $wrd_name.":"; ?></p>
                      <textarea class="about-this-booking-form-textarea" id="about-this-booking-form-textarea-name" placeholder="<?php echo $wrd_fullname; ?>" oninput="aboutBookingsProgressFormTextarea(this)" onkeyup="aboutBookingsProgressFormTextarea(this)" <?php echo $disabledAttribute; ?>><?php echo $guestName; ?></textarea>
                    </div>
                    <div class="about-this-booking-form-error-wrp">
                      <p class="about-this-booking-form-error-txt" id="about-this-booking-form-error-txt-name"></p>
                    </div>
                  </div>
                  <div class="about-this-booking-form-line">
                    <div class="about-this-booking-form-about">
                      <p class="about-this-booking-form-title"><?php echo $wrd_email.":"; ?></p>
                      <textarea class="about-this-booking-form-textarea" id="about-this-booking-form-textarea-email" placeholder="<?php echo "email@email.com"; ?>" oninput="aboutBookingsProgressFormTextarea(this)" onkeyup="aboutBookingsProgressFormTextarea(this)" <?php echo $disabledAttribute; ?>><?php echo $guestEmail; ?></textarea>
                    </div>
                    <div class="about-this-booking-form-error-wrp">
                      <p class="about-this-booking-form-error-txt" id="about-this-booking-form-error-txt-email"></p>
                    </div>
                  </div>
                  <div class="about-this-booking-form-line">
                    <div class="about-this-booking-form-about">
                      <p class="about-this-booking-form-title"><?php echo $wrd_phoneNumber.":"; ?></p>
                      <textarea class="about-this-booking-form-textarea" id="about-this-booking-form-textarea-phone" placeholder="<?php echo '+'.$wrd_code.' 000 000 000'; ?>" oninput="aboutBookingsProgressFormTextarea(this)" onkeyup="aboutBookingsProgressFormTextarea(this)" <?php echo $disabledAttribute; ?>><?php echo $guestPhone; ?></textarea>
                    </div>
                    <div class="about-this-booking-form-error-wrp">
                      <p class="about-this-booking-form-error-txt" id="about-this-booking-form-error-txt-phone"></p>
                    </div>
                  </div>
              <?php
                }
              ?>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_guestNum.":"; ?></p>
                  <div class="about-this-booking-form-data-wrp">
                    <div class="about-this-booking-form-data-txt-size">
                      <div class="about-this-booking-form-data-txt-wrp">
                        <p class="about-this-booking-form-data-txt"><?php echo $bookingRow['guestnum']; ?></p>
                      </div>
                    </div>
                    <?php
                      if ($bookingRow['status'] != "waiting") {
                    ?>
                      <div class="about-this-booking-form-data-btn-wrp">
                        <button type="button" class="about-this-booking-form-data-btn" onclick="aboutBookingsChangeGuestsNumModal('show');" <?php echo $disabledAttribute; ?>><?php echo $wrd_change; ?></button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt"></p>
                </div>
              </div>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_from.":"; ?></p>
                  <div class="about-this-booking-form-data-wrp">
                    <div class="about-this-booking-form-data-txt-size">
                      <div class="about-this-booking-form-data-txt-wrp">
                        <p class="about-this-booking-form-data-txt">
                          <?php
                            if ($bookingRow['firstday'] == "half") {
                              echo $bookingRow['fromd'].".".$bookingRow['fromm'].".".$bookingRow['fromy']." (".$wrd_from." 14:00)";
                            } else {
                              echo $bookingRow['fromd'].".".$bookingRow['fromm'].".".$bookingRow['fromy']." (".$wrd_theWholeDay.")";
                            }
                          ?>
                        </p>
                      </div>
                    </div>
                    <?php
                      if ($bookingRow['status'] != "waiting") {
                    ?>
                      <div class="about-this-booking-form-data-btn-wrp">
                        <button type="button" class="about-this-booking-form-data-btn" onclick="aboutBookingsChangeDatesModal('show');" <?php echo $disabledAttribute; ?>><?php echo $wrd_change; ?></button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt"></p>
                </div>
              </div>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_to.":"; ?></p>
                  <div class="about-this-booking-form-data-wrp">
                    <div class="about-this-booking-form-data-txt-size">
                      <div class="about-this-booking-form-data-txt-wrp">
                        <p class="about-this-booking-form-data-txt">
                          <?php
                            if ($bookingRow['lastday'] == "half") {
                              echo $bookingRow['tod'].".".$bookingRow['tom'].".".$bookingRow['toy']." (".$wrd_to." 11:00)";
                            } else {
                              echo $bookingRow['tod'].".".$bookingRow['tom'].".".$bookingRow['toy']." (".$wrd_theWholeDay.")";
                            }
                          ?>
                        </p>
                      </div>
                    </div>
                    <?php
                      if ($bookingRow['status'] != "waiting") {
                    ?>
                      <div class="about-this-booking-form-data-btn-wrp">
                        <button type="button" class="about-this-booking-form-data-btn" onclick="aboutBookingsChangeDatesModal('show');" <?php echo $disabledAttribute; ?>><?php echo $wrd_change; ?></button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt"></p>
                </div>
              </div>
              <?php
                if ($priceDeposit >= 5) {
                  if ($depositSts == "1") {
                    $depositCheckboxSts = "checked";
                  } else {
                    $depositCheckboxSts = "";
                  }
              ?>
                  <div class="about-this-booking-form-line">
                    <div class="about-this-booking-form-about">
                      <p class="about-this-booking-form-title"><?php echo $wrd_deposit.":"; ?></p>
                      <div class="about-this-booking-form-data-wrp">
                        <div class="about-this-booking-form-data-txt-size">
                          <div class="about-this-booking-form-data-txt-wrp">
                            <p class="about-this-booking-form-data-txt"><?php echo addCurrency($bookingRow['totalcurrency'], $priceDeposit); ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                      if ($bookingRow['status'] != "waiting") {
                    ?>
                        <div class="about-this-booking-form-desc">
                          <label class="checkbox-label-mid"><?php echo $wrd_theDepositIsPaid; ?>
                            <input type="checkbox" id="about-this-booking-form-checkbox-deposit" <?php echo $depositCheckboxSts; ?> <?php echo $disabledAttribute; ?> onchange="aboutBookingsPriceCheckboxHandler('deposit')">
                            <span class="checkmark-inpt-mid"></span>
                          </label>
                        </div>
                    <?php
                      }
                    ?>
                  </div>
              <?php
                }
                if ($fullAmountSts == "1") {
                  $fullAmountCheckboxSts = "checked";
                } else {
                  $fullAmountCheckboxSts = "";
                }
              ?>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_fullAmount.":"; ?></p>
                  <div class="about-this-booking-form-data-wrp">
                    <div class="about-this-booking-form-data-txt-size">
                      <div class="about-this-booking-form-data-txt-wrp">
                        <p class="about-this-booking-form-data-txt"><?php echo addCurrency($bookingRow['totalcurrency'], $bookingRow['totalprice']); ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
                  if ($bookingRow['status'] != "waiting") {
                ?>
                    <div class="about-this-booking-form-desc">
                      <label class="checkbox-label-mid"><?php echo $wrd_theFullAmountIsPaid; ?>
                        <input type="checkbox" id="about-this-booking-form-checkbox-total-price" <?php echo $fullAmountCheckboxSts; ?> <?php echo $disabledAttribute; ?> onchange="aboutBookingsPriceCheckboxHandler('total-price')">
                        <span class="checkmark-inpt-mid"></span>
                      </label>
                    </div>
                <?php
                  }
                ?>
              </div>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_fee.":"; ?></p>
                  <div class="about-this-booking-form-data-wrp">
                    <div class="about-this-booking-form-data-txt-size">
                      <div class="about-this-booking-form-data-txt-wrp">
                        <p class="about-this-booking-form-data-txt"><?php echo addCurrency($bookingRow['totalcurrency'], $arch_fee). " (".floatval($arch_prcFee)."%)"; ?></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
              if (new DateTime() < new DateTime($bookingRow['fromdate']) && $bookingRow['status'] == "booked") {
                $sqlUpdateRequests = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' ORDER BY fulldate DESC");
                if ($sqlUpdateRequests->num_rows > 0) {
            ?>
                  <div id="about-this-booking-about-wrp">
                    <div class="about-booking-title-wrp">
                      <p class="about-booking-title"><?php echo $wrd_requestsForChangesInTheBooking ; ?></p>
                    </div>
                    <div id="about-this-booking-about-update-requests-list">
            <?php
                  while($updReq = $sqlUpdateRequests->fetch_assoc()) {
                    $reqID = getFrontendId($updReq['beid']);
                    $reqFirstDay = "";
                    $reqLastDay = "";
                    if ($updReq['firstday'] == "whole") {
                      $reqFirstDay = $wrd_theWholeDay;
                    } else {
                      $reqFirstDay = $wrd_from." 14:00";
                    }
                    if ($updReq['lastday'] == "whole") {
                      $reqLastDay = $wrd_theWholeDay;
                    } else {
                      $reqLastDay = $wrd_to." 11:00";
                    }
                    if ($updReq['type'] == "date") {
                      $reqPrice = totalPriceCalc(
                        $plcBeId,
                        $bookingRow['guestnum'],
                        $updReq['fromy'],
                        $updReq['fromm'],
                        $updReq['fromd'],
                        $updReq['firstday'],
                        $updReq['toy'],
                        $updReq['tom'],
                        $updReq['tod'],
                        $updReq['lastday']
                      );
                    } else if ($updReq['type'] == "guests") {
                      $reqPrice = totalPriceCalc(
                        $plcBeId,
                        $updReq['guestNum'],
                        $bookingRow['fromy'],
                        $bookingRow['fromm'],
                        $bookingRow['fromd'],
                        $bookingRow['firstday'],
                        $bookingRow['toy'],
                        $bookingRow['tom'],
                        $bookingRow['tod'],
                        $bookingRow['lastday']
                      );
                    }
                    if ($reqPrice < $bookingRow['totalprice']) {
                      $reqPriceDiff = $bookingRow['totalprice'] - $reqPrice;
                      $reqPriceDiff = "-".$reqPriceDiff;
                    } else if ($reqPrice > $bookingRow['totalprice']) {
                      $reqPriceDiff = $reqPrice - $bookingRow['totalprice'];
                      $reqPriceDiff = "+".$reqPriceDiff;
                    } else {
                      $reqPriceDiff = 0;
                    }
                    $reqFee = $arch_prcFee * $reqPrice / 100;
                    if ($reqFee < $arch_fee) {
                      $reqFeeDiff = $arch_fee - $reqFee;
                      $reqFeeDiff = "-".$reqFeeDiff;
                    } else if ($reqFee > $arch_fee) {
                      $reqFeeDiff = $reqFee - $arch_fee;
                      $reqFeeDiff = "+".$reqFeeDiff;
                    } else {
                      $reqFeeDiff = 0;
                    }
                    if ($updReq['type'] == "date") {
                      $reqNewData = $wrd_newDate.". <b>".$updReq['fromd'].".".$updReq['fromm'].".".$updReq['fromy']." (".$reqFirstDay.") - ".$updReq['tod'].".".$updReq['tom'].".".$updReq['toy']." (".$reqLastDay.")</b>";
                    } else {
                      $reqNewData = $wrd_newNumberOfGuests.". <b>".$updReq['guestNum']."</b>";
                    }
                    if ($reqPriceDiff == 0) {
                      $reqNewPrice = $wrd_thePriceForTheBookingIsNotAffectedByThisUpdateAndIs." ".addCurrency($bookingRow['totalcurrency'], $bookingRow['totalprice']);;
                    } else {
                      $reqNewPrice = $wrd_newPriceForBooking." <b>".addCurrency($bookingRow['totalcurrency'], $reqPrice)." (".$wrd_difference.": ".addCurrency($bookingRow['totalcurrency'], $reqPriceDiff).")</b>";
                    }
                    if ($reqFeeDiff == 0) {
                      $reqNewFee = $wrd_theFeeForTheBookingIsNotAffectedByThisUpdateAndIs." ".addCurrency($bookingRow['totalcurrency'], $arch_fee);
                    } else {
                      $reqNewFee = $wrd_newFeeForBooking." <b>".addCurrency($bookingRow['totalcurrency'], $reqFee)." (".$wrd_difference.": ".addCurrency($bookingRow['totalcurrency'], $reqFeeDiff).")</b>";
                    }
            ?>
                      <div class="about-this-booking-about-update-request-block">
                        <div class="about-this-booking-about-update-request-layout">
                          <div class="about-this-booking-about-update-request-line">
                            <p class="about-this-booking-about-update-request-txt"><?php echo $reqNewData; ?></p>
                          </div>
                          <div class="about-this-booking-about-update-request-line">
                            <p class="about-this-booking-about-update-request-txt"><?php echo $reqNewPrice; ?></p>
                          </div>
                          <div class="about-this-booking-about-update-request-line">
                            <p class="about-this-booking-about-update-request-txt"><?php echo $reqNewFee; ?></p>
                          </div>
                          <div class="about-this-booking-about-update-request-error-wrp" id="about-this-booking-about-update-request-error-wrp-<?php echo $reqID; ?>">
                            <p class="about-this-booking-about-update-request-error-txt" id="about-this-booking-about-update-request-error-txt-<?php echo $reqID; ?>"></p>
                          </div>
                          <div class="about-this-booking-about-update-request-footer">
                            <button type="button" class="btn btn-sml btn-fth about-this-booking-about-update-request-footer-btn" id="about-this-booking-about-update-request-footer-btn-reject-<?php echo $reqID; ?>" onclick="bookingUpdateRequestReject('<?php echo $reqID; ?>')"><?php echo $wrd_reject; ?></button>
                            <button type="button" class="btn btn-sml btn-prim about-this-booking-about-update-request-footer-btn" id="about-this-booking-about-update-request-footer-btn-confirm-<?php echo $reqID; ?>" onclick="bookingUpdateRequestConfirmCheck('<?php echo $reqID; ?>')"><?php echo $wrd_confirm; ?></button>
                          </div>
                        </div>
                      </div>
            <?php
                  }
            ?>
                    </div>
                  </div>
            <?php
                }
              }
            ?>
            <div id="about-this-booking-about-wrp">
              <div class="about-booking-title-wrp">
                <p class="about-booking-title"><?php echo $wrd_detailedInformationAboutTheBooking; ?></p>
              </div>
              <div id="about-this-booking-about-notifications-list">
                <?php
                  if ($bookingRow['status'] == "booked") {
                    $bookedInfoCounter = 0;
                    if (getAccountData($usrBeId, "cancel-unpaid-bookings") == "1") {
                      if ($depositSts == "0" && $fullAmountSts == "0" && $lessthan48h != "1") {
                        ++$bookedInfoCounter;
                        $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($bookingRow['fulldate']));
                        $tillCancel = floor($diff / 3600);
                        $tillCancel = 48 - $tillCancel;
                        if ($tillCancel <= 0) {
                          $countdownText = $wrd_thisReservationWillBeAutomaticallyCanceledAtAnyTimeIfThisHasNotAlreadyBeenDoneToPreventThisYouMustCheckTheBoxForTheDepositPaidOrTheFullAmount;
                        } else if ($tillCancel == 1) {
                          $countdownText = $wrd_thereAre1." ".$tillCancel." ".$wrd_hour." ".$wrd_untilThisReservationIsAutomaticallyCanceled;
                        } else if ($tillCancel >= 2 && $tillCancel <= 4) {
                          $countdownText = $wrd_thereAre2." ".$tillCancel." ".$wrd_hours1." ".$wrd_untilThisReservationIsAutomaticallyCanceled;
                        } else {
                          $countdownText = $wrd_thereAre1." ".$tillCancel." ".$wrd_hours2." ".$wrd_untilThisReservationIsAutomaticallyCanceled;
                        }
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $countdownText; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                      }
                    }
                    if ($lessthan48h == "1") {
                      ++$bookedInfoCounter;
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-red">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $wrd_theReservationWasMadeLessThan48HoursBeforeItsStartTheGuestHasBeenInformedToReportByPhone; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                    }
                    if ($plcSts == "place-deleted") {
                      ++$bookedInfoCounter;
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $wrd_placeHasBeenDeletedButBookingIsBooked; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                    }
                    if ($bookedInfoCounter == 0) {
                      if ($bookingRow['todate'] > $date) {
                        $defaultNotifyText = $wrd_bookingIsActive;
                      } else {
                        $defaultNotifyText = $wrd_thisBookingHasAlreadyTakenPlace;
                      }
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $defaultNotifyText; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                    }
                  } else if ($bookingRow['status'] == "canceled") {
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-red">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $wrd_thisBookingHasAlreadyBeenCanceled; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                  } else if ($bookingRow['status'] == "waiting") {
                ?>
                    <div class="about-this-booking-about-notification-block">
                      <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                        <div class="about-this-booking-about-notification-layout">
                          <div class="about-this-booking-about-notification-txt-wrp">
                            <div class="about-this-booking-about-notification-txt-size">
                              <p class="about-this-booking-about-notification-txt"><?php echo $wrd_byConfirmingTheBookingYouWillBeProvidedWithAllInformationAboutThisBookingSuchAsTheNameAndContactDetailsOfTheGuestAtTheSameTimeTheGuestWillReceivePaymentInstructionsRejectionWillCancelTheBooking; ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>
          <div id="about-booking-facility-layout">
            <div class="about-booking-facility-blck">
              <div class="about-booking-title-wrp">
                <p class="about-booking-title"><?php echo $wrd_aboutTheCottage; ?></p>
              </div>
              <div class="about-booking-facility-card">
                <?php
                  if ($plcSts == "good") {
                ?>
                    <div class="about-booking-facility-card-layout">
                      <div id="about-booking-facility-place-layout">
                        <div id="about-booking-facility-place-img-wrp">
                          <?php
                            if ($plcMiniImgSrc != "#") {
                          ?>
                            <img src="<?php echo $plcMiniImgSrc; ?>" alt="place image" id="about-booking-facility-place-img">
                          <?php
                            } else {
                          ?>
                            <div id="about-booking-facility-place-img-fake"></div>
                          <?php
                            }
                          ?>
                        </div>
                        <div id="about-booking-facility-place-name-wrp">
                          <div id="about-booking-facility-place-name-size">
                            <p id="about-booking-facility-place-name-txt"><?php echo $plcRow['name']; ?></p>
                          </div>
                        </div>
                      </div>
                      <div id="about-booking-facility-place-btn-wrp">
                        <button type="button" id="about-booking-facility-place-btn" onclick="location.href = '../place/?id=<?php echo getFrontendId($plcBeId); ?>'"><?php echo $wrd_visitTheCottagePage; ?></button>
                      </div>
                    </div>
                <?php
                  } else {
                ?>
                    <div class="about-booking-facility-alt-txt-wrp">
                      <p class="about-booking-facility-alt-txt">
                <?php
                        if ($plcSts == "place-deleted") {
                          echo $wrd_placeDeleted;
                        } else if ($plcSts == "place-not-exist") {
                          echo $wrd_cottageWithIdNotExist;
                        }
                ?>
                      </p>
                    </div>
                <?php
                  }
                ?>
              </div>
            </div>
            <?php
              if ($guestBlockSts && $bookingRow['status'] != "waiting") {
            ?>
                <div class="about-booking-facility-blck">
                  <div class="about-booking-title-wrp">
                    <p class="about-booking-title"><?php echo $wrd_aboutTheGuest; ?></p>
                  </div>
                  <div class="about-booking-facility-card">
                    <div class="about-booking-facility-card-layout">
                      <?php
                        if ($guestBeID == $usrBeId) {
                          $guestCardID = "about-booking-facility-card-i-am-the-guest";
                        } else {
                          $guestCardID = "";
                        }
                      ?>
                      <div class="about-booking-facility-card-layout" id="<?php echo $guestCardID; ?>">
                        <div id="about-booking-facility-host-layout">
                          <div id="about-booking-facility-host-img-wrp">
                            <img src="../<?php echo getAccountData($guestBeID, "medium-profile-image"); ?>" alt="profile image of the host" id="about-booking-facility-host-img">
                          </div>
                          <div id="about-booking-facility-host-txt-layout">
                            <div class="about-booking-facility-host-txt-wrp">
                              <a href="../user/?id=<?php echo getFrontendId($guestBeID); ?>&section=about" id="about-booking-facility-host-name"><?php echo $guestBlockName; ?></a>
                            </div>
                            <div class="about-booking-facility-host-txt-wrp">
                              <a href="../user/?id=<?php echo getFrontendId($guestBeID); ?>&section=about" class="about-booking-facility-host-txt-show-more"><?php echo $wrd_showMore; ?></a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            <?php
              }
              if ($bookingRow['status'] != "waiting") {
            ?>
                <div class="about-booking-facility-blck">
                  <div class="about-booking-title-wrp">
                    <p class="about-booking-title"><?php echo $wrd_notes; ?></p>
                  </div>
                  <textarea class="about-booking-facility-textarea" id="about-booking-facility-textarea-notes" placeholder="<?php echo $wrd_type; ?>" <?php echo $disabledAttribute ?>><?php echo $bookingNotes; ?></textarea>
                  <div class="about-booking-facility-desc-wrp">
                    <p class="about-booking-facility-desc-txt"><?php echo $wrd_onlyYouCanSeeTheNotesTheyAreNotDisplayedToGuests; ?></p>
                  </div>
                </div>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
      <?php
        if ($bookingRow['status'] == "booked") {
      ?>
          <div class="modal-cover" id="modal-cover-cancel-booking">
            <div class="modal-block" id="modal-cover-cancel-booking-blck">
              <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-cancel-booking');"></button>
              <div id="cancel-booking-ask-wrp">
                <div id="cancel-booking-ask-layout">
                  <div id="cancel-booking-ask-txt-wrp">
                    <p id="cancel-booking-ask-txt-title"><?php echo $wrd_areYouSureYouWantToCancelThisBooking; ?></p>
                    <p id="cancel-booking-ask-txt-desc"><?php echo $wrd_ifYouClickYesThisBookingWillBeCanceledAndguestWillBeNotified; ?></p>
                  </div>
                  <div id="cancel-booking-ask-btn-wrp">
                    <button type="button" class="btn btn-mid btn-sec cancel-booking-ask-btn" onclick="modCover('hide', 'modal-cover-cancel-booking');"><?php echo $wrd_no; ?></button>
                    <button type="button" class="btn btn-mid btn-fth cancel-booking-ask-btn" onclick="aboutCancelBooking('<?php echo $bookingRow['fromd'] ?>', '<?php echo $bookingRow['fromm'] ?>', '<?php echo $bookingRow['fromy'] ?>', '<?php echo $bookingRow['tod'] ?>', '<?php echo $bookingRow['tom'] ?>', '<?php echo $bookingRow['toy'] ?>');"><?php echo $wrd_yes; ?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
      <?php
        }
        if ($bookingRow['status'] != "waiting") {
      ?>
        <div class="modal-cover" id="modal-cover-change-data-dates">
          <div class="modal-block" id="modal-cover-change-data-dates-blck">
            <button class="cancel-btn" onclick="aboutBookingsChangeDatesModal('hide');"></button>
            <div class="change-data-content-wrp">
              <div class="change-data-content-about-wrp">
                <p id="change-data-content-about-txt-d-num-of-guests"><?php echo $bookingRow['guestnum']; ?></p>
                <p id="change-data-content-about-txt-d-from-d"><?php echo $bookingRow['fromd']; ?></p>
                <p id="change-data-content-about-txt-d-from-m"><?php echo $bookingRow['fromm']; ?></p>
                <p id="change-data-content-about-txt-d-from-y"><?php echo $bookingRow['fromy']; ?></p>
                <p id="change-data-content-about-txt-d-firstday"><?php echo $bookingRow['firstday']; ?></p>
                <p id="change-data-content-about-txt-d-to-d"><?php echo $bookingRow['tod']; ?></p>
                <p id="change-data-content-about-txt-d-to-m"><?php echo $bookingRow['tom']; ?></p>
                <p id="change-data-content-about-txt-d-to-y"><?php echo $bookingRow['toy']; ?></p>
                <p id="change-data-content-about-txt-d-lastday"><?php echo $bookingRow['lastday']; ?></p>
                <p id="change-data-content-about-txt-d-total"><?php echo $bookingRow['totalprice']; ?></p>
                <p id="change-data-content-about-txt-d-price-mode"><?php echo $arch_plcPriceMode; ?></p>
                <p id="change-data-content-about-txt-d-price-currency"><?php echo $bookingRow['totalcurrency']; ?></p>
                <p id="change-data-content-about-txt-d-work-price"><?php echo $arch_plcWorkPrice; ?></p>
                <p id="change-data-content-about-txt-d-week-price"><?php echo $arch_plcWeekPrice; ?></p>
              </div>
              <div class="change-data-layout">
                <div class="change-data-input-layout">
                  <div class="change-data-input-row">
                    <p class="change-data-input-title"><?php echo $wrd_from.":"; ?></p>
                    <div class="change-data-input-field-wrp">
                      <?php
                        $fromDateFormat = $bookingRow['fromy']."-".sprintf("%02d", $bookingRow['fromm'])."-".sprintf("%02d", $bookingRow['fromd']);
                        if ($bookingRow['firstday'] == "half") {
                          $fromSelect1Option = "selected";
                          $fromSelect2Option = "";
                        } else {
                          $fromSelect1Option = "";
                          $fromSelect2Option = "selected";
                        }
                      ?>
                      <input type="date" class="change-data-input" id="change-data-input-from-date" value="<?php echo $fromDateFormat; ?>" onchange="aboutGuestBookingChangeDataDatesPrice();" onkeyup="aboutGuestBookingChangeDataDatesPrice();">
                      <select class="change-data-select" id="change-data-select-firstday" onchange="aboutGuestBookingChangeDataDatesPrice();">
                        <option value="half" <?php echo $fromSelect1Option; ?>><?php echo $wrd_from." 14:00"; ?></option>
                        <option value="whole" <?php echo $fromSelect2Option; ?>><?php echo $wrd_theWholeDay; ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="change-data-input-row">
                    <p class="change-data-input-title"><?php echo $wrd_to.":"; ?></p>
                    <div class="change-data-input-field-wrp">
                      <?php
                        $toDateFormat = $bookingRow['toy']."-".sprintf("%02d", $bookingRow['tom'])."-".sprintf("%02d", $bookingRow['tod']);
                        if ($bookingRow['lastday'] == "half") {
                          $toSelect1Option = "selected";
                          $toSelect2Option = "";
                        } else {
                          $toSelect1Option = "";
                          $toSelect2Option = "selected";
                        }
                      ?>
                      <input type="date" class="change-data-input" id="change-data-input-to-date" value="<?php echo $toDateFormat; ?>" onchange="aboutGuestBookingChangeDataDatesPrice();" onkeyup="aboutGuestBookingChangeDataDatesPrice();">
                      <select class="change-data-select" id="change-data-select-lastday" onchange="aboutGuestBookingChangeDataDatesPrice();">
                        <option value="half" <?php echo $toSelect1Option; ?>><?php echo $wrd_to." 11:00"; ?></option>
                        <option value="whole" <?php echo $toSelect2Option; ?>><?php echo $wrd_theWholeDay; ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="change-data-input-row change-data-input-row-new-price" id="change-data-input-row-new-price-dates">
                    <p class="change-data-input-title"><?php echo $wrd_newTotalAmount.":"; ?></p>
                    <div class="change-data-input-field-wrp">
                      <div class="change-data-new-price-txt-wrp">
                        <p class="change-data-new-price-txt" id="change-data-new-price-txt-dates"></p>
                      </div>
                    </div>
                  </div>
                  <div class="change-data-input-row change-data-input-row-new-price" id="change-data-input-row-new-price-dates-diff">
                    <p class="change-data-input-subtitle"><?php echo $wrd_difference.":"; ?></p>
                    <div class="change-data-input-field-wrp">
                      <div class="change-data-new-price-txt-wrp">
                        <p class="change-data-new-price-subtxt" id="change-data-new-price-txt-dates-diff"></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="change-data-btn-wrp">
                  <button type="button" class="btn btn-mid btn-prim" id="change-data-btn-dates-save" onclick="modCover('hide', 'modal-cover-change-data-dates');aboutSaveBooking();"><?php echo $wrd_save; ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-cover" id="modal-cover-change-data-number-of-guests">
          <div class="modal-block" id="modal-cover-change-data-number-of-guests-blck">
            <button class="cancel-btn" onclick="aboutBookingsChangeGuestsNumModal('hide');"></button>
            <div class="change-data-content-wrp">
              <div class="change-data-content-about-wrp">
                <p id="change-data-content-about-txt-g-num-of-guests"><?php echo $bookingRow['guestnum']; ?></p>
                <p id="change-data-content-about-txt-g-from-d"><?php echo $bookingRow['fromd']; ?></p>
                <p id="change-data-content-about-txt-g-from-m"><?php echo $bookingRow['fromm']; ?></p>
                <p id="change-data-content-about-txt-g-from-y"><?php echo $bookingRow['fromy']; ?></p>
                <p id="change-data-content-about-txt-g-firstday"><?php echo $bookingRow['firstday']; ?></p>
                <p id="change-data-content-about-txt-g-to-d"><?php echo $bookingRow['tod']; ?></p>
                <p id="change-data-content-about-txt-g-to-m"><?php echo $bookingRow['tom']; ?></p>
                <p id="change-data-content-about-txt-g-to-y"><?php echo $bookingRow['toy']; ?></p>
                <p id="change-data-content-about-txt-g-lastday"><?php echo $bookingRow['lastday']; ?></p>
                <p id="change-data-content-about-txt-g-total"><?php echo $bookingRow['totalprice']; ?></p>
                <p id="change-data-content-about-txt-g-price-mode"><?php echo $arch_plcPriceMode; ?></p>
                <p id="change-data-content-about-txt-g-price-currency"><?php echo $bookingRow['totalcurrency']; ?></p>
                <p id="change-data-content-about-txt-g-work-price"><?php echo $arch_plcWorkPrice; ?></p>
                <p id="change-data-content-about-txt-g-week-price"><?php echo $arch_plcWeekPrice; ?></p>
              </div>
              <div class="change-data-layout">
                <div class="change-data-input-layout">
                  <div class="change-data-input-row">
                    <p class="change-data-input-title"><?php echo $wrd_guestNum.":"; ?></p>
                    <div class="change-data-input-field-wrp">
                      <input type="number" value="<?php echo $bookingRow['guestnum']; ?>" min="1" max="<?php echo $plcRow['guestNum']; ?>" class="change-data-input" id="change-data-input-number-of-guests" onchange="aboutGuestBookingChangeDataNumOfGuestsPrice();" onkeyup="aboutGuestBookingChangeDataNumOfGuestsPrice();">
                    </div>
                  </div>
                  <?php
                    if ($plcRow['priceMode'] == "guests") {
                  ?>
                      <div class="change-data-input-row change-data-input-row-new-price" id="change-data-input-row-new-price-guests">
                        <p class="change-data-input-title"><?php echo $wrd_newTotalAmount.":"; ?></p>
                        <div class="change-data-input-field-wrp">
                          <div class="change-data-new-price-txt-wrp">
                            <p class="change-data-new-price-txt" id="change-data-new-price-txt-guests"></p>
                          </div>
                        </div>
                      </div>
                      <div class="change-data-input-row change-data-input-row-new-price" id="change-data-input-row-new-price-guests-diff">
                        <p class="change-data-input-subtitle"><?php echo $wrd_difference.":"; ?></p>
                        <div class="change-data-input-field-wrp">
                          <div class="change-data-new-price-txt-wrp">
                            <p class="change-data-new-price-subtxt" id="change-data-new-price-txt-guests-diff"></p>
                          </div>
                        </div>
                      </div>
                  <?php
                    }
                  ?>
                </div>
                <div class="change-data-btn-wrp">
                  <button type="button" class="btn btn-mid btn-prim" id="change-data-btn-guests-save" onclick="modCover('hide', 'modal-cover-change-data-number-of-guests');aboutSaveBooking();"><?php echo $wrd_save; ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php
        }
        include "../uni/code/php-frontend/permission-needed-modal.php";
      } else {
    ?>
      <div class="page-error">
        <p class="page-error-p">
          <?php
            if ($idSts == "not-exist") {
              echo $wrd_bookingWithThisIDDoesNotExist;
            } else if ($idSts == "not-signed-in") {
              echo $wrd_signInToUseThisFeature;
            } else if ($idSts == "not-belong-to-you") {
              echo $wrd_youDoNotHaveTheRequiredRightsToViewThisBooking;
            } else if ($idSts == "feature-not-available") {
              echo $wrd_featureNotAvailable;
            } else if ($idSts == "beId-not-in-database") {
              echo $wrd_noBookingFound;
            } else if ($idSts == "rejected") {
              echo $wrd_bookingWasRejected;
            }
          ?>
        </p>
      </div>
    <?php
      }
    ?>
  </body>
</html>
