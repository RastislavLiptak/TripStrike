<div class="modal-cover modal-cover-up" id="modal-cover-password-1">
  <div class="modal-block pass-modal-block" id="modal-cover-password-1-blck">
    <button class="cancel-btn" onclick="modCover('hide', 'modal-cover-password-1');toggSet('show', 'cancel-account');cancelAccEmpty()"></button>
    <div class="settings-pass-wrp">
      <div class="settings-pass-inpt-wrp">
        <input type="password" name="password" value="" placeholder="<?php echo $wrd_password; ?>" class="sett-inpt-pass" id="cancel-account-inpt" onkeyup="cancelAccInput()">
        <div class="settings-pass-inpt-err-wrp">
          <p class="set-pass-inpt-err-style" id="cancel-account-pass-inpt-err"></p>
        </div>
        <button class="settings-pass-help" id="cancel-pass-help" onclick="window.open('../forgotten-password/', '_blank');">
          <p id="cancel-account-password-help"><?php echo $wrd_forgot; ?>?</p>
        </button>
      </div>
    </div>
    <button class="btn btn-fth" id="set-cancel-my-account-btn" onclick="cancelMyAccount();"><?php echo $wrd_cancelAccout; ?></button>
  </div>
</div>


<div class="modal-cover modal-cover-up" id="modal-cover-sign-password">
  <div class="modal-block" id="modal-cover-sign-password-blck">
    <button class="cancel-btn" id="cancel-cover-sign-btn" onclick="modCover('hide', 'modal-cover-sign-password');"></button>
    <div id="settings-pass-w-txt-content">
      <div class="settings-pass-w-txt-wrp">
        <div class="settings-pass-w-txt-head-wrp">
          <div class="settings-pass-w-txt-img" id="settings-pass-w-txt-img-sign-error"></div>
          <h3 class="settings-pass-w-txt-title"><?php echo $wrd_changeSigninData; ?></h3>
        </div>
        <div class="settings-pass-w-txt-p-wrp">
          <p class="settings-pass-w-txt-p" id="settings-pass-w-txt-email"></p>
          <p class="settings-pass-w-txt-p" id="settings-pass-w-txt-tel"></p>
        </div>
        <div class="settings-pass-w-txt-inpt-wrp">
          <input type="password" name="password" value="" placeholder="<?php echo $wrd_password; ?>" class="sett-inpt-pass sett-inpt-pass-w-txt" id="change-sign-data-inpt" onfocus="settingsPasswordErrorHide()" onkeyup="settingsPasswordErrorHide()">
          <div class="settings-pass-w-txt-inpt-err-wrp">
            <p class="set-pass-inpt-err-style" id="sign-data-change-pass-alert-err"></p>
          </div>
          <button class="settings-pass-help-block" id="settings-pass-w-txt-help-btn" onclick="window.open('../forgotten-password/', '_blank');">
            <p id="settings-pass-w-txt-help"><?php echo $wrd_forgot; ?>?</p>
          </button>
        </div>
      </div>
      <button class="btn btn-mid btn-prim sett-save-btn sett-pass-txt-submit-btn" id="sett-main-save-pass-btn" value="" onclick="settingsPasswordSubmitManager(this.value)"><?php echo $wrd_save; ?></button>
    </div>
  </div>
</div>


<div class="modal-cover modal-cover-up" id="modal-cover-password-2">
  <div class="modal-loader-wrp" id="modal-cover-cancel-account-loader-wrp">
    <img class="modal-loader-img" id="modal-cover-cancel-account-loader" src="../uni/gifs/loader-tail.svg">
    <p class="modal-loader-txt" id="modal-cover-cancel-account-txt"><?php echo $wrd_accountCanceling; ?></p>
  </div>
</div>
