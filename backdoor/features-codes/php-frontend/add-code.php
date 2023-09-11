<div class="modal-cover" id="modal-cover-add-feature-code">
  <div class="modal-block" id="modal-cover-add-feature-code-blck">
    <button class="cancel-btn" onclick="addFeatureCodeModal('hide');"></button>
    <div id="b-d-add-feature-code-wrp">
      <div id="b-d-add-feature-code-scroll-wrp">
        <div id="b-d-add-feature-code-content-layout">
          <div class="b-d-add-feature-code-content-title-wrp">
            <p class="b-d-add-feature-code-content-title"><?php echo $wrd_addFeaturesCodes; ?></p>
          </div>
          <div class="b-d-add-feature-code-content-row" id="b-d-add-feature-code-content-row-code">
            <div class="b-d-add-feature-code-content-txt-size">
              <p class="b-d-add-feature-code-content-txt"><?php echo $wrd_codeHash; ?>:</p>
            </div>
            <div class="b-d-add-feature-code-content-input-wrp" id="b-d-add-feature-code-content-input-wrp-code">
              <div id="b-d-add-feature-code-content-input-code-size">
                <input type="text" id="b-d-add-feature-code-content-input-code" class="b-d-add-feature-code-content-input-text" value="" maxlength="11" oninput="addFeatureCodeInputLength(this);addFeatureCodeInputCheckAvailability();">
                <div id="b-d-add-feature-code-content-input-code-details-position">
                  <div id="b-d-add-feature-code-content-input-code-details">
                    <div id="b-d-add-feature-code-content-input-code-details-txt-wrp">
                      <p class="b-d-add-feature-code-content-input-code-details-txt" id="b-d-add-feature-code-content-input-code-details-txt-characters-num">0 / 11</p>
                      <p class="b-d-add-feature-code-content-input-code-details-txt" id="b-d-add-feature-code-content-input-code-details-txt-error"><?php echo $wrd_codeExists; ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="b-d-add-feature-code-content-input-slash-wrp">
                <p class="b-d-add-feature-code-content-input-slash">/</p>
              </div>
              <div id="b-d-add-feature-code-content-input-code-checkbox-wrp">
                <label id="b-d-add-feature-code-content-input-code-checkbox-label" onclick="addFeatureCodeCheckboxOnclick();"><?php echo $wrd_random; ?>
                  <input type="checkbox" id="b-d-add-feature-code-content-input-code-checkbox-input">
                  <span id="b-d-add-feature-code-content-input-code-checkbox-checkmark"></span>
                </label>
              </div>
            </div>
          </div>
          <div class="b-d-add-feature-code-content-row">
            <div class="b-d-add-feature-code-content-txt-size">
              <p class="b-d-add-feature-code-content-txt"><?php echo $wrd_user; ?>:</p>
            </div>
            <div class="b-d-add-feature-code-content-input-wrp" id="b-d-add-feature-code-content-input-wrp-user">
              <div id="b-d-add-feature-code-content-input-code-user-select-position">
                <input type="text" id="b-d-add-feature-code-content-input-user" class="b-d-add-feature-code-content-input-text" value="" oninput="addFeatureCodeUserSearch(this);" onfocus="addFeatureCodeUserOnfocus()" onfocusout="addFeatureCodeUserOnfocusout()">
                <div id="b-d-add-feature-code-content-input-code-user-select"></div>
              </div>
            </div>
          </div>
          <div class="b-d-add-feature-code-content-row">
            <div class="b-d-add-feature-code-content-txt-size">
              <p class="b-d-add-feature-code-content-txt"><?php echo $wrd_numberOfGeneratedCodes; ?>:</p>
            </div>
            <div class="b-d-add-feature-code-content-input-wrp" id="b-d-add-feature-code-content-input-wrp-num-of-codes">
              <input type="number" id="b-d-add-feature-code-content-input-num-of-codes" class="b-d-add-feature-code-content-input-text" value="1">
            </div>
          </div>
          <div id="b-d-add-feature-code-content-features-wrp">
            <div class="b-d-add-feature-code-content-txt-size">
              <p class="b-d-add-feature-code-content-txt"><?php echo $wrd_features; ?>:</p>
            </div>
            <div id="b-d-add-feature-code-content-features-content">
              <div id="b-d-add-feature-code-content-features-list"></div>
              <div id="b-d-add-feature-code-content-features-add-btn-wrp">
                <button type="button" id="b-d-add-feature-code-content-features-add-btn" onclick="addFeatureCodeAddFeature();"></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="b-d-add-feature-code-btns-wrp">
        <button class="btn btn-big btn-prim" id="b-d-add-feature-code-btn-save" onclick="addFeatureCodeSave();"><?php echo $wrd_save; ?></button>
      </div>
    </div>
  </div>
</div>
