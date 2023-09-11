<div class="editor-content-section-wrp" id="editor-content-conditions-wrp">
  <div id="editor-content-conditions-layout">
    <div id="editor-content-row-input-wrp">
      <textarea placeholder="<?php echo $wrd_type; ?>" class="editor-content-input-style" id="editor-content-textarea-conditions" oninput="editorInputVerticalResize('editor-content-textarea-conditions');" disabled></textarea>
    </div>
    <div id="editor-content-conditions-lang-slider-wrp">
      <div id="editor-content-conditions-lang-size">
        <button type="button" class="editor-content-conditions-lang-slider-btn" id="editor-content-conditions-lang-slider-btn-left" onclick="editorConditionsLangListControlBtnsScroll('left')">
          <div class="editor-content-conditions-lang-slider-btn-icn"></div>
        </button>
        <button type="button" class="editor-content-conditions-lang-slider-btn" id="editor-content-conditions-lang-slider-btn-right" onclick="editorConditionsLangListControlBtnsScroll('right')">
          <div class="editor-content-conditions-lang-slider-btn-icn"></div>
        </button>
        <div id="editor-content-conditions-lang-slider-loader"></div>
        <div id="editor-content-conditions-lang-slider" onscroll="editorConditionsLangListControlBtnsManager()">
          <div id="editor-content-conditions-lang-slider-content">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
