<form class="n-c-layout" id="n-c-layout-photos">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-photos"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_photos; ?></h2>
  <div id="n-c-photos-wrp">
    <div id="n-c-photos-grid">
      <div class="n-c-photo-wrp">
        <div class="n-c-photo-size">
          <div class="n-c-photo-content" id="n-c-photo-content-input-file">
            <div class="n-c-photo-details">
              <input type="file" name="n-c-photos-input-file" id="n-c-photos-input-file" onchange="ncPhotosUpload(this)" multiple>
            </div>
            <div class="n-c-photo-layout">
              <label id="n-c-photos-input-file-label" for="n-c-photos-input-file" ondragover="return false">
                <div id="n-c-photos-input-file-icon"></div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
