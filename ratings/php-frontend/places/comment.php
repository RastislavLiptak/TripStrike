<?php
  if ($bnft_add_comment == "good") {
?>
  <div class="set-up-step-by-step-content set-up-step-by-step-content-places" id="set-up-step-by-step-content-places-comment">
    <div class="set-up-step-by-step-content-title-wrp">
      <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
      <div class="set-up-step-by-step-content-title-txt">
        <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_wouldYouLikeToWriteUsAnythingMoreAboutYourStay; ?></h1>
      </div>
      <div class="set-up-step-by-step-content-title-txt">
        <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_youCanWritePraiseOrPossibleComplaints; ?></h2>
      </div>
    </div>
    <div class="set-up-step-by-step-content-comment-wrp">
      <textarea class="set-up-step-by-step-content-comment-textarea" id="set-up-step-by-step-content-comment-textarea-places" placeholder="<?php echo $wrd_type; ?>"></textarea>
    </div>
    <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-places-comment">
      <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-places-comment"></p>
    </div>
    <div class="set-up-step-by-step-content-footer-wrp">
      <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-left">
        <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn set-up-step-by-step-content-footer-back-btn" onclick="ratingsContentBack(this.value)"><?php echo $wrd_back; ?></button>
      </div>
      <div class="set-up-step-by-step-content-footer-counter">
        <p class="set-up-step-by-step-content-footer-counter-txt set-up-step-by-step-content-footer-counter-txt-places">0 / 0</p>
      </div>
      <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-right">
        <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-continue-btn-places-comment" onclick="ratingsContentCommentSave('places');"><?php echo $wrd_continue; ?></button>
      </div>
    </div>
  </div>
<?php
  }
?>
