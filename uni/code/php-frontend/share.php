<?php
  $page_URL = urlencode("$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
?>
<div class="modal-cover" id="modal-cover-share">
  <div class="modal-block" id="modal-cover-share-blck">
    <button class="cancel-btn" onclick="share('hide')"></button>
    <div id="share-wrp">
      <div id="share-copy-wrp">
        <input type="text" id="share-copy-inpt" value="" onchange="shareInpt(this)">
        <label for="share-copy-btn" id="share-copy-label"><?php echo $wrd_copy; ?></label>
        <button type="button" id="share-copy-btn" onclick="shareCopy()"><?php echo $wrd_copy; ?></button>
      </div>
      <div id="share-social-grid">

        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $page_URL; ?>" target="_blank" rel="noopener noreferrer" class="share-social-link">
          <img class="share-social-icn" alt="" src="../uni/icons/social/fb.svg">
          <p class="share-social-txt">Facebook</p>
        </a>
        <a href="https://twitter.com/intent/tweet?text=<?php echo $page_URL; ?>" target="_blank" rel="noopener noreferrer" class="share-social-link">
          <img class="share-social-icn" alt="" src="../uni/icons/social/twt.svg">
          <p class="share-social-txt">Twitter</p>
        </a>
        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $page_URL; ?>" target="_blank" rel="noopener noreferrer" class="share-social-link">
          <img class="share-social-icn" alt="" src="../uni/icons/social/lnkin.svg">
          <p class="share-social-txt">LinkedIn</p>
        </a>
        <a href="mailto:?subject=&amp;body=<?php echo $page_URL; ?>" target="_blank" rel="noopener noreferrer" class="share-social-link">
          <img class="share-social-icn" alt="" src="../uni/icons/social/mail.svg">
          <p class="share-social-txt">Email</p>
        </a>

      </div>
    </div>
  </div>
</div>
