<div class="settings-errors-wrp">
  <p class="settings-error-txt" id="settings-error-txt-additional-information"></p>
</div>
<h1 class="settings-content-title"><?php echo $wrd_additionalInformation; ?></h1>
<form class="settings-form-wrp">
  <div class="settings-form-row">
    <div class="settings-textarea-wrp">
      <div class="settings-textarea-header">
        <p class="settings-textarea-title"><?php echo $wrd_describeYourself; ?>:</p>
      </div>
      <textarea class="settings-textarea" id="settings-textarea-desc" placeholder="<?php echo $wrd_description; ?>"><?php echo htmlspecialchars($setdescription); ?></textarea>
    </div>
  </div>
  <div class="settings-form-row">
    <div class="settings-textarea-wrp">
      <div class="settings-textarea-header">
        <p class="settings-textarea-title"><?php echo $wrd_theLanguagesYouSpeak; ?></p>
        <p class="settings-textarea-desc"><?php echo $wrd_separateLanguages; ?></p>
      </div>
      <div class="settings-textarea" id="settings-languages-area" onclick="document.getElementById('settings-lang-add-input').focus();">
        <div id="settings-lang-list">
          <?php
            include "../uni/code/php-backend/multibyte_character_encodings.php";
            for ($uLA=0; $uLA < sizeof($langarray); $uLA++) {
          ?>
              <div class="settings-lang-wrp" id="settings-lang-<?php echo $langarray[$uLA]; ?>">
                <button class="settings-remove" value="<?php echo $langarray[$uLA]; ?>" onclick="removeLang('settings', this)"></button>
                <div class="settings-lang-block">
                  <p class="settings-lang-block-txt"><?php echo mb_ucfirst($langarray[$uLA], "utf8"); ?></p>
                </div>
              </div>
          <?php
            }
          ?>
        </div>
        <div id="settings-lang-add-wrp">
          <div id="settings-lang-add-input-wrp">
            <input type="text" id="settings-lang-add-input" placeholder="<?php echo $wrd_add." ".$wrd_language; ?>" onkeypress="langSearchType('settings', this, event)" onkeyup="langSearchType('settings', this, event)" onkeydown="langSearchType('settings', this, event)" onfocusout="langSearchSplit('settings', this.value)">
          </div>
        </div>
        <div id="settings-lang-add-span-wrp">
          <span id="settings-lang-add-span"></span>
        </div>
      </div>
    </div>
  </div>
</form>
<div class="settings-btns-wrap">
  <button class="btn btn-mid btn-prim" id="settings-save-btn-additional-information" onclick="saveAdditionalInformationSettings()"><?php echo $wrd_save; ?></button>
</div>
