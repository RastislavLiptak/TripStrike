<?php
  if (!$plcAllreadyRated) {
    $ratingsOverallRatingStyle = "set-up-step-by-step-content-selected";
  } else {
    $ratingsOverallRatingStyle = "";
  }
?>
<div class="set-up-step-by-step-content set-up-step-by-step-content-places <?php echo $ratingsOverallRatingStyle; ?>" id="set-up-step-by-step-content-places-overall-rating">
  <div class="set-up-step-by-step-content-title-wrp">
    <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_howWasYourStayInTheCottage; ?> <a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcName; ?></a>?</h1>
    </div>
    <div class="set-up-step-by-step-content-title-txt">
      <h2 class="set-up-step-by-step-content-title-h2"><?php echo $wrd_summarizeTheOverallAccommodationExperience; ?></h2>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-slider-row">
      <div class="set-up-step-by-step-content-slider-about">
        <p id="set-up-step-by-step-content-slider-about-places-overall-rating-progress">50</p>
      </div>
      <div class="set-up-step-by-step-content-slider-legend">
        <img src="../uni/icons/sad.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-sad" onclick="setUpStepByStepSliderFncHappySadClick('places-overall-rating', 'sad')">
        <img src="../uni/icons/happy.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-happy" onclick="setUpStepByStepSliderFncHappySadClick('places-overall-rating', 'happy')">
      </div>
      <div class="set-up-step-by-step-content-slider-blck" onmousedown="setUpStepByStepSliderFncMouseDown('places-overall-rating', event)">
        <div class="set-up-step-by-step-content-slider-bar" id="set-up-step-by-step-content-slider-bar-places-overall-rating">
          <div class="set-up-step-by-step-content-slider-progress" id="set-up-step-by-step-content-slider-progress-places-overall-rating" style="width: 50%;">
            <div class="set-up-step-by-step-content-slider-circle"></div>
          </div>
          <div class="set-up-step-by-step-content-slider-checkpoints-wrp">
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
            <div class="set-up-step-by-step-content-slider-checkpoint"></div>
          </div>
        </div>
      </div>
      <div class="set-up-step-by-step-content-slider-txt-wrp">
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-overall-rating"><?php echo $wrd_bad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-overall-rating"><?php echo $wrd_guiteBad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-overall-rating set-up-step-by-step-content-slider-txt-selected"><?php echo $wrd_good; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-overall-rating"><?php echo $wrd_veryGood; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-overall-rating"><?php echo $wrd_great; ?></span>
      </div>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-places-overall-rating">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-places-overall-rating"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-btn-overall-rating-skip" onclick="ratingsOverallRatingOnclick('skip');"><?php echo $wrd_skipSpecificRatings; ?></button>
    <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-btn-overall-rating-continue" onclick="ratingsOverallRatingOnclick('continue');"><?php echo $wrd_continue; ?></button>
  </div>
</div>
