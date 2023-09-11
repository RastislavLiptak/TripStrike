<div class="modal-cover modal-cover-up-2" id="modal-cover-nc-bank-account">
  <div class="modal-block" id="modal-cover-nc-bank-account-blck">
    <button class="cancel-btn" onclick="ncBankAccountModal('hide')"></button>
    <div id="nc-bank-account-wrp">
      <div id="nc-bank-account-error-wrp">
        <p id="nc-bank-account-error-txt"></p>
      </div>
      <div class="nc-bank-account-txt-wrp">
        <h3 id="nc-bank-account-txt-title"><?php echo $wrd_bankAccount; ?></h3>
      </div>
      <div class="nc-bank-account-txt-wrp">
        <p class="nc-bank-account-txt-desc"><?php echo $wrd_pleaseFillInYourBankAccountDetailsWhereYourGuestsWillBeAbleToSendPaymentsForReservations; ?></p>
      </div>
      <form id="nc-bank-account-form">
        <div id="nc-bank-account-form-content-wrp">
          <div class="nc-bank-account-form-row">
            <div class="nc-bank-account-form-input-wrp">
              <p class="nc-bank-account-form-input-title">IBAN:</p>
              <input type="text" name="iban" class="nc-bank-account-form-input" id="nc-bank-account-form-input-iban" value="">
            </div>
          </div>
          <div class="nc-bank-account-form-row">
            <div class="nc-bank-account-form-input-wrp">
              <p class="nc-bank-account-form-input-title"><?php echo $wrd_bankAccountNumber; ?>:</p>
              <input type="text" name="bank-account-number" class="nc-bank-account-form-input" id="nc-bank-account-form-input-bank-account-number" value="">
            </div>
          </div>
          <div class="nc-bank-account-form-row">
            <div class="nc-bank-account-form-input-wrp">
              <p class="nc-bank-account-form-input-title">BIC/SWIFT:</p>
              <input type="text" name="BIC-SWIFT" class="nc-bank-account-form-input" id="nc-bank-account-form-input-bicswift" value="">
            </div>
          </div>
        </div>
        <div id="nc-bank-account-form-submit-wrp">
          <button type="button" class="btn btn-big btn-prim" id="nc-bank-account-form-submit-btn" onclick="ncBankAccountSave()"><?php echo $wrd_save; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
