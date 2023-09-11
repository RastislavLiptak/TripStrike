<?php
  include "../uni/code/php-head.php";
  include "../uni/code/php-backend/get-data-from-date.php";
  include "php-backend/get-list-of-my-bookings.php";
  $subtitle = $wrd_myBookings;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../uni/code/css/table.css">
    <link rel="stylesheet" type="text/css" href="css/bookings-list.css">
    <link rel="stylesheet" type="text/css" href="css/my-bookings.css">
    <script src="js/my-bookings.js" async></script>
    <?php
      include "../uni/code/php-frontend/head.php";
    ?>
    <title><?php echo $wrd_myBookings." - ".$title; ?></title>
  </head>
  <body onload="<?php echo $onload; ?>">
    <?php
      include "../uni/code/php-frontend/fix-elmnts.php";
    ?>
    <div class="bookings-list-wrp">
      <div class="bookings-list-layout">
        <div class="bookings-list-title-wrp">
          <h1 class="bookings-list-title-txt"><?php echo $wrd_myBookings; ?></h1>
        </div>
        <div class="table-layout">
          <div class="table-filter-wrp">
            <div class="table-filter-search-bar-wrp">
              <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-search" id="bookings-list-search-bar-input-btn-search" onclick="myBookingsSearchBtn();"></button>
              <input type="text" class="table-filter-search-bar-input" id="bookings-list-search-bar-input-my-bookings" placeholder="<?php echo $wrd_searchForBookingByPlaceNameOrDate; ?>" value="" onkeyup="if(!(event.keyCode>36&&event.keyCode<41) && event.keyCode!=16){myBookingsSearchType(this);}">
              <button type="button" class="table-filter-search-bar-input-btn table-filter-search-bar-input-btn-cancel" id="bookings-list-search-bar-input-btn-cancel" onclick="myBookingsSearchCancel();"></button>
            </div>
          </div>
          <?php
            if ($sign == "yes") {
              $listOfMyBookings = getListOfMyBookings("list", $usrBeId, 0, "");
              $loadedListData = getListOfMyBookings("load-amount", $usrBeId, 0, "");
            } else {
              $listOfMyBookings = [];
              $loadedListData = [
                "all-bookings" => 0,
                "loaded" => 0,
                "remain" => 0
              ];
            }
          ?>
          <div class="table-wrp">
            <table class="table" id="bookings-list-table-my-bookings">
              <tr>
                <th><?php echo $wrd_status; ?></th>
                <th><?php echo $wrd_place; ?></th>
                <th><?php echo $wrd_dates; ?></th>
                <th><?php echo $wrd_guestNum; ?></th>
              </tr>
              <?php
                if (sizeof($listOfMyBookings) > 0) {
                  for ($lOMB=0; $lOMB < sizeof($listOfMyBookings); $lOMB++) {
                    if ($listOfMyBookings[$lOMB]['status'] == "canceled") {
                      $bookingSts = "<i>".$wrd_canceled."</i>";
                    } else if ($listOfMyBookings[$lOMB]['status'] == "waiting") {
                      $bookingSts = $wrd_waitingForConfirmation;
                    } else if ($listOfMyBookings[$lOMB]['status'] == "booked") {
                      $bookingSts = $wrd_booked;
                    }
              ?>
                    <tr class="my-bookings-list-row">
                      <td><?php echo $bookingSts; ?></td>
              <?php
                    if ($listOfMyBookings[$lOMB]['plcSts'] == "active") {
              ?>
                      <td><a href="../place/?id=<?php echo $listOfMyBookings[$lOMB]['plcID']; ?>" target="_blank"><?php echo $listOfMyBookings[$lOMB]['plcName']; ?></a></td>
              <?php
                    } else {
                      if ($listOfMyBookings[$lOMB]['plcName'] != "-") {
              ?>
                      <td><i><?php echo $listOfMyBookings[$lOMB]['plcName']; ?></i></td>
              <?php
                      } else {
              ?>
                      <td><i><?php echo $wrd_placeDeleted; ?></i></td>
              <?php
                      }
                    }
              ?>
                      <td><?php echo $listOfMyBookings[$lOMB]['fromD'].".".$listOfMyBookings[$lOMB]['fromM'].".".$listOfMyBookings[$lOMB]['fromY']." - ".$listOfMyBookings[$lOMB]['toD'].".".$listOfMyBookings[$lOMB]['toM'].".".$listOfMyBookings[$lOMB]['toY']; ?></td>
                      <td><?php echo $listOfMyBookings[$lOMB]['numOfGuests']; ?></td>
                      <td><a href="about-my-booking.php?id=<?php echo $listOfMyBookings[$lOMB]['bookingID']; ?>" class="table-row-link-blue my-bookings-show-more"><?php echo $wrd_showMore; ?></a></td>
                    </tr>
              <?php
                  }
                }
              ?>
            </table>
          </div>
          <div class="table-tools-wrp">
            <div class="table-tools-errors-wrp" id="bookings-list-tools-errors-wrp-my-bookings">
              <div class="table-tools-errors-txt-size">
                <p class="table-tools-errors-txt" id="bookings-list-tools-errors-txt-my-bookings"></p>
              </div>
            </div>
            <div class="table-tools-loader-wrp" id="bookings-list-tools-loader-wrp-my-bookings">
              <img src="../uni/gifs/loader-tail.svg" alt="loading gif" class="table-tools-loader-img">
            </div>
            <?php
              if ($loadedListData["remain"] > 0) {
                $loadMoreStyle = "display: flex;";
              } else {
                $loadMoreStyle = "";
              }
            ?>
            <div class="table-tools-load-more-wrp" id="bookings-list-tools-load-more-wrp-my-bookings" style="<?php echo $loadMoreStyle; ?>">
              <button class="btn btn-mid btn-prim table-tools-load-more-btn" id="bookings-list-tools-load-more-btn-my-bookings" onclick="myBookingsLoadMore();"><?php echo $wrd_loadMore; ?></button>
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
            <div class="table-tools-no-content-wrp" id="bookings-list-tools-no-bookings-wrp-my-bookings" style="<?php echo $noBookingsStyle; ?>">
              <p class="table-tools-no-content-txt"><?php echo $wrd_noBookingFound; ?></p>
            </div>
            <?php
              if ($sign == "yes") {
                $featureNotAvailableStyle = "";
              } else {
                $featureNotAvailableStyle = "display: flex;";
              }
            ?>
            <div class="table-tools-feature-not-available-wrp" style="<?php echo $featureNotAvailableStyle; ?>">
              <div class="table-tools-feature-not-available-btn-wrp">
                <button class="btn btn-mid btn-prim" onclick="signInModal('show')"><?php echo $wrd_signIn; ?></button>
              </div>
              <p class="table-tools-feature-not-available-txt"><?php echo $wrd_accountRequired; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
