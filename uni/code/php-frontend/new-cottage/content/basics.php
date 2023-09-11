<form class="n-c-layout n-c-layout-selected" id="n-c-layout-basics">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-basics"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_basics; ?></h2>
  <div class="n-c-basics-row">
    <div class="n-c-basics-input-layout">
      <div class="n-c-basics-row-title-wrp">
        <p class="n-c-content-row-title" id="n-c-basics-title-txt"><?php echo $wrd_title; ?>:</p>
      </div>
      <textarea
        class="n-c-input-text"
        id="n-c-input-text-title"
        oninput="nCBasicsPlcTitleResize(this)"
        onkeypress="nCBasicsPlcTitleResize(this)"
        onkeyup="nCBasicsPlcTitleResize(this)"
        onkeydown="nCBasicsPlcTitleResize(this)"
        onchenge="nCBasicsPlcTitleResize(this)"
      ></textarea>
      <div class="n-c-input-width-basics-resize-wrp" id="n-c-input-width-resize-wrp-title">
        <p class="n-c-input-width-basics-resize-txt" id="n-c-input-width-resize-txt-title"></p>
      </div>
    </div>
  </div>
  <div class="n-c-basics-row">
    <div class="n-c-basics-input-layout">
      <div class="n-c-basics-row-title-wrp">
        <p class="n-c-content-row-title" id="n-c-basics-description-txt"><?php echo $wrd_description; ?>:</p>
      </div>
      <textarea class="n-c-textarea" id="n-c-textarea-description"></textarea>
    </div>
  </div>
</form>
