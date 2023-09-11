<div class="b-d-settings-wrp">
  <div class="b-d-settings-layout">
    <div class="b-d-settings-title-wrp">
      <h2 class="b-d-settings-title"><?php echo $wrd_bankDetailsForThePaymentOfFees; ?></h2>
    </div>
    <div class="b-d-settings-blck">
      <div class="b-d-settings-row">
        <div class="b-d-settings-txt-size">
          <div class="b-d-settings-txt-wrp">
            <p class="b-d-settings-txt"><?php echo "IBAN"; ?></p>
          </div>
        </div>
        <div class="b-d-settings-input-wrp">
          <input type="text" class="b-d-settings-input-field b-d-settings-input-field-text" id="b-d-settings-input-field-number-bank-details-for-the-payment-of-fees-iban" value="<?php echo $bds_detailsForThePaymentOfFeesIban; ?>">
        </div>
      </div>
      <div class="b-d-settings-row">
        <div class="b-d-settings-txt-size">
          <div class="b-d-settings-txt-wrp">
            <p class="b-d-settings-txt"><?php echo $wrd_bankAccountNumber; ?></p>
          </div>
        </div>
        <div class="b-d-settings-input-wrp">
          <input type="text" class="b-d-settings-input-field b-d-settings-input-field-text" id="b-d-settings-input-field-number-bank-details-for-the-payment-of-fees-bank-account" value="<?php echo $bds_detailsForThePaymentOfFeesBankAccount; ?>">
        </div>
      </div>
      <div class="b-d-settings-row">
        <div class="b-d-settings-txt-size">
          <div class="b-d-settings-txt-wrp">
            <p class="b-d-settings-txt"><?php echo "BIC/SWIFT"; ?></p>
          </div>
        </div>
        <div class="b-d-settings-input-wrp">
          <input type="text" class="b-d-settings-input-field b-d-settings-input-field-text" id="b-d-settings-input-field-number-bank-details-for-the-payment-of-fees-bic-swift" value="<?php echo $bds_detailsForThePaymentOfFeesBicSwift; ?>">
        </div>
      </div>
      <div class="b-d-settings-error-wrp" id="b-d-settings-error-wrp-bank-details-for-the-payment-of-fees">
        <p class="b-d-settings-error-txt" id="b-d-settings-error-txt-bank-details-for-the-payment-of-fees"></p>
      </div>
    </div>
  </div>
  <div class="b-d-settings-btn-wrp">
    <div class="b-d-settings-btn-size">
      <button class="btn btn-mid btn-prim b-d-finance-settings-btn" id="b-d-finance-settings-btn-bank-details-for-the-payment-of-fees" onclick="detailsForThePaymentOfFeesSave();"><?php echo $wrd_save; ?></button>
    </div>
  </div>
</div>
