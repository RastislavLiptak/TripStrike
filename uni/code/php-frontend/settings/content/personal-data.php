<div class="settings-errors-wrp">
  <p class="settings-error-txt" id="settings-error-txt-personal-data"></p>
</div>
<h1 class="settings-content-title"><?php echo $wrd_personalData; ?></h1>
<form class="settings-form-wrp">
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_email; ?>:</p>
      <input type="email" name="email" class="settings-input" id="settings-input-email" value="<?php echo $setemail; ?>">
    </div>
    <div class="settings-data-sync-checkbox-wrp">
      <label class="checkbox-label-inpt">
        <?php
          if ($setsyncemailsts) {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-email" checked>
        <?php
          } else {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-email">
        <?php
          }
        ?>
        <span class="checkmark-inpt"></span>
      </label>
      <p class="settings-data-sync-checkbox-txt"><?php echo $wrd_autoSyncEmail; ?></p>
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_phoneNumber; ?>:</p>
      <input type="tel" name="phone number" class="settings-input" id="settings-input-phone" value="<?php echo $setphonenum; ?>">
    </div>
    <div class="settings-data-sync-checkbox-wrp">
      <label class="checkbox-label-inpt">
        <?php
          if ($setsyncnumsts) {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-phone" checked>
        <?php
          } else {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-phone">
        <?php
          }
        ?>
        <span class="checkmark-inpt"></span>
      </label>
      <p class="settings-data-sync-checkbox-txt"><?php echo $wrd_autoSyncTel; ?></p>
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title">IBAN:</p>
      <input type="text" name="iban" class="settings-input" id="settings-input-iban" value="<?php if ($iban != "-") { echo $iban; } else { echo ""; }; ?>">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_bankAccountNumber; ?>:</p>
      <input type="text" name="bank account number" class="settings-input" id="settings-input-bank-account-number" value="<?php if ($bankaccount != "-") { echo $bankaccount; } else { echo ""; }; ?>">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title">BIC/SWIFT:</p>
      <input type="text" name="BIC/SWIFT" class="settings-input" id="settings-input-bicswift" value="<?php if ($bicswift != "-") { echo $bicswift; } else { echo ""; }; ?>">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_birth; ?>:</p>
      <div class="settings-input-birth-wrp">
        <input type="number" name="birth day" value="<?php echo $setbirthd; ?>" min="1" max="31" placeholder="DD" class="settings-input-birth" id="settings-input-birth-day">
        <input type="number" name="birth month" value="<?php echo $setbirthmN; ?>" min="1" max="12" placeholder="MM" class="settings-input-birth" id="settings-input-birth-month">
        <input type="number" name="birth year" value="<?php echo $setbirthy; ?>" placeholder="YYYY" class="settings-input-birth settings-input-birth-long" id="settings-input-birth-year">
      </div>
    </div>
  </div>
</form>
<div class="settings-btns-wrap">
  <button class="btn btn-mid btn-prim" id="settings-save-btn-personal-data" onclick="savePersonalDataSettings(false)"><?php echo $wrd_save; ?></button>
</div>
