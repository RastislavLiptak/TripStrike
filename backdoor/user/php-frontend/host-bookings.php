<div class="table-layout">
  <div class="table-filter-wrp" id="table-filter-wrp-host-bookings">
    <div class="table-filter-search-bar-wrp">
      <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" onclick="hostBookingsSearchBtn();"></button>
      <input type="text" class="table-filter-search-bar-input" id="table-filter-search-bar-input-host-bookings" placeholder="<?php echo $wrd_searchForBookingByPlaceNameOrDate; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){hostBookingsSearchType(this);}">
      <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" onclick="hostBookingsSearchCancel();"></button>
    </div>
    <div class="table-filter-tools-wrp">
      <div class="table-filter-tools-search-bar-wrp">
        <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" onclick="hostBookingsSearchPayRBtn();"></button>
        <input type="text" class="table-filter-search-bar-input" id="table-filter-search-bar-input-host-bookings-payment-reference" placeholder="<?php echo $wrd_paymentReference; ?>" value="<?php echo $urlPaymentReference; ?>" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){hostBookingsSearchPayRType(this);}">
        <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" onclick="hostBookingsSearchPayRCancel();"></button>
      </div>
      <div class="table-filter-select-wrp">
        <select class="table-filter-select" id="b-d-host-bookings-table-filter-months" onchange="hostBookingsPeriodFilterOnchange();">
          <?php
            $hostBookings0Month = "";
            $hostBookings1Month = "";
            $hostBookings2Month = "";
            $hostBookings3Month = "";
            $hostBookings4Month = "";
            $hostBookings5Month = "";
            $hostBookings6Month = "";
            $hostBookings7Month = "";
            $hostBookings8Month = "";
            $hostBookings9Month = "";
            $hostBookings10Month = "";
            $hostBookings11Month = "";
            $hostBookings12Month = "";
            if ($periodM == "1") {
              $hostBookings1Month = "selected";
            } else if ($periodM == "2") {
              $hostBookings2Month = "selected";
            } else if ($periodM == "3") {
              $hostBookings3Month = "selected";
            } else if ($periodM == "4") {
              $hostBookings4Month = "selected";
            } else if ($periodM == "5") {
              $hostBookings5Month = "selected";
            } else if ($periodM == "6") {
              $hostBookings6Month = "selected";
            } else if ($periodM == "7") {
              $hostBookings7Month = "selected";
            } else if ($periodM == "8") {
              $hostBookings8Month = "selected";
            } else if ($periodM == "9") {
              $hostBookings9Month = "selected";
            } else if ($periodM == "10") {
              $hostBookings10Month = "selected";
            } else if ($periodM == "11") {
              $hostBookings11Month = "selected";
            } else if ($periodM == "12") {
              $hostBookings12Month = "selected";
            } else {
              $hostBookings0Month = "selected";
            }
          ?>
          <option value="" <?php echo $hostBookings0Month; ?>><?php echo $wrd_month; ?></option>
          <option value="1" <?php echo $hostBookings1Month; ?>><?php echo $wrd_january; ?></option>
          <option value="2" <?php echo $hostBookings2Month; ?>><?php echo $wrd_february; ?></option>
          <option value="3" <?php echo $hostBookings3Month; ?>><?php echo $wrd_march; ?></option>
          <option value="4" <?php echo $hostBookings4Month; ?>><?php echo $wrd_april; ?></option>
          <option value="5" <?php echo $hostBookings5Month; ?>><?php echo $wrd_may; ?></option>
          <option value="6" <?php echo $hostBookings6Month; ?>><?php echo $wrd_june; ?></option>
          <option value="7" <?php echo $hostBookings7Month; ?>><?php echo $wrd_july; ?></option>
          <option value="8" <?php echo $hostBookings8Month; ?>><?php echo $wrd_august; ?></option>
          <option value="9" <?php echo $hostBookings9Month; ?>><?php echo $wrd_september; ?></option>
          <option value="10" <?php echo $hostBookings10Month; ?>><?php echo $wrd_october; ?></option>
          <option value="11" <?php echo $hostBookings11Month; ?>><?php echo $wrd_november; ?></option>
          <option value="12" <?php echo $hostBookings12Month; ?>><?php echo $wrd_december; ?></option>
        </select>
      </div>
      <div class="table-filter-select-wrp">
        <select class="table-filter-select" id="b-d-host-bookings-table-filter-years" onchange="hostBookingsPeriodFilterOnchange();">
          <?php
            $hostBookings0Year = "";
            if ($periodY < date('Y')-75 || $periodY > date('Y')+75) {
              $hostBookings0Year = "selected";
            }
            for ($y=date('Y')+75; $y >= date('Y')-75; $y--) {
              if (date('Y') == $y) {
          ?>
                <option value="" <?php echo $hostBookings0Year; ?>><?php echo $wrd_year; ?></option>
          <?php
              }
              if ($y == $periodY) {
          ?>
                <option value="<?php echo $y; ?>" selected><?php echo $y; ?></option>
          <?php
              } else {
          ?>
                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
          <?php
              }
            }
          ?>
        </select>
      </div>
    </div>
  </div>
  <?php
    $listOfHostBookings = getListOfHostBookings("list", "", $userBeId, $periodY, $periodM, $urlPaymentReference, "");
    $loadedListData = getListOfHostBookings("load-amount", "", $userBeId, $periodY, $periodM, $urlPaymentReference, "");
  ?>
  <div class="table-wrp" id="b-d-host-bookings-table-wrp">
    <table class="table" id="b-d-host-bookings-table">
      <tr>
        <th><?php echo $wrd_status; ?></th>
        <th><?php echo $wrd_place; ?></th>
        <th><?php echo $wrd_dates; ?></th>
        <th><?php echo $wrd_price; ?></th>
        <th><?php echo $wrd_fee; ?></th>
        <th><?php echo $wrd_feeInPercent; ?></th>
      </tr>
      <?php
        if (sizeof($listOfHostBookings) > 0) {
          for ($lOHB=0; $lOHB < sizeof($listOfHostBookings); $lOHB++) {
            if ($listOfHostBookings[$lOHB]['status'] == "") {
              $rowStatus = "<i>".$wrd_unknown." (empty)</i>";
            } else if ($listOfHostBookings[$lOHB]['status'] == "-") {
              $rowStatus = "<i>".$wrd_unknown." (-)</i>";
            } else if ($listOfHostBookings[$lOHB]['status'] == "rejected") {
              $rowStatus = "<i>".$wrd_rejected."</i>";
            } else if ($listOfHostBookings[$lOHB]['status'] == "canceled") {
              $rowStatus = "<i>".$wrd_canceled."</i>";
            } else if ($listOfHostBookings[$lOHB]['status'] == "waiting") {
              $rowStatus = $wrd_waitingForConfirmation;
            } else if ($listOfHostBookings[$lOHB]['status'] == "booked") {
              $rowStatus = $wrd_booked;
            } else if ($listOfHostBookings[$lOHB]['status'] == "paid") {
              $rowStatus = "<span class='table-data-green'>".$wrd_paid."</span>";
            } else if ($listOfHostBookings[$lOHB]['status'] == "unpaid") {
              $rowStatus = "<span class='table-data-red'>".$wrd_unpaid."</span>";
            }
            if ($listOfHostBookings[$lOHB]['plcSts'] == "active") {
              $plcName = $listOfHostBookings[$lOHB]['plcName'];
            } else {
              if ($listOfHostBookings[$lOHB]['plcName'] != "-") {
                $plcName = "<i>".$listOfHostBookings[$lOHB]['plcName']."</i>";
              } else {
                $plcName = "<i>".$wrd_placeDeleted."</i>";
              }
            }
      ?>
            <tr class="b-d-host-bookings-table-row">
              <td><?php echo $rowStatus; ?></td>
              <td><?php echo $plcName; ?></td>
              <td><?php echo $listOfHostBookings[$lOHB]['fromD'].".".$listOfHostBookings[$lOHB]['fromM'].".".$listOfHostBookings[$lOHB]['fromY']." - ".$listOfHostBookings[$lOHB]['toD'].".".$listOfHostBookings[$lOHB]['toM'].".".$listOfHostBookings[$lOHB]['toY']; ?></td>
              <td><?php echo addCurrency($listOfHostBookings[$lOHB]['currency'], $listOfHostBookings[$lOHB]['totalPrice']); ?></td>
              <td><?php echo addCurrency($listOfHostBookings[$lOHB]['currency'], $listOfHostBookings[$lOHB]['fee']); ?></td>
              <td><?php echo ($listOfHostBookings[$lOHB]['percentageFee'] + 0)."%"; ?></td>
              <td><a href="../booking/?section=<?php echo $contentSection; ?>&nav=about&id=<?php echo $listOfHostBookings[$lOHB]['bookingID']; ?>" class="table-row-link-blue"><?php echo $wrd_showMore; ?></a></td>
            </tr>
      <?php
          }
        }
      ?>
    </table>
  </div>
  <div class="table-about-wrp">
    <?php
      if (sizeof($listOfHostBookings) > 0) {
        $lastID = $listOfHostBookings[sizeof($listOfHostBookings) -1]['bookingID'];
      } else {
        $lastID = "";
      }
    ?>
    <p id="b-d-host-bookings-table-about-last-id"><?php echo $lastID; ?></p>
  </div>
  <div class="table-tools-wrp">
    <div class="table-tools-errors-wrp" id="b-d-host-bookings-table-tools-errors-wrp">
      <div class="table-tools-errors-txt-size">
        <p class="table-tools-errors-txt" id="b-d-host-bookings-table-tools-errors-txt"></p>
      </div>
    </div>
    <div class="table-tools-loader-wrp" id="b-d-host-bookings-table-tools-loader-wrp">
      <img src="../../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
    </div>
    <?php
      if ($loadedListData["remain"] > 0) {
        $loadMoreStyle = "display: flex;";
      } else {
        $loadMoreStyle = "";
      }
    ?>
    <div class="table-tools-load-more-wrp" id="b-d-host-bookings-table-tools-load-more-wrp" style="<?php echo $loadMoreStyle; ?>">
      <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="b-d-host-bookings-table-tools-load-more-btn" onclick="hostBookingsLoadMore();"><?php echo $wrd_loadMore; ?></button>
    </div>
    <?php
      if ($loadedListData["all-bookings"] > 0) {
        $noContentStyle = "";
      } else {
        $noContentStyle = "display: flex;";
      }
    ?>
    <div class="table-tools-no-content-wrp" id="b-d-host-bookings-table-tools-no-content-wrp" style="<?php echo $noContentStyle; ?>">
      <p class="table-tools-no-content-txt"><?php echo $wrd_noContent; ?></p>
    </div>
  </div>
</div>
