<div class="settings-errors-wrp">
  <p class="settings-error-txt" id="settings-error-txt-password-change"></p>
</div>
<h1 class="settings-content-title"><?php echo $wrd_password; ?></h1>
<form class="settings-form-wrp">
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_oldPassword; ?>:</p>
      <input type="password" name="old password" class="settings-input" id="settings-input-old-password" value="">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_newPassword; ?>:</p>
      <input type="password" name="new password" class="settings-input" id="settings-input-new-password" value="">
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-input-wrp">
      <p class="settings-input-title"><?php echo $wrd_passwordVerification; ?>:</p>
      <input type="password" name="new password verification" class="settings-input" id="settings-input-new-password-verification" value="">
    </div>
  </div>
</form>
<div class="settings-btns-wrap">
  <button class="btn btn-mid btn-prim" id="settings-save-btn-password-change" onclick="savePasswordChangeSettings()"><?php echo $wrd_save; ?></button>
</div>
