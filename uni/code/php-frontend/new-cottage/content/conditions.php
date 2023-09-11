<form class="n-c-layout" id="n-c-layout-conditions">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-conditions"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_conditions; ?></h2>
  <div id="n-c-conditions-of-stay-content-wrp">
    <div id="n-c-conditions-of-stay-lang-slider-wrp">
      <button type="button" class="n-c-conditions-of-stay-lang-slider-btn" id="n-c-conditions-of-stay-lang-slider-btn-left" onclick="ncConditionsOfStayLangListControlBtnsScroll('left')">
        <div class="n-c-conditions-of-stay-lang-slider-btn-icn"></div>
      </button>
      <button type="button" class="n-c-conditions-of-stay-lang-slider-btn" id="n-c-conditions-of-stay-lang-slider-btn-right" onclick="ncConditionsOfStayLangListControlBtnsScroll('right')">
        <div class="n-c-conditions-of-stay-lang-slider-btn-icn"></div>
      </button>
      <div id="n-c-conditions-of-stay-lang-slider" onscroll="ncConditionsOfStayLangListControlBtnsManager()">
        <div id="n-c-conditions-of-stay-lang-slider-content">
        </div>
      </div>
    </div>
    <div id="n-c-conditions-of-stay-textarea-wrp">
      <textarea class="n-c-textarea" id="n-c-conditions-of-stay-textarea" placeholder="<?php echo $wrd_textHere; ?>" disabled></textarea>
      <div id="n-c-conditions-of-stay-textarea-loader-wrp">
        <img alt="" src="../uni/gifs/loader-tail.svg" id="n-c-conditions-of-stay-textarea-loader">
      </div>
    </div>
  </div>
  <div id="n-c-conditions-of-stay-loader-wrp">
    <div id="n-c-conditions-of-stay-loader-blck">
      <img alt="loader icon" src="../uni/gifs/loader-tail.svg" id="n-c-conditions-of-stay-loader-icon">
      <p id="n-c-conditions-of-stay-loader-txt"><?php echo $wrd_loading."..."; ?></p>
    </div>
  </div>
</form>
