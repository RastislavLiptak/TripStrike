<form class="n-c-layout" id="n-c-layout-price">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-price"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_price; ?></h2>
  <div class="n-c-price-wrp">
    <p class="n-c-content-row-title"><?php echo $wrd_priceMode; ?>:</p>
    <select class="n-c-select n-c-select-price" id="n-c-select-price-mode">
      <option class="n-c-select-option n-c-select-option-price-mode" value="nights" selected><?php echo $wrd_pricePerNight; ?></option>
      <option class="n-c-select-option n-c-select-option-price-mode" value="guests"><?php echo $wrd_pricePerNightForOneGuest; ?></option>
    </select>
  </div>
  <div class="n-c-price-wrp">
    <div class="n-c-price-title-wrp">
      <p class="n-c-price-title-txt"><?php echo $wrd_pricePerNightDuringTheWorkingWeek; ?></p>
      <p class="n-c-price-nights-txt"><?php echo $wrd_sunday_short."-".$wrd_monday_short.", ".$wrd_monday_short."-".$wrd_tuesday_short.", ".$wrd_tuesday_short."-".$wrd_wednesday_short.", ".$wrd_wednesday_short."-".$wrd_thursday_short.", ".$wrd_thursday_short."-".$wrd_friday_short; ?></p>
    </div>
    <div class="n-c-price-input-wrp">
      <div class="n-c-price-input-layout">
        <input type="number" class="n-c-input-number n-c-price-input" id="n-c-price-input-work" value="1" min="1">
        <p class="n-c-price-currency">€</p>
      </div>
    </div>
  </div>
  <div class="n-c-price-wrp">
    <div class="n-c-price-title-wrp">
      <p class="n-c-price-title-txt"><?php echo $wrd_pricePerNightOverTheWeekend; ?></p>
      <p class="n-c-price-nights-txt"><?php echo $wrd_friday_short."-".$wrd_saturday_short.", ".$wrd_saturday_short."-".$wrd_sunday_short; ?></p>
    </div>
    <div class="n-c-price-input-wrp">
      <div class="n-c-price-input-layout">
        <input type="number" class="n-c-input-number n-c-price-input" id="n-c-price-input-week" value="1" min="1">
        <p class="n-c-price-currency">€</p>
      </div>
    </div>
  </div>
  <?php
    if (getAccountData($usrBeId, "feature-no-fees") == "good") {
      $bds_percAmountOfTheFees = 0;
    }
    if ($bds_percAmountOfTheFees > 0) {
  ?>
      <div class="n-c-price-wrp">
        <div class="n-c-notice-txt-wrp">
          <p class="n-c-notice-txt"><?php echo $wrd_forEachStayCreatedOnTheSiteWhichYouConfirmYouWillBeChargedFeeOfOfTheTotalBookingPrice1." ".$bds_percAmountOfTheFees."% ".$wrd_forEachStayCreatedOnTheSiteWhichYouConfirmYouWillBeChargedFeeOfOfTheTotalBookingPrice2; ?></p>
        </div>
      </div>
  <?php
    }
  ?>
</form>
