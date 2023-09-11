<div class="set-up-step-by-step-content" id="set-up-step-by-step-content-code-verification">
  <div class="set-up-step-by-step-content-title-wrp">
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_enterTheCodeToVerify; ?></h1>
    </div>
    <div class="set-up-step-by-step-content-title-txt">
      <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_verificationCodeHasBeenSentTo1." <span id='set-up-step-by-step-content-title-h2-code-verification-email'></span> ".$wrd_verificationCodeHasBeenSentTo2; ?></h2>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-form-layout">
      <p id="forgotten-password-code-verification-user-id"></p>
      <input type="text" value="" class="set-up-step-by-step-content-input set-up-step-by-step-content-input-big" id="set-up-step-by-step-content-input-forgotten-password-code" placeholder="<?php echo $wrd_verificationCode; ?>">
      <p class="forgotten-password-under-input-txt"><span id="forgotten-password-under-input-txt-countdown"></span> <?php echo $wrd_untilTheCodeExpires; ?></p>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-code-verification">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-code-verification"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-btn-code-verification-send-again" onclick="forgottenPasswordCodeVerificationSendAgain();"><?php echo $wrd_sendAgain; ?></button>
    <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-btn-code-verification-continue" onclick="forgottenPasswordCodeVerificationContinue();"><?php echo $wrd_continue; ?></button>
  </div>
</div>
