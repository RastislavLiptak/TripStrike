<div class="table-layout">
  <?php
    $listOfBookingHistory = getBookingHistory("list", $bookingBeId, "");
    $loadedListData = getBookingHistory("load-amount", $bookingBeId, "");
  ?>
  <div class="table-wrp">
    <table class="table" id="b-d-booking-history-table">
      <tr>
        <th><?php echo $wrd_status; ?></th>
        <th><?php echo $wrd_feePaymentStatus; ?></th>
        <th><?php echo $wrd_host; ?></th>
        <th><?php echo $wrd_capitalGuest; ?></th>
        <th><?php echo $wrd_place; ?></th>
        <th><?php echo $wrd_bookingSource; ?></th>
        <th><?php echo $wrd_guestNum; ?></th>
        <th><?php echo $wrd_from; ?></th>
        <th><?php echo $wrd_to; ?></th>
        <th><?php echo $wrd_priceMode; ?></th>
        <th><?php echo $wrd_priceOnWorkingDays; ?></th>
        <th><?php echo $wrd_weekendPrice; ?></th>
        <th><?php echo $wrd_price; ?></th>
        <th><?php echo $wrd_fee; ?></th>
        <th><?php echo $wrd_feeInPercent; ?></th>
        <th><?php echo $wrd_validFrom; ?></th>
      </tr>
      <?php
        if (sizeof($listOfBookingHistory) > 0) {
          for ($lOBU=0; $lOBU < sizeof($listOfBookingHistory); $lOBU++) {
            if ($listOfBookingHistory[$lOBU]['status'] == "") {
              $rowStatus = $wrd_unknown." (empty)";
            } else if ($listOfBookingHistory[$lOBU]['status'] == "-") {
              $rowStatus = $wrd_unknown." (-)";
            } else if ($listOfBookingHistory[$lOBU]['status'] == "rejected") {
              $rowStatus = $wrd_rejected;
            } else if ($listOfBookingHistory[$lOBU]['status'] == "canceled") {
              $rowStatus = $wrd_canceled;
            } else if ($listOfBookingHistory[$lOBU]['status'] == "waiting") {
              $rowStatus = $wrd_waitingForConfirmation;
            } else if ($listOfBookingHistory[$lOBU]['status'] == "booked") {
              $rowStatus = $wrd_booked;
            }
            if ($listOfBookingHistory[$lOBU]['paymentStatus'] == "1") {
              $rowPaymentStatus = $wrd_paid;
            } else if ($listOfBookingHistory[$lOBU]['paymentStatus'] == "0") {
              $rowPaymentStatus = $wrd_unpaid;
            }
            if ($listOfBookingHistory[$lOBU]['source'] == "booking-form") {
              $rowSource = $wrd_bookingForm;
            } else if ($listOfBookingHistory[$lOBU]['source'] == "editor") {
              $rowSource = $wrd_bookingEditor;
            } else {
              $rowSource = $listOfBookingHistory[$lOBU]['source'];
            }
            if ($listOfBookingHistory[$lOBU]['firstday'] == "whole") {
              $fromDate = $listOfBookingHistory[$lOBU]['fromD'].".".$listOfBookingHistory[$lOBU]['fromM'].".".$listOfBookingHistory[$lOBU]['fromY']." (".$wrd_theWholeDay.")";
            } else if ($listOfBookingHistory[$lOBU]['firstday'] == "half") {
              $fromDate = $listOfBookingHistory[$lOBU]['fromD'].".".$listOfBookingHistory[$lOBU]['fromM'].".".$listOfBookingHistory[$lOBU]['fromY']." (".$wrd_from." 14:00)";
            } else {
              $fromDate = $listOfBookingHistory[$lOBU]['fromD'].".".$listOfBookingHistory[$lOBU]['fromM'].".".$listOfBookingHistory[$lOBU]['fromY']." (".$listOfBookingHistory[$lOBU]['firstday'].")";
            }
            if ($listOfBookingHistory[$lOBU]['lastday'] == "whole") {
              $toDate = $listOfBookingHistory[$lOBU]['toD'].".".$listOfBookingHistory[$lOBU]['toM'].".".$listOfBookingHistory[$lOBU]['toY']." (".$wrd_theWholeDay.")";
            } else if ($listOfBookingHistory[$lOBU]['lastday'] == "half") {
              $toDate = $listOfBookingHistory[$lOBU]['toD'].".".$listOfBookingHistory[$lOBU]['toM'].".".$listOfBookingHistory[$lOBU]['toY']." (".$wrd_to." 11:00)";
            } else {
              $toDate = $listOfBookingHistory[$lOBU]['toD'].".".$listOfBookingHistory[$lOBU]['toM'].".".$listOfBookingHistory[$lOBU]['toY']." (".$listOfBookingHistory[$lOBU]['lastday'].")";
            }
            if ($listOfBookingHistory[$lOBU]['priceMode'] == "nights") {
              $rowPriceMode = $wrd_pricePerNight;
            } else {
              $rowPriceMode = $wrd_pricePerNightForOneGuest;
            }
      ?>
            <tr class="b-d-booking-history-table-row">
              <td><?php echo $rowStatus; ?></td>
              <td><?php echo $rowPaymentStatus; ?></td>
              <td><?php echo $listOfBookingHistory[$lOBU]['hostName']; ?></td>
              <td><?php echo $listOfBookingHistory[$lOBU]['guestName']; ?></td>
              <td><?php echo $listOfBookingHistory[$lOBU]['plcName']; ?></td>
              <td><?php echo $rowSource; ?></td>
              <td><?php echo $listOfBookingHistory[$lOBU]['numOfGuests']; ?></td>
              <td><?php echo $fromDate; ?></td>
              <td><?php echo $toDate; ?></td>
              <td><?php echo $rowPriceMode; ?></td>
              <td><?php echo addCurrency($listOfBookingHistory[$lOBU]['currency'], $listOfBookingHistory[$lOBU]['workPrice']); ?></td>
              <td><?php echo addCurrency($listOfBookingHistory[$lOBU]['currency'], $listOfBookingHistory[$lOBU]['weekPrice']); ?></td>
              <td><?php echo addCurrency($listOfBookingHistory[$lOBU]['currency'], $listOfBookingHistory[$lOBU]['totalPrice']); ?></td>
              <td><?php echo addCurrency($listOfBookingHistory[$lOBU]['currency'], $listOfBookingHistory[$lOBU]['fee']); ?></td>
              <td><?php echo ($listOfBookingHistory[$lOBU]['percentageFee'] + 0)."%"; ?></td>
              <td><?php echo $listOfBookingHistory[$lOBU]['validFrom']; ?></td>
            </tr>
      <?php
          }
        }
      ?>
    </table>
  </div>
  <div class="table-about-wrp">
    <?php
      if (sizeof($listOfBookingHistory) > 0) {
        $lastID = $listOfBookingHistory[sizeof($listOfBookingHistory) -1]['updateID'];
      } else {
        $lastID = "";
      }
    ?>
    <p id="b-d-booking-history-table-about-last-id"><?php echo $lastID; ?></p>
  </div>
  <div class="table-tools-wrp">
    <div class="table-tools-errors-wrp" id="b-d-booking-history-table-tools-errors-wrp">
      <div class="table-tools-errors-txt-size">
        <p class="table-tools-errors-txt" id="b-d-booking-history-table-tools-errors-txt"></p>
      </div>
    </div>
    <div class="table-tools-loader-wrp" id="b-d-booking-history-table-tools-loader-wrp">
      <img src="../../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
    </div>
    <?php
      if ($loadedListData["remain"] > 0) {
        $loadMoreStyle = "display: flex;";
      } else {
        $loadMoreStyle = "";
      }
    ?>
    <div class="table-tools-load-more-wrp" id="b-d-booking-history-table-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
      <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="b-d-booking-history-table-tools-load-more-btn" onclick="bookingUpdatesHistoryLoadMore();"><?php echo $wrd_loadMore; ?></button>
    </div>
    <?php
      if ($loadedListData["all-bookings"] > 0) {
        $noContentStyle = "";
      } else {
        $noContentStyle = "display: flex;";
      }
    ?>
    <div class="table-tools-no-content-wrp" id="b-d-booking-history-table-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
      <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
    </div>
  </div>
</div>
