<?php
  if ($ratingPlcSectLct == "none") {
    $ratingPlcSectLctPercSlide = 50;
  } else {
    $ratingPlcSectLctPercSlide = $ratingPlcSectLct;
  }

?>
<div class="set-up-step-by-step-content set-up-step-by-step-content-places" id="set-up-step-by-step-content-places-location">
  <div class="set-up-step-by-step-content-title-wrp">
    <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_howDoYouRateTheLocationWhereTheCottageIsLocated; ?></h1>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-slider-row">
      <div class="set-up-step-by-step-content-slider-about">
        <p id="set-up-step-by-step-content-slider-about-places-location-progress"><?php echo $ratingPlcSectLctPercSlide; ?></p>
      </div>
      <div class="set-up-step-by-step-content-slider-legend">
        <img src="../uni/icons/sad.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-sad" onclick="setUpStepByStepSliderFncHappySadClick('places-location', 'sad')">
        <img src="../uni/icons/happy.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-happy" onclick="setUpStepByStepSliderFncHappySadClick('places-location', 'happy')">
      </div>
      <div class="set-up-step-by-step-content-slider-blck" onmousedown="setUpStepByStepSliderFncMouseDown('places-location', event)">
        <div class="set-up-step-by-step-content-slider-bar" id="set-up-step-by-step-content-slider-bar-places-location">
          <div class="set-up-step-by-step-content-slider-progress" id="set-up-step-by-step-content-slider-progress-places-location" style="width: <?php echo $ratingPlcSectLctPercSlide.'%'; ?>;">
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
        <?php
          $ratingPlcSectLctSliderTxt_1 = "";
          $ratingPlcSectLctSliderTxt_2 = "";
          $ratingPlcSectLctSliderTxt_3 = "";
          $ratingPlcSectLctSliderTxt_4 = "";
          $ratingPlcSectLctSliderTxt_5 = "";
          if ($ratingPlcSectLctPercSlide == 0) {
            $ratingPlcSectLctSliderTxt_1 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectLctPercSlide == 25) {
            $ratingPlcSectLctSliderTxt_2 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectLctPercSlide == 50) {
            $ratingPlcSectLctSliderTxt_3 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectLctPercSlide == 75) {
            $ratingPlcSectLctSliderTxt_4 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectLctPercSlide == 100) {
            $ratingPlcSectLctSliderTxt_5 = "set-up-step-by-step-content-slider-txt-selected";
          }
        ?>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-location <?php echo $ratingPlcSectLctSliderTxt_1; ?>"><?php echo $wrd_bad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-location <?php echo $ratingPlcSectLctSliderTxt_2; ?>"><?php echo $wrd_guiteBad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-location <?php echo $ratingPlcSectLctSliderTxt_3; ?>"><?php echo $wrd_good; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-location <?php echo $ratingPlcSectLctSliderTxt_4; ?>"><?php echo $wrd_veryGood; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-location <?php echo $ratingPlcSectLctSliderTxt_5; ?>"><?php echo $wrd_great; ?></span>
      </div>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-places-location">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-places-location"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-left">
      <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn set-up-step-by-step-content-footer-back-btn" onclick="ratingsContentBack(this.value)"><?php echo $wrd_back; ?></button>
    </div>
    <div class="set-up-step-by-step-content-footer-counter">
      <p class="set-up-step-by-step-content-footer-counter-txt set-up-step-by-step-content-footer-counter-txt-places">0 / 0</p>
    </div>
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-right">
      <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-continue-btn-places-location" onclick="ratingsContentContinueSave('places', 'location');"><?php echo $wrd_continue; ?></button>
    </div>
  </div>
</div>
