<div class="modal-cover" id="modal-cover-settings">
  <div class="modal-block" id="modal-cover-settings-blck">
    <div id="settings-content-wrp" class="settings-mode-nav">
      <button class="cancel-btn" id="settings-back-btn" onclick="settingsContentBack()"></button>
      <button class="cancel-btn" onclick="toggSet('hide', '')"></button>
      <div id="settings-content-nav-layout">
        <div id="settings-content-nav-scroll">
          <div id="settings-content-nav-list">
            <button class="set-nav-btn" id="set-btn-public-data" onclick="settNav('public-data')"><?php echo $wrd_publicData; ?></button>
            <button class="set-nav-btn" id="set-btn-personal-data" onclick="settNav('personal-data')"><?php echo $wrd_personalData; ?></button>
            <button class="set-nav-btn" id="set-btn-additional-information" onclick="settNav('additional-information')"><?php echo $wrd_additionalInformation; ?></button>
            <button class="set-nav-btn" id="set-btn-pass" onclick="settNav('pass')"><?php echo $wrd_password; ?></button>
            <button class="set-nav-btn" id="set-btn-prof-img" onclick="settNav('prof-img')"><?php echo $wrd_profileImage; ?></button>
            <button class="set-nav-btn" id="set-btn-lang" onclick="settNav('lang')"><?php echo $wrd_language; ?></button>
            <button class="set-nav-btn" id="set-btn-features" onclick="settNav('features')"><?php echo $wrd_features; ?></button>
            <button class="set-nav-btn" id="set-btn-automated-processes" onclick="settNav('automated-processes')"><?php echo $wrd_automatedProcesses; ?></button>
            <button class="set-nav-btn" id="set-btn-cancel-account" onclick="settNav('cancel-account')"><?php echo $wrd_cancelAccout; ?></button>
          </div>
        </div>
      </div>
      <div id="settings-content-layout">
        <div id="settings-content-scroll">
          <div id="settings-content-blocks-wrp">
            <div id="settings-public-data" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/public-data.php";
                }
              ?>
            </div>
            <div id="settings-personal-data" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/personal-data.php";
                }
              ?>
            </div>
            <div id="settings-additional-information" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/additional-information.php";
                }
              ?>
            </div>
            <div id="settings-pass" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/password-change.php";
                }
              ?>
            </div>
            <div id="settings-prof-img" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/prof-img.php";
                }
              ?>
            </div>
            <div id="settings-lang" class="settings-cont">
              <?php
                include "content/language-settings.php";
              ?>
            </div>
            <div id="settings-features" class="settings-cont">
              <?php
                include "content/features.php";
              ?>
            </div>
            <div id="settings-automated-processes" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/automated-processes.php";
                }
              ?>
            </div>
            <div id="settings-cancel-account" class="settings-cont">
              <?php
                if ($sign != "yes") {
                  include "content/def-sign.php";
                } else {
                  include "content/cancel-account.php";
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
