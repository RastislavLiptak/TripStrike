<div class="b-d-settings-wrp">
  <div class="b-d-settings-layout">
    <div class="b-d-settings-blck">
      <div class="b-d-settings-row">
        <div class="b-d-settings-txt-size">
          <div class="b-d-settings-txt-wrp">
            <p class="b-d-settings-txt"><?php echo $wrd_dateOfCallForFees; ?></p>
          </div>
        </div>
        <div class="b-d-settings-input-wrp">
          <input type="number" min="1" max="31" class="b-d-settings-input-field b-d-settings-input-field-number-small" id="b-d-settings-input-field-number-date-of-call-for-fees-day" value="<?php echo $bds_dateOfCallForFeesDay; ?>">
          <input type="time" class="b-d-settings-input-field b-d-settings-input-field-time" id="b-d-settings-input-field-number-date-of-call-for-fees-time" value="<?php echo $bds_dateOfCallForFeesTimeHours.":".$bds_dateOfCallForFeesTimeMinutes; ?>">
        </div>
      </div>
      <div class="b-d-settings-error-wrp" id="b-d-settings-error-wrp-date-of-call-for-fees">
        <p class="b-d-settings-error-txt" id="b-d-settings-error-txt-date-of-call-for-fees"></p>
      </div>
    </div>
  </div>
  <div class="b-d-settings-btn-wrp">
    <div class="b-d-settings-btn-size">
      <button class="btn btn-mid btn-prim b-d-finance-settings-btn" id="b-d-finance-settings-btn-date-of-call-for-fees" onclick="dateOfCallForFeesSave();"><?php echo $wrd_save; ?></button>
    </div>
  </div>
</div>
