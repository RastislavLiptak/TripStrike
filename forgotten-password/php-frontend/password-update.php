<div class="set-up-step-by-step-content" id="set-up-step-by-step-content-password-update">
  <div class="set-up-step-by-step-content-title-wrp">
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_enterNewPassword; ?></h1>
    </div>
    <div class="set-up-step-by-step-content-title-txt">
      <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_passwordConditions; ?></h2>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-form-layout">
      <p id="forgotten-password-password-update-user-id"></p>
      <p id="forgotten-password-password-update-code"></p>
      <input type="password" value="" id="forgotten-password-new-pass" class="set-up-step-by-step-content-input set-up-step-by-step-content-input-big" placeholder="<?php echo $wrd_password; ?>">
      <input type="password" value="" id="forgotten-password-new-pass-verify" class="set-up-step-by-step-content-input set-up-step-by-step-content-input-big forgotten-password-input-margin-top" placeholder="<?php echo $wrd_passwordVerification; ?>">
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-password-update">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-password-update"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-btn-password-update-continue" onclick="forgottenPasswordSave()"><?php echo $wrd_save; ?></button>
  </div>
</div>
