<div class="set-up-step-by-step-content set-up-step-by-step-content-selected" id="set-up-step-by-step-content-type-sign-in-email">
  <div class="set-up-step-by-step-content-title-wrp">
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_recoverForgottenPassword; ?></h1>
    </div>
    <div class="set-up-step-by-step-content-title-txt">
      <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_enterYourSignInEmailOrSignInPhoneNumberInTheFieldIfYouDoNotRememberItOrOnlyKnowYourContactEmailOrContactPhoneNumberContactSupport; ?></h2>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-form-layout">
      <input type="email" value="" class="set-up-step-by-step-content-input set-up-step-by-step-content-input-big" id="set-up-step-by-step-content-input-type-sign-in-email" placeholder="<?php echo $wrd_email." ".$wrd_or." ".$wrd_phoneNumber; ?>">
      <a href="../support/" target="_blank" class="forgotten-password-under-input-txt"><?php echo $wrd_support; ?></a>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-type-sign-in-email">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-type-sign-in-email"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-btn-type-sign-in-email-continue" onclick="forgottenPasswordTypeSignInEmailContinue();"><?php echo $wrd_continue; ?></button>
  </div>
</div>
