<div class="b-d-settings-wrp">
  <div class="b-d-settings-layout">
    <div class="b-d-settings-blck">
      <div class="b-d-settings-row">
        <div class="b-d-settings-txt-size">
          <div class="b-d-settings-txt-wrp">
            <p class="b-d-settings-txt"><?php echo $wrd_theAmountOfTheFees; ?></p>
          </div>
        </div>
        <div class="b-d-settings-input-wrp">
          <input type="number" class="b-d-settings-input-field b-d-settings-input-field-number" id="b-d-settings-input-field-number-amount-of-the-fees" value="<?php echo $bds_percAmountOfTheFees; ?>">
          <p class="b-d-settings-input-txt">%</p>
        </div>
      </div>
      <div class="b-d-settings-error-wrp" id="b-d-settings-error-wrp-amount-of-the-fees">
        <p class="b-d-settings-error-txt" id="b-d-settings-error-txt-amount-of-the-fees"></p>
      </div>
    </div>
  </div>
  <div class="b-d-settings-btn-wrp">
    <div class="b-d-settings-btn-size">
      <button class="btn btn-mid btn-prim b-d-finance-settings-btn" id="b-d-finance-settings-btn-amount-of-the-fees" onclick="amountOfTheFeesSave();"><?php echo $wrd_save; ?></button>
    </div>
  </div>
</div>
