<div class="n-c-layout" id="n-c-layout-videos">
  <div class="n-c-error-wrp">
    <p class="n-c-error-txt" id="n-c-error-txt-videos"></p>
  </div>
  <h2 class="n-c-title"><?php echo $wrd_videos; ?></h2>
  <div id="n-c-videos-wrp">
    <div id="n-c-videos-searchbar-wrp">
      <button type="button" class="n-c-videos-searchbar-btn" id="n-c-videos-searchbar-btn-search" onclick="ncVideosSearch()"></button>
      <input type="text" name="search-video" id="n-c-videos-searchbar-input" placeholder="<?php echo $wrd_pasteLinkToYouTubeVideo; ?>" value="">
      <button type="button" class="n-c-videos-searchbar-btn" id="n-c-videos-searchbar-btn-submit" onclick="ncVideosSearch()"></button>
    </div>
    <div id="n-c-videos-grid">
    </div>
  </div>
</div>
