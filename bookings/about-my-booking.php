<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "../uni/code/php-backend/total-price-calculator.php";
  $subtitle = $wrp_reservation;
  $idSts = "unset";
  $plcSts = "unset";
  $hstSts = "unset";
  if (isset($_GET['id'])) {
    $bookingId = $_GET['id'];
    $idSts = "good";
  }
  $date = date("Y-m-d H:i:s");
  $priceDeposit = 0;
  if ($idSts == "good") {
    $sqlBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingId'");
    if ($sqlBeId->num_rows > 0) {
      $bookingBeId = $sqlBeId->fetch_assoc()['beid'];
      if ($sign == "yes") {
        $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' and usrbeid='$usrBeId' LIMIT 1");
        if ($sqlBooking->num_rows > 0) {
          $idSts = "good";
          $bookingRow = $sqlBooking->fetch_assoc();
          $priceDeposit = 10 * $bookingRow['totalprice'] / 100;
          $placeBeID = $bookingRow['plcbeid'];
          $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$placeBeID' LIMIT 1");
          if ($sqlPlace->num_rows > 0) {
            $plcRow = $sqlPlace->fetch_assoc();
            if ($plcRow['status'] == "active") {
              $plcSts = "good";
              $sqlPlcMiniImgBeID = $link->query("SELECT imgbeid FROM placeimages WHERE cottbeid='$placeBeID' and type='mini' and sts='main' ORDER BY numid DESC LIMIT 1");
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
            $hostBeID = $plcRow['usrbeid'];
            if ($hostBeID != "-") {
              $sqlHost = $link->query("SELECT * FROM users WHERE beid='$hostBeID' LIMIT 1");
              if ($sqlHost->num_rows > 0) {
                $hstRow = $sqlHost->fetch_assoc();
                if ($hstRow['status'] == "active") {
                  $hstSts = "good";
                } else {
                  $hstSts = "host-profile-deleted";
                }
              } else {
                $hstSts = "host-not-exist";
              }
            } else {
              $hstSts = "undefined";
            }
          } else {
            $plcSts = "place-not-exist";
          }
        } else {
          $idSts = "not-belong-to-you";
        }
      } else {
        $idSts = "not-signed-in";
      }
    } else {
      $idSts = "not-exist";
    }
  }
  $guestsUpdateReq = 0;
  $fromDUpdateReq = 0;
  $fromMUpdateReq = 0;
  $fromYUpdateReq = 0;
  $firstDayUpdateReq = "";
  $toDUpdateReq = 0;
  $toMUpdateReq = 0;
  $toYUpdateReq = 0;
  $lastDayUpdateReq = "";
  $reqTotalAmount = 0;
  if ($idSts == "good") {
    $guestsUpdateReq = $bookingRow['guestnum'];
    $fromDUpdateReq = $bookingRow['fromd'];
    $fromMUpdateReq = $bookingRow['fromm'];
    $fromYUpdateReq = $bookingRow['fromy'];
    $firstDayUpdateReq = $bookingRow['firstday'];
    $toDUpdateReq = $bookingRow['tod'];
    $toMUpdateReq = $bookingRow['tom'];
    $toYUpdateReq = $bookingRow['toy'];
    $lastDayUpdateReq = $bookingRow['lastday'];
    $reqTotalAmount = $bookingRow['totalprice'];
    $sqlSelectBookingUpdateRequestGuests = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='guests'");
    if ($sqlSelectBookingUpdateRequestGuests->num_rows > 0) {
      $bUpdtReqG = $sqlSelectBookingUpdateRequestGuests->fetch_assoc();
      $guestsUpdateReq = $bUpdtReqG['guestNum'];
    }
    $sqlSelectBookingUpdateRequestDates = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' and status='ready' and type='date'");
    if ($sqlSelectBookingUpdateRequestDates->num_rows > 0) {
      $bUpdtReqD = $sqlSelectBookingUpdateRequestDates->fetch_assoc();
      if (
        $bookingRow['fromd'] != $bUpdtReqD['fromd'] ||
        $bookingRow['fromm'] != $bUpdtReqD['fromm'] ||
        $bookingRow['fromy'] != $bUpdtReqD['fromy'] ||
        $bookingRow['firstday'] != $bUpdtReqD['firstday']
      ) {
        $fromDUpdateReq = $bUpdtReqD['fromd'];
        $fromMUpdateReq = $bUpdtReqD['fromm'];
        $fromYUpdateReq = $bUpdtReqD['fromy'];
        $firstDayUpdateReq = $bUpdtReqD['firstday'];
      }
      if (
        $bookingRow['tod'] != $bUpdtReqD['tod'] ||
        $bookingRow['tom'] != $bUpdtReqD['tom'] ||
        $bookingRow['toy'] != $bUpdtReqD['toy'] ||
        $bookingRow['lastday'] != $bUpdtReqD['lastday']
      ) {
        $toDUpdateReq = $bUpdtReqD['tod'];
        $toMUpdateReq = $bUpdtReqD['tom'];
        $toYUpdateReq = $bUpdtReqD['toy'];
        $lastDayUpdateReq = $bUpdtReqD['lastday'];
      }
    }
    $reqTotalAmount = totalPriceCalc(
      $placeBeID,
      $guestsUpdateReq,
      $fromYUpdateReq,
      $fromMUpdateReq,
      $fromDUpdateReq,
      $firstDayUpdateReq,
      $toYUpdateReq,
      $toMUpdateReq,
      $toDUpdateReq,
      $lastDayUpdateReq
    );
  }
  $reqDeposit = 10 * $reqTotalAmount / 100;
  $plcRatingSts = "unset";
  if ($plcSts == "good") {
    $plcRatingSts = "none";
    $sqlPlaceRating = $link->query("SELECT * FROM ratingcriticsummary WHERE critic='$usrBeId' and beid='$placeBeID' LIMIT 1");
    if ($sqlPlaceRating->num_rows > 0) {
      $plcRatingRow = $sqlPlaceRating->fetch_assoc();
      $plcRatingSts = "done";
    }
  }
  $aboutBookingsProgressSectClass1 = "about-booking-progress-section-done";
  $aboutBookingsProgressSectClass2 = "";
  $aboutBookingsProgressSectClass3 = "";
  $aboutBookingsProgressSectClass4 = "";
  $aboutBookingsProgressSectClass5 = "";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/about-booking.css">
    <script src="js/about-booking.js" async></script>
    <script src="js/about-my-booking.js" async></script>
    <script src="../uni/code/js/add-currency.js" async></script>
    <?php
      include "../uni/code/php-frontend/head.php";
      if ($plcSts == "good") {
    ?>
        <title><?php echo $wrd_aboutMyBooking." - ".$plcRow['name']." - ".$title; ?></title>
    <?php
      } else {
    ?>
        <title><?php echo $wrd_aboutMyBooking." - ".$title; ?></title>
    <?php
      }
    ?>
  </head>
  <body onload="<?php echo $onload; ?>aboutBookingsProgressSetScroll();aboutBookingsProgressFormTextareaAdjust();">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($idSts == "good") {
    ?>
      <div id="about-booking-size">
        <div id="about-booking-progress-wrp">
          <?php
            if ($bookingRow['status'] == "canceled") {
          ?>
            <div class="about-booking-progress-alt-text-wrp">
              <div class="about-booking-progress-alt-text-size">
                <p class="about-booking-progress-alt-text"><?php echo $wrd_thisBookingHasAlreadyBeenCanceled; ?></p>
              </div>
            </div>
          <?php
            } else if (new DateTime($bookingRow['todate']) < new DateTime()) {
          ?>
            <div class="about-booking-progress-alt-text-wrp">
              <div class="about-booking-progress-alt-text-size">
                <p class="about-booking-progress-alt-text"><?php echo $wrd_thisBookingHasAlreadyTakenPlace; ?></p>
              </div>
            </div>
          <?php
            } else {
              if ($bookingRow['status'] == "waiting") {
                $aboutBookingsProgressSectClass1 = $aboutBookingsProgressSectClass1." about-booking-progress-section-done-last";
              } else if ($bookingRow['status'] == "booked") {
                $aboutBookingsProgressSectClass2 = "about-booking-progress-section-done";
                if ($bookingRow['lessthan48h'] == "0") {
                  if ($bookingRow['deposit'] == "1") {
                    $aboutBookingsProgressSectClass3 = "about-booking-progress-section-done";
                    if ($bookingRow['fullAmount'] == "1") {
                      $aboutBookingsProgressSectClass4 = "about-booking-progress-section-done";
                      $aboutBookingsProgressSectClass5 = "about-booking-progress-section-done about-booking-progress-section-done-last";
                    } else {
                      if ($priceDeposit >= 5) {
                        $aboutBookingsProgressSectClass3 = $aboutBookingsProgressSectClass3." about-booking-progress-section-done-last";
                      } else {
                        $aboutBookingsProgressSectClass2 = $aboutBookingsProgressSectClass2." about-booking-progress-section-done-last";
                      }
                    }
                  } else {
                    $aboutBookingsProgressSectClass2 = $aboutBookingsProgressSectClass2." about-booking-progress-section-done-last";
                  }
                } else {
                  $aboutBookingsProgressSectClass3 = "about-booking-progress-section-done";
                  $aboutBookingsProgressSectClass4 = "about-booking-progress-section-done";
                  $aboutBookingsProgressSectClass5 = "about-booking-progress-section-done about-booking-progress-section-done-last";
                }
              }
          ?>
            <div id="about-booking-progress-slider">
              <div id="about-booking-progress-slider-content">
                <div class="about-booking-progress-text-wrp <?php echo $aboutBookingsProgressSectClass1; ?>">
                  <p class="about-booking-progress-text"><?php echo $wrd_bookingRequest; ?></p>
                </div>
                <div class="about-booking-progress-line-wrp <?php echo $aboutBookingsProgressSectClass2; ?>">
                  <div class="about-booking-progress-line"></div>
                </div>
                <div class="about-booking-progress-text-wrp <?php echo $aboutBookingsProgressSectClass2; ?>">
                  <p class="about-booking-progress-text"><?php echo $wrd_theRequestWasConfirmedByTheHost; ?></p>
                </div>
                <?php
                  if ($bookingRow['lessthan48h'] == "0") {
                    if ($priceDeposit >= 5) {
                ?>
                      <div class="about-booking-progress-line-wrp <?php echo $aboutBookingsProgressSectClass3; ?>">
                        <div class="about-booking-progress-line"></div>
                      </div>
                      <div class="about-booking-progress-text-wrp <?php echo $aboutBookingsProgressSectClass3; ?>">
                        <p class="about-booking-progress-text"><?php echo $wrd_depositPaid; ?></p>
                      </div>
                <?php
                    }
                ?>
                  <div class="about-booking-progress-line-wrp <?php echo $aboutBookingsProgressSectClass4; ?>">
                    <div class="about-booking-progress-line"></div>
                  </div>
                  <div class="about-booking-progress-text-wrp <?php echo $aboutBookingsProgressSectClass4; ?>">
                    <p class="about-booking-progress-text"><?php echo $wrd_fullAmountPaid; ?></p>
                  </div>
                <?php
                  }
                ?>
                <div class="about-booking-progress-line-wrp  <?php echo $aboutBookingsProgressSectClass5; ?>">
                  <div class="about-booking-progress-line"></div>
                </div>
                <div class="about-booking-progress-text-wrp  <?php echo $aboutBookingsProgressSectClass5; ?>">
                  <p class="about-booking-progress-text"><?php echo $wrd_done; ?></p>
                </div>
              </div>
            </div>
          <?php
            }
          ?>
        </div>
        <div id="about-booking-layout">
          <div id="about-this-booking-layout">
            <?php
              if (new DateTime() < new DateTime($bookingRow['fromdate']) && $bookingRow['status'] != "canceled") {
            ?>
              <div id="about-this-booking-btn-wrp">
                <?php
                  if ($hstSts != "host-profile-deleted") {
                ?>
                    <button type="button" class="btn btn-mid btn-prim about-this-booking-btn" id="about-this-booking-btn-save" onclick="aboutBookingsSave()"><?php echo $wrd_save; ?></button>
                    <button type="button" class="btn btn-mid btn-fth about-this-booking-btn" id="about-this-booking-btn-cancel-booking" onclick="modCover('show', 'modal-cover-cancel-booking');"><?php echo $wrd_cancelBooking; ?></button>
                <?php
                  }
                ?>
              </div>
            <?php
              }
            ?>
            <div id="about-this-booking-error-wrp">
              <p id="about-this-booking-error-txt"></p>
            </div>
            <div id="about-this-booking-form-wrp">
              <?php
                if ($bookingRow['status'] == "canceled" || new DateTime($bookingRow['fromdate']) < new DateTime()) {
                  $disabledAttribute = "disabled";
                } else {
                  $disabledAttribute = "";
                }
              ?>
              <div class="about-this-booking-form-line about-this-booking-form-first-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_name.":"; ?></p>
                  <textarea class="about-this-booking-form-textarea" id="about-this-booking-form-textarea-name" placeholder="<?php echo $wrd_fullname; ?>" oninput="aboutBookingsProgressFormTextarea(this)" onkeyup="aboutBookingsProgressFormTextarea(this)" <?php echo $disabledAttribute; ?>><?php echo $bookingRow['name']; ?></textarea>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt" id="about-this-booking-form-error-txt-name"></p>
                </div>
              </div>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_email.":"; ?></p>
                  <textarea class="about-this-booking-form-textarea" id="about-this-booking-form-textarea-email" placeholder="<?php echo "email@email.com"; ?>" oninput="aboutBookingsProgressFormTextarea(this)" onkeyup="aboutBookingsProgressFormTextarea(this)" <?php echo $disabledAttribute; ?>><?php echo $bookingRow['email']; ?></textarea>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt" id="about-this-booking-form-error-txt-email"></p>
                </div>
              </div>
              <div class="about-this-booking-form-line">
                <div class="about-this-booking-form-about">
                  <p class="about-this-booking-form-title"><?php echo $wrd_phoneNumber.":"; ?></p>
                  <textarea class="about-this-booking-form-textarea" id="about-this-booking-form-textarea-phone" placeholder="<?php echo '+'.$wrd_code.' 000 000 000'; ?>" oninput="aboutBookingsProgressFormTextarea(this)" onkeyup="aboutBookingsProgressFormTextarea(this)" <?php echo $disabledAttribute; ?>><?php echo $bookingRow['phonenum']; ?></textarea>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt" id="about-this-booking-form-error-txt-phone"></p>
                </div>
              </div>
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
                        <button type="button" class="about-this-booking-form-data-btn" onclick="aboutBookingsChangeGuestsNumModal('show');" <?php echo $disabledAttribute; ?>><?php echo $wrd_requestChange; ?></button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <?php
                  if ($guestsUpdateReq != $bookingRow['guestnum']) {
                    $guestsUpdateReqStyle = "display: table;";
                  } else {
                    $guestsUpdateReqStyle = "";
                  }
                ?>
                <div class="about-this-booking-form-request-change-wrp" id="about-this-booking-form-request-change-wrp-guests-num" style="<?php echo $guestsUpdateReqStyle; ?>">
                  <div class="about-this-booking-form-request-change-size">
                    <p class="about-this-booking-form-request-change-txt"><?php echo $wrd_waitForTheHostToConfirmTheRequestForChangeTo.":"; ?> <b class="about-this-booking-form-request-change-txt-bold" id="about-this-booking-form-request-change-txt-bold-guests-num"><?php echo $guestsUpdateReq; ?></b></p>
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
                        <button type="button" class="about-this-booking-form-data-btn" onclick="aboutBookingsChangeDatesModal('show');" <?php echo $disabledAttribute; ?>><?php echo $wrd_requestChange; ?></button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <?php
                  if ($fromDUpdateReq != $bookingRow['fromd'] || $fromMUpdateReq != $bookingRow['fromm'] || $fromYUpdateReq != $bookingRow['fromy'] || $firstDayUpdateReq != $bookingRow['firstday']) {
                    $fromUpdateReqStyle = "display: table;";
                    if ($firstDayUpdateReq == "whole") {
                      $fromUpdateReqTxt = $fromDUpdateReq.".".$fromMUpdateReq.".".$fromYUpdateReq." (".$wrd_theWholeDay.")";
                    } else {
                      $fromUpdateReqTxt = $fromDUpdateReq.".".$fromMUpdateReq.".".$fromYUpdateReq." (".$wrd_from." 14:00)";
                    }
                  } else {
                    $fromUpdateReqStyle = "";
                    $fromUpdateReqTxt = "";
                  }
                ?>
                <div class="about-this-booking-form-request-change-wrp" id="about-this-booking-form-request-change-wrp-from-date" style="<?php echo $fromUpdateReqStyle; ?>">
                  <div class="about-this-booking-form-request-change-size">
                    <p class="about-this-booking-form-request-change-txt"><?php echo $wrd_waitForTheHostToConfirmTheRequestForChangeTo.":"; ?> <b class="about-this-booking-form-request-change-txt-bold" id="about-this-booking-form-request-change-txt-bold-from-date"><?php echo $fromUpdateReqTxt; ?></b></p>
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
                        <button type="button" class="about-this-booking-form-data-btn" onclick="aboutBookingsChangeDatesModal('show');" <?php echo $disabledAttribute; ?>><?php echo $wrd_requestChange; ?></button>
                      </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <?php
                  if ($toDUpdateReq != $bookingRow['tod'] || $toMUpdateReq != $bookingRow['tom'] || $toYUpdateReq != $bookingRow['toy'] || $lastDayUpdateReq != $bookingRow['lastday']) {
                    $toUpdateReqStyle = "display: table;";
                    if ($lastDayUpdateReq == "whole") {
                      $toUpdateReqTxt = $toDUpdateReq.".".$toMUpdateReq.".".$toYUpdateReq." (".$wrd_theWholeDay.")";
                    } else {
                      $toUpdateReqTxt = $toDUpdateReq.".".$toMUpdateReq.".".$toYUpdateReq." (".$wrd_to." 11:00)";
                    }
                  } else {
                    $toUpdateReqStyle = "";
                    $toUpdateReqTxt = "";
                  }
                ?>
                <div class="about-this-booking-form-request-change-wrp" id="about-this-booking-form-request-change-wrp-to-date" style="<?php echo $toUpdateReqStyle; ?>">
                  <div class="about-this-booking-form-request-change-size">
                    <p class="about-this-booking-form-request-change-txt"><?php echo $wrd_waitForTheHostToConfirmTheRequestForChangeTo.":"; ?> <b class="about-this-booking-form-request-change-txt-bold" id="about-this-booking-form-request-change-txt-bold-to-date"><?php echo $toUpdateReqTxt; ?></b></p>
                  </div>
                </div>
                <div class="about-this-booking-form-error-wrp">
                  <p class="about-this-booking-form-error-txt"></p>
                </div>
              </div>
              <?php
                if ($priceDeposit >= 5) {
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
                        <div class="about-this-booking-form-status-wrp">
                          <?php
                            if ($bookingRow['status'] != "waiting") {
                              if ($bookingRow['deposit'] == 0) {
                          ?>
                                <div class="about-this-booking-form-status-icn about-this-booking-form-status-icn-unpaid"></div>
                                <p class="about-this-booking-form-status-txt about-this-booking-form-status-txt-unpaid"><?php echo $wrd_unpaid; ?></p>
                          <?php
                              } else {
                          ?>
                                <div class="about-this-booking-form-status-icn about-this-booking-form-status-icn-paid"></div>
                                <p class="about-this-booking-form-status-txt about-this-booking-form-status-txt-paid"><?php echo $wrd_paid; ?></p>
                          <?php
                              }
                            }
                          ?>
                        </div>
                      </div>
                    </div>
                    <?php
                      if (intval($reqTotalAmount) != intval($bookingRow['totalprice']) && $reqDeposit >= 5) {
                        $depositUpdateReqStyle = "display: table;";
                      } else {
                        $depositUpdateReqStyle = "";
                      }
                    ?>
                    <div class="about-this-booking-form-request-change-wrp" id="about-this-booking-form-request-change-wrp-deposit" style="<?php echo $depositUpdateReqStyle; ?>">
                      <div class="about-this-booking-form-request-change-size">
                        <p class="about-this-booking-form-request-change-txt"><?php echo $wrd_expectedPriceChangeTo.":"; ?> <b class="about-this-booking-form-request-change-txt-bold" id="about-this-booking-form-request-change-txt-bold-deposit"><?php echo addCurrency($bookingRow['totalcurrency'], $reqDeposit); ?></b></p>
                      </div>
                    </div>
                  </div>
              <?php
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
                    <div class="about-this-booking-form-status-wrp">
                      <?php
                        if ($bookingRow['status'] != "waiting") {
                          if ($bookingRow['fullAmount'] == 0) {
                      ?>
                            <div class="about-this-booking-form-status-icn about-this-booking-form-status-icn-unpaid"></div>
                            <p class="about-this-booking-form-status-txt about-this-booking-form-status-txt-unpaid"><?php echo $wrd_unpaid; ?></p>
                      <?php
                          } else {
                      ?>
                            <div class="about-this-booking-form-status-icn about-this-booking-form-status-icn-paid"></div>
                            <p class="about-this-booking-form-status-txt about-this-booking-form-status-txt-paid"><?php echo $wrd_paid; ?></p>
                      <?php
                          }
                        }
                      ?>
                    </div>
                  </div>
                </div>
                <?php
                  if ($bookingRow['status'] != "waiting" && intval($reqTotalAmount) != intval($bookingRow['totalprice'])) {
                    $totalUpdateReqStyle = "display: table;";
                  } else {
                    $totalUpdateReqStyle = "";
                  }
                ?>
                <div class="about-this-booking-form-request-change-wrp" id="about-this-booking-form-request-change-wrp-total" style="<?php echo $totalUpdateReqStyle; ?>">
                  <div class="about-this-booking-form-request-change-size">
                    <p class="about-this-booking-form-request-change-txt"><?php echo $wrd_expectedPriceChangeTo.":"; ?> <b class="about-this-booking-form-request-change-txt-bold" id="about-this-booking-form-request-change-txt-bold-total"><?php echo addCurrency($bookingRow['totalcurrency'], $reqTotalAmount); ?></b></p>
                  </div>
                </div>
              </div>
            </div>
            <div id="about-this-booking-about-wrp">
              <div class="about-booking-title-wrp">
                <p class="about-booking-title"><?php echo $wrd_detailedInformationAboutTheBooking; ?></p>
              </div>
              <div id="about-this-booking-about-notifications-list">
                <?php
                  if ($bookingRow['status'] == "canceled") {
                ?>
                    <div class="about-this-booking-about-notification-block">
                      <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-red">
                        <div class="about-this-booking-about-notification-layout">
                          <div class="about-this-booking-about-notification-txt-wrp">
                            <div class="about-this-booking-about-notification-txt-size">
                              <p class="about-this-booking-about-notification-txt"><?php echo $wrd_thisBookingIsNoLongerActive; ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                  } else if (new DateTime($bookingRow['todate']) < new DateTime()) {
                    if ($bookingRow['lastday'] == "half") {
                      $toDate = $bookingRow['todate']." 11:00";
                    } else {
                      $toDate = $bookingRow['todate']." 23:59";
                    }
                    $diff = abs(strtotime($toDate) - strtotime($date));
                    $hours = floor($diff / 3600);
                    if ($date < $toDate) {
                      $hours = $hours * -1;
                    }
                    if ($hours >= 12 && $plcSts != "place-deleted") {
                ?>
                    <div class="about-this-booking-about-notification-block">
                      <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                        <div class="about-this-booking-about-notification-layout">
                          <div class="about-this-booking-about-notification-txt-wrp">
                            <div class="about-this-booking-about-notification-txt-size">
                              <p class="about-this-booking-about-notification-txt">
                                <?php echo $wrd_howWasYourStayRateItHere1." "; ?>
                                <a href="../ratings/?booking=<?php echo getFrontendId($bookingBeId); ?>&fromd=<?php echo $bookingRow['fromd']; ?>&fromm=<?php echo $bookingRow['fromm']; ?>&fromy=<?php echo $bookingRow['fromy']; ?>&tod=<?php echo $bookingRow['tod']; ?>&tom=<?php echo $bookingRow['tom']; ?>&toy=<?php echo $bookingRow['toy']; ?>&plc=<?php echo getFrontendId($placeBeID); ?>" target="_blank" class="about-this-booking-about-notification-txt"><?php echo $wrd_howWasYourStayRateItHere2; ?></a>
                                <?php echo $wrd_howWasYourStayRateItHere3; ?>
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                    } else {
                      if ($plcRatingSts == "none") {
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $wrd_theFormForTheStayEvaluationWillBeAvailableToYou12HoursAfterTheAndOfTheBooking; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                      }
                    }
                ?>
                    <div class="about-this-booking-about-notification-block">
                      <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                        <div class="about-this-booking-about-notification-layout">
                          <div class="about-this-booking-about-notification-txt-wrp">
                            <div class="about-this-booking-about-notification-txt-size">
                              <p class="about-this-booking-about-notification-txt"><?php echo $wrd_thisBookingHasAlreadyTakenPlace; ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                  }
                  if ($plcSts == "place-deleted" && $bookingRow['status'] == "booked") {
                ?>
                      <div class="about-this-booking-about-notification-block">
                        <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                          <div class="about-this-booking-about-notification-layout">
                            <div class="about-this-booking-about-notification-txt-wrp">
                              <div class="about-this-booking-about-notification-txt-size">
                                <p class="about-this-booking-about-notification-txt"><?php echo $wrd_placeIsDeletedButBookingIsActive; ?></p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                <?php
                  }
                  if ($aboutBookingsProgressSectClass1 == "about-booking-progress-section-done about-booking-progress-section-done-last") {
                ?>
                    <div class="about-this-booking-about-notification-block">
                      <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                        <div class="about-this-booking-about-notification-layout">
                          <div class="about-this-booking-about-notification-txt-wrp">
                            <div class="about-this-booking-about-notification-txt-size">
                              <p class="about-this-booking-about-notification-txt"><?php echo $wrd_bookingRequestCreatedNowWait; ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                    if ($bookingRow['lessthan48h'] == "1") {
                ?>
                    <div class="about-this-booking-about-notification-block">
                      <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-red">
                        <div class="about-this-booking-about-notification-layout">
                          <div class="about-this-booking-about-notification-txt-wrp">
                            <div class="about-this-booking-about-notification-txt-size">
                              <p class="about-this-booking-about-notification-txt"><?php echo $wrd_theDateOfTheFirstDayOfTheBookingIsLessThan48HoursAwayThereforePleaseContactTheHostAsSoonAsPossibleToConfirmYourDate; ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php
                    }
                  } else if ($aboutBookingsProgressSectClass2 == "about-booking-progress-section-done about-booking-progress-section-done-last") {
                    if ($bookingRow['lessthan48h'] == "0") {
                      $diff = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($bookingRow['fulldate']));
                      $tillCancel = floor($diff / 3600);
                      $tillCancel = 48 - $tillCancel;
                      if ($hstSts == "good") {
                        $hostAutoCancelStatus = getAccountData($hostBeID, "cancel-unpaid-bookings");
                      } else {
                        $hostAutoCancelStatus = 0;
                      }
                      if ($hostAutoCancelStatus == "1") {
                        if ($tillCancel > 0) {
                ?>
                            <div class="about-this-booking-about-notification-block">
                              <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                                <div class="about-this-booking-about-notification-layout">
                                  <div class="about-this-booking-about-notification-txt-wrp">
                                    <div class="about-this-booking-about-notification-txt-size">
                                      <p class="about-this-booking-about-notification-txt">
                                        <?php
                                          if ($priceDeposit >= 5) {
                                            if ($tillCancel == 1) {
                                              echo $wrd_bookingConfirmedNowPay1Deposit." ".$wrd_bookingConfirmedNowPayCountdown." ".$tillCancel." ".$wrd_bookingConfirmedNowPay2_1;
                                            } elseif ($tillCancel > 1 && $tillCancel < 5) {
                                              echo $wrd_bookingConfirmedNowPay1Deposit." ".$wrd_bookingConfirmedNowPayCountdown." ".$tillCancel." ".$wrd_bookingConfirmedNowPay2_2;
                                            } else {
                                              echo $wrd_bookingConfirmedNowPay1Deposit." ".$wrd_bookingConfirmedNowPayCountdown." ".$tillCancel." ".$wrd_bookingConfirmedNowPay2_3;
                                            }
                                          } else {
                                            if ($tillCancel == 1) {
                                              echo $wrd_bookingConfirmedNowPay1." ".$wrd_bookingConfirmedNowPayCountdown." ".$tillCancel." ".$wrd_bookingConfirmedNowPay2_1;
                                            } elseif ($tillCancel > 1 && $tillCancel < 5) {
                                              echo $wrd_bookingConfirmedNowPay1." ".$wrd_bookingConfirmedNowPayCountdown." ".$tillCancel." ".$wrd_bookingConfirmedNowPay2_2;
                                            } else {
                                              echo $wrd_bookingConfirmedNowPay1." ".$wrd_bookingConfirmedNowPayCountdown." ".$tillCancel." ".$wrd_bookingConfirmedNowPay2_3;
                                            }
                                          }
                                        ?>
                                      </p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                <?php
                        } else {
                ?>
                            <div class="about-this-booking-about-notification-block">
                              <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-red">
                                <div class="about-this-booking-about-notification-layout">
                                  <div class="about-this-booking-about-notification-txt-wrp">
                                    <div class="about-this-booking-about-notification-txt-size">
                                      <p class="about-this-booking-about-notification-txt"><?php echo $wrd_canceledBookingDueToNonPayment; ?></p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                <?php
                        }
                      } else {
                ?>
                            <div class="about-this-booking-about-notification-block">
                              <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                                <div class="about-this-booking-about-notification-layout">
                                  <div class="about-this-booking-about-notification-txt-wrp">
                                    <div class="about-this-booking-about-notification-txt-size">
                                      <p class="about-this-booking-about-notification-txt">
                                        <?php
                                          if ($priceDeposit >= 5) {
                                            echo $wrd_bookingConfirmedNowPay1Deposit;
                                          } else {
                                            echo $wrd_bookingConfirmedNowPay1;
                                          }
                                        ?>
                                      </p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                <?php
                      }
                    } else {
                ?>
                          <div class="about-this-booking-about-notification-block">
                            <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-red">
                              <div class="about-this-booking-about-notification-layout">
                                <div class="about-this-booking-about-notification-txt-wrp">
                                  <div class="about-this-booking-about-notification-txt-size">
                                    <p class="about-this-booking-about-notification-txt"><?php echo $wrd_thisBookingWasMadeLessThan48HoursBeforeItsStartThereforePleaseContactYourHostByPhoneAsSoonAsPossibleAndAgreeOnTheMethodOfPayment; ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                <?php
                    }
                  } else if ($aboutBookingsProgressSectClass3 == "about-booking-progress-section-done about-booking-progress-section-done-last") {
                ?>
                          <div class="about-this-booking-about-notification-block">
                            <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                              <div class="about-this-booking-about-notification-layout">
                                <div class="about-this-booking-about-notification-txt-wrp">
                                  <div class="about-this-booking-about-notification-txt-size">
                                    <p class="about-this-booking-about-notification-txt"><?php echo $wrd_depositHasBeenPaidNowPayMore; ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                <?php
                  } else if ($aboutBookingsProgressSectClass4 == "about-booking-progress-section-done about-booking-progress-section-done-last" || $aboutBookingsProgressSectClass5 == "about-booking-progress-section-done about-booking-progress-section-done-last") {
                ?>
                          <div class="about-this-booking-about-notification-block">
                            <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                              <div class="about-this-booking-about-notification-layout">
                                <div class="about-this-booking-about-notification-txt-wrp">
                                  <div class="about-this-booking-about-notification-txt-size">
                                    <p class="about-this-booking-about-notification-txt"><?php echo $wrd_enjoyYourStay; ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                <?php
                    if ($plcRatingSts == "none") {
                ?>
                          <div class="about-this-booking-about-notification-block">
                            <div class="about-this-booking-about-notification-background about-this-booking-about-notification-background-blue">
                              <div class="about-this-booking-about-notification-layout">
                                <div class="about-this-booking-about-notification-txt-wrp">
                                  <div class="about-this-booking-about-notification-txt-size">
                                    <p class="about-this-booking-about-notification-txt"><?php echo $wrd_theFormForTheStayEvaluationWillBeAvailableToYou12HoursAfterTheAndOfTheBooking; ?></p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                <?php
                    }
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
                        <button type="button" id="about-booking-facility-place-btn" onclick="location.href = '../place/?id=<?php echo getFrontendId($placeBeID); ?>'"><?php echo $wrd_visitTheCottagePage; ?></button>
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
            <div class="about-booking-facility-blck">
              <div class="about-booking-title-wrp">
                <p class="about-booking-title"><?php echo $wrd_aboutTheHost; ?></p>
              </div>
              <div class="about-booking-facility-card">
                <div class="about-booking-facility-card-layout">
                  <?php
                    if ($hstSts == "good") {
                      if ($hostBeID == $usrBeId) {
                        $hostCardID = "about-booking-facility-card-i-am-the-host";
                      } else {
                        $hostCardID = "";
                      }
                  ?>
                      <div class="about-booking-facility-card-layout" id="<?php echo $hostCardID; ?>">
                        <div id="about-booking-facility-host-layout">
                          <div id="about-booking-facility-host-img-wrp">
                            <img src="../<?php echo getAccountData($hostBeID, "medium-profile-image"); ?>" alt="profile image of the host" id="about-booking-facility-host-img">
                          </div>
                          <div id="about-booking-facility-host-txt-layout">
                            <div class="about-booking-facility-host-txt-wrp">
                              <a href="../user/?id=<?php echo getFrontendId($hostBeID); ?>&section=about" id="about-booking-facility-host-name"><?php echo $hstRow['firstname']." ".$hstRow['lastname']; ?></a>
                            </div>
                            <div class="about-booking-facility-host-txt-wrp">
                              <a href="mailto:<?php echo $hstRow['contactemail']; ?>" target="_blank" class="about-booking-facility-host-txt" id="about-booking-facility-host-txt-email"><?php echo $hstRow['contactemail']; ?></a>
                            </div>
                            <div class="about-booking-facility-host-txt-wrp">
                              <a href="tel:<?php echo $hstRow['contactphonenum']; ?><" class="about-booking-facility-host-txt" id="about-booking-facility-host-txt-phone"><?php echo $hstRow['contactphonenum']; ?></a>
                            </div>
                            <?php
                              if ($bookingRow['status'] == "booked" && ($hstRow['bankaccount'] != "-" || $hstRow['iban'] != "-" || $hstRow['bicswift'] != "-")) {
                            ?>
                                <div class="about-booking-facility-host-txt-wrp" id="about-booking-facility-host-txt-wrp-bank-details-title">
                                  <p class="about-booking-facility-host-section-title"><?php echo $wrd_bankDetails.":"; ?></p>
                                </div>
                            <?php
                                if ($hstRow['bankaccount'] != "-") {
                            ?>
                                  <div class="about-booking-facility-host-txt-wrp" id="about-booking-facility-host-txt-wrp-bank-account-number">
                                    <p class="about-booking-facility-host-txt"><?php echo "<b id='about-booking-facility-host-txt-bold-bank-account-number'>".$wrd_bankAccountNumber.":</b> ".$hstRow['bankaccount']; ?></p>
                                  </div>
                            <?php
                                }
                                if ($hstRow['iban'] != "-") {
                            ?>
                                  <div class="about-booking-facility-host-txt-wrp" id="about-booking-facility-host-txt-wrp-iban">
                                    <p class="about-booking-facility-host-txt"><?php echo "<b id='about-booking-facility-host-txt-bold-iban'>IBAN:</b> ".$hstRow['iban']; ?></p>
                                  </div>
                            <?php
                                }
                                if ($hstRow['bicswift'] != "-") {
                            ?>
                                  <div class="about-booking-facility-host-txt-wrp" id="about-booking-facility-host-txt-wrp-bicswift">
                                    <p class="about-booking-facility-host-txt"><?php echo "<b id='about-booking-facility-host-txt-bold-bicswift'>BIC/SWIFT:</b> ".$hstRow['bicswift']; ?></p>
                                  </div>
                            <?php
                                }
                              }
                            ?>
                          </div>
                        </div>
                      </div>
                  <?php
                    } else {
                  ?>
                      <div class="about-booking-facility-alt-txt-wrp">
                        <p class="about-booking-facility-alt-txt">
                  <?php
                          if ($hstSts == "undefined") {
                            echo $wrd_weWereUnableToDetermineTheHostProfile;
                          } else if ($hstSts == "host-not-exist") {
                            echo $wrd_theHostProfileWasNotFoundInOurDatabase;
                          } else if ($hstSts == "host-profile-deleted") {
                            echo $wrd_theHostProfileIsDeleted;
                          }
                  ?>
                        </p>
                      </div>
                  <?php
                    }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-cover" id="modal-cover-cancel-booking">
        <div class="modal-block" id="modal-cover-cancel-booking-blck">
          <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-cancel-booking');"></button>
          <div id="cancel-booking-ask-wrp">
            <div id="cancel-booking-ask-layout">
              <div id="cancel-booking-ask-txt-wrp">
                <?php
                  if ($bookingRow['status'] == "waiting") {
                    $cancelBookingAskDesc = $wrd_thisBookingHasNotBeenConfirmedByTheHostYetThereforeIfYouCancelItYouWillNotBeChargedAnyFees;
                  } else {
                    if ($bookingRow['firstday'] == "half") {
                      $fullFromDate = $bookingRow['fromdate']." 14:00";
                    } else {
                      $fullFromDate = $bookingRow['fromdate']." 00:00";
                    }
                    $diffWeek = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($fullFromDate));
                    $hoursWeek = floor($diffWeek / 3600);
                    if (date("Y-m-d H:i:s") > $fullFromDate) {
                      $hoursWeek = $hoursWeek * -1;
                    }
                    if ($hoursWeek >= 336) {
                      $cancelBookingAskDesc = $wrd_ifYouClickYesThisBookingWillBeCanceledAndYouGetAllYourMoneyBack;
                    } else {
                      $cancelBookingAskDesc = $wrd_ifYouClickYesThisBookingWillBeCanceledAndYouWillPayCancelingFee;
                    }
                  }
                ?>
                <p id="cancel-booking-ask-txt-title"><?php echo $wrd_areYouSureYouWantToCancelThisBooking; ?></p>
                <p id="cancel-booking-ask-txt-desc"><?php echo $cancelBookingAskDesc; ?></p>
              </div>
              <div id="cancel-booking-ask-btn-wrp">
                <button type="button" class="btn btn-mid btn-sec cancel-booking-ask-btn" onclick="modCover('hide', 'modal-cover-cancel-booking');"><?php echo $wrd_no; ?></button>
                <button type="button" class="btn btn-mid btn-fth cancel-booking-ask-btn" onclick="aboutCancelBooking();"><?php echo $wrd_yes; ?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-cover" id="modal-cover-change-data-dates">
        <div class="modal-block" id="modal-cover-change-data-dates-blck">
          <button class="cancel-btn" onclick="aboutBookingsChangeDatesModal('hide');"></button>
          <div class="change-data-content-wrp">
            <div class="change-data-content-about-wrp">
              <p id="change-data-content-about-txt-d-num-of-guests"><?php echo $guestsUpdateReq; ?></p>
              <p id="change-data-content-about-txt-d-from-d"><?php echo $bookingRow['fromd']; ?></p>
              <p id="change-data-content-about-txt-d-from-m"><?php echo $bookingRow['fromm']; ?></p>
              <p id="change-data-content-about-txt-d-from-y"><?php echo $bookingRow['fromy']; ?></p>
              <p id="change-data-content-about-txt-d-firstday"><?php echo $bookingRow['firstday']; ?></p>
              <p id="change-data-content-about-txt-d-to-d"><?php echo $bookingRow['tod']; ?></p>
              <p id="change-data-content-about-txt-d-to-m"><?php echo $bookingRow['tom']; ?></p>
              <p id="change-data-content-about-txt-d-to-y"><?php echo $bookingRow['toy']; ?></p>
              <p id="change-data-content-about-txt-d-lastday"><?php echo $bookingRow['lastday']; ?></p>
              <p id="change-data-content-about-txt-d-total"><?php echo $bookingRow['totalprice']; ?></p>
              <p id="change-data-content-about-txt-d-price-mode"><?php echo $plcRow['priceMode']; ?></p>
              <p id="change-data-content-about-txt-d-price-currency"><?php echo $bookingRow['totalcurrency']; ?></p>
              <p id="change-data-content-about-txt-d-work-price"><?php echo $plcRow['workDayPrice']; ?></p>
              <p id="change-data-content-about-txt-d-week-price"><?php echo $plcRow['weekDayPrice']; ?></p>
            </div>
            <div class="change-data-layout">
              <div class="change-data-txt-wrp">
                <p class="change-data-title"><?php echo $wrd_requestToChangeTheDateWillBeSentToTheHostWhoWillConfirmOrRejectTheChangeUntilTheyDoSoOnlyTheOriginalTermApplies; ?></p>
              </div>
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
                    <input type="date" class="change-data-input" id="change-data-input-from-date" value="<?php echo $fromDateFormat; ?>" onchange="aboutMyBookingChangeDataDatesPrice();" onkeyup="aboutMyBookingChangeDataDatesPrice();">
                    <select class="change-data-select" id="change-data-select-firstday" onchange="aboutMyBookingChangeDataDatesPrice();">
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
                    <input type="date" class="change-data-input" id="change-data-input-to-date" value="<?php echo $toDateFormat; ?>" onchange="aboutMyBookingChangeDataDatesPrice();" onkeyup="aboutMyBookingChangeDataDatesPrice();">
                    <select class="change-data-select" id="change-data-select-lastday" onchange="aboutMyBookingChangeDataDatesPrice();">
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
              <div class="change-data-error-wrp">
                <p class="change-data-error-txt" id="change-data-error-txt-dates"></p>
              </div>
              <div class="change-data-btn-wrp">
                <button type="button" class="btn btn-mid btn-prim" id="change-data-btn-dates-save" onclick="aboutMyBookingChangeDataDatesSave();"><?php echo $wrd_save; ?></button>
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
              <p id="change-data-content-about-txt-g-from-d"><?php echo $fromDUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-from-m"><?php echo $fromMUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-from-y"><?php echo $fromYUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-firstday"><?php echo $firstDayUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-to-d"><?php echo $toDUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-to-m"><?php echo $toMUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-to-y"><?php echo $toYUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-lastday"><?php echo $lastDayUpdateReq; ?></p>
              <p id="change-data-content-about-txt-g-total"><?php echo $bookingRow['totalprice']; ?></p>
              <p id="change-data-content-about-txt-g-price-mode"><?php echo $plcRow['priceMode']; ?></p>
              <p id="change-data-content-about-txt-g-price-currency"><?php echo $bookingRow['totalcurrency']; ?></p>
              <p id="change-data-content-about-txt-g-work-price"><?php echo $plcRow['workDayPrice']; ?></p>
              <p id="change-data-content-about-txt-g-week-price"><?php echo $plcRow['weekDayPrice']; ?></p>
            </div>
            <div class="change-data-layout">
              <div class="change-data-txt-wrp">
                <p class="change-data-title"><?php echo $wrd_requestToChangeTheNumberOfGuestsWillBeSentToTheHostWhoWillConfirmOrRejectTheChangeUntilHeSheDoSoOnlyTheOriginalTermApplies; ?></p>
              </div>
              <div class="change-data-input-layout">
                <div class="change-data-input-row">
                  <p class="change-data-input-title"><?php echo $wrd_guestNum.":"; ?></p>
                  <div class="change-data-input-field-wrp">
                    <input type="number" value="<?php echo $bookingRow['guestnum']; ?>" min="1" max="<?php echo $plcRow['guestNum']; ?>" class="change-data-input" id="change-data-input-number-of-guests" onchange="aboutMyBookingChangeDataNumOfGuestsPrice();" onkeyup="aboutMyBookingChangeDataNumOfGuestsPrice();">
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
              <div class="change-data-error-wrp">
                <p class="change-data-error-txt" id="change-data-error-txt-guests"></p>
              </div>
              <div class="change-data-btn-wrp">
                <button type="button" class="btn btn-mid btn-prim" id="change-data-btn-guests-save" onclick="aboutMyBookingChangeDataNumOfGuestsSave();"><?php echo $wrd_save; ?></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php
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
            }
          ?>
        </p>
      </div>
    <?php
      }
    ?>
  </body>
</html>
