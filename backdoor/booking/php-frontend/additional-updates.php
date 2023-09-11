<div class="table-layout">
  <?php
    $listOfAdditionalUpdates = getAdditionalUpdates("list", $bookingBeId, "");
    $loadedListData = getAdditionalUpdates("load-amount", $bookingBeId, "");
  ?>
  <div class="table-wrp">
    <table class="table" id="b-d-booking-additional-updates-table">
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
        if (sizeof($listOfAdditionalUpdates) > 0) {
          for ($lOAU=0; $lOAU < sizeof($listOfAdditionalUpdates); $lOAU++) {
            if ($listOfAdditionalUpdates[$lOAU]['status'] == "") {
              $rowStatus = $wrd_unknown." (empty)";
            } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "-") {
              $rowStatus = $wrd_unknown." (-)";
            } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "rejected") {
              $rowStatus = $wrd_rejected;
            } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "canceled") {
              $rowStatus = $wrd_canceled;
            } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "waiting") {
              $rowStatus = $wrd_waitingForConfirmation;
            } else if ($listOfAdditionalUpdates[$lOAU]['status'] == "booked") {
              $rowStatus = $wrd_booked;
            }
            if ($listOfAdditionalUpdates[$lOAU]['paymentStatus'] == "1") {
              $rowPaymentStatus = $wrd_paid;
            } else if ($listOfAdditionalUpdates[$lOAU]['paymentStatus'] == "0") {
              $rowPaymentStatus = $wrd_unpaid;
            }
            if ($listOfAdditionalUpdates[$lOAU]['source'] == "booking-form") {
              $rowSource = $wrd_bookingForm;
            } else if ($listOfAdditionalUpdates[$lOAU]['source'] == "editor") {
              $rowSource = $wrd_bookingEditor;
            } else {
              $rowSource = $listOfAdditionalUpdates[$lOAU]['source'];
            }
            if ($listOfAdditionalUpdates[$lOAU]['firstday'] == "whole") {
              $fromDate = $listOfAdditionalUpdates[$lOAU]['fromD'].".".$listOfAdditionalUpdates[$lOAU]['fromM'].".".$listOfAdditionalUpdates[$lOAU]['fromY']." (".$wrd_theWholeDay.")";
            } else if ($listOfAdditionalUpdates[$lOAU]['firstday'] == "half") {
              $fromDate = $listOfAdditionalUpdates[$lOAU]['fromD'].".".$listOfAdditionalUpdates[$lOAU]['fromM'].".".$listOfAdditionalUpdates[$lOAU]['fromY']." (".$wrd_from." 14:00)";
            } else {
              $fromDate = $listOfAdditionalUpdates[$lOAU]['fromD'].".".$listOfAdditionalUpdates[$lOAU]['fromM'].".".$listOfAdditionalUpdates[$lOAU]['fromY']." (".$listOfAdditionalUpdates[$lOAU]['firstday'].")";
            }
            if ($listOfAdditionalUpdates[$lOAU]['lastday'] == "whole") {
              $toDate = $listOfAdditionalUpdates[$lOAU]['toD'].".".$listOfAdditionalUpdates[$lOAU]['toM'].".".$listOfAdditionalUpdates[$lOAU]['toY']." (".$wrd_theWholeDay.")";
            } else if ($listOfAdditionalUpdates[$lOAU]['lastday'] == "half") {
              $toDate = $listOfAdditionalUpdates[$lOAU]['toD'].".".$listOfAdditionalUpdates[$lOAU]['toM'].".".$listOfAdditionalUpdates[$lOAU]['toY']." (".$wrd_to." 11:00)";
            } else {
              $toDate = $listOfAdditionalUpdates[$lOAU]['toD'].".".$listOfAdditionalUpdates[$lOAU]['toM'].".".$listOfAdditionalUpdates[$lOAU]['toY']." (".$listOfAdditionalUpdates[$lOAU]['lastday'].")";
            }
            if ($listOfAdditionalUpdates[$lOAU]['priceMode'] == "nights") {
              $rowPriceMode = $wrd_pricePerNight;
            } else {
              $rowPriceMode = $wrd_pricePerNightForOneGuest;
            }
      ?>
            <tr class="b-d-booking-additional-updates-table-row">
              <td><?php echo $rowStatus; ?></td>
              <td><?php echo $rowPaymentStatus; ?></td>
              <td><?php echo $listOfAdditionalUpdates[$lOAU]['hostName']; ?></td>
              <td><?php echo $listOfAdditionalUpdates[$lOAU]['guestName']; ?></td>
              <td><?php echo $listOfAdditionalUpdates[$lOAU]['plcName']; ?></td>
              <td><?php echo $rowSource; ?></td>
              <td><?php echo $listOfAdditionalUpdates[$lOAU]['numOfGuests']; ?></td>
              <td><?php echo $fromDate; ?></td>
              <td><?php echo $toDate; ?></td>
              <td><?php echo $rowPriceMode; ?></td>
              <td><?php echo addCurrency($listOfAdditionalUpdates[$lOAU]['currency'], $listOfAdditionalUpdates[$lOAU]['workPrice']); ?></td>
              <td><?php echo addCurrency($listOfAdditionalUpdates[$lOAU]['currency'], $listOfAdditionalUpdates[$lOAU]['weekPrice']); ?></td>
              <td><?php echo addCurrency($listOfAdditionalUpdates[$lOAU]['currency'], $listOfAdditionalUpdates[$lOAU]['totalPrice']); ?></td>
              <td><?php echo addCurrency($listOfAdditionalUpdates[$lOAU]['currency'], $listOfAdditionalUpdates[$lOAU]['fee']); ?></td>
              <td><?php echo ($listOfAdditionalUpdates[$lOAU]['percentageFee'] + 0)."%"; ?></td>
              <td><?php echo $listOfAdditionalUpdates[$lOAU]['validFrom']; ?></td>
            </tr>
      <?php
          }
        }
      ?>
    </table>
  </div>
  <div class="table-about-wrp">
    <?php
      if (sizeof($listOfAdditionalUpdates) > 0) {
        $lastID = $listOfAdditionalUpdates[sizeof($listOfAdditionalUpdates) -1]['updateID'];
      } else {
        $lastID = "";
      }
    ?>
    <p id="b-d-booking-additional-updates-table-about-last-id"><?php echo $lastID; ?></p>
  </div>
  <div class="table-tools-wrp">
    <div class="table-tools-errors-wrp" id="b-d-booking-additional-updates-table-tools-errors-wrp">
      <div class="table-tools-errors-txt-size">
        <p class="table-tools-errors-txt" id="b-d-booking-additional-updates-table-tools-errors-txt"></p>
      </div>
    </div>
    <div class="table-tools-loader-wrp" id="b-d-booking-additional-updates-table-tools-loader-wrp">
      <img src="../../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
    </div>
    <?php
      if ($loadedListData["remain"] > 0) {
        $loadMoreStyle = "display: flex;";
      } else {
        $loadMoreStyle = "";
      }
    ?>
    <div class="table-tools-load-more-wrp" id="b-d-booking-additional-updates-table-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
      <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="b-d-booking-additional-updates-table-tools-load-more-btn" onclick="additionalUpdatesLoadMore();"><?php echo $wrd_loadMore; ?></button>
    </div>
    <?php
      if ($loadedListData["all-bookings"] > 0) {
        $noContentStyle = "";
      } else {
        $noContentStyle = "display: flex;";
      }
    ?>
    <div class="table-tools-no-content-wrp" id="b-d-booking-additional-updates-table-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
      <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
    </div>
  </div>
</div>
