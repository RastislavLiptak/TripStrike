<?php
  $bigPlcList = getPlacesOderedByBookings(3, "big", $listOfUsedPlaces);
  foreach ($bigPlcList as $bigPlcByBookingExOf) {
    array_push($listOfUsedPlaces, $bigPlcByBookingExOf["beid"]);
  }
?>
<div id="h-big-list-wrp">
  <?php
    for ($bP=0; $bP < sizeof($bigPlcList); $bP++) {
      if ($bP % 2 != 0) {
        $hReverseClass = "h-big-list-layout-reverse";
      } else {
        $hReverseClass = "";
      }
  ?>
      <div class="h-big-list-row">
        <a href="../place/?id=<?php echo $bigPlcList[$bP]['id']; ?>" class="h-big-list-link">
          <div class="h-big-list-layout <?php echo $hReverseClass; ?>">
            <div class="h-big-list-img-wrp">
              <div class="h-big-list-img" style="background-image: url(../<?php echo $bigPlcList[$bP]['img']; ?>)"></div>
            </div>
            <div class="h-big-list-details-wrp">
              <div class="h-big-list-details-title-wrp">
                <div class="h-big-list-details-txt-wrp">
                  <h2 class="h-big-list-details-title"><?php echo $bigPlcList[$bP]['name']; ?></h2>
                </div>
              </div>
              <div class="h-big-list-details-about-wrp">
                <?php
                  if ($bigPlcList[$bP]['guestNum'] > 4) {
                    $wrdGuest = $wrd_guests2;
                  } else if ($bigPlcList[$bP]['guestNum'] > 1) {
                    $wrdGuest = $wrd_guests;
                  } else {
                    $wrdGuest = $wrd_guest;
                  }
                  if ($bigPlcList[$bP]['bedNum'] > 4) {
                    $wrdBedroom = $wrd_bedrooms2;
                  } else if ($bigPlcList[$bP]['bedNum'] > 1) {
                    $wrdBedroom = $wrd_bedrooms;
                  } else {
                    $wrdBedroom = $wrd_bedroom;
                  }
                  if ($bigPlcList[$bP]['bathNum'] > 4) {
                    $wrdBathroom = $wrd_bathrooms2;
                  } else if ($bigPlcList[$bP]['bathNum'] > 1) {
                    $wrdBathroom = $wrd_bathrooms;
                  } else {
                    $wrdBathroom = $wrd_bathroom;
                  }
                ?>
                <div class="h-big-list-details-about-blck">
                  <div class="h-big-list-details-about-icn-wrp">
                    <div class="h-big-list-details-about-icn h-big-list-details-about-icn-money"></div>
                  </div>
                  <p class="h-big-list-details-about-txt">
                    <?php
                      if ($bigPlcList[$bP]['price_mode'] == "guests") {
                        $perWhat = $wrd_perPersonPerNight;
                      } else {
                        $perWhat = $wrd_perNight;
                      }
                      if ($bigPlcList[$bP]['price_work'] == $bigPlcList[$bP]['price_week']) {
                        echo $bigPlcList[$bP]['price_work']." ".$perWhat;
                      } else {
                        echo $bigPlcList[$bP]['price_work']." / ".$bigPlcList[$bP]['price_week']." ".$perWhat;
                      }
                    ?>
                  </p>
                </div>
                <div class="h-big-list-details-about-blck">
                  <div class="h-big-list-details-about-icn-wrp">
                    <div class="h-big-list-details-about-icn h-big-list-details-about-icn-guests"></div>
                  </div>
                  <p class="h-big-list-details-about-txt"><?php echo $bigPlcList[$bP]['guestNum']." ".$wrdGuest; ?></p>
                </div>
                <div class="h-big-list-details-about-blck">
                  <div class="h-big-list-details-about-icn-wrp">
                    <div class="h-big-list-details-about-icn h-big-list-details-about-icn-bedrooms"></div>
                  </div>
                  <p class="h-big-list-details-about-txt"><?php echo $bigPlcList[$bP]['bedNum']." ".$wrdBedroom; ?></p>
                </div>
                <div class="h-big-list-details-about-blck">
                  <div class="h-big-list-details-about-icn-wrp">
                    <div class="h-big-list-details-about-icn h-big-list-details-about-icn-bathrooms"></div>
                  </div>
                  <p class="h-big-list-details-about-txt"><?php echo $bigPlcList[$bP]['bathNum']." ".$wrd_bathroom; ?></p>
                </div>
                <?php
                  if ($bigPlcList[$bP]['distanceFromTheWater'] > 0) {
                    if ($bigPlcList[$bP]['distanceFromTheWater'] < 26) {
                ?>
                    <div class="h-big-list-details-about-blck">
                      <div class="h-big-list-details-about-icn-wrp">
                        <div class="h-big-list-details-about-icn h-big-list-details-about-icn-wave"></div>
                      </div>
                      <p class="h-big-list-details-about-txt"><?php echo $wrd_closeToTheWater; ?></p>
                    </div>
                <?php
                    } else {
                ?>
                    <div class="h-big-list-details-about-blck">
                      <div class="h-big-list-details-about-icn-wrp">
                        <div class="h-big-list-details-about-icn h-big-list-details-about-icn-wave"></div>
                      </div>
                      <p class="h-big-list-details-about-txt"><?php echo $bigPlcList[$bP]['distanceFromTheWater']." ".$wrd_metersToTheWater; ?></p>
                    </div>
                <?php
                    }
                  }
                ?>
              </div>
              <?php
                if ($bigPlcList[$bP]['desc'] != "") {
              ?>
                <div class="h-big-list-details-desc-wrp">
                  <div class="h-big-list-details-txt-wrp">
                    <p class="h-big-list-details-desc"><?php echo $bigPlcList[$bP]['desc']; ?></p>
                  </div>
                </div>
              <?php
                }
              ?>
              <div class="h-big-list-details-more-wrp">
                <button type="button" class="h-big-list-details-more-btn">
                  <p class="h-big-list-details-more-btn-txt h-more-learn-more-txt"><?php echo $wrd_learnMore; ?></p>
                  <div class="h-big-list-details-more-btn-icn h-more-learn-more-icn"></div>
                </button>
                <?php
                  if ($bigPlcList[$bP]['type'] == "cottage") {
                ?>
                  <button type="button" class="h-big-list-details-more-btn" onclick="homeNewBooking(event, '<?php echo $bigPlcList[$bP]['id']; ?>')">
                    <p class="h-big-list-details-more-btn-txt h-more-book-txt"><?php echo $wrd_book; ?></p>
                    <div class="h-big-list-details-more-btn-icn h-more-book-icn"></div>
                  </button>
                <?php
                  }
                  if ($bigPlcList[$bP]['usrbeid'] == $usrBeId) {
                ?>
                    <button type="button" class="h-big-list-details-more-btn" onclick="homeEditor(event, '<?php echo $bigPlcList[$bP]['id']; ?>')">
                      <p class="h-big-list-details-more-btn-txt h-more-editor-txt"><?php echo $wrd_editor; ?></p>
                      <div class="h-big-list-details-more-btn-icn h-more-editor-icn"></div>
                    </button>
                <?php
                  }
                ?>
              </div>
            </div>
          </div>
        </a>
      </div>
  <?php
    }
  ?>
</div>
