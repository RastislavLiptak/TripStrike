<?php
  $bannerSliderSts = true;
  $sqlNumOfPlcs = $link->query("SELECT * FROM places WHERE status='active'");
  if ($sqlNumOfPlcs->num_rows > 7) {
    // $bannerPlcList = getPlacesOderedByBookings(3, "big", $listOfUsedPlaces); order by number of bookings at that place
    $bannerPlcList = getPlacesOderedByRand(3, "big", $listOfUsedPlaces);
  } else if ($sqlNumOfPlcs->num_rows > 10) {
    // $bannerPlcList = getPlacesOderedByBookings(5, "big", $listOfUsedPlaces); order by number of bookings at that place
    $bannerPlcList = getPlacesOderedByRand(5, "big", $listOfUsedPlaces);
  } else {
    $bannerSliderSts = false;
    // $bannerPlcList = getPlacesOderedByBookings(1, "big", $listOfUsedPlaces); order by number of bookings at that place
    $bannerPlcList = getPlacesOderedByRand(1, "big", $listOfUsedPlaces);
  }
  foreach ($bannerPlcList as $bannerPlcByBookingExOf) {
    array_push($listOfUsedPlaces, $bannerPlcByBookingExOf["beid"]);
  }
?>
<div id="h-banner-wrp">
  <div id="h-banner-block">
    <div id="h-banner-slider">
      <?php
        $bannSlideNum = 0;
        for ($pB=0; $pB < sizeof($bannerPlcList); $pB++) {
          if ($bannSlideNum == 0) {
            $bannerSlideSelectedClass = "h-banner-one-slide-selected";
          } else {
            $bannerSlideSelectedClass = "";
          }
      ?>
          <div class="h-banner-one-slide <?php echo $bannerSlideSelectedClass; ?>" id="h-banner-one-slide-<?php echo $bannSlideNum; ?>">
            <a href="../place/?id=<?php echo $bannerPlcList[$pB]['id']; ?>" class="h-banner-details-link">
              <div class="h-banner-one-slide-layout">
                <div class="h-banner-details-wrp">
                  <div class="h-banner-details-title-wrp">
                    <h1 class="h-banner-details-title"><?php echo $bannerPlcList[$pB]['name']; ?></h1>
                  </div>
                  <div class="h-banner-details-about-wrp">
                    <?php
                      if ($bannerPlcList[$pB]['guestNum'] > 4) {
                        $wrdGuest = $wrd_guests2;
                      } else if ($bannerPlcList[$pB]['guestNum'] > 1) {
                        $wrdGuest = $wrd_guests;
                      } else {
                        $wrdGuest = $wrd_guest;
                      }
                      if ($bannerPlcList[$pB]['bedNum'] > 4) {
                        $wrdBedroom = $wrd_bedrooms2;
                      } else if ($bannerPlcList[$pB]['bedNum'] > 1) {
                        $wrdBedroom = $wrd_bedrooms;
                      } else {
                        $wrdBedroom = $wrd_bedroom;
                      }
                      if ($bannerPlcList[$pB]['bathNum'] > 4) {
                        $wrdBathroom = $wrd_bathrooms2;
                      } else if ($bannerPlcList[$pB]['bathNum'] > 1) {
                        $wrdBathroom = $wrd_bathrooms;
                      } else {
                        $wrdBathroom = $wrd_bathroom;
                      }
                    ?>
                    <div class="h-banner-details-about-blck">
                      <div class="h-banner-details-about-icn-wrp">
                        <div class="h-banner-details-about-icn h-banner-details-about-icn-money"></div>
                      </div>
                      <p class="h-banner-details-about-txt">
                        <?php
                          if ($bannerPlcList[$pB]['price_mode'] == "guests") {
                            $perWhat = $wrd_perPersonPerNight;
                          } else {
                            $perWhat = $wrd_perNight;
                          }
                          if ($bannerPlcList[$pB]['price_work'] == $bannerPlcList[$pB]['price_week']) {
                            echo $bannerPlcList[$pB]['price_work']." ".$perWhat;
                          } else {
                            echo $bannerPlcList[$pB]['price_work']." / ".$bannerPlcList[$pB]['price_week']." ".$perWhat;
                          }
                        ?>
                      </p>
                    </div>
                    <div class="h-banner-details-about-blck">
                      <div class="h-banner-details-about-icn-wrp">
                        <div class="h-banner-details-about-icn h-banner-details-about-icn-guests"></div>
                      </div>
                      <p class="h-banner-details-about-txt"><?php echo $bannerPlcList[$pB]['guestNum']." ".$wrdGuest; ?></p>
                    </div>
                    <div class="h-banner-details-about-blck">
                      <div class="h-banner-details-about-icn-wrp">
                        <div class="h-banner-details-about-icn h-banner-details-about-icn-bedrooms"></div>
                      </div>
                      <p class="h-banner-details-about-txt"><?php echo $bannerPlcList[$pB]['bedNum']." ".$wrdBedroom; ?></p>
                    </div>
                    <div class="h-banner-details-about-blck">
                      <div class="h-banner-details-about-icn-wrp">
                        <div class="h-banner-details-about-icn h-banner-details-about-icn-bathrooms"></div>
                      </div>
                      <p class="h-banner-details-about-txt"><?php echo $bannerPlcList[$pB]['bathNum']." ".$wrdBathroom; ?></p>
                    </div>
                    <?php
                      if ($bannerPlcList[$pB]['distanceFromTheWater'] > 0) {
                        if ($bannerPlcList[$pB]['distanceFromTheWater'] < 26) {
                    ?>
                        <div class="h-banner-details-about-blck">
                          <div class="h-banner-details-about-icn-wrp">
                            <div class="h-banner-details-about-icn h-banner-details-about-icn-wave"></div>
                          </div>
                          <p class="h-banner-details-about-txt"><?php echo $wrd_closeToTheWater; ?></p>
                        </div>
                    <?php
                        } else {
                    ?>
                        <div class="h-banner-details-about-blck">
                          <div class="h-banner-details-about-icn-wrp">
                            <div class="h-banner-details-about-icn h-banner-details-about-icn-wave"></div>
                          </div>
                          <p class="h-banner-details-about-txt"><?php echo $bannerPlcList[$pB]['distanceFromTheWater']." ".$wrd_metersToTheWater; ?></p>
                        </div>
                    <?php
                        }
                      }
                    ?>
                  </div>
                  <div class="h-banner-details-see-more-blck">
                    <button type="button" class="h-banner-details-see-more-btn">
                      <p class="h-banner-details-see-more-txt h-more-learn-more-txt"><?php echo $wrd_learnMore; ?></p>
                      <div class="h-banner-details-see-more-icn h-more-learn-more-icn"></div>
                    </button>
                    <?php
                      if ($bannerPlcList[$pB]['type'] == "cottage") {
                    ?>
                      <button type="button" class="h-banner-details-see-more-btn" onclick="homeNewBooking(event, '<?php echo $bannerPlcList[$pB]['id']; ?>')">
                        <p class="h-banner-details-see-more-txt h-more-book-txt"><?php echo $wrd_book; ?></p>
                        <div class="h-banner-details-see-more-icn h-more-book-icn"></div>
                      </button>
                    <?php
                      }
                      if ($bannerPlcList[$pB]['usrbeid'] == $usrBeId) {
                    ?>
                        <button type="button" class="h-banner-details-see-more-btn" onclick="homeEditor(event, '<?php echo $bannerPlcList[$pB]['id']; ?>')">
                          <p class="h-banner-details-see-more-txt h-more-editor-txt"><?php echo $wrd_editor; ?></p>
                          <div class="h-banner-details-see-more-icn h-more-editor-icn"></div>
                        </button>
                    <?php
                      }
                    ?>
                  </div>
                </div>
                <?php
                  if ($bannerPlcList[$pB]['img'] != "uni/icons/home5.svg") {
                    $bannerBlckMaxWidth = "1176";
                    $bannerBlckMaxHeight = "500";
                    list($bannerImgWidth, $bannerImgHeight) = getimagesize("../".$bannerPlcList[$pB]['img']);
                    if ($bannerBlckMaxWidth / $bannerBlckMaxHeight > $bannerImgWidth / $bannerImgHeight) {
                      $mult = $bannerBlckMaxWidth / $bannerImgWidth;
                      $neededImgHeight = $bannerImgHeight * $mult;
                      $bckSizeH = $neededImgHeight * 100 / $bannerBlckMaxHeight;
                      $backgroundSizeStyle = "background-size: auto ".$bckSizeH."%;";
                    } else {
                      $backgroundSizeStyle = "background-size: auto 100%;";
                    }
                  } else {
                    $backgroundSizeStyle = "";
                  }
                ?>
                <div class="h-banner-img" style="<?php echo "background-image: url(../".$bannerPlcList[$pB]['img'].");".$backgroundSizeStyle; ?>">
                  <div class="h-banner-img-cover"></div>
                </div>
              </div>
            </a>
          </div>
      <?php
          ++$bannSlideNum;
        }
      ?>
    </div>
    <?php
      if ($bannerSliderSts) {
    ?>
      <div id="h-banner-controls-wrp">
        <div id="h-banner-controls-blck">
          <button type="button" aria-label="banner slide left button" class="h-banner-controls-btn" id="h-banner-controls-btn-left" onclick="homeBannerPrev(this)" value="0"></button>
          <div id="h-banner-controls-dots-wrp">
            <?php
              for ($bannerDot=0; $bannerDot < sizeof($bannerPlcList); $bannerDot++) {
                if ($bannerDot == 0) {
            ?>
                  <div class="h-banner-controls-dot h-banner-controls-dot-selected" id="h-banner-controls-dot-<?php echo $bannerDot; ?>" onclick="homeBannerPerformSlide(<?php echo $bannerDot; ?>, 'auto');"></div>
            <?php
                } else {
            ?>
                  <div class="h-banner-controls-dot" id="h-banner-controls-dot-<?php echo $bannerDot; ?>" onclick="homeBannerPerformSlide(<?php echo $bannerDot; ?>, 'auto');"></div>
            <?php
                }
              }
            ?>
          </div>
          <button type="button" aria-label="banner slide right button" class="h-banner-controls-btn" onclick="homeBannerNext(this)" value="0"></button>
        </div>
      </div>
    <?php
      }
    ?>
  </div>
</div>
