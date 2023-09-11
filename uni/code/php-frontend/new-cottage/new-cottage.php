<div class="modal-cover modal-cover-up" id="modal-cover-new-cottage">
  <div class="modal-block" id="modal-cover-new-cottage-blck">
    <button class="cancel-btn" id="modal-cancel-btn-new-cottage" onclick="newCottageModal('hide')"></button>
    <div id="n-c-wrp">
      <div id="n-c-size">
        <div id="n-c-content">
          <?php
            include "content/basics.php";
            include "content/details.php";
            include "content/price.php";
            include "content/photos.php";
            include "content/videos.php";
            include "content/map.php";
            include "content/conditions.php";
            include "content/calendar-sync.php";
          ?>
        </div>
        <div id="n-c-content-cover">
          <div id="n-c-content-cover-scroll">
            <div id="n-c-content-cover-scroll-content">
              <?php
                include "cover/data-processing.php";
                include "cover/preview.php";
              ?>
            </div>
          </div>
        </div>
      </div>
      <div id="n-c-nav">
        <div id="n-c-nav-slider-controls-wrp">
          <div id="n-c-nav-slider-wrp" onscroll="nCNavOnSliderHandler()">
            <div id="n-c-nav-slider-content">
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn n-c-nav-slider-btn-selected" id="n-c-nav-slider-btn-basics" value="basics" onclick="nCSlide('basics')"><?php echo $wrd_basics; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-details" value="details" onclick="nCSlide('details')"><?php echo $wrd_details; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-price" value="price" onclick="nCSlide('price')"><?php echo $wrd_price; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-photos" value="photos" onclick="nCSlide('photos')"><?php echo $wrd_photos; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-videos" value="videos" onclick="nCSlide('videos')"><?php echo $wrd_videos; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-map" value="map" onclick="nCSlide('map')"><?php echo $wrd_map; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-conditions" value="conditions" onclick="nCSlide('conditions')"><?php echo $wrd_conditions; ?></button>
              </div>
              <div class="n-c-nav-slider-btn-wrp">
                <button type="button" class="n-c-nav-slider-btn" id="n-c-nav-slider-btn-calendar-sync" value="calendar-sync" onclick="nCSlide('calendar-sync')"><?php echo $wrd_calendarSync; ?></button>
              </div>
            </div>
          </div>
          <div class="n-c-nav-slider-cover-wrp" id="n-c-nav-slider-cover-wrp-left">
            <button type="button" class="n-c-nav-slider-controls-btn" id="n-c-nav-slider-controls-btn-left" onclick="ncNavSliderScroll('left')"></button>
          </div>
          <div class="n-c-nav-slider-cover-wrp" id="n-c-nav-slider-cover-wrp-right">
            <button type="button" class="n-c-nav-slider-controls-btn" id="n-c-nav-slider-controls-btn-right" onclick="ncNavSliderScroll('right')"></button>
          </div>
        </div>
        <div id="n-c-nav-btn-wrp">
          <div id="n-c-nav-btn-prim">
            <button type="button" class="btn btn-big btn-prim" id="n-c-nav-btn-continue" onclick="ncContinue()"><?php echo $wrd_continue; ?></button>
            <button type="button" class="btn btn-big btn-prim" id="n-c-nav-btn-save" onclick="ncSave()"><?php echo $wrd_save; ?></button>
          </div>
          <div id="n-c-nav-btn-cover">
            <button type="button" class="btn btn-big btn-fth" id="n-c-nav-btn-cancel" onclick="ncCoverNavBtnOnclick()"><?php echo $wrd_cancel; ?></button>
            <button type="button" class="btn btn-big btn-prim" id="n-c-nav-btn-add-another" onclick="ncCoverNavBtnOnclick()"><?php echo $wrd_addAnother; ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal-cover modal-cover-up-2" id="modal-cover-n-c-map-search">
  <div id="n-c-map-search-blck">
    <div id="n-c-map-search-wrp">
      <button type="button" class="n-c-map-search-modal-btn" id="n-c-map-search-modal-btn-search"></button>
      <button type="button" class="n-c-map-search-modal-btn" id="n-c-map-search-modal-btn-cancel" onclick="ncMapSearchClose('click')"></button>
      <input type="text" class="form-control" id="n-c-map-search-input" placeholder="<?php echo $wrd_searchForAPlaceGM; ?>">
    </div>
  </div>
</div>
<?php
  include "bank-account.php";
?>
