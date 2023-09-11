<?php
  include "../uni/code/php-head.php";
  include "php-backend/get-list-of-payment-references-fees.php";
  $subtitle = $wrd_fees;
?>
<!DOCTYPE html>
<html lang="<?php echo $wrd_htmlLang; ?>">
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <link rel="stylesheet" type="text/css" href="../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="../bookings/css/bookings-list.css">
    <link rel="stylesheet" type="text/css" href="css/fees.css">
    <script src="js/fees.js"></script>
    <script src="../uni/code/js/add-currency.js" async></script>
    <meta name="description" content="<?php echo $wrd_metaDescription; ?>">
    <title><?php echo $wrd_fees." - ".$title; ?></title>
  </head>
  <body onload="
    <?php echo $onload; ?>
    feesDictionary(
      '<?php echo $wrd_searchByReferenceCode; ?>',
      '<?php echo $wrd_searchForBookingByPlaceNameOrDate; ?>'
    );
  ">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div class="bookings-list-wrp">
      <div class="bookings-list-layout">
        <div class="bookings-list-header">
          <div class="bookings-list-title-wrp">
            <h1 class="bookings-list-title-txt"><?php echo $wrd_fees; ?></h1>
          </div>
          <div class="bookings-list-header-tools-wrp">
            <button type="button" class="bookings-list-header-tools-btn" id="fees-details-modal-btn" onclick="modCover('show', 'modal-cover-fees-details');"></button>
          </div>
        </div>
        <div class="table-layout">
          <div class="table-filter-wrp">
            <div class="table-filter-search-bar-wrp">
              <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" id="fees-list-search-bar-input-btn-search" onclick="feesContentSearchBtn();"></button>
              <input type="text" class="table-filter-search-bar-input" id="fees-list-search-bar-input" placeholder="<?php echo $wrd_searchByReferenceCode; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){feesContentSearchType(this);}">
              <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" id="fees-list-search-bar-input-btn-cancel" onclick="feesContentSearchCancel();"></button>
            </div>
            <div class="table-filter-tools-wrp">
              <div class="table-filter-select-wrp">
                <select class="table-filter-select" id="fees-list-table-filter-switch-content" value="payment-reference" onchange="feesSwitchTableContent(this.value);">
                  <option value="payment-reference" selected><?php echo $wrd_paymentReference; ?></option>
                  <option value="bookings"><?php echo $wrd_bookings; ?></option>
                </select>
              </div>
            </div>
          </div>
          <?php
            if ($sign == "yes" && $bnft_add_cottage == "good") {
              $listOfPaymentReferencesFees = getListOfPaymentReferencesFees("list", $usrBeId, "", "");
              $loadedListData = getListOfPaymentReferencesFees("load-amount", $usrBeId, "", "");
            } else {
              $listOfPaymentReferencesFees = [];
              $loadedListData = [
                "all-fees" => 0,
                "loaded" => 0,
                "remain" => 0
              ];
            }
          ?>
          <div class="table-wrp fees-list-table-wrp fees-list-table-wrp-selected" id="fees-list-table-payment-reference-wrp">
            <table class="table" id="fees-list-table-payment-reference">
              <tr>
                <th><?php echo $wrd_status; ?></th>
                <th><?php echo $wrd_paymentReference; ?></th>
                <th><?php echo $wrd_numberOfBookings; ?></th>
                <th><?php echo $wrd_fee; ?></th>
              </tr>
              <?php
                if (sizeof($listOfPaymentReferencesFees) > 0) {
                  for ($lOPRF=0; $lOPRF < sizeof($listOfPaymentReferencesFees); $lOPRF++) {
                    if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "") {
                      $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "-") {
                      $rowStatus = "<i>".$wrd_unknown." (-)</i>";
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "rejected") {
                      $rowStatus = "<i>".$wrd_rejected."</i>";
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "canceled") {
                      $rowStatus = "<i>".$wrd_canceled."</i>";
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "waiting") {
                      $rowStatus = $wrd_waitingForConfirmation;
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "booked") {
                      $rowStatus = $wrd_booked;
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "paid") {
                      $rowStatus = "<span class='table-data-green'>".$wrd_paid."</span>";
                    } else if ($listOfPaymentReferencesFees[$lOPRF]['status'] == "unpaid") {
                      $rowStatus = "<span class='table-data-red'>".$wrd_unpaid."</span>";
                    }
              ?>
                    <tr class="fees-list-table-row">
                      <td id="fees-list-table-row-status-<?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?>"><?php echo $rowStatus; ?></td>
                      <td><?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?></td>
                      <td><?php echo $listOfPaymentReferencesFees[$lOPRF]['numOfBookings']; ?></td>
                      <td><?php echo addCurrency($listOfPaymentReferencesFees[$lOPRF]['currency'], $listOfPaymentReferencesFees[$lOPRF]['totalFee']); ?></td>
                      <td><a href="fee-details.php?paymentreference=<?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?>" class="table-row-link-blue"><?php echo $wrd_showMore; ?></a></td>
                    </tr>
              <?php
                  }
                }
              ?>
            </table>
          </div>
          <div class="table-wrp fees-list-table-wrp" id="fees-list-table-bookings-wrp">
            <table class="table" id="fees-list-table-bookings">
              <tr>
                <th><?php echo $wrd_status; ?></th>
                <th><?php echo $wrd_place; ?></th>
                <th><?php echo $wrd_dates; ?></th>
                <th><?php echo $wrd_fee; ?></th>
                <th><?php echo $wrd_feeInPercent; ?></th>
              </tr>
            </table>
          </div>
          <div class="table-about-wrp">
            <?php
              if (sizeof($listOfPaymentReferencesFees) > 0) {
                $lastID = $listOfPaymentReferencesFees[sizeof($listOfPaymentReferencesFees) -1]['payment-reference'];
              } else {
                $lastID = "";
              }
            ?>
            <p id="fees-list-table-about-last-id"><?php echo $lastID; ?></p>
          </div>
          <div class="table-tools-wrp">
            <div class="table-tools-errors-wrp" id="fees-list-tools-errors-wrp">
              <div class="table-tools-errors-txt-size">
                <p class="table-tools-errors-txt" id="fees-list-tools-errors-txt"></p>
              </div>
            </div>
            <div class="table-tools-loader-wrp" id="fees-list-tools-loader-wrp">
              <img src="../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
            </div>
            <?php
              if ($loadedListData["remain"] > 0) {
                $loadMoreStyle = "display: flex;";
              } else {
                $loadMoreStyle = "";
              }
            ?>
            <div class="table-tools-load-more-wrp" id="fees-list-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
              <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="fees-list-tools-load-more-btn" onclick="feesContentLoadMore();"><?php echo $wrd_loadMore; ?></button>
            </div>
            <?php
              if ($loadedListData["all-fees"] > 0) {
                $noContentStyle = "";
              } else {
                if ($sign == "yes") {
                  $noContentStyle = "display: flex;";
                } else {
                  $noContentStyle = "";
                }
              }
            ?>
            <div class="table-tools-no-content-wrp" id="fees-list-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
              <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
            </div>
            <?php
              if ($sign == "yes" && $bnft_add_cottage == "good") {
                $featureNotAvailableStyle = "";
              } else {
                $featureNotAvailableStyle = "display: flex;";
              }
            ?>
            <div class="table-tools-feature-not-available-wrp" style="<?php echo $featureNotAvailableStyle; ?>">
              <?php
                if ($sign != "yes"){
              ?>
                <div class="table-tools-feature-not-available-btn-wrp">
                  <button class="btn btn-mid btn-prim" onclick="signInModal('show')"><?php echo $wrd_signIn; ?></button>
                </div>
                <p class="table-tools-feature-not-available-txt"><?php echo $wrd_accountRequired; ?></p>
              <?php
                } else if ($bnft_add_cottage != "good") {
              ?>
                <p class="table-tools-feature-not-available-txt"><?php echo $wrd_featureNotAvailable; ?></p>
              <?php
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-cover" id="modal-cover-fees-details">
      <div class="modal-block" id="modal-cover-fees-details-blck">
        <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-fees-details');"></button>
        <div id="fees-details-modal-size">
          <div id="fees-details-modal-layout">
            <h2 class="fees-details-modal-title"><?php echo $wrd_fees; ?></h2>
            <p class="fees-details-modal-txt"><?php echo $wrd_howTheBookingSystemWorksDesc14; ?></p>
            <br>
            <p class="fees-details-modal-txt"><?php echo $wrd_howTheBookingSystemWorksDesc15; ?></p>
            <br>
            <p class="fees-details-modal-txt"><?php echo $wrd_howTheBookingSystemWorksDesc16; ?></p>
            <br>
            <p class="fees-details-modal-txt"><?php echo $wrd_feeAmount; ?>: <b><?php echo $bds_percAmountOfTheFees."%"; ?></b> <i><?php echo "(".$wrd_ofTheTotalValueOfTheBooking.")"; ?></i></p>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
