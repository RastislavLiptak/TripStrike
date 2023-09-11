<?php
  if ($ratingPlcSectTidy == "none") {
    $ratingPlcSectTidyPercSlide = 50;
  } else {
    $ratingPlcSectTidyPercSlide = $ratingPlcSectTidy;
  }

?>
<div class="set-up-step-by-step-content set-up-step-by-step-content-places" id="set-up-step-by-step-content-places-tidines">
  <div class="set-up-step-by-step-content-title-wrp">
    <span class="set-up-step-by-step-content-title-details"><a href="<?php echo "../place/?id=".$rt_plcID; ?>" target="_blank"><?php echo $plcNameShrt; ?></a></span>
    <div class="set-up-step-by-step-content-title-txt">
      <h1 class="set-up-step-by-step-content-title-h1"><?php echo $wrd_howWellWasTheCottageTidyAndReadyForYourStay; ?></h1>
    </div>
  </div>
  <div class="set-up-step-by-step-content-form-wrp">
    <div class="set-up-step-by-step-content-slider-row">
      <div class="set-up-step-by-step-content-slider-about">
        <p id="set-up-step-by-step-content-slider-about-places-tidines-progress"><?php echo $ratingPlcSectTidyPercSlide; ?></p>
      </div>
      <div class="set-up-step-by-step-content-slider-legend">
        <img src="../uni/icons/sad.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-sad" onclick="setUpStepByStepSliderFncHappySadClick('places-tidines', 'sad')">
        <img src="../uni/icons/happy.svg" alt="sad face" class="set-up-step-by-step-content-slider-legend-img set-up-step-by-step-content-slider-legend-img-happy" onclick="setUpStepByStepSliderFncHappySadClick('places-tidines', 'happy')">
      </div>
      <div class="set-up-step-by-step-content-slider-blck" onmousedown="setUpStepByStepSliderFncMouseDown('places-tidines', event)">
        <div class="set-up-step-by-step-content-slider-bar" id="set-up-step-by-step-content-slider-bar-places-tidines">
          <div class="set-up-step-by-step-content-slider-progress" id="set-up-step-by-step-content-slider-progress-places-tidines" style="width: <?php echo $ratingPlcSectTidyPercSlide.'%'; ?>;">
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
          $ratingPlcSectTidySliderTxt_1 = "";
          $ratingPlcSectTidySliderTxt_2 = "";
          $ratingPlcSectTidySliderTxt_3 = "";
          $ratingPlcSectTidySliderTxt_4 = "";
          $ratingPlcSectTidySliderTxt_5 = "";
          if ($ratingPlcSectTidyPercSlide == 0) {
            $ratingPlcSectTidySliderTxt_1 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectTidyPercSlide == 25) {
            $ratingPlcSectTidySliderTxt_2 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectTidyPercSlide == 50) {
            $ratingPlcSectTidySliderTxt_3 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectTidyPercSlide == 75) {
            $ratingPlcSectTidySliderTxt_4 = "set-up-step-by-step-content-slider-txt-selected";
          } else if ($ratingPlcSectTidyPercSlide == 100) {
            $ratingPlcSectTidySliderTxt_5 = "set-up-step-by-step-content-slider-txt-selected";
          }
        ?>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-tidines <?php echo $ratingPlcSectTidySliderTxt_1; ?>"><?php echo $wrd_bad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-tidines <?php echo $ratingPlcSectTidySliderTxt_2; ?>"><?php echo $wrd_guiteBad; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-tidines <?php echo $ratingPlcSectTidySliderTxt_3; ?>"><?php echo $wrd_good; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-tidines <?php echo $ratingPlcSectTidySliderTxt_4; ?>"><?php echo $wrd_veryGood; ?></span>
        <span class="set-up-step-by-step-content-slider-txt set-up-step-by-step-content-slider-txt-places-tidines <?php echo $ratingPlcSectTidySliderTxt_5; ?>"><?php echo $wrd_great; ?></span>
      </div>
    </div>
  </div>
  <div class="set-up-step-by-step-content-error-wrp" id="set-up-step-by-step-content-error-wrp-places-tidines">
    <p class="set-up-step-by-step-content-error-txt" id="set-up-step-by-step-content-error-txt-places-tidines"></p>
  </div>
  <div class="set-up-step-by-step-content-footer-wrp">
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-left">
      <button class="btn btn-mid btn-sec set-up-step-by-step-content-footer-btn set-up-step-by-step-content-footer-back-btn" onclick="ratingsContentBack(this.value)"><?php echo $wrd_back; ?></button>
    </div>
    <div class="set-up-step-by-step-content-footer-counter">
      <p class="set-up-step-by-step-content-footer-counter-txt set-up-step-by-step-content-footer-counter-txt-places">0 / 0</p>
    </div>
    <div class="set-up-step-by-step-content-footer-btn-wrp set-up-step-by-step-content-footer-btn-wrp-right">
      <button class="btn btn-mid btn-prim set-up-step-by-step-content-footer-btn" id="set-up-step-by-step-content-footer-continue-btn-places-tidines" onclick="ratingsContentContinueSave('places', 'tidines');"><?php echo $wrd_continue; ?></button>
    </div>
  </div>
</div>
