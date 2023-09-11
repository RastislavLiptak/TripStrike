<div id="editor-nav-wrp">
  <div class="editor-nav-slider-button-wrp" id="editor-nav-slider-button-wrp-left">
    <button type="button" class="editor-nav-slider-button" id="editor-nav-slider-button-left" onclick="editorNavSliderScroll('left');"></button>
  </div>
  <div class="editor-nav-slider-button-wrp" id="editor-nav-slider-button-wrp-right">
    <button type="button" class="editor-nav-slider-button" id="editor-nav-slider-button-right" onclick="editorNavSliderScroll('right');"></button>
  </div>
  <div id="editor-nav-scroll" onscroll="editorNavSliderBtnHandler();">
    <div id="editor-nav-content">
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn editor-nav-content-block-btn-selected" id="editor-nav-content-block-btn-basics" onclick="editorNavContent('basics');"><?php echo $wrd_basics; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-details" onclick="editorNavContent('details');"><?php echo $wrd_details; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-price" onclick="editorNavContent('price');"><?php echo $wrd_price; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-videos" onclick="editorNavContent('videos');"><?php echo $wrd_videos; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-map" onclick="editorNavContent('map');"><?php echo $wrd_map; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-conditions" onclick="editorNavContent('conditions');"><?php echo $wrd_conditions; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-calendar" onclick="editorNavContent('calendar');"><?php echo $wrd_calendar; ?></button>
      </div>
      <div class="editor-nav-content-block">
        <button type="button" class="editor-nav-content-block-btn" id="editor-nav-content-block-btn-calendar-sync" onclick="editorNavContent('calendar-sync');"><?php echo $wrd_calendarSync; ?></button>
      </div>
    </div>
  </div>
</div>
