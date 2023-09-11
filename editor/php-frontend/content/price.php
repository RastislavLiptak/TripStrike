<div class="editor-content-section-wrp" id="editor-content-price-wrp">
  <div id="editor-content-price-input-blcks-layout">
    <div class="editor-content-price-input-blck">
      <div class="editor-content-price-input-txt-wrp">
        <p class="editor-content-price-input-title"><?php echo $wrd_pricePerNightDuringTheWorkingWeek; ?></p>
      </div>
      <div class="editor-content-price-input-txt-wrp">
        <p class="editor-content-price-input-subtitle"><?php echo $wrd_sunday_short."-".$wrd_monday_short.", ".$wrd_monday_short."-".$wrd_tuesday_short.", ".$wrd_tuesday_short."-".$wrd_wednesday_short.", ".$wrd_wednesday_short."-".$wrd_thursday_short.", ".$wrd_thursday_short."-".$wrd_friday_short; ?></p>
      </div>
      <div class="editor-content-price-input-layout">
        <input type="number" value="<?php echo $plcPriceWork; ?>" class="editor-content-price-input-field editor-input-style editor-input-style-big" id="editor-input-price-work">
        <p class="editor-content-price-currency">€</p>
      </div>
    </div>
    <div class="editor-content-price-input-blck">
      <div class="editor-content-price-input-txt-wrp">
        <p class="editor-content-price-input-title"><?php echo $wrd_pricePerNightOverTheWeekend; ?></p>
      </div>
      <div class="editor-content-price-input-txt-wrp">
        <p class="editor-content-price-input-subtitle"><?php echo $wrd_friday_short."-".$wrd_saturday_short.", ".$wrd_saturday_short."-".$wrd_sunday_short; ?></p>
      </div>
      <div class="editor-content-price-input-layout">
        <input type="number" value="<?php echo $plcPriceWeek; ?>" class="editor-content-price-input-field editor-input-style editor-input-style-big" id="editor-input-price-week">
        <p class="editor-content-price-currency">€</p>
      </div>
    </div>
  </div>
  <div id="editor-content-price-mode-layout-wrp">
    <p class="editor-content-price-mode-txt"><?php echo $wrd_priceMode; ?>:</p>
    <div class="editor-content-price-mode-select-wrp">
      <select class="editor-input-style editor-select-style" id="editor-select-price-mode">
        <?php
          $prcModeNightsSelected = "";
          $prcModeGuestsSelected = "";
          if ($plcPriceMode == "guests") {
            $prcModeGuestsSelected = "selected";
          } else {
            $prcModeNightsSelected = "selected";
          }
        ?>
        <option value="nights" <?php echo $prcModeNightsSelected; ?>><?php echo $wrd_pricePerNight; ?></option>
        <option value="guests" <?php echo $prcModeGuestsSelected; ?>><?php echo $wrd_pricePerNightForOneGuest; ?></option>
      </select>
    </div>
  </div>
  <div id="editor-content-price-notify-wrp">
    <?php
      if (getAccountData($usrBeId, "feature-no-fees") == "good") {
        $bds_percAmountOfTheFees = 0;
      }
    ?>
    <p id="editor-content-price-notify-txt"><?php echo $wrd_forEachStayCreatedOnTheSiteWhichYouConfirmYouWillBeChargedFeeOfOfTheTotalBookingPrice1." ".$bds_percAmountOfTheFees."% ".$wrd_forEachStayCreatedOnTheSiteWhichYouConfirmYouWillBeChargedFeeOfOfTheTotalBookingPrice2; ?></p>
  </div>
</div>
