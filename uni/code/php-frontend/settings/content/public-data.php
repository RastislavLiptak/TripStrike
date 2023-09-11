<div class="settings-errors-wrp">
  <p class="settings-error-txt" id="settings-error-txt-public-data"></p>
</div>
<h1 class="settings-content-title"><?php echo $wrd_publicData; ?></h1>
<form class="settings-form-wrp">
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_firstName; ?>:</p>
      <input type="text" name="firstname" class="settings-input" id="settings-input-firstname" value="<?php echo htmlspecialchars($setfirstname); ?>">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_lastName; ?>:</p>
      <input type="text" name="lastname" class="settings-input" id="settings-input-lastname" value="<?php echo htmlspecialchars($setlastname); ?>">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_contactEmail; ?>:</p>
      <input type="email" name="cantact email" class="settings-input" id="settings-input-contact-email" value="<?php echo $setcontactemail; ?>">
    </div>
    <div class="settings-data-sync-checkbox-wrp">
      <label class="checkbox-label-inpt">
        <?php
          if ($setsyncemailsts) {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-contact-email" checked>
        <?php
          } else {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-contact-email">
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
      <p class="settings-input-title"><?php echo $wrd_contactPhoneNumber; ?>:</p>
      <input type="tel" name="cantact phone number" class="settings-input" id="settings-input-contact-phone" value="<?php echo $setcontactphonenum; ?>">
    </div>
    <div class="settings-data-sync-checkbox-wrp">
      <label class="checkbox-label-inpt">
        <?php
          if ($setsyncnumsts) {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-contact-phone" checked>
        <?php
          } else {
        ?>
          <input type="checkbox" id="settings-data-sync-checkbox-contact-phone">
        <?php
          }
        ?>
        <span class="checkmark-inpt"></span>
      </label>
      <p class="settings-data-sync-checkbox-txt"><?php echo $wrd_autoSyncTel; ?></p>
    </div>
  </div>
</form>
<div class="settings-btns-wrap">
  <button class="btn btn-mid btn-prim" id="settings-save-btn-public-data" onclick="savePublicDataSettings(false)"><?php echo $wrd_save; ?></button>
</div>
