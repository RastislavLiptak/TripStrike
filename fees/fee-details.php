<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/add-currency.php";
  include "php-backend/get-list-of-payment-reference-bookings.php";
  $subtitle = $wrd_feeDetails;
  $feeDetailsSts = true;
  $bookingUpdateErrorTxt = "";
  $totalAmount = 0;
  $totalCurrency = "eur";
  $paymentStatus = "";
  $numberOfBookings = 0;
  if (isset($_GET['paymentreference'])) {
    $paymentReferenceCode = $_GET['paymentreference'];
  } else {
    $paymentReferenceCode = "";
  }
  if ($paymentReferenceCode != "") {
    if ($sign == "yes") {
      if ($bnft_add_cottage == "good") {
        $sqlCheckExistence = $linkBD->query("SELECT * FROM feespaymentcalls WHERE paymentreference='$paymentReferenceCode'");
        if ($sqlCheckExistence->num_rows > 0) {
          $sqlCheckHost = $linkBD->query("SELECT * FROM feespaymentcalls WHERE paymentreference='$paymentReferenceCode' and hostbeid='$usrBeId'");
          if ($sqlCheckHost->num_rows > 0) {
            $paymentReferenceDetailsArr = paymentReferenceDetails($paymentReferenceCode);
            $totalAmount = $paymentReferenceDetailsArr['totalFee'];
            $totalCurrency = $paymentReferenceDetailsArr['currency'];
            $paymentStatus = $paymentReferenceDetailsArr['status'];
            $numberOfBookings = $paymentReferenceDetailsArr['numberOfBookings'];
          } else {
            $feeDetailsSts = false;
            $bookingUpdateErrorTxt = "This Payment does not belong to this profile";
          }
        } else {
          $feeDetailsSts = false;
          $bookingUpdateErrorTxt = "Payment Reference does not exist in the database";
        }
      } else {
        $feeDetailsSts = false;
        $bookingUpdateErrorTxt = $wrd_featureNotAvailable;
      }
    } else {
      $feeDetailsSts = false;
      $bookingUpdateErrorTxt = $wrd_accountRequired;
    }
  } else {
    $feeDetailsSts = false;
    $bookingUpdateErrorTxt = "Empty 'paymentreference' code in URL";
  }

  function paymentReferenceDetails($paymentReferenceCode) {
    global $linkBD;
    $status = "";
    $currency = "eur";
    $totalFee = 0;
    $numberOfBookings = 0;
    $sqlPaymentReferencesCalls = $linkBD->query("SELECT * FROM feespaymentcalls WHERE paymentreference='$paymentReferenceCode'");
    if ($sqlPaymentReferencesCalls->num_rows > 0) {
      while($rowPaymentReferencesCalls = $sqlPaymentReferencesCalls->fetch_assoc()) {
        $paymentReferenceBeId = $rowPaymentReferencesCalls['beid'];
        $sqlPaymentReferencesKey = $linkBD->query("SELECT * FROM feespaymentcallskey WHERE beid='$paymentReferenceBeId'");
        $numberOfBookings = $sqlPaymentReferencesKey->num_rows;
        if ($numberOfBookings > 0) {
          $paymentStatus = "none";
          while($rowPaymentReferencesKey = $sqlPaymentReferencesKey->fetch_assoc()) {
            $bookingBeId = $rowPaymentReferencesKey['bookingbeid'];
            $sqlBooking = $linkBD->query("SELECT * FROM bookingarchive WHERE beid='$bookingBeId' LIMIT 1");
            if ($sqlBooking->num_rows > 0) {
              while ($rowBooking = $sqlBooking->fetch_assoc()) {
                $bookingSts = $rowBooking['status'];
                $currency = $rowBooking['currency'];
                if ($rowBooking['paymentStatus'] == 1) {
                  if ($paymentStatus == "none" || $paymentStatus == "paid") {
                    $paymentStatus = "paid";
                  } else {
                    $paymentStatus = "multiple";
                  }
                } else {
                  if ($paymentStatus == "none" || $paymentStatus == "unpaid") {
                    $paymentStatus = "unpaid";
                  } else {
                    $paymentStatus = "multiple";
                  }
                }
                if ($paymentStatus == "paid") {
                  $bookingSts = "paid";
                } else {
                  if (checkTimelinessOfBooking($rowBooking['beid']) == "past" && $rowBooking['tom']."-".$rowBooking['toy'] != (int)date("m")."-".date("Y")) {
                    if ($bookingSts == "waiting" || $bookingSts == "booked") {
                      if ($rowBooking['paymentStatus'] == 1) {
                        $bookingSts = "paid";
                      } else {
                        $bookingSts = "unpaid";
                      }
                    }
                  }
                }
                if (giveStatusValue($status) < giveStatusValue($bookingSts)) {
                  $status = $bookingSts;
                }
                $totalFee = $totalFee * 1 + $rowBooking['fee'];
              }
            }
          }
        }
      }
    }
    return [
      "status" => $status,
      "totalFee" => $totalFee,
      "currency" => $currency,
      "numberOfBookings" => $numberOfBookings
    ];
  }

  function giveStatusValue($status) {
    $stsValueArray = ["", "-", "rejected", "canceled", "waiting", "paid", "booked", "unpaid"];
    return array_search($status, $stsValueArray);
  }
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/fee-details.js"></script>
    <script src="../uni/code/js/add-currency.js" async></script>
    <link rel="stylesheet" type="text/css" href="../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="css/fee-details.css">
    <meta name="description" content="<?php echo $wrd_metaDescription; ?>">
    <title><?php echo $wrd_feeDetails." - ".$paymentReferenceCode." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
      if ($feeDetailsSts) {
    ?>
      <div id="fee-details-size">
        <div id="fee-details-header-wrp">
          <div id="fee-details-header-blck">
            <div class="fee-details-header-details-layout">
              <div class="fee-details-header-details-txt-wrp">
                <h2 class="fee-details-header-details-title fee-details-header-details-title-none-top-margin"><?php echo $wrd_basicInfo; ?></h2>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <?php
                  if ($paymentStatus == "paid") {
                    $paymentStatusTxt = $wrd_paid;
                    $paymentStatusClass = "fee-details-header-details-txt-green";
                  } else if ($paymentStatus == "unpaid") {
                    $paymentStatusTxt = $wrd_unpaid;
                    $paymentStatusClass = "fee-details-header-details-txt-red";
                  } else {
                    $paymentStatusTxt = $paymentStatus;
                    $paymentStatusClass = "";
                  }
                ?>
                <p class="fee-details-header-details-txt"><b><?php echo $wrd_status; ?>:</b> <span class="<?php echo $paymentStatusClass; ?>"><?php echo $paymentStatusTxt; ?></span></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-txt"><b><?php echo $wrd_numberOfBookings; ?>:</b> <?php echo $numberOfBookings; ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-txt"><b><?php echo $wrd_fullAmount; ?>:</b> <?php echo addCurrency($totalCurrency, $totalAmount); ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <h2 class="fee-details-header-details-title"><?php echo $wrd_paymentInformation; ?></h2>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-txt"><b><?php echo "IBAN"; ?>:</b> <?php echo $bds_detailsForThePaymentOfFeesIban; ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-txt"><b><?php echo $wrd_bankAccount; ?>:</b> <?php echo $bds_detailsForThePaymentOfFeesBankAccount; ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-txt"><b><?php echo "BIC/SWIFT"; ?>:</b> <?php echo $bds_detailsForThePaymentOfFeesBicSwift; ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-txt"><b><?php echo $wrd_paymentReference." / ".$wrd_variableSymbol; ?>:</b> <?php echo $paymentReferenceCode; ?></p>
              </div>
            </div>
            <div class="fee-details-header-details-free-space"></div>
            <div class="fee-details-header-details-layout">
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-desc"><?php echo $wrd_checkIfPaymentReferenceAndAmountIsCorrect." ".addCurrency($totalCurrency, $totalAmount)."."; ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-desc"><?php echo $wrd_ifEverythingIsInOrderPayAndReceivePaymentConfirmationEmail; ?></p>
              </div>
              <div class="fee-details-header-details-txt-wrp">
                <p class="fee-details-header-details-desc"><?php echo $wrd_inCaseOfProblemOrQuestionContactUsHere1; ?> <a href="../support/" target="_blank"><?php echo $wrd_inCaseOfProblemOrQuestionContactUsHere2; ?></a>.</p>
              </div>
            </div>
          </div>
        </div>
        <div id="fee-details-table-blck">
          <div class="table-layout">
            <?php
              $listOfPaymentReferenceBookings = getListOfPaymentReferenceBookings("list", $paymentReferenceCode, "");
              $loadedListData = getListOfPaymentReferenceBookings("load-amount", $paymentReferenceCode, "");
            ?>
            <div class="table-wrp">
              <table class="table" id="booking-fees-list-table-payment-reference">
                <tr>
                  <th><?php echo $wrd_status; ?></th>
                  <th><?php echo $wrd_place; ?></th>
                  <th><?php echo $wrd_dates; ?></th>
                  <th><?php echo $wrd_fee; ?></th>
                  <th><?php echo $wrd_feeInPercent; ?></th>
                </tr>
                <?php
                  if (sizeof($listOfPaymentReferenceBookings) > 0) {
                    for ($lOPRB=0; $lOPRB < sizeof($listOfPaymentReferenceBookings); $lOPRB++) {
                      if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "") {
                        $bookingSts = "<i>".$wrd_unknown." (empty)</i>";
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "-") {
                        $bookingSts = "<i>".$wrd_unknown." (-)</i>";
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "rejected") {
                        $bookingSts = "<i>".$wrd_rejected."</i>";
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "canceled") {
                        $bookingSts = "<i>".$wrd_canceled."</i>";
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "waiting") {
                        $bookingSts = $wrd_waitingForConfirmation;
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "booked") {
                        $bookingSts = $wrd_booked;
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "paid") {
                        $bookingSts = "<span class='table-data-green'>".$wrd_paid."</span>";
                      } else if ($listOfPaymentReferenceBookings[$lOPRB]['status'] == "unpaid") {
                        $bookingSts = "<span class='table-data-red'>".$wrd_unpaid."</span>";
                      }
                      if ($listOfPaymentReferenceBookings[$lOPRB]['plcSts'] == "active") {
                        $plcName = $listOfPaymentReferenceBookings[$lOPRB]['plcName'];
                      } else {
                        if ($listOfPaymentReferenceBookings[$lOPRB]['plcName'] != "-") {
                          $plcName = "<i>".$listOfPaymentReferenceBookings[$lOPRB]['plcName']."</i>";
                        } else {
                          $plcName = "<i>".$wrd_placeDeleted."</i>";
                        }
                      }
                ?>
                      <tr class="booking-fees-list-table-row">
                        <td><?php echo $bookingSts; ?></td>
                        <td><?php echo $plcName; ?></td>
                        <td><?php echo $listOfPaymentReferenceBookings[$lOPRB]['fromD'].".".$listOfPaymentReferenceBookings[$lOPRB]['fromM'].".".$listOfPaymentReferenceBookings[$lOPRB]['fromY']." - ".$listOfPaymentReferenceBookings[$lOPRB]['toD'].".".$listOfPaymentReferenceBookings[$lOPRB]['toM'].".".$listOfPaymentReferenceBookings[$lOPRB]['toY']; ?></td>
                        <td><?php echo addCurrency($listOfPaymentReferenceBookings[$lOPRB]['currency'], $listOfPaymentReferenceBookings[$lOPRB]['fee']); ?></td>
                        <td><?php echo ($listOfPaymentReferenceBookings[$lOPRB]['percentageFee'] + 0)."%"; ?></td>
                        <td><a href="../bookings/about-guest-booking.php?id=<?php echo $listOfPaymentReferenceBookings[$lOPRB]['bookingID']; ?>" target="_blank" class="table-row-link-blue"><?php echo $wrd_showMore; ?></a></td>
                      </tr>
                <?php
                    }
                  }
                ?>
              </table>
            </div>
            <div class="table-about-wrp">
              <?php
                if (sizeof($listOfPaymentReferenceBookings) > 0) {
                  $lastID = $listOfPaymentReferenceBookings[sizeof($listOfPaymentReferenceBookings) -1]['bookingID'];
                } else {
                  $lastID = "";
                }
              ?>
              <p id="booking-fees-list-table-about-last-id"><?php echo $lastID; ?></p>
            </div>
            <div class="table-tools-wrp">
              <div class="table-tools-errors-wrp" id="booking-fees-list-tools-errors-wrp">
                <div class="table-tools-errors-txt-size">
                  <p class="table-tools-errors-txt" id="booking-fees-list-tools-errors-txt"></p>
                </div>
              </div>
              <div class="table-tools-loader-wrp" id="booking-fees-list-tools-loader-wrp">
                <img src="../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
              </div>
              <?php
                if ($loadedListData["remain"] > 0) {
                  $loadMoreStyle = "display: flex;";
                } else {
                  $loadMoreStyle = "";
                }
              ?>
              <div class="table-tools-load-more-wrp" id="booking-fees-list-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
                <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="booking-fees-list-tools-load-more-btn" onclick="bookingFeesContentLoadMore();"><?php echo $wrd_loadMore; ?></button>
              </div>
              <?php
                if ($loadedListData["all-bookings"] > 0) {
                  $noContentStyle = "";
                } else {
                  $noContentStyle = "display: flex;";
                }
              ?>
              <div class="table-tools-no-content-wrp" id="booking-fees-list-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
                <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
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
            echo $bookingUpdateErrorTxt;
          ?>
        </p>
      </div>
    <?php
      }
    ?>
  </body>
</html>
