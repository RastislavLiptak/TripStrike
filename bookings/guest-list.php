<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/get-data-from-date.php";
  include "php-backend/get-list-of-guest-bookings.php";
  $subtitle = $wrd_guestList;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="css/bookings-list.css">
    <link rel="stylesheet" type="text/css" href="css/guest-list.css">
    <script src="js/guest-list.js" async></script>
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <title><?php echo $wrd_guestList." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div class="bookings-list-wrp">
      <div class="bookings-list-layout">
        <div class="bookings-list-title-wrp">
          <h1 class="bookings-list-title-txt"><?php echo $wrd_guestList; ?></h1>
        </div>
        <div class="table-layout">
          <div class="table-filter-wrp">
            <div class="table-filter-search-bar-wrp">
              <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" id="guest-list-search-bar-input-btn-search" onclick="guestBookingsSearchBtn();"></button>
              <input type="text" class="table-filter-search-bar-input" id="bookings-list-search-bar-input-guest-bookings" placeholder="<?php echo $wrd_searchForBookingByPlaceGuestDate; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){guestBookingsSearchType(this);}">
              <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" id="guest-list-search-bar-input-btn-cancel" onclick="guestBookingsSearchCancel();"></button>
            </div>
          </div>
          <?php
            if ($sign == "yes" && $bnft_add_cottage == "good") {
              $listOfGuestBookings = getlistOfGuestBookings("list", $usrBeId, 0, "");
              $loadedListData = getlistOfGuestBookings("load-amount", $usrBeId, 0, "");
            } else {
              $listOfGuestBookings = [];
              $loadedListData = [
                "all-bookings" => 0,
                "loaded" => 0,
                "remain" => 0
              ];
            }
          ?>
          <div class="table-wrp">
            <table class="table" id="bookings-list-table-guest-list">
              <tr>
                <th><?php echo $wrd_status; ?></th>
                <th><?php echo $wrd_capitalGuest; ?></th>
                <th><?php echo $wrd_place; ?></th>
                <th><?php echo $wrd_dates; ?></th>
              </tr>
              <?php
                if (sizeof($listOfGuestBookings) > 0) {
                  for ($lOGB=0; $lOGB < sizeof($listOfGuestBookings); $lOGB++) {
                    if ($listOfGuestBookings[$lOGB]['status'] == "canceled") {
                      $bookingSts = "<i>".$wrd_canceled."</i>";
                    } else if ($listOfGuestBookings[$lOGB]['status'] == "waiting") {
                      $bookingSts = $wrd_waitingForConfirmation;
                    } else if ($listOfGuestBookings[$lOGB]['status'] == "booked") {
                      $bookingSts = $wrd_booked;
                    }
              ?>
                    <tr class="guest-bookings-list-row">
                      <td><?php echo $bookingSts; ?></td>
              <?php
                    if ($listOfGuestBookings[$lOGB]['guestID'] != "-") {
              ?>
                      <td><a href="../user/?id=<?php echo $listOfGuestBookings[$lOGB]['guestID']; ?>&section=about" target="_blank"><?php echo $listOfGuestBookings[$lOGB]['guestName']; ?></a></td>
              <?php
                    } else {
              ?>
                      <td><?php echo $listOfGuestBookings[$lOGB]['guestName']; ?></td>
              <?php
                    }
                    if ($listOfGuestBookings[$lOGB]['plcSts'] == "active") {
              ?>
                      <td><a href="../place/?id=<?php echo $listOfGuestBookings[$lOGB]['plcID']; ?>" target="_blank"><?php echo $listOfGuestBookings[$lOGB]['plcName']; ?></a></td>
              <?php
                    } else {
                      if ($listOfGuestBookings[$lOGB]['plcName'] != "-") {
              ?>
                      <td><i><?php echo $listOfGuestBookings[$lOGB]['plcName']; ?></i></td>
              <?php
                      } else {
              ?>
                      <td><i><?php echo $wrd_placeDeleted; ?></i></td>
              <?php
                      }
                    }
              ?>
                      <td><?php echo $listOfGuestBookings[$lOGB]['fromD'].".".$listOfGuestBookings[$lOGB]['fromM'].".".$listOfGuestBookings[$lOGB]['fromY']." - ".$listOfGuestBookings[$lOGB]['toD'].".".$listOfGuestBookings[$lOGB]['toM'].".".$listOfGuestBookings[$lOGB]['toY']; ?></td>
                      <td><a href="about-guest-booking.php?id=<?php echo $listOfGuestBookings[$lOGB]['bookingID']; ?>" class="table-row-link-blue guest-bookings-show-more"><?php echo $wrd_showMore; ?></a></td>
                    </tr>
              <?php
                  }
                }
              ?>
            </table>
          </div>
          <div class="table-tools-wrp">
            <div class="table-tools-errors-wrp" id="bookings-list-tools-errors-wrp-guest-bookings">
              <div class="table-tools-errors-txt-size">
                <p class="table-tools-errors-txt" id="bookings-list-tools-errors-txt-guest-bookings"></p>
              </div>
            </div>
            <div class="table-tools-loader-wrp" id="bookings-list-tools-loader-wrp-guest-bookings">
              <img src="../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
            </div>
            <?php
              if ($loadedListData["remain"] > 0) {
                $loadMoreStyle = "display: flex;";
              } else {
                $loadMoreStyle = "";
              }
            ?>
            <div class="table-tools-load-more-wrp" id="bookings-list-tools-load-more-wrp-guest-bookings" style="<?php echo $loadMoreStyle; ?>">
              <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="bookings-list-tools-load-more-btn-guest-bookings" onclick="guestBookingsLoadMore();"><?php echo $wrd_loadMore; ?></button>
            </div>
            <?php
              if ($loadedListData["all-bookings"] > 0) {
                $noBookingsStyle = "";
              } else {
                if ($sign == "yes") {
                  $noBookingsStyle = "display: flex;";
                } else {
                  $noBookingsStyle = "";
                }
              }
            ?>
            <div class="table-tools-no-content-wrp" id="bookings-list-tools-no-bookings-wrp-guest-bookings" style="<?php echo $noBookingsStyle; ?>">
              <p class="table-tools-no-content-txt"><?php echo $wrd_noBookingFound; ?></p>
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
                if ($sign != "yes") {
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
  </body>
</html>
