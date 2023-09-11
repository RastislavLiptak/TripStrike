<?php
  $smallPlcList = getPlacesOderedByBookings(24, "sml", $listOfUsedPlaces);
  foreach ($smallPlcList as $smallPlcByBookingExOf) {
    array_push($listOfUsedPlaces, $smallPlcByBookingExOf["beid"]);
  }
?>
<div class="h-sml-list-wrp">
  <div class="h-sml-grid">
    <?php
      for ($sP=0; $sP < sizeof($smallPlcList); $sP++) {
    ?>
      <div class="h-link-blck">
        <a class="h-link-a" href="../place/?id=<?php echo $smallPlcList[$sP]['id']; ?>">
          <div class="h-link-layout">
            <div class="h-link-img-wrp">
              <div class="h-link-img" style="background-image: url(../<?php echo $smallPlcList[$sP]['img']; ?>)"></div>
            </div>
            <div class="h-link-details">
              <div class="h-link-title-wrp">
                <p class="h-link-title"><?php echo $smallPlcList[$sP]['name']; ?></p>
              </div>
              <div class="h-link-price-wrp">
                <p class="h-link-details-txt">
                  <?php
                    if ($smallPlcList[$sP]['price_mode'] == "guests") {
                      $perWhat = $wrd_perPersonPerNight;
                    } else {
                      $perWhat = $wrd_perNight;
                    }
                    if ($smallPlcList[$sP]['price_work'] == $smallPlcList[$sP]['price_week']) {
                      echo $smallPlcList[$sP]['price_work']." ".$perWhat;
                    } else {
                      echo $smallPlcList[$sP]['price_work']." / ".$smallPlcList[$sP]['price_week']." ".$perWhat;
                    }
                  ?>
                </p>
              </div>
            </div>
          </div>
        </a>
      </div>
    <?php
      }
    ?>
  </div>
</div>
