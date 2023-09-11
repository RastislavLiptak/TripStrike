<div class="editor-content-section-wrp" id="editor-content-videos-wrp">
  <div id="editor-content-videos-search-wrp">
    <div id="editor-content-videos-search-size">
      <input type="text" placeholder="<?php echo $wrd_pasteLinkToYouTubeVideo; ?>" class="editor-content-input-style" id="editor-content-input-style-videos-search" onfocusin="editorContentVideoSearchFocus('in');" onfocusout="editorContentVideoSearchFocus('out');">
      <button type="button" id="editor-content-videos-search-btn" onclick="editorVideoSearch();"></button>
    </div>
  </div>
  <div id="editor-content-videos-list-size">
    <div id="editor-content-videos-list-wrp">
      <?php
        $sqlVid = $link->query("SELECT videoid FROM placevideos WHERE beid='$plcBeId'");
        if ($sqlVid->num_rows > 0) {
          while($rowVid = $sqlVid->fetch_assoc()) {
      ?>
          <div class="editor-content-video-blck" id="editor-content-video-blck-<?php echo $rowVid['videoid']; ?>">
            <div class="editor-content-video-about-wrp">
              <p class="editor-content-video-about-txt"><?php echo $rowVid['videoid']; ?></p>
            </div>
            <div class="editor-content-video-size">
              <div class="editor-content-video-layout">
                <div class="editor-content-video-img-wrp">
                  <button type="button" class="editor-content-video-delete-btn" onclick="editorVideosDelete('<?php echo $rowVid['videoid']; ?>');" style="display: table;"></button>
                  <img class="editor-content-video-img" src="<?php echo youtubeAboutVideo($rowVid['videoid'], "thumb-medium"); ?>" alt="YouTube video thumbnail">
                </div>
                <div class="editor-content-video-details-wrp">
                  <div class="editor-content-video-details-title-wrp">
                    <p class="editor-content-video-details-title-txt"><?php echo youtubeAboutVideo($rowVid['videoid'], "title"); ?></p>
                  </div>
                  <div class="editor-content-video-logo-wrp" style="display: table;">
                    <img class="editor-content-video-logo" src="../uni/icons/social/youtube.svg" alt="YouTube logo">
                  </div>
                </div>
              </div>
            </div>
          </div>
      <?php
          }
        }
        if ($sqlVid->num_rows == 0) {
          $noVideoContentStyle = "display: table;";
        } else {
          $noVideoContentStyle = "";
        }
      ?>
      <div id="editor-content-no-video-wrp" style="<?php echo $noVideoContentStyle; ?>">
        <div id="editor-content-no-video-layout">
          <div id="editor-content-no-video-txt-wrp">
            <p id="editor-content-no-video-txt"><?php echo $wrd_hereYouCanAddYouTubeVideosToBePartOfAd; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
