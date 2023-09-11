<?php
  include "../uni/code/php-head.php";
  include "../editor/php-backend/place-verification.php";
  include "../uni/code/php-backend/total-price-calculator.php";
  include "../uni/code/php-backend/add-currency.php";
  $bookingUpdateErrorTxt = "";
  $bookingUpdateSts = false;
  if (isset($_GET['plc'])) {
    $placeVerify = placeVerification($_GET['plc']);
    if ($placeVerify == "good") {
      $plcID = $_GET['plc'];
      $sqlPlcIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$plcID' LIMIT 1");
      $plcBeId = $sqlPlcIdToBeId->fetch_assoc()["beid"];
      if (isset($_GET['booking'])) {
        $sqlPlace = $link->query("SELECT * FROM places WHERE beid='$plcBeId' LIMIT 1");
        $plcRow = $sqlPlace->fetch_assoc();
        $bookingId = $_GET['booking'];
        $sqlBookingIdToBeId = $link->query("SELECT beid FROM idlist WHERE id='$bookingId' LIMIT 1");
        if ($sqlBookingIdToBeId->num_rows > 0) {
          $bookingBeId = $sqlBookingIdToBeId->fetch_assoc()["beid"];
          $sqlBooking = $link->query("SELECT * FROM booking WHERE beid='$bookingBeId' and plcbeid='$plcBeId' LIMIT 1");
          if ($sqlBooking->num_rows > 0) {
            $bookingRow = $sqlBooking->fetch_assoc();
            if ($bookingRow["status"] != "canceled") {
              $bookingUpdateSts = true;
            } else {
              $bookingUpdateErrorTxt = $wrd_thisBookingHasAlreadyBeenCanceled;
            }
          } else {
           $bookingUpdateErrorTxt = "booking-does-not-match-with-place";
          }
        } else {
          $bookingUpdateErrorTxt = "booking-ID-from-url-not-exist";
        }
      } else {
        $bookingUpdateErrorTxt = "undefined-url-booking-ID";
      }
    } else {
      $bookingUpdateErrorTxt = "Place verification: ".$placeVerify;
    }
  } else {
    $bookingUpdateErrorTxt = "undefined-url-place-ID";
  }
  if ($bookingUpdateSts) {
    $subtitle = $wrd_requestsForChangesInTheBooking;
  } else {
    $subtitle = $wrd_unknown;
  }
  $firstDay = "";
  $lastDay = "";
  if ($bookingUpdateSts) {
    if ($bookingRow['firstday'] == "whole") {
      $firstDay = $wrd_theWholeDay;
    } else {
      $firstDay = $wrd_from." 14:00";
    }
    if ($bookingRow['lastday'] == "whole") {
      $lastDay = $wrd_theWholeDay;
    } else {
      $lastDay = $wrd_to." 11:00";
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <script src="js/booking-update.js" async></script>
    <link rel="stylesheet" type="text/css" href="css/booking-update.css">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/permission-needed-modal.css">
    <script src="../uni/code/js/permission-needed-modal.js" async></script>
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <title><?php echo $wrd_requestsForChangesInTheBooking." - ".$title; ?></title>
  </head>
  <body onload="
    <?php echo $onload; ?>
    permissionNeededDictionary(
      '<?php echo $wrd_thisReservationMustBeDeletedDueToNewModifications; ?>',
      '<?php echo $wrd_becauseReservationsOverlapAndContainTheSameInformationTheyWillBeCombinedIntoOne; ?>',
      '<?php echo $wrd_theDatesOfThisReservationWillBePostponedDueToNewModifications; ?>',
      '<?php echo $wrd_newModificationsWillSplitThisReservation; ?>',
      '<?php echo $wrd_inOrderForNewAdjustmentsToBeMadeThisBookingMustBeRejected; ?>',
      '<?php echo $wrd_name; ?>',
      '<?php echo $wrd_email; ?>',
      '<?php echo $wrd_phoneNumber; ?>',
      '<?php echo $wrd_cleaning; ?>',
      '<?php echo $wrd_maintenance; ?>',
      '<?php echo $wrd_reconstruction; ?>',
      '<?php echo $wrd_other; ?>'
    );
  ">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($bookingUpdateSts) {
    ?>
        <div id="booking-update-wrp">
          <div id="booking-update-header">
            <div class="booking-update-txt-wrp">
              <h1 class="booking-update-title"><?php echo $wrd_requestsForChangesInTheBooking; ?></h1>
            </div>
            <div class="booking-update-header-details-layout">
              <div class="booking-update-txt-wrp">
                <p class="booking-update-header-details-txt"><?php echo $wrd_place; ?>: <b><a href="../place/?id=<?php echo $plcID; ?>" target="_blank"><?php echo $plcRow['name']; ?></a></b></p>
              </div>
              <div class="booking-update-txt-wrp">
                <p class="booking-update-header-details-txt"><?php echo $wrd_dates; ?>: <b><?php echo $bookingRow['fromd'].".".$bookingRow['fromm'].".".$bookingRow['fromy']." (".$firstDay.") - ".$bookingRow['tod'].".".$bookingRow['tom'].".".$bookingRow['toy']." (".$lastDay.")"; ?></b></p>
              </div>
              <div class="booking-update-txt-wrp">
                <p class="booking-update-header-details-txt"><?php echo $wrd_guestNum ?>: <b><?php echo $bookingRow['guestnum']; ?></b></p>
              </div>
              <div class="booking-update-txt-wrp">
                <p class="booking-update-header-details-txt"><?php echo $wrd_fullAmount ?>: <b><?php echo addCurrency($bookingRow['totalcurrency'], $bookingRow['totalprice']); ?></b></p>
              </div>
            </div>
            <div class="booking-update-header-border"></div>
          </div>
          <div id="booking-update-requests-list">
            <?php
              $sqlUpdateRequests = $link->query("SELECT * FROM bookingupdaterequest WHERE bookingbeid='$bookingBeId' ORDER BY fulldate DESC");
              if ($sqlUpdateRequests->num_rows > 0) {
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
                  $sqlArchive = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
                  if ($sqlArchive->num_rows > 0) {
                    $arch = $sqlArchive->fetch_assoc();
                    $fee = $arch['fee'];
                    $feePerc = $arch['percentagefee'];
                  } else {
                    $fee = 0;
                    $feePerc = 0;
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
                  $reqFee = $feePerc * $reqPrice / 100;
                  if ($reqFee < $fee) {
                    $reqFeeDiff = $fee - $reqFee;
                    $reqFeeDiff = "-".$reqFeeDiff;
                  } else if ($reqFee > $fee) {
                    $reqFeeDiff = $reqFee - $fee;
                    $reqFeeDiff = "+".$reqFeeDiff;
                  } else {
                    $reqFeeDiff = 0;
                  }
            ?>
                  <div class="booking-update-request-blck">
                    <div class="booking-update-request-layout">
                      <div class="booking-update-txt-wrp">
                        <p class="booking-update-request-title">
                          <?php
                            if ($updReq['type'] == "date") {
                              echo $wrd_requestToChangeTheDateOfBooking;
                            } else if ($updReq['type'] == "guests") {
                              echo $wrd_requestToChangeTheNumberOfGuestsInTheBooking;
                            }
                          ?>
                        </p>
                      </div>
                      <div class="booking-update-request-details">
                        <?php
                          if ($updReq['type'] == "date") {
                            if ($updReq['status'] == "ready") {
                        ?>
                              <div class="booking-update-txt-wrp">
                                <p class="booking-update-request-details-txt"><?php echo $wrd_originalDate; ?>: <b><?php echo $bookingRow['fromd'].".".$bookingRow['fromm'].".".$bookingRow['fromy']." (".$firstDay.") - ".$bookingRow['tod'].".".$bookingRow['tom'].".".$bookingRow['toy']." (".$lastDay.")"; ?></b></p>
                              </div>
                              <div class="booking-update-txt-wrp">
                                <p class="booking-update-request-details-txt"><?php echo $wrd_newDate; ?>: <b><?php echo $updReq['fromd'].".".$updReq['fromm'].".".$updReq['fromy']." (".$reqFirstDay.") - ".$updReq['tod'].".".$updReq['tom'].".".$updReq['toy']." (".$reqLastDay.")"; ?></b></p>
                              </div>
                        <?php
                            } else {
                        ?>
                              <div class="booking-update-txt-wrp">
                                <p class="booking-update-request-details-txt"><?php echo $wrd_newDate; ?>: <b><?php echo $updReq['fromd'].".".$updReq['fromm'].".".$updReq['fromy']." (".$reqFirstDay.") - ".$updReq['tod'].".".$updReq['tom'].".".$updReq['toy']." (".$reqLastDay.")"; ?></b></p>
                              </div>
                        <?php
                            }
                          } else if ($updReq['type'] == "guests") {
                            if ($updReq['status'] == "ready") {
                        ?>
                              <div class="booking-update-txt-wrp">
                                <p class="booking-update-request-details-txt"><?php echo $wrd_originalNumberOfGuests; ?>: <b><?php echo $bookingRow['guestnum']; ?></b></p>
                              </div>
                              <div class="booking-update-txt-wrp">
                                <p class="booking-update-request-details-txt"><?php echo $wrd_newNumberOfGuests; ?>: <b><?php echo $updReq['guestNum']; ?></b></p>
                              </div>
                        <?php
                            } else {
                        ?>
                              <div class="booking-update-txt-wrp">
                                <p class="booking-update-request-details-txt"><?php echo $wrd_newNumberOfGuests; ?>: <b><?php echo $updReq['guestNum']; ?></b></p>
                              </div>
                        <?php
                            }
                          }
                        ?>
                      </div>
                      <?php
                        if ($updReq['status'] == "ready") {
                          if ($reqPriceDiff == 0) {
                      ?>
                            <div class="booking-update-txt-wrp">
                              <p class="booking-update-request-details-txt"><?php echo $wrd_thePriceForTheBookingIsNotAffectedByThisUpdateAndIs." ".addCurrency($bookingRow['totalcurrency'], $bookingRow['totalprice']); ?></p>
                            </div>
                      <?php
                          } else {
                      ?>
                            <div class="booking-update-txt-wrp">
                              <p class="booking-update-request-details-txt"><?php echo $wrd_newPriceForBooking; ?>: <b class="booking-update-request-details-txt-price"><?php echo addCurrency($bookingRow['totalcurrency'], $reqPrice)." (".$wrd_difference.": ".addCurrency($bookingRow['totalcurrency'], $reqPriceDiff).")"; ?></b></p>
                            </div>
                      <?php
                          }
                          if ($reqFeeDiff == 0) {
                      ?>
                            <div class="booking-update-txt-wrp">
                              <p class="booking-update-request-details-txt"><?php echo $wrd_theFeeForTheBookingIsNotAffectedByThisUpdateAndIs." ".addCurrency($bookingRow['totalcurrency'], $fee); ?></p>
                            </div>
                      <?php
                          } else {
                      ?>
                            <div class="booking-update-txt-wrp">
                              <p class="booking-update-request-details-txt"><?php echo $wrd_newFeeForBooking; ?>: <b class="booking-update-request-details-txt-price"><?php echo addCurrency($bookingRow['totalcurrency'], $reqFee)." (".$wrd_difference.": ".addCurrency($bookingRow['totalcurrency'], $reqFeeDiff).")"; ?></b></p>
                            </div>
                      <?php
                          }
                        }
                      ?>
                      <div class="booking-update-error-wrp" id="booking-update-error-wrp-<?php echo $reqID; ?>">
                        <p class="booking-update-error-txt" id="booking-update-error-txt-<?php echo $reqID; ?>"></p>
                      </div>
                      <div class="booking-update-btn-wrp">
                        <?php
                          if ($updReq['status'] == "ready") {
                        ?>
                          <button type="button" class="btn btn-mid btn-fth booking-update-btn" id="booking-update-btn-reject-<?php echo $reqID; ?>" onclick="bookingUpdateReject('<?php echo $reqID; ?>')"><?php echo $wrd_reject; ?></button>
                          <button type="button" class="btn btn-mid btn-prim booking-update-btn" id="booking-update-btn-confirm-<?php echo $reqID; ?>" onclick="bookingUpdateConfirmCheck('<?php echo $reqID; ?>')"><?php echo $wrd_confirm; ?></button>
                        <?php
                          } else {
                            if ($updReq['status'] == "canceled") {
                              $bookingUpdateFooterTxt = $wrd_changeRequestCanceled;
                            } else if ($updReq['status'] == "confirmed") {
                              $bookingUpdateFooterTxt = $wrd_changeRequestConfirmed;
                            } elseif ($updReq['status'] == "rejected") {
                              $bookingUpdateFooterTxt = $wrd_changeRequestRejected;
                            } else {
                              $bookingUpdateFooterTxt = $updReq['status'];
                            }
                        ?>
                          <p class="booking-update-footer-txt"><?php echo $bookingUpdateFooterTxt; ?></p>
                        <?php
                          }
                        ?>
                      </div>
                    </div>
                  </div>
            <?php
                }
              } else {
            ?>
                <div id="booking-update-requests-list-error-wrp">
                  <div class="booking-update-txt-wrp">
                    <p id="booking-update-requests-list-error-txt"><?php echo $wrd_noChangeRequestsWereFoundInThisBooking; ?></p>
                  </div>
                </div>
            <?php
              }
            ?>
          </div>
        </div>
    <?php
        include "../uni/code/php-frontend/permission-needed-modal.php";
      } else {
    ?>
      <div class="page-error">
        <p class="page-error-p">
          <?php
            echo $bookingUpdateErrorTxt;
          ?>
        </p>
      </div>
    <?php
      }
    ?>
  </body>
</html>
