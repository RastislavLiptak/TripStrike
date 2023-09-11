<?php
  include "../uni/code/php-head.php";
  include "../../uni/code/php-backend/add-currency.php";
  include "../../uni/code/php-backend/get-data-from-date.php";
  include "php-backend/get-list-of-payment-references-fees.php";
  if (!isset($_GET['section'])) {
    header("Location: ../fees/?section=fees");
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <script src="js/fees.js"></script>
    <script src="../../uni/code/js/add-currency.js"></script>
    <link rel="stylesheet" type="text/css" href="../../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="css/fees.css">
    <title><?php echo $wrd_fees." - ".$title." Back Door"; ?></title>
  </head>
  <body onload="
    feesDictionary(
      '<?php echo $wrd_searchByReferenceCodeOrHost; ?>',
      '<?php echo $wrd_searchForHostByNameOrPeriod; ?>',
      '<?php echo $wrd_searchForBookingByPlaceHostDate; ?>'
    );
  ">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div id="page-layout">
      <?php
        include "../uni/code/php-frontend/main-menu.php";
      ?>
      <div id="content-wrp">
        <div id="content">
          <div id="content-title-wrp">
            <h1 id="content-title"><?php echo $wrd_fees; ?></h1>
          </div>
          <div class="table-layout">
            <div class="table-filter-wrp">
              <div class="table-filter-search-bar-wrp">
                <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" onclick="feesContentSearchBtn();"></button>
                <input type="text" class="table-filter-search-bar-input" id="table-filter-search-bar-input-fees" placeholder="<?php echo $wrd_searchByReferenceCodeOrHost; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){feesContentSearchType(this);}">
                <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" onclick="feesContentSearchCancel();"></button>
              </div>
              <div class="table-filter-tools-wrp">
                <div class="table-filter-select-wrp">
                  <select class="table-filter-select" id="b-d-fees-table-filter-switch-content" onchange="backDoorFeesSwitchContent(this.value);">
                    <option value="payment-reference" selected><?php echo $wrd_paymentReference; ?></option>
                    <option value="hosts"><?php echo $wrd_hosts; ?></option>
                    <option value="bookings"><?php echo $wrd_bookings; ?></option>
                  </select>
                </div>
              </div>
            </div>
            <?php
              $listOfPaymentReferencesFees = getListOfPaymentReferencesFees("list", "", "");
              $loadedListData = getListOfPaymentReferencesFees("load-amount", "", "");
            ?>
            <div class="table-wrp b-d-fees-table-wrp b-d-fees-table-selected" id="b-d-fees-table-payment-reference-wrp">
              <table class="table" id="b-d-fees-table-payment-reference">
                <tr>
                  <th><?php echo $wrd_status; ?></th>
                  <th><?php echo $wrd_host; ?></th>
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
                      if ($listOfPaymentReferencesFees[$lOPRF]['paymentStatus'] == "paid") {
                        $paymentBtnTxt = $wrd_unpaid;
                        $paymentBtnStyle = "";
                      } else if ($listOfPaymentReferencesFees[$lOPRF]['paymentStatus'] == "unpaid") {
                        $paymentBtnTxt = $wrd_paid;
                        $paymentBtnStyle = "";
                      } else {
                        $paymentBtnTxt = "";
                        $paymentBtnStyle = "display:none;";
                      }
                ?>
                      <tr class="b-d-fees-table-row">
                        <td id="b-d-fees-table-row-status-<?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?>"><?php echo $rowStatus; ?></td>
                        <td><a href="../user/?section=users&nav=about&id=<?php echo $listOfPaymentReferencesFees[$lOPRF]['hostID']; ?>&m=&y=&paymentreference=" target="_blank"><?php echo $listOfPaymentReferencesFees[$lOPRF]['hostName']; ?></a></td>
                        <td><?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?></td>
                        <td><?php echo $listOfPaymentReferencesFees[$lOPRF]['numOfBookings']; ?></td>
                        <td><?php echo addCurrency($listOfPaymentReferencesFees[$lOPRF]['currency'], $listOfPaymentReferencesFees[$lOPRF]['totalFee']); ?></td>
                        <td><button class="btn btn-mid btn-sec b-d-fees-table-payment-btn" id="b-d-fees-table-payment-btn-<?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?>" onclick="togglePaymentReferenceFeesPaymentStatus('<?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?>');" value="ready" style="<?php echo $paymentBtnStyle; ?>"><?php echo $paymentBtnTxt; ?></button></td>
                        <td><a href="../user/?section=fees&nav=hostbookings&id=<?php echo $listOfPaymentReferencesFees[$lOPRF]['hostID']; ?>&m=&y=&paymentreference=<?php echo $listOfPaymentReferencesFees[$lOPRF]['payment-reference']; ?>" class="table-row-link-blue"><?php echo $wrd_showMore; ?></a></td>
                      </tr>
                <?php
                    }
                  }
                ?>
              </table>
            </div>
            <div class="table-wrp b-d-fees-table-wrp" id="b-d-fees-table-hosts-wrp">
              <table class="table" id="b-d-fees-table-hosts">
                <tr>
                  <th><?php echo $wrd_status; ?></th>
                  <th><?php echo $wrd_host; ?></th>
                  <th><?php echo $wrd_period; ?></th>
                  <th><?php echo $wrd_numberOfBookings; ?></th>
                  <th><?php echo $wrd_price; ?></th>
                  <th><?php echo $wrd_fee; ?></th>
                </tr>
              </table>
            </div>
            <div class="table-wrp b-d-fees-table-wrp" id="b-d-fees-table-bookings-wrp">
              <table class="table" id="b-d-fees-table-bookings">
                <tr>
                  <th><?php echo $wrd_status; ?></th>
                  <th><?php echo $wrd_place; ?></th>
                  <th><?php echo $wrd_host; ?></th>
                  <th><?php echo $wrd_dates; ?></th>
                  <th><?php echo $wrd_price; ?></th>
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
              <p id="b-d-fees-table-about-last-id"><?php echo $lastID; ?></p>
              <p id="b-d-fees-table-about-last-y"></p>
              <p id="b-d-fees-table-about-last-m"></p>
            </div>
            <div class="table-tools-wrp">
              <div class="table-tools-errors-wrp" id="b-d-fees-table-tools-errors-wrp">
                <div class="table-tools-errors-txt-size">
                  <p class="table-tools-errors-txt" id="b-d-fees-table-tools-errors-txt"></p>
                </div>
              </div>
              <div class="table-tools-loader-wrp" id="b-d-fees-table-tools-loader-wrp">
                <img src="../../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
              </div>
              <?php
                if ($loadedListData["remain"] > 0) {
                  $loadMoreStyle = "display: flex;";
                } else {
                  $loadMoreStyle = "";
                }
              ?>
              <div class="table-tools-load-more-wrp" id="b-d-fees-table-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
                <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="b-d-fees-table-tools-load-more-btn" onclick="feesContentLoadMore();"><?php echo $wrd_loadMore; ?></button>
              </div>
              <?php
                if ($loadedListData["all-bookings"] > 0) {
                  $noContentStyle = "";
                } else {
                  $noContentStyle = "display: flex;";
                }
              ?>
              <div class="table-tools-no-content-wrp" id="b-d-fees-table-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
                <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
