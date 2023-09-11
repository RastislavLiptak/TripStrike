<div class="modal-cover" id="modal-cover-sign-in">
  <div class="modal-block" id="modal-cover-sign-in-blck">
    <button class="cancel-btn" onclick="signInModal('hide')"></button>
    <div class="sign-in-size">
      <form class="sign-in-form" onsubmit="signIn(event)">
        <div class="sign-in-form-body" id="sign-in-form-body-in">
          <p class="sign-in-title"><?php echo $wrd_signIn; ?></p>
          <div class="sign-in-error-wrp">
            <p class="sign-in-error-txt" id="sign-in-error-txt-in"></p>
          </div>
          <div class="sign-in-form-content">
            <div class="sign-in-form-row">
              <input type="text" name="email" placeholder="<?php echo $wrd_email." ".$wrd_or." ".$wrd_phoneNumber; ?>" class="sign-in-input" id="sign-in-input-account-id">
            </div>
            <div class="sign-in-form-row">
              <input type="password" name="password" placeholder="<?php echo $wrd_password; ?>" class="sign-in-input" id="sign-in-input-password">
            </div>
            <div class="sign-in-form-row">
              <button type="button" id="sign-in-forgotten-password-btn" onclick="window.open('../forgotten-password/', '_blank');"><?php echo $wrd_forgot; ?>?</button>
            </div>
          </div>
        </div>
        <div class="sign-in-footer">
          <button type="button" class="btn btn-mid sign-in-footer-switch-btn" onclick="signSwitch('sign-up')"><?php echo $wrd_signUp; ?></button>
          <button type="submit" class="btn btn-mid btn-prim sign-in-footer-submit-btn" id="sign-in-btn"><?php echo $wrd_signIn; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
